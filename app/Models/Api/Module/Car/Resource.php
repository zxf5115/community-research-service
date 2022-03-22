<?php
namespace App\Models\Api\Module\Car;

use App\Models\Common\Module\Car\Resource as Common;


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-05-23
 *
 * 汽车资源模型类
 */
class Resource extends Common
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
