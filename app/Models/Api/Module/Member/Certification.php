<?php
namespace App\Models\Api\Module\Member;

use App\Models\Common\Module\Member\Certification as Common;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-12-24
 *
 * 会员认证模型类
 */
class Certification extends Common
{
  // 隐藏的属性
  public $hidden = [
    // 'id',
    'organization_id',
    'member_id',
    // 'realname',
    // 'mobile',
    // 'certificate_type',
    // 'certificate_no',
    // 'certificate_no',
    // 'bank_card_no',
    // 'cerificate_front_picture',
    // 'cerificate_behind_picture',
    // 'audit_content',
    'status',
    // 'create_time',
    'update_time'
  ];

}
