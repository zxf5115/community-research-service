<?php
namespace App\Models\Api\Module\Car;

use App\Models\Common\Module\Car\Config as Common;


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-05-23
 *
 * 汽车配置模型类
 */
class Config extends Common
{
  // 隐藏的属性
  public $hidden = [
    'id',
    'organization_id',
    'car_id',
    'status',
    'create_time',
    'update_time'
  ];
}
