<?php
namespace App\Enum\Common;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-10-21
 *
 * 时间枚举类
 */
class TimeEnum
{
  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-10-21
   * ------------------------------------------
   * 时长封装
   * ------------------------------------------
   *
   * 时长封装
   *
   * @param int $value 时间（秒）
   * @return 时长信息
   */
  public static function getTimeLength($value)
  {
    if (empty($value))
    {
      return '暂无';
    }

    $day = floor($value / (3600 * 24));

    $day = $day > 0 ? $day . ' 天 ' : '';

    $hour = floor(($value % (3600 * 24)) / 3600);

    $hour = $hour > 0 ? $hour . ' 小时 ' : '';

    $minutes = floor((($value % (3600 * 24)) % 3600) / 60);

    $minutes = $minutes > 0 ? $minutes . ' 分钟 ' : '';

    $seconds = floor((($value % (3600 * 24)) % 3600 % 60) / 60);

    $seconds = $seconds > 0 ? $seconds . ' 秒 ' : '';

    return $day . $hour . $minutes . $seconds;
  }



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-10-21
   * ------------------------------------------
   * 计算天数封装
   * ------------------------------------------
   *
   * 计算天数封装
   *
   * @param int $value 时间（秒）
   * @return 时长信息
   */
  public static function getDayNumber($value)
  {
    if (empty($value))
    {
      return '0 天 ';
    }

    $day = floor($value / (3600 * 24));

    $day = $day > 0 ? $day . ' 天 ' : '0 天 ';

    return $day;
  }


  // 日期时间封装
  public static function getDateTime($value)
  {
    $text = '0';

    if(0 != $value)
    {
      $text = date('Y-m-d H:i:s', $value);
    }

    return [
      'text' => $text,
      'value' => $value,
    ];
  }
}
