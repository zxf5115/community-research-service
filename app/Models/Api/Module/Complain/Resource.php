<?php
namespace App\Models\Api\Module\Complain;

use App\Models\Common\Module\Complain\Resource as Common;


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-05-28
 *
 * 投诉资源模型类
 */
class Resource extends Common
{
  // 隐藏的属性
  public $hidden = [
    'id',
    'organization_id',
    'complain_id',
    'status',
    'create_time',
    'update_time'
  ];
}
