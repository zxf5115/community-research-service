<?php
namespace App\Enum\Module\Member\Message;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-07-20
 *
 * 会员消息枚举类
 */
class MessageEnum
{
  // 菜单分类状态
  const NOTICE       = 1; // 已读
  const ANNOUNCEMENT = 2; // 未读


  // 分类状态
  public static $type = [
    self::NOTICE       => [
      'value' => self::NOTICE,
      'text' => '已读'
    ],

    self::ANNOUNCEMENT => [
      'value' => self::ANNOUNCEMENT,
      'text' => '未读'
    ],
  ];



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-07-05
   * ------------------------------------------
   * 消息类型封装
   * ------------------------------------------
   *
   * 消息类型封装
   *
   * @param int $code 信息代码
   * @return 信息内容
   */
  public static function getStatus($code)
  {
    return self::$type[$code] ?: self::$type[self::NOTICE];
  }
}
