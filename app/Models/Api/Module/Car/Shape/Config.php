<?php
namespace App\Models\Api\Module\Car\Shape;

use App\Enum\Module\Car\Shape\ConfigEnum;
use App\Models\Common\Module\Car\Shape\Config as Common;


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-05-20
 *
 * 汽车车型配置模型类
 */
class Config extends Common
{
  // 隐藏的属性
  public $hidden = [
    'organization_id',
    'sort',
    'status',
    'create_time',
    'update_time'
  ];


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-07-23
   * ------------------------------------------
   * 订单状态封装
   * ------------------------------------------
   *
   * 订单状态封装
   *
   * @param [type] $value [description]
   * @return [type]
   */
  public function getTypeAttribute($value)
  {
    return ConfigEnum::getTypeStatus($value);
  }



  // public function getParamsAttribute($value)
  // {
  //   $data = explode(',', $value);

  //   foreach($data as &$item)
  //   {
  //     $item = trim($item);
  //   }

  //   return $data;
  // }
}
