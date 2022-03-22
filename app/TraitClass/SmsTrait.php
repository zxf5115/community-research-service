<?php
namespace App\TraitClass;

trait SmsTrait
{
  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-08-27
   * ------------------------------------------
   * 发送验证短信
   * ------------------------------------------
   *
   * 发送验证短信
   *
   * @param string $mobile 接收者手机号
   * @param string $content 短信内容
   * @return [type]
   */
  public static function sendValidationSms($mobile, $content)
  {
    $sms = app('easysms');

    try
    {
        $sms->send($mobile, [
            'template' => 'SMS_205123382',
            'data' => [
              $content,
              'from' => 'code'
            ]
        ]);
    }
    catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception)
    {
      $message = $exception->getException('huawei')->getMessage();

      \Log::error($message);
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-08-27
   * ------------------------------------------
   * 发送注册短信
   * ------------------------------------------
   *
   * 发送注册短信
   *
   * @param string $mobile 接收者手机号
   * @param string $content 短信内容
   * @return [type]
   */
  public static function sendRegistereSms($mobile)
  {
    $sms = app('easysms');

    try
    {
        $sms->send($mobile, [
            'template' => 'SMS_205123383',
            'data' => [
              $mobile, '123456',
              'from' => 'code'
            ]
        ]);
    }
    catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception)
    {
      $message = $exception->getException('huawei')->getMessage();

      \Log::error($message);
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-08-27
   * ------------------------------------------
   * 发送重置短信
   * ------------------------------------------
   *
   * 发送重置短信
   *
   * @param string $mobile 接收者手机号
   * @param string $content 短信内容
   * @return [type]
   */
  public static function sendDeleteSms($mobile)
  {
    $sms = app('easysms');

    try
    {
        $sms->send($mobile, [
            'template' => 'SMS_205123384',
            'data' => [
              $mobile
            ]
        ]);
    }
    catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception)
    {
      $message = $exception->getException('huawei')->getMessage();

      \Log::error($message);
    }
  }
}
