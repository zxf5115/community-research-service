<?php
namespace App\Models\Api\Module\Order;

use App\Models\Common\Module\Order\Logistics as Common;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-05-24
 *
 * 订单物流模型类
 */
class Logistics extends Common
{
  // 隐藏的属性
  public $hidden = [
    'organization_id',
    'status',
    'update_time'
  ];



  // 关联函数 ------------------------------------------------------

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-05-24
   * ------------------------------------------
   * 物流与订单关联函数
   * ------------------------------------------
   *
   * 物流与订单关联函数
   *
   * @return [关联对象]
   */
  public function order()
  {
    return $this->belongsTo(
      'App\Models\Api\Module\Order',
      'order_id',
      'id'
    );
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-05-24
   * ------------------------------------------
   * 物流与学员关联函数
   * ------------------------------------------
   *
   * 物流与学员关联函数
   *
   * @return [关联对象]
   */
  public function member()
  {
    return $this->belongsTo(
      'App\Models\Api\Module\Member',
      'member_id',
      'id'
    );
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-05-24
   * ------------------------------------------
   * 物流与物流分类关联函数
   * ------------------------------------------
   *
   * 物流与物流分类关联函数
   *
   * @return [关联对象]
   */
  public function category()
  {
    return $this->belongsTo(
      'App\Models\Api\Module\Common\Logistics',
      'type',
      'id'
    );
  }
}
