<?php
namespace App\Enum\Module\Member\Relevance;

use App\Enum\BaseEnum;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-12-24
 *
 * 会员棒棒糖枚举类
 */
class LollipopEnum extends BaseEnum
{
  // 棒棒糖类型状态
  const INCOME = 1; // 获取
  const EXPEND = 2; // 消费

  // 棒棒糖类型封装
  public static $type = [
    self::INCOME => [
      'value' => self::INCOME,
      'text' => '获取'
    ],

    self::EXPEND => [
      'value' => self::EXPEND,
      'text' => '消费'
    ]
  ];


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-12-24
   * ------------------------------------------
   * 棒棒糖类型封装
   * ------------------------------------------
   *
   * 棒棒糖类型封装
   *
   * @param int $code 状态代码
   * @return 状态信息
   */
  public static function getTypeStatus($code)
  {
    return self::$type[$code] ?: self::$type[self::INCOME];
  }
}
