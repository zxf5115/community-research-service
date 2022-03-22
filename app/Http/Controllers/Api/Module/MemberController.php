<?php
namespace App\Http\Controllers\Api\Module;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Constant\Code;
use App\TraitClass\ToolTrait;
use App\Http\Constant\Parameter;
use App\Http\Controllers\Api\BaseController;
use App\Models\Api\Module\Member\Archive;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-05-28
 *
 * 会员控制器类
 */
class MemberController extends BaseController
{
  protected $_model = 'App\Models\Api\Module\Member';

  protected $_where = [];

  protected $_params = [];

  protected $_addition = [];

  protected $_order = [
    ['key' => 'create_time', 'value' => 'desc'],
  ];

  protected $_relevance = [
    'archive' => [
      'archive',
      'asset'
    ],
    'view' => [
      'archive'
    ],
    'status' => [
      'archive',
      'relevance'
    ]
  ];


  /**
   * @api {get} /api/member/archive 01. 当前会员档案
   * @apiDescription 获取当前会员的档案信息
   * @apiGroup 20. 会员模块
   * @apiPermission jwt
   * @apiHeader {String} Authorization 身份令牌
   * @apiHeaderExample {json} Header-Example:
   * {
   *   "Authorization": "Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiO"
   * }
   *
   * @apiSuccess (basic params) {Number} id 会员编号
   * @apiSuccess (basic params) {Number} role_id 角色编号 1 车商 2 消费者
   * @apiSuccess (basic params) {Number} open_id 第三方登录编号
   * @apiSuccess (basic params) {Number} member_no 会员号
   * @apiSuccess (basic params) {String} avatar 会员头像
   * @apiSuccess (basic params) {String} username 登录账户
   * @apiSuccess (basic params) {String} nickname 会员姓名
   * @apiSuccess (basic params) {Number} create_time 注册时间
   * @apiSuccess (archive params) {String} sex 性别
   * @apiSuccess (asset params) {String} cash_money 现金金额
   * @apiSuccess (asset params) {String} credit_money 信用余额
   *
   * @apiSampleRequest /api/member/archive
   * @apiVersion 1.0.0
   */
  public function archive(Request $request)
  {
    try
    {
      // 获取当前会员基础查询条件
      $condition = self::getCurrentWhereData('id');

      $condition = array_merge($condition, $this->_where);

      // 获取关联对象
      $relevance = self::getRelevanceData($this->_relevance, 'archive');

      $response = $this->_model::getRow($condition, $relevance);

      return self::success($response);
    }
    catch(\Exception $e)
    {
      // 记录异常信息
      self::record($e);

      return self::error(Code::ERROR);
    }
  }


  /**
   * @api {get} /api/member/data/{id} 02. 会员数据
   * @apiDescription 获取会员详情
   * @apiGroup 20. 会员模块
   * @apiPermission jwt
   * @apiHeader {String} Authorization 身份令牌
   * @apiHeaderExample {json} Header-Example:
   * {
   *   "Authorization": "Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiO"
   * }
   *
   * @apiSuccess (basic params) {Number} id 会员编号
   * @apiSuccess (basic params) {Number} role_id 角色编号 1 车商 2 消费者
   * @apiSuccess (basic params) {Number} open_id 第三方登录编号
   * @apiSuccess (basic params) {Number} member_no 会员号
   * @apiSuccess (basic params) {String} avatar 会员头像
   * @apiSuccess (basic params) {String} username 登录账户
   * @apiSuccess (basic params) {String} nickname 会员姓名
   * @apiSuccess (basic params) {Number} create_time 注册时间
   * @apiSuccess (archive params) {String} sex 性别
   * @apiSuccess (asset params) {String} cash_money 现金金额
   * @apiSuccess (asset params) {String} credit_money 信用余额
   *
   * @apiSampleRequest /api/member/data/{id}
   * @apiVersion 1.0.0
   */
  public function data(Request $request, $id)
  {
    try
    {
      $condition = ['id' => $id];

      // 获取关联对象
      $relevance = self::getRelevanceData($this->_relevance, 'view');

      $response = $this->_model::getRow($condition, $relevance);

      return self::success($response);
    }
    catch(\Exception $e)
    {
      // 记录异常信息
      self::record($e);

      return self::error(Code::ERROR);
    }
  }


  /**
   * @api {post} /api/member/handle 03. 编辑会员信息
   * @apiDescription 编辑会员信息
   * @apiGroup 20. 会员模块
   * @apiPermission jwt
   * @apiHeader {String} Authorization 身份令牌
   * @apiHeaderExample {json} Header-Example:
   * {
   *   "Authorization": "Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiO"
   * }
   *
   * @apiParam {string} avatar 会员头像（不可为空）
   * @apiParam {string} nickname 会员姓名（不可为空）
   * @apiParam {string} sex 会员性别（0未知 1男 2女 3保密）（不可为空）
   *
   *
   * @apiSampleRequest /api/member/handle
   * @apiVersion 1.0.0
   */
  public function handle(Request $request)
  {
    $messages = [
      'nickname.required'    => '请您输入会员姓名',
      'avatar.required'      => '请您上传会员头像',
      'sex.required'         => '请您选择会员性别',
    ];

    $rule = [
      'nickname'    => 'required',
      'avatar'      => 'required',
      'sex'         => 'required',
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
        // 获取当前会员编号
        $member_id = self::getCurrentId();

        $model = $this->_model::firstOrNew(['id' => $member_id]);

        $model->avatar   = $request->avatar;
        $model->nickname = $request->nickname;

        $model->save();

        $archive = Archive::firstOrNew(['member_id' => $member_id]);
        $archive->sex = $request->sex;
        $archive->save();

        DB::commit();

        return self::success(Code::message(Code::HANDLE_SUCCESS));
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
   * @api {post} /api/member/personal 04. 编辑个人信息
   * @apiDescription 编辑会员个人信息
   * @apiGroup 20. 会员模块
   * @apiPermission jwt
   * @apiHeader {String} Authorization 身份令牌
   * @apiHeaderExample {json} Header-Example:
   * {
   *   "Authorization": "Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiO"
   * }
   *
   * @apiParam {string} realname 姓名
   * @apiParam {string} certificate_no 证件号
   * @apiParam {string} phone 手机号
   *
   * @apiSampleRequest /api/member/personal
   * @apiVersion 1.0.0
   */
  public function personal(Request $request)
  {
    $messages = [
      'realname.required'       => '请您输入姓名',
      'certificate_no.required' => '请您输入证件号',
      'phone.required'          => '请您输入手机号',
    ];

    $rule = [
      'realname'       => 'required',
      'certificate_no' => 'required',
      'phone'          => 'required',
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
        // 获取当前会员编号
        $member_id = self::getCurrentId();

        $model = Archive::firstOrNew(['member_id' => $member_id]);

        $model->realname       = $request->realname;
        $model->certificate_no = $request->certificate_no;
        $model->phone          = $request->phone;
        $model->save();

        return self::success(Code::message(Code::HANDLE_SUCCESS));
      }
      catch(\Exception $e)
      {
        // 记录异常信息
        self::record($e);

        return self::error(Code::HANDLE_FAILURE);
      }
    }
  }





  /**
   * @api {post} /api/member/bank 05. 编辑会员银行卡
   * @apiDescription 编辑会员银行卡信息
   * @apiGroup 20. 会员模块
   * @apiPermission jwt
   * @apiHeader {String} Authorization 身份令牌
   * @apiHeaderExample {json} Header-Example:
   * {
   *   "Authorization": "Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiO"
   * }
   *
   * @apiParam {string} account 银行卡户名
   * @apiParam {string} bank_card_no 银行卡号
   * @apiParam {string} id_card_no 持卡人身份证号
   * @apiParam {string} mobile 银行预留手机号
   *
   * @apiSampleRequest /api/member/bank
   * @apiVersion 1.0.0
   */
  public function bank(Request $request)
  {
    $messages = [
      'account.required'      => '请您输入银行卡户名',
      'bank_card_no.required' => '请您输入银行卡号',
      'id_card_no.required'   => '请您输入持卡人身份证号',
      'mobile.required'       => '请您输入银行预留手机号',
    ];

    $rule = [
      'account'      => 'required',
      'bank_card_no' => 'required',
      'id_card_no'   => 'required',
      'mobile'       => 'required',
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
        // 获取当前会员编号
        $member_id = self::getCurrentId();

        $model = Archive::firstOrNew(['member_id' => $member_id]);

        $model->account      = $request->account;
        $model->bank_card_no = $request->bank_card_no;
        $model->id_card_no   = $request->id_card_no;
        $model->mobile       = $request->mobile;
        $model->save();

        return self::success(Code::message(Code::HANDLE_SUCCESS));
      }
      catch(\Exception $e)
      {
        // 记录异常信息
        self::record($e);

        return self::error(Code::HANDLE_FAILURE);
      }
    }
  }





  /**
   * @api {post} /api/member/datum 06. 编辑会员资料
   * @apiDescription 编辑会员资料信息
   * @apiGroup 20. 会员模块
   * @apiPermission jwt
   * @apiHeader {String} Authorization 身份令牌
   * @apiHeaderExample {json} Header-Example:
   * {
   *   "Authorization": "Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiO"
   * }
   *
   * @apiParam {string} realname 姓名
   * @apiParam {string} sex 会员性别（0未知 1男 2女 3保密）
   * @apiParam {string} certificate_type 证件类型（1 身份证 2 护照 3 其他）
   * @apiParam {string} certificate_no 证件号
   * @apiParam {string} phone 手机号
   * @apiParam {string} account 银行卡户名
   * @apiParam {string} bank_card_no 银行卡号
   * @apiParam {string} id_card_no 持卡人身份证号
   * @apiParam {string} mobile 银行预留手机号
   *
   *
   * @apiSampleRequest /api/member/datum
   * @apiVersion 1.0.0
   */
  public function datum(Request $request)
  {
    $messages = [
      'phone.regex'    => '仅可以使用中国大陆11位手机号',
      // 'realname.required'         => '请您输入姓名',
      // 'certificate_type.required' => '请您输入证类型',
      // 'certificate_no.required'   => '请您输入证件号',
      // 'phone.required'            => '请您输入手机号',
      // 'account.required'          => '请您输入银行卡户名',
      // 'bank_card_no.required'     => '请您输入银行卡号',
      // 'id_card_no.required'       => '请您输入持卡人身份证号',
      // 'mobile.required'           => '请您输入银行预留手机号',
    ];

    $rule = [
      'phone' => 'regex:/^1[3456789][0-9]{9}$/',     //正则验证
      // 'realname'         => 'required',
      // 'certificate_type' => 'required',
      // 'certificate_no'   => 'required',
      // 'phone'            => 'required',
      // 'account'          => 'required',
      // 'bank_card_no'     => 'required',
      // 'id_card_no'       => 'required',
      // 'mobile'           => 'required',
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
        // 获取当前会员编号
        $member_id = self::getCurrentId();

        $archive = Archive::firstOrNew(['member_id' => $member_id]);
        $archive->sex              = $request->sex ?? 1;
        $archive->realname         = $request->realname ?? '';
        $archive->certificate_type = $request->certificate_type ?? 1;
        $archive->certificate_no   = $request->certificate_no ?? '';
        $archive->phone            = $request->phone ?? '';
        $archive->account          = $request->account ?? '';
        $archive->bank_card_no     = $request->bank_card_no ?? '';
        $archive->id_card_no       = $request->id_card_no ?? '';
        $archive->mobile           = $request->mobile ?? '';
        $archive->save();

        return self::success(Code::message(Code::HANDLE_SUCCESS));
      }
      catch(\Exception $e)
      {
        // 记录异常信息
        self::record($e);

        return self::error(Code::HANDLE_FAILURE);
      }
    }
  }
}
