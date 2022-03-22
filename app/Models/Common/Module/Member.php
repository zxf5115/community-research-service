<?php
namespace App\Models\Common\Module;

use App\Models\Base;
use App\Http\Constant\Parameter;
use App\Enum\Module\Member\MemberEnum;
use App\Enum\Module\Teacher\TeacherEnum;


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-08-01
 *
 * 会员模型类
 */
class Member extends Base
{
  // 表名
  public $table = "module_member";

  // 可以批量修改的字段
  public $fillable = ['username', 'password'];

  // 隐藏的属性
  public $hidden = [
    'password',
    'remember_token',
    'password_salt',
    'try_number',
    'last_login_ip'
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

    return $password;
  }




  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-12-20
   * ------------------------------------------
   * 会员状态封装
   * ------------------------------------------
   *
   * 会员状态封装
   *
   * @param [type] $value [description]
   * @return [type]
   */
  public function getStatusAttribute($value)
  {
    return MemberEnum::getStatus($value);
  }


  // 关联函数 ------------------------------------------------------


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-20
   * ------------------------------------------
   * 会员与机构关联表
   * ------------------------------------------
   *
   * 会员与机构关联表
   *
   * @return [关联对象]
   */
  public function organization()
  {
      return $this->belongsTo('App\Models\Common\Module\Organization\Organization', 'organization_id', 'id')
                  ->where(['status'=>1]);
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-10-20
   * ------------------------------------------
   * 会员与会员档案关联表
   * ------------------------------------------
   *
   * 会员与会员档案关联表
   *
   * @return [关联对象]
   */
  public function archive()
  {
      return $this->hasOne('App\Models\Common\Module\Member\Archive', 'member_id', 'id');
  }

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-10-20
   * ------------------------------------------
   * 会员与会员资产关联表
   * ------------------------------------------
   *
   * 会员与会员资产关联表
   *
   * @return [关联对象]
   */
  public function asset()
  {
      return $this->hasOne('App\Models\Common\Module\Member\Asset', 'member_id', 'id');
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-10-20
   * ------------------------------------------
   * 会员与会员地址关联表
   * ------------------------------------------
   *
   * 会员与会员地址关联表
   *
   * @return [关联对象]
   */
  public function address()
  {
    return $this->belongsTo(
      'App\Models\Common\Module\Member\Address',
      'id',
      'member_id'
    );
  }

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-10-20
   * ------------------------------------------
   * 会员与会员资产关联表
   * ------------------------------------------
   *
   * 会员与会员资产关联表
   *
   * @return [关联对象]
   */
  public function car()
  {
      return $this->hasMany('App\Models\Common\Module\Car', 'member_id', 'id');
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-10-20
   * ------------------------------------------
   * 会员与会员认证关联表
   * ------------------------------------------
   *
   * 会员与会员认证关联表
   *
   * @return [关联对象]
   */
  public function certification()
  {
    return $this->belongsTo(
      'App\Models\Common\Module\Member\Certification',
      'id',
      'member_id'
    );
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

    static::updating(function($model) {
      $model->archive()->update(['status' => -1]);
      $model->asset()->update(['status' => -1]);
      $model->address()->update(['status' => -1]);
      $model->car()->update(['status' => -1]);
    });
  }
}
