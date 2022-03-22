<?php
namespace App\Models\Api\Module\Car;

use App\Models\Common\Module\Car\Source as Common;


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-05-20
 *
 * 汽车来源模型类
 */
class Source extends Common
{
  // 隐藏的属性
  public $hidden = [
    'organization_id',
    'sort',
    'status',
    'create_time',
    'update_time'
  ];
}
