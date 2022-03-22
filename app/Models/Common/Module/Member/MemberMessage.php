<?php
namespace App\Models\Common\Module\Member;

use App\Models\Base;
use App\Enum\Module\Member\Message\MessageEnum;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-07-15
 *
 * 会员消息模型类
 */
class MemberMessage extends Base
{
  // 表名
  public $table = 'module_member_message';

  // 隐藏的属性
  public $hidden = [
    'organization_id',
    'status',
    'update_time'
  ];

  // 追加到模型数组表单的访问器
  public $appends = [];

  /**
   * 可以被批量赋值的属性
   */
  public $fillable = [
    'organization_id',
    'member_id',
    'member_id',
  ];


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-07-20
   * ------------------------------------------
   * 消息类型封装
   * ------------------------------------------
   *
   * 消息类型封装
   *
   * @param int $value [数据库存在的值]
   * @return 状态值
   */
  public function getReadStatusAttribute($value)
  {
    return MessageEnum::getStatus($value);
  }


  // 关联函数 ------------------------------------------------------


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-07-20
   * ------------------------------------------
   * 会员消息与会员关联函数
   * ------------------------------------------
   *
   * 会员消息与会员关联函数
   *
   * @return [type]
   */
  public function member()
  {
    return $this->belongsTo(
      'App\Models\Common\Module\Member',
      'member_id',
      'id'
    );
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-07-20
   * ------------------------------------------
   * 会员消息与消息关联函数
   * ------------------------------------------
   *
   * 会员消息与消息关联函数
   *
   * @return [type]
   */
  public function message()
  {
    return $this->belongsTo(
      'App\Models\Common\Module\Message',
      'message_id',
      'id'
    );
  }
}
