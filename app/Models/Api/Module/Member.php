<?php
namespace App\Models\Api\Module;

use App\Models\Common\Module\Member as Common;


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-10-21
 *
 * 会员模型类
 */
class Member extends Common
{
  // 隐藏的属性
  public $hidden = [
    'organization_id',
    'open_id',
    'password',
    'password_salt',
    'remember_token',
    'condition',
    'last_login_time',
    'last_login_ip',
    'try_number',
    'status',
    'update_time'
  ];


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-07-15
   * ------------------------------------------
   * 登录操作
   * ------------------------------------------
   *
   * 登录操作，更新最后时间，更新失败登录次数
   *
   * @param [type] $request [description]
   * @return [type]
   */
  public static function login($request)
  {
    try
    {
      $request->last_login_time = time();
      $request->try_number = 0;
      $request->save();

      return true;
    }
    catch(\Exception $e)
    {
      self::log($e);

      return false;
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-06-10
   * ------------------------------------------
   * 获取openid
   * ------------------------------------------
   *
   * 获取openid
   *
   * @param string $code [description]
   * @return [type]
   */
  public static function  getUserOpenId($code)
  {
    $param = [];

    $param[] = 'appid=' . getenv('WEIXIN_KEY');
    $param[] = 'secret=' . getenv('WEIXIN_SECRET');
    $param[] = 'js_code=' . $code;
    $param[] = 'grant_type=authorization_code';

    $params = implode('&', $param);    //用&符号连起来

    $url = getenv('WEIXIN_URI') . '?' . $params;

    //请求接口
    $client = new \GuzzleHttp\Client([
        'timeout' => 60
    ]);

    $res = $client->request('GET', $url);

    //openid和session_key
    return json_decode($res->getBody()->getContents(), true);
  }



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-06-10
   * ------------------------------------------
   * 获取微信token信息
   * ------------------------------------------
   *
   * 获取微信token信息
   *
   * @param string $code [description]
   * @return [type]
   */
  public static function  getWeixinToken()
  {
    $param = [];

    $param[] = 'grant_type=client_credential';
    $param[] = 'appid=' . getenv('WEIXIN_KEY');
    $param[] = 'secret=' . getenv('WEIXIN_SECRET');

    $params = implode('&', $param);    //用&符号连起来

    $url = getenv('WEIXIN_TOKEN_URL') . '?' . $params;

    //请求接口
    $client = new \GuzzleHttp\Client([
        'timeout' => 60
    ]);

    $res = $client->request('GET', $url);

    return json_decode($res->getBody()->getContents(), true);
  }



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-06-10
   * ------------------------------------------
   * 获取微信小程序二维码
   * ------------------------------------------
   *
   * 获取微信小程序二维码
   *
   * @param string $token 微信token
   * @param string $member_id 邀请人编号
   * @return [type]
   */
  public static function  getQrCode($token, $member_id)
  {
    $param = [];

    $param[] = 'access_token=' . $token;

    $params = implode('&', $param);    //用&符号连起来

    $url = getenv('WEIXIN_QRCODE_URL') . '?' . $params;

    //请求接口
    $client = new \GuzzleHttp\Client([
        'timeout' => 60
    ]);

    $res = $client->request('POST', $url, [
      'json' => [
        'path' => 'pages/home/index/index?member_id='.$member_id
      ]
    ]);

    return $res->getBody()->getContents();
  }





  // 关联函数 ------------------------------------------------------


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-20
   * ------------------------------------------
   * 会员与机构关联表
   * ------------------------------------------
   *
   * 会员与机构关联表
   *
   * @return [关联对象]
   */
  public function organization()
  {
    return $this->belongsTo(
      'App\Models\Api\Module\Organization\Organization',
      'organization_id',
      'id'
    )->where(['status'=>1]);
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-10-20
   * ------------------------------------------
   * 会员与会员档案关联表
   * ------------------------------------------
   *
   * 会员与会员档案关联表
   *
   * @return [关联对象]
   */
  public function archive()
  {
    return $this->hasOne(
      'App\Models\Api\Module\Member\Archive',
      'member_id',
      'id'
    );
  }

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-10-20
   * ------------------------------------------
   * 会员与会员资产关联表
   * ------------------------------------------
   *
   * 会员与会员资产关联表
   *
   * @return [关联对象]
   */
  public function asset()
  {
    return $this->hasOne(
      'App\Models\Api\Module\Member\Asset',
      'member_id',
      'id'
    );
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-10-20
   * ------------------------------------------
   * 会员与会员地址关联表
   * ------------------------------------------
   *
   * 会员与会员地址关联表
   *
   * @return [关联对象]
   */
  public function address()
  {
    return $this->belongsTo(
      'App\Models\Api\Module\Member\Address',
      'id',
      'member_id'
    );
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-10-20
   * ------------------------------------------
   * 会员与会员认证关联表
   * ------------------------------------------
   *
   * 会员与会员认证关联表
   *
   * @return [关联对象]
   */
  public function certification()
  {
    return $this->belongsTo(
      'App\Models\Api\Module\Member\Certification',
      'id',
      'member_id'
    );
  }
}
