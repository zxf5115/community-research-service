<?php
namespace App\Http\Controllers\Api\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;

use App\Http\Constant\Code;
use App\TraitClass\ToolTrait;
use App\Http\Constant\RedisKey;
use App\Http\Constant\Parameter;
use App\Models\Api\Module\Member;
use App\Events\Common\Message\SmsEvent;
use App\Http\Controllers\Api\BaseController;
use App\Models\Api\Module\Member\Relevance\Archive;


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-10-21
 *
 * 登录控制器
 */
class LoginController extends BaseController
{
  /**
   * @api {post} /api/weixin_login 01. 微信登录
   * @apiDescription 通过微信进行登录
   * @apiGroup 02. 登录模块
   * @apiParam {string} code 微信code
   * @apiParam {string} inviter_id 邀请人
   *
   * @apiSuccess (basic params) {String} token 身份令牌
   * @apiSuccess (user_info params) {Number} id 会员编号
   * @apiSuccess (user_info params) {Number} role_id 角色编号 1 车商 2 消费者
   * @apiSuccess (user_info params) {Number} open_id 第三方登录编号
   * @apiSuccess (user_info params) {Number} inviter_id 邀请人
   * @apiSuccess (user_info params) {Number} member_no 会员号
   * @apiSuccess (user_info params) {String} avatar 会员头像
   * @apiSuccess (user_info params) {String} username 登录账户
   * @apiSuccess (user_info params) {String} nickname 会员昵称
   * @apiSuccess (user_info params) {String} create_time 注册时间
   * @apiSuccess (certification params) {String} type 认证类型 1 个人认证 2 银行卡认证 3 企业认证
   * @apiSuccess (certification params) {String} audit_status 审核状态 0 待审核 1 审核通过 2 审核失败
   *
   * @apiSampleRequest /api/weixin_login
   * @apiVersion 1.0.0
   */
  public function weixin_login(Request $request)
  {
    $messages = [
      'code.required' => '请您输入微信Code',
    ];

    $rule = [
      'code' => 'required',
    ];

    // 验证用户数据内容是否正确
    $validation = self::validation($request, $messages, $rule);

    if(!$validation['status'])
    {
      return $validation['message'];
    }
    else
    {
      try
      {
        $data = Member::getUserOpenId($request->code);

        if(empty($data) || empty($data['openid']))
        {
          return self::error(Code::WX_REQUIRE_ERROR);
        }

        $condition = self::getSimpleWhereData();

        // $where = ['open_id' => 'oOn4f41roEZZfTejl_I1362kRhjY'];
        $where = ['open_id' => $data['openid']];

        $where = array_merge($condition, $where);

        $response = Member::getRow($where, ['certification']);

        // 用户不存在
        if(is_null($response) && !empty($data['openid']))
        {
          $model = Member::firstOrNew($where);

          $model->open_id    = $data['openid'];
          $model->role_id    = 2;
          $model->inviter_id = $request->inviter_id ?? 0;
          $model->save();

          $response = $model;
        }

        // 绑定邀请人
        if(empty($response->inviter_id) && !empty($request->inviter_id))
        {
          $response->inviter_id = $request->inviter_id;
          $response->save();
        }

        // 用户已禁用
        if(2 == $response->status['value'])
        {
          return self::error(Code::MEMBER_DISABLE);
        }

        // 在特定时间内访问次数过多，就触发访问限制
        if(Member::AccessRestrictions($response))
        {
          return self::error(Code::ACCESS_RESTRICTIONS);
        }

        // 认证用户密码是否可以登录
        if (! $token = auth('api')->tokenById($response->id))
        {
          return self::error(Code::USER_EMPTY);
        }

        // 获取客户端ip地址
        $response->last_login_ip = $request->getClientIp();

        $old_token = $response->remember_token;

        if(!empty($old_token))
        {
          \JWTAuth::setToken($old_token)->invalidate();
        }

        // 记录登录信息
        Member::login($response);

        return self::success([
          'code' => 200,
          'token' => $token,
          'token_type' => 'bearer',
          'expires_in' => auth('api')->factory()->getTTL() * 60,
          'user_info' => $response
        ]);
      }
      catch(\Exception $e)
      {
        // 记录异常信息
        self::record($e);

        return self::error(Code::ERROR);
      }
    }
  }


  /**
   * @api {post} /api/register 02. 用户注册
   * @apiDescription 注册用户信息
   * @apiGroup 02. 登录模块
   *
   * @apiParam {int} code 微信code
   * @apiParam {int} role_id 会员角色 1 车商 2 消费者
   * @apiParam {string} username 登录手机号码（不可为空）
   * @apiParam {string} avatar 会员头像（不可为空）
   * @apiParam {string} nickname 会员姓名（不可为空）
   * @apiParam {string} sex 会员性别（不可为空）
   *
   * @apiSampleRequest /api/register
   * @apiVersion 1.0.0
   */
  public function register(Request $request)
  {
    $messages = [
      'code.required'     => '请您输入微信Code',
      'role_id.required'  => '请您选择会员角色',
      'username.required' => '请您输入登录手机号码',
      'nickname.required' => '请您输入会员姓名',
      'avatar.required'   => '请您上传会员头像',
      'sex.required'      => '请您选择会员性别',
    ];

    $rule = [
      'code'     => 'required',
      'role_id'  => 'required',
      'username' => 'required',
      'nickname' => 'required',
      'avatar'   => 'required',
      'sex'      => 'required',
    ];

    // 验证用户数据内容是否正确
    $validation = self::validation($request, $messages, $rule);

    if(!$validation['status'])
    {
      return $validation['message'];
    }
    else
    {
      DB::beginTransaction();

      try
      {
        $data = Member::getUserOpenId($request->code);

        if(empty($data) || empty($data['openid']))
        {
          return self::error(Code::WX_REQUIRE_ERROR);
        }

        $model = Member::firstOrNew([
          'open_id' => $data['openid'],
          'status' => 1
        ]);

        if(empty($request->id))
        {
          $model->member_no = ToolTrait::generateOnlyNumber(3);
        }

        $model->open_id  = $request->open_id;
        $model->role_id  = $request->role_id;
        $model->avatar   = $request->avatar;
        $model->username = $request->username;
        $model->nickname = $request->nickname;

        $model->save();

        $data = [
          'sex' => $request->sex
        ];

        if(!empty($data))
        {
          $model->archive()->delete();
          $model->archive()->create($data);
        }

        $data = [
          'cash_money' => 0.00,
          'credit_money' => 0.00,
        ];

        if(!empty($data))
        {
          $model->asset()->delete();
          $model->asset()->create($data);
        }

        DB::commit();

        return self::success(Code::message(Code::REGISTER_SUCCESS));
      }
      catch(\Exception $e)
      {
        DB::rollback();

        // 记录异常信息
        self::record($e);

        return self::error(Code::HANDLE_FAILURE);
      }
    }
  }


  /**
   * @api {get} /api/logout 03. 退出
   * @apiDescription 退出登录状态
   * @apiGroup 02. 登录模块
   * @apiPermission jwt
   * @apiHeader {String} Authorization 身份令牌
   * @apiHeaderExample {json} Header-Example:
   * {
   *   "Authorization": "eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOjM2NzgsImF1ZGllbmN"
   * }
   *
   * @apiSampleRequest /api/logout
   * @apiVersion 1.0.0
   */
  public function logout()
  {
    try
    {
      auth('api')->logout();

      return self::success([], '退出成功');
    }
    catch(\Exception $e)
    {
      // 记录异常信息
      self::record($e);

      return self::error(Code::ERROR);
    }
  }
}
