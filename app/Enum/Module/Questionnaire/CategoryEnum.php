<?php
namespace App\Enum\Module\Questionnaire;

use App\Enum\BaseEnum;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2022-03-21
 *
 * 调研问卷分类枚举类
 */
class CategoryEnum extends BaseEnum
{
  // 阅读状态
  const UNREAD = 0;
  const READED = 1;
  const SINGLE = 2;
  const MULTI = 3;
  const MORE = 4;
  const TITLE = 5;
  const THREE = 6;


  // 阅读状态
  public static $type = [
    self::READED => [
      'value' => self::READED,
      'text' => '填空'
    ],

    self::SINGLE => [
      'value' => self::SINGLE,
      'text' => '单选'
    ],

    self::MULTI => [
      'value' => self::MULTI,
      'text' => '多选'
    ],

    self::MORE => [
      'value' => self::MORE,
      'text' => '选填'
    ],

    self::TITLE => [
      'value' => self::TITLE,
      'text' => '标题'
    ],

    self::THREE => [
      'value' => self::THREE,
      'text' => '三选'
    ]
  ];


  // 阅读状态
  public static $status = [
    self::UNREAD => [
      'value' => self::UNREAD,
      'text' => '否'
    ],

    self::READED => [
      'value' => self::READED,
      'text' => '是'
    ]
  ];


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2022-03-21
   * ------------------------------------------
   * 阅读状态状态值
   * ------------------------------------------
   *
   * 阅读状态状态值
   *
   * @param int $code 信息代码
   * @return 信息内容
   */
  public static function getTypeStatus($code)
  {
    return self::$type[$code] ?: self::$type[self::READED];
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2022-03-21
   * ------------------------------------------
   * 阅读状态状态值
   * ------------------------------------------
   *
   * 阅读状态状态值
   *
   * @param int $code 信息代码
   * @return 信息内容
   */
  public static function getStatus($code)
  {
    return self::$status[$code] ?: self::$status[self::UNREAD];
  }
}
