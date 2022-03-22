<?php
namespace App\Models\Api\Module\Car\Shape;

use App\Models\Common\Module\Car\Shape\Attention as Common;


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-07-19
 *
 * 汽车车型关注模型类
 */
class Attention extends Common
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
   * @dateTime 2021-07-19
   * ------------------------------------------
   * 车型关注与会员关联表
   * ------------------------------------------
   *
   * 车型关注与会员关联表
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
   * @dateTime 2021-07-19
   * ------------------------------------------
   * 车型关注与车型关联表
   * ------------------------------------------
   *
   * 车型关注与车型关联表
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
}
