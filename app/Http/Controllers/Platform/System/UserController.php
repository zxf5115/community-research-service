<?php
namespace App\Http\Controllers\Platform\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

use App\Http\Constant\Code;
use App\Http\Constant\RedisKey;
use App\Http\Constant\Parameter;
use App\Models\Platform\System\Menu;
use App\Models\Platform\System\Log\Action;
use App\Http\Controllers\Platform\BaseController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-07-14
 *
 * 平台用户控制器类
 */
class UserController extends BaseController
{
  protected $_model = 'App\Models\Platform\System\User';

  protected $_where = [];

  protected $_params = [
    'nickname',
    'mobile',
    'create_time'
  ];

  protected $_order = [
    ['key' => 'id', 'value' => 'asc'],
  ];

  protected $_relevance = [
    'role',
    'relevance',
  ];


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-12
   * ------------------------------------------
   * 操作信息
   * ------------------------------------------
   *
   * 操作信息
   *
   * @param Request $request [请求参数]
   * @return [type]
   */
  public function handle(Request $request)
  {
    $messages = [
      'username.required' => '请您输入登录账户',
      'username.unique'   => '登录账户重复',
      'nickname.required' => '请您输入用户昵称',
    ];

    $rule = [
      'username' => 'required',
      'username' => 'unique:system_user,username,' . $request->id,
      'nickname' => 'required',
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
        $model = $this->_model::firstOrNew(['id' => $request->id]);

        $organization_id = self::getOrganizationId();

        $model->organization_id = $organization_id;
        $model->username    = $request->username;
        $model->nickname    = $request->nickname;
        $model->avatar      = $request->avatar;
        $model->mobile      = $request->mobile;
        $model->email       = $request->email;

        if(empty($request->id))
        {
          $model->password    = $this->_model::generate(Parameter::PASSWORD);
        }

        if(empty($request->role_id))
        {
          return self::error(Code::USER_ROLE_EMPTY);
        }

        $data = $this->_model::getRoleId($request->role_id, $organization_id);

        $response = $model->save();

        $model->relevance()->delete();

        if(!empty($data))
        {
          $model->relevance()->createMany($data);
        }

        DB::commit();

        return self::success(Code::$message[Code::HANDLE_SUCCESS]);
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
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-25
   * ------------------------------------------
   * 初始化密码
   * ------------------------------------------
   *
   * 初始化密码
   *
   * @param Request $request [description]
   * @return [type]
   */
  public function password(Request $request)
  {
    try
    {
      $model = $this->_model::find($request->id);

      $password = $this->_model::generate(Parameter::PASSWORD);

      $model->password = $password;

      $response = $model->save();

      if($response)
      {
        return self::success(Code::$message[Code::HANDLE_SUCCESS]);
      }
      else
      {
        return self::error(Code::HANDLE_FAILURE);
      }
    }
    catch(\Exception $e)
    {
      // 记录异常信息
      self::record($e);

      return self::error(Code::HANDLE_FAILURE);
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-12-25
   * ------------------------------------------
   * 修改密码
   * ------------------------------------------
   *
   * 修改密码
   *
   * @param Request $request [description]
   * @return [type]
   */
  public function change_password(Request $request)
  {
    $messages = [
      'old_password.required'   => '请您输入旧密码',
      'password.required'       => '请您输入新密码',
      'password.between' => '输入的密码必须是6-16位',
      'password.confirmed'      => '您输入的两次密码信息不一致',
    ];

    $rule = [
      'old_password' => 'required',
      'password'     => 'required|between:6,16|confirmed',
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
        $model = $this->_model::find($request->id);

        $status = $this->_model::checkPassword($model, $request->old_password);

        if($status)
        {
          return self::error(Code::OLD_PASSWORD_ERROR);
        }

        $password = $this->_model::generate($request->password);

        $model->password = $password;

        $response = $model->save();

        if($response)
        {
          return self::success(Code::$message[Code::HANDLE_SUCCESS]);
        }
        else
        {
          return self::error(Code::HANDLE_FAILURE);
        }
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
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-11
   * ------------------------------------------
   * 获取当前用户角色对应的菜单树
   * ------------------------------------------
   *
   * 获取当前用户角色对应的菜单树
   *
   * @param Request $request [请求参数]
   * @return [菜单树]
   */
  public function tree(Request $request)
  {
    try
    {
      $result = [];

      $user_id = self::getCurrentId();

      $role = $this->_model::find($user_id)->role[0] ?: [];

      $role_id = $role->id;

      // 平台菜单redis Key
      $key = RedisKey::PLATFORM_MENU;

      if(Redis::hexists($key, $role_id))
      {
        $menus = Redis::hget($key, $role_id);

        $result = unserialize($menus);
      }
      else
      {
        $permission = $role->permission->toArray();

        $menu_ids = array_column($permission, 'menu_id');

        // 获取用户可访问菜单
        $result = Menu::getCurrentUserMenuData($menu_ids);

        $menus = serialize($result);

        Redis::hset($key, $role_id, $menus);
      }

      return self::success($result);
    }
    catch(\Exception $e)
    {
      // 记录异常信息
      self::record($e);

      return self::error(Code::ERROR);
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-10-10
   * ------------------------------------------
   * 获取用户登录行为日志
   * ------------------------------------------
   *
   * 获取用户登录行为日志，只显示最新10条数据
   *
   * @return [type]
   */
  public function action(Request $request)
  {
    try
    {
      $user_id = self::getCurrentId();

      $where = ['user_id' => $user_id];
      $order = [
        ['key' => 'create_time', 'value' => 'desc']
      ];

      $response = Action::getList($where, false, $order, false, 10);

      return self::success($response);
    }
    catch(\Exception $e)
    {
      // 记录异常信息
      self::record($e);

      return self::error(Code::ERROR);
    }
  }
}
