<?php
namespace App\Models\Common\System;

use App\Models\Base;
use App\Http\Constant\Parameter;


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-07-14
 *
 * 用户模型类
 */
class User extends Base
{
  // 表名
  public $table = "system_user";

  // 可以批量修改的字段
  public $fillable = ['username', 'password', 'add_time'];

  // 隐藏的属性
  public $hidden = [
    'password', 'remember_token', 'password_salt', 'try_number', 'last_login_ip'
  ];


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-19
   * ------------------------------------------
   * 访问限制
   * ------------------------------------------
   *
   * 在一个小时内访问超过五次，就会触发禁止访问
   *
   * @param [type] $request [description]
   */
  public static function AccessRestrictions($request)
  {
    try
    {
      // 如果用户上次登录时间和当前时间相差小于一个小时并且登录次数小于五次，返回可以访问，否则禁止访问
      if(time() - $request->last_login_time < 3600 && $request->try_number > 5)
      {
        return true;
      }

      return false;
    }
    catch(\Exception $e)
    {
      self::log($e);

      return false;
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-19
   * ------------------------------------------
   * 验证密码
   * ------------------------------------------
   *
   * 具体描述一些细节
   *
   * @param array $request 用户对象
   * @param string $password 用户输入的密码
   * @return 密码正确返回false，否则true
   */
  public static function checkPassword($request, $password)
  {
    try
    {
      if(password_verify($password, $request->password))
      {
        return false;
      }

      return true;
    }
    catch(\Exception $e)
    {
      self::log($e);

      return true;
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-20
   * ------------------------------------------
   * 设置密码尝试数据
   * ------------------------------------------
   *
   * 在用户输入密码错误后，进行尝试次数记录
   *
   * @param object $request 用户信息
   */
  public static function setTryNumber($request)
  {
    try
    {
      $request->increment('try_number');
      $request->save();

      return true;
    }
    catch(\Exception $e)
    {
      self::log($e);

      return false;
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-20
   * ------------------------------------------
   * 生成密码
   * ------------------------------------------
   *
   * 生成密码 TODO： 后期修改进行加盐处理
   *
   * @param string $password 用户输入的密码
   * @return 加密的密码信息
   */
  public static function generate($password = Parameter::PASSWORD)
  {
    $salt = bin2hex(random_bytes(60));

    $options = [
      'cost' => 12,
    ];

    $password = password_hash($password, PASSWORD_BCRYPT, $options);

    // $password = $password | $salt;

    return $password;
  }




  // 关联函数 ------------------------------------------------------


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-20
   * ------------------------------------------
   * 关联到角色表
   * ------------------------------------------
   *
   * 关联到角色表
   *
   * @return [关联对象]
   */
  public function role()
  {
    return $this->belongsToMany(
      'App\Models\Common\System\Role',
      'system_user_role_relevance',
      'user_id',
      'role_id'
    )->wherePivot('status', 1);
  }


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
  public function relevance()
  {
      return $this->hasMany('App\Models\Common\System\User\UserRoleRelevance', 'user_id', 'id')
                  ->where(['status'=>1]);
  }

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-03-07
   * ------------------------------------------
   * 注册关联删除
   * ------------------------------------------
   *
   * 注册关联删除
   *
   * @return [type]
   */
  public static function boot()
  {
    parent::boot();

    // 超级管理员不可以删除
    static::deleting(function($model) {
      if(1 == $model->id)
      {
        return false;
      }
    });

    static::deleted(function($model) {
      $model->relevance()->delete();
    });
  }
}
