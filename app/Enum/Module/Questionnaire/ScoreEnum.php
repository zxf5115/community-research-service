<?php
namespace App\Enum\Module\Questionnaire;

use App\Enum\BaseEnum;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2022-03-21
 *
 * 调研问卷评分类型枚举类
 */
class ScoreEnum extends BaseEnum
{
  // 评分类型
  const UNREAD = 0;
  const READED = 1;
  const SINGLE = 2;


  // 评分类型
  public static $type = [
    self::UNREAD => [
      'value' => self::UNREAD,
      'text' => '否'
    ],

    self::READED => [
      'value' => self::READED,
      'text' => '是'
    ],

    self::SINGLE => [
      'value' => self::SINGLE,
      'text' => '调整'
    ],
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
    return self::$type[$code] ?: self::$type[self::UNREAD];
  }
}
