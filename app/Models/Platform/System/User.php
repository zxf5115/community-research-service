<?php
namespace App\Models\Platform\System;

use App\Models\Common\System\User as Common;


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-07-14
 *
 * 用户模型类
 */
class User extends Common
{
  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-07-16
   * ------------------------------------------
   * 获取角色编号
   * ------------------------------------------
   *
   * 获取角色编号
   *
   * @param array $request 请求数据
   * @param array $organization_id 机构编号
   * @return 角色数据
   */
  public static function getRoleId($request, $organization_id)
  {
    $response = [];

    foreach($request as $key => $item)
    {
      $response[$key]['role_id']     = $item;
      $response[$key]['organization_id'] = $organization_id;
    }

    return $response;
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-07-15
   * ------------------------------------------
   * 登录操作
   * ------------------------------------------
   *
   * 登录操作，更新最后时间，更新失败登录次数
   *
   * @param [type] $request [description]
   * @return [type]
   */
  protected static function login($request)
  {
    try
    {
      $request->last_login_time = time();
      $request->try_number = 0;
      $request->save();

      return true;
    }
    catch(\Exception $e)
    {
      self::log($e);

      return false;
    }
  }



  // 关联函数 ------------------------------------------------------

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-20
   * ------------------------------------------
   * 关联用户与角色关联表
   * ------------------------------------------
   *
   * 关联用户与角色关联表，用于删除
   *
   * @return [关联对象]
   */
  public function message()
  {
      return $this->hasMany('App\Models\Common\System\User\Message', 'user_id', 'id')
                  ->where(['status'=>1]);
  }
}
