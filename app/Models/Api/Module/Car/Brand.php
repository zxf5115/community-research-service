<?php
namespace App\Models\Api\Module\Car;

use App\Models\Common\Module\Car\Brand as Common;


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-05-20
 *
 * 汽车品牌模型类
 */
class Brand extends Common
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
   * 品牌与车型关联函数
   * ------------------------------------------
   *
   * 品牌与车型关联函数
   *
   * @return [关联对象]
   */
  public function shape()
  {
    return $this->hasMany(
      'App\Models\Api\Module\Car\Shape',
      'brand_id',
      'id',
    );
  }
}
