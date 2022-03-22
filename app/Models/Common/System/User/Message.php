<?php
namespace App\Models\Common\System\User;

use App\Models\Base;
use App\Enum\MessageEnum;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-07-17
 *
 * 系统用户消息模型类
 */
class Message extends Base
{
  // 表名
  public $table = 'system_user_message_relevance';

  /**
   * 可以被批量赋值的属性
   */
  public $fillable = ['user_id'];

  // 隐藏的属性
  public $hidden = [
    'update_time'
  ];



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-07-17
   * ------------------------------------------
   * 状态属性类型转换函数
   * ------------------------------------------
   *
   * 状态属性类型转换函数
   *
   * @param int $value [数据库存在的值]
   * @return 状态值
   */
  public function getStatusAttribute($value)
  {
    return MessageEnum::getReadStatus($value);
  }


  // 关联函数 ------------------------------------------------------

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-07-17
   * ------------------------------------------
   * 用户关联函数
   * ------------------------------------------
   *
   * 用户关联函数
   *
   * @return [关联对象]
   */
  public function user()
  {
      return $this->hasOne('App\Models\Common\System\User', 'id', 'user_id');
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-26
   * ------------------------------------------
   * 消息关联函数
   * ------------------------------------------
   *
   * 消息关联函数
   *
   * @return [关联对象]
   */
  public function message()
  {
    return $this->belongsTo('App\Models\Common\System\Message')
                ->where(['status' => 1]);
  }
}
