<?php
namespace App\Enum\Module\Member\Relevance;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-12-20
 *
 * 会员认证枚举类
 */
class CertificationEnum
{
  const WAIT   = 0; // 待审核
  const FINISH = 1; // 审核通过
  const ERROR  = 2; // 审核不通过

  const PEOPLE  = 1; // 个人认证
  const CARD    = 2; // 银行卡认证
  const COMPNAY = 3; // 车商认证

  // 审核状态封装
  public static $audit = [
    self::WAIT => [
      'value' => self::WAIT,
      'text' => '待审核'
    ],

    self::FINISH => [
      'value' => self::FINISH,
      'text' => '审核通过'
    ],

    self::ERROR => [
      'value' => self::ERROR,
      'text' => '审核不通过'
    ]
  ];


  // 认证状态封装
  public static $certification = [
    self::PEOPLE => [
      'value' => self::PEOPLE,
      'text' => '个人认证'
    ],

    self::CARD => [
      'value' => self::CARD,
      'text' => '银行卡认证'
    ],

    self::COMPNAY => [
      'value' => self::COMPNAY,
      'text' => '车商认证'
    ]
  ];


  // 证件类型封装
  public static $certificate = [
    self::PEOPLE => [
      'value' => self::PEOPLE,
      'text' => '中华共和国居民身份证'
    ],

    self::CARD => [
      'value' => self::CARD,
      'text' => '营业执照'
    ]
  ];



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-10-20
   * ------------------------------------------
   * 认证状态封装
   * ------------------------------------------
   *
   * 认证状态封装
   *
   * @param int $code 状态代码
   * @return 状态信息
   */
  public static function getAuditStatus($code)
  {
    return self::$audit[$code] ?: self::$audit[self::WAIT];
  }

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-10-20
   * ------------------------------------------
   * 认证状态封装
   * ------------------------------------------
   *
   * 认证状态封装
   *
   * @param int $code 状态代码
   * @return 状态信息
   */
  public static function getCertificationStatus($code)
  {
    return self::$certification[$code] ?: self::$certification[self::PEOPLE];
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-10-20
   * ------------------------------------------
   * 证书类型封装
   * ------------------------------------------
   *
   * 证书类型封装
   *
   * @param int $code 状态代码
   * @return 状态信息
   */
  public static function getCertificateType($code)
  {
    return self::$certificate[$code] ?: self::$certificate[self::PEOPLE];
  }
}
