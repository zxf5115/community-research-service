<?php
namespace App\Models\Api\Module;

use App\Models\Common\Module\Store as Common;


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-05-20
 *
 * 门店模型类
 */
class Store extends Common
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
   * @dateTime 2021-07-01
   * ------------------------------------------
   * 计算两个经纬度之间的距离
   * ------------------------------------------
   *
   * 计算两个经纬度之间的距离
   *
   * @param [type] $longitude [description]
   * @param [type] $latitude [description]
   * @param [type] $data [description]
   * @return [type]
   */
  public static function getDistanceData($longitude, $latitude, $data)
  {
    $radLat1 = deg2rad(floatval($latitude));
    $radLat2 = deg2rad(floatval($data['latitude']));

    $radLng1 = deg2rad(floatval($longitude));
    $radLng2 = deg2rad(floatval($data['longitude']));

    $a = $radLat1 - $radLat2;

    $b = $radLng1 - $radLng2;

    $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137;

    return round($s, 2);//返回公里数
  }
}
