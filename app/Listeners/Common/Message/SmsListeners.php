<?php
namespace App\Listeners\Common\Message;

use App\Events\Common\Message\SmsEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SmsListeners
{
  /**
   * Create the event listener.
   *
   * @return void
   */
  public function __construct()
  {
      //
  }

  /**
   * Handle the event.
   *
   * @param  SmsEvent  $event
   * @return void
   */
  public function handle(SmsEvent $event)
  {
    $type   = $event->type;
    $mobile = $event->mobile;
    $code   = $event->code;

    // 登录验证码
    if(1 == $type)
    {
      $this->code($mobile, $code);
    }
    else if(2 == $type)
    {
      $this->bind($mobile, $code);
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-10-29
   * ------------------------------------------
   * 获取登录验证码
   * ------------------------------------------
   *
   * 获取登录验证码
   *
   * @param [type] $mobile 手机号码
   * @param [type] $code 验证码
   * @return [type]
   */
  protected function code($mobile, $code)
  {
    $sms = app('easysms');

    try
    {
        $sms->send($mobile, [
            'template' => '02858fdfa87a4c6eb2821d805091dd84',
            'data' => [
              $code
            ]
        ]);
    }
    catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception)
    {
      $message = $exception->getException('huawei')->getMessage();

      \Log::error($message);

      throw new \Exception($message);
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-01-07
   * ------------------------------------------
   * 获取绑定验证码
   * ------------------------------------------
   *
   * 获取绑定验证码
   *
   * @param [type] $mobile 手机号码
   * @param [type] $code 验证码
   * @return [type]
   */
  protected function bind($mobile, $code)
  {
    $sms = app('easysms');

    try
    {
        $sms->send($mobile, [
            'template' => '02858fdfa87a4c6eb2821d805091dd84',
            'data' => [
              $code
            ]
        ]);
    }
    catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception)
    {
      $message = $exception->getException('huawei')->getMessage();

      \Log::error($message);

      throw new \Exception($message);
    }
  }
}
