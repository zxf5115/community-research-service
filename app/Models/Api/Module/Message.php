<?php
namespace App\Models\Api\Module;

use App\Models\Common\Module\Message as Common;


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-07-20
 *
 * 消息模型类
 */
class Message extends Common
{
  // 隐藏的属性
  public $hidden = [
    'organization_id',
    'founder_id',
    'status',
    'create_time',
    'update_time'
  ];


}
