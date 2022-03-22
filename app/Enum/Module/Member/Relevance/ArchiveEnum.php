<?php
namespace App\Enum\Module\Member\Relevance;

use App\Enum\BaseEnum;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-09-25
 *
 * 会员档案枚举类
 */
class ArchiveEnum extends BaseEnum
{
  // 技能水平状态
  const ID       = 1; // 身份证
  const PASSPORT = 2; // 护照
  const OTHER    = 3; // 其他

  // 技能水平状态封装
  public static $type = [
    self::ID => [
      'value' => self::ID,
      'text' => '身份证'
    ],

    self::PASSPORT => [
      'value' => self::PASSPORT,
      'text' => '护照'
    ],

    self::OTHER => [
      'value' => self::OTHER,
      'text' => '其他'
    ]
  ];


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-10-20
   * ------------------------------------------
   * 技能水平状态类型封装
   * ------------------------------------------
   *
   * 技能水平状态类型封装
   *
   * @param int $code 状态代码
   * @return 状态信息
   */
  public static function getCertificateType($code)
  {
    return self::$type[$code] ?: self::$type[self::ID];
  }
}
