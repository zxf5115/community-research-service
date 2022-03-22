<?php
namespace App\Enum\Module\Member\Relevance;

use App\Enum\BaseEnum;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-12-24
 *
 * 会员红包枚举类
 */
class MoneyEnum extends BaseEnum
{
  // 红包类型状态
  const INCOME = 1; // 收入
  const EXPEND = 2; // 提现

  const WAIT = 0; // 待提现
  const FINISH = 1; //已同意
  const ERROR = 2; //已失败

  // 红包类型封装
  public static $type = [
    self::INCOME => [
      'value' => self::INCOME,
      'text' => '收入'
    ],

    self::EXPEND => [
      'value' => self::EXPEND,
      'text' => '提现'
    ]
  ];

  // 提现类型封装
  public static $withdrawal_type = [
    self::INCOME => [
      'value' => self::INCOME,
      'text' => '支付宝提现'
    ],

    self::EXPEND => [
      'value' => self::EXPEND,
      'text' => '微信提现'
    ]
  ];

  // 提现状态封装
  public static $withdrawal_status = [
    self::WAIT => [
      'value' => self::WAIT,
      'text' => '已申请'
    ],

    self::FINISH => [
      'value' => self::FINISH,
      'text' => '已到账'
    ],

    self::ERROR => [
      'value' => self::ERROR,
      'text' => '已失败'
    ]
  ];

  // 审核类型封装
  public static $audit_type = [
    self::INCOME => [
      'value' => self::INCOME,
      'text' => '人工审核'
    ],

    self::EXPEND => [
      'value' => self::EXPEND,
      'text' => '自动审核'
    ]
  ];



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-12-24
   * ------------------------------------------
   * 红包类型封装
   * ------------------------------------------
   *
   * 红包类型封装
   *
   * @param int $code 状态代码
   * @return 状态信息
   */
  public static function getTypeStatus($code)
  {
    return self::$type[$code] ?: self::$type[self::INCOME];
  }

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-12-24
   * ------------------------------------------
   * 提现方式封装
   * ------------------------------------------
   *
   * 提现方式封装
   *
   * @param int $code 状态代码
   * @return 状态信息
   */
  public static function getWithdrawalTypeStatus($code)
  {
    return self::$withdrawal_type[$code] ?: self::$withdrawal_type[self::INCOME];
  }

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-12-24
   * ------------------------------------------
   * 提现状态封装
   * ------------------------------------------
   *
   * 提现状态封装
   *
   * @param int $code 状态代码
   * @return 状态信息
   */
  public static function getWithdrawalStatus($code)
  {
    return self::$withdrawal_status[$code] ?: self::$withdrawal_status[self::WAIT];
  }

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-12-24
   * ------------------------------------------
   * 审核方式封装
   * ------------------------------------------
   *
   * 审核方式封装
   *
   * @param int $code 状态代码
   * @return 状态信息
   */
  public static function getAuditTypeStatus($code)
  {
    return self::$audit_type[$code] ?: self::$audit_type[self::INCOME];
  }
}
