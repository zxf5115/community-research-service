<?php
namespace App\Enum\Module\Member;

use App\Enum\BaseEnum;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-12-24
 *
 * 会员设置枚举类
 */
class SettingEnum extends BaseEnum
{
  // 设置类型状态
  const OPEN  = 1; // 送车服务
  const CLOSE = 2; // 门店自提

  // 设置类型封装
  public static $switch = [
    self::OPEN => [
      'value' => self::OPEN,
      'text' => '送车服务'
    ],

    self::CLOSE => [
      'value' => self::CLOSE,
      'text' => '门店自提'
    ]
  ];


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-06-11
   * ------------------------------------------
   * 开关类型封装
   * ------------------------------------------
   *
   * 开关类型封装
   *
   * @param int $code 状态代码
   * @return 状态信息
   */
  public static function getSwitchStatus($code)
  {
    return self::$switch[$code] ?: self::$switch[self::OPEN];
  }
}
