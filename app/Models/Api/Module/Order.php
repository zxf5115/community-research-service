<?php
namespace App\Models\Api\Module;

use App\Models\Common\Module\Order as Common;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-05-24
 *
 * 订单模型类
 */
class Order extends Common
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
   * 订单与汽车关联函数
   * ------------------------------------------
   *
   * 订单与汽车关联函数
   *
   * @return [关联对象]
   */
  public function car()
  {
    return $this->belongsTo(
      'App\Models\Api\Module\Car',
      'car_id',
      'id'
    );
  }

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-05-24
   * ------------------------------------------
   * 订单与汽车来源关联函数
   * ------------------------------------------
   *
   * 订单与汽车来源关联函数
   *
   * @return [关联对象]
   */
  public function source()
  {
    return $this->belongsTo(
      'App\Models\Api\Module\Car\Source',
      'source_id',
      'id'
    );
  }

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-05-24
   * ------------------------------------------
   * 订单与汽车品牌关联函数
   * ------------------------------------------
   *
   * 订单与汽车品牌关联函数
   *
   * @return [关联对象]
   */
  public function brand()
  {
    return $this->belongsTo(
      'App\Models\Api\Module\Car\Brand',
      'brand_id',
      'id',
    );
  }

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-05-24
   * ------------------------------------------
   * 订单与汽车车型关联函数
   * ------------------------------------------
   *
   * 订单与汽车车型关联函数
   *
   * @return [关联对象]
   */
  public function shape()
  {
    return $this->belongsTo(
      'App\Models\Api\Module\Car\Shape',
      'shape_id',
      'id'
    );
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-05-24
   * ------------------------------------------
   * 订单与会员关联函数
   * ------------------------------------------
   *
   * 订单与会员关联函数
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
   * 订单与收货地址关联函数
   * ------------------------------------------
   *
   * 订单与收货地址关联函数
   *
   * @return [关联对象]
   */
  public function address()
  {
    return $this->belongsTo(
      'App\Models\Api\Module\Member\Address',
      'address_id',
      'id'
    );
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-05-24
   * ------------------------------------------
   * 汽车订单与物流关联函数
   * ------------------------------------------
   *
   * 汽车订单与物流关联函数
   *
   * @return [关联对象]
   */
  public function logistics()
  {
    return $this->hasMany(
      'App\Models\Api\Module\Order\Logistics',
      'order_id',
      'id'
    );
  }
}
