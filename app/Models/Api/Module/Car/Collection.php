<?php
namespace App\Models\Api\Module\Car;

use App\Models\Common\Module\Car\Collection as Common;


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-05-23
 *
 * 汽车收藏模型类
 */
class Collection extends Common
{
  // 隐藏的属性
  public $hidden = [
    'id',
    'organization_id',
    'member_id',
    'status',
    'update_time',
  ];


  // 关联函数 ------------------------------------------------------

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-05-23
   * ------------------------------------------
   * 汽车收藏与会员关联表
   * ------------------------------------------
   *
   * 汽车收藏与会员关联表
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
   * @dateTime 2021-05-23
   * ------------------------------------------
   * 汽车收藏与汽车关联表
   * ------------------------------------------
   *
   * 汽车收藏与汽车关联表
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
}
