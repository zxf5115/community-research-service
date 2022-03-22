<?php
namespace App\TraitClass;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-05-25
 *
 * 统计特征
 */
trait StatisticalTrait
{

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-05-25
   * ------------------------------------------
   * 获取统计时间区间
   * ------------------------------------------
   *
   * 获取统计时间区间
   *
   * @param integer $type 今日、最近七日、最近一个月、最近三个月、最近半年、最近一
   * @return [type]
   */
  public static function getWhereCondition($type = 1)
  {
    $response = [];

    if(1 == $type)
    {
      // 今天
      $today = strtotime(date("Y-m-d"),time());

      $response = [
        'status' => 1,
        ['create_time', '>', $today]
      ];
    }
    else if(2 == $type)
    {
      // 一周前
      $today = strtotime('-1 week');

      $response = [
        'status' => 1,
        ['create_time', '>', $today]
      ];
    }
    else if(3 == $type)
    {
      // 一个月前
      $today = strtotime('-1 month');

      $response = [
        'status' => 1,
        ['create_time', '>', $today]
      ];
    }
    else if(4 == $type)
    {
      // 三个月前
      $today = strtotime('-3 month');

      $response = [
        'status' => 1,
        ['create_time', '>', $today]
      ];
    }
    else if(5 == $type)
    {
      // 六个月前
      $today = strtotime('-6 month');

      $response = [
        'status' => 1,
        ['create_time', '>', $today]
      ];
    }
    else if(6 == $type)
    {
      // 一年前
      $today = strtotime('-1 year');

      $response = [
        'status' => 1,
        ['create_time', '>', $today]
      ];
    }

    return $response;
  }
}
