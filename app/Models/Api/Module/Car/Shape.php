<?php
namespace App\Models\Api\Module\Car;

use App\Models\Common\Module\Car\Shape as Common;


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-05-20
 *
 * 汽车车型模型类
 */
class Shape extends Common
{
  // 隐藏的属性
  public $hidden = [
    'organization_id',
    'sort',
    'status',
    'create_time',
    'update_time'
  ];



  // 关联函数 ------------------------------------------------------

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-05-21
   * ------------------------------------------
   * 车型与品牌关联函数
   * ------------------------------------------
   *
   * 车型与品牌关联函数
   *
   * @return [关联对象]
   */
  public function brand()
  {
    return $this->hasOne(
      'App\Models\Api\Module\Car\Brand',
      'id',
      'brand_id',
    );
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-05-21
   * ------------------------------------------
   * 车型与车型配置关联函数
   * ------------------------------------------
   *
   * 车型与车型配置关联函数
   *
   * @return [关联对象]
   */
  public function config()
  {
    return $this->hasMany(
      'App\Models\Api\Module\Car\Shape\Config',
      'shape_id',
      'id',
    );
  }
}
