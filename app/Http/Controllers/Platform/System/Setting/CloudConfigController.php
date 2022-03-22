<?php
namespace App\Http\Controllers\Platform\System\Setting;

use App\Http\Constant\Code;
use Illuminate\Http\Request;
use App\Models\Platform\System\Config;
use App\Models\Platform\System\Config\Category;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Platform\BaseController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-07-13
 *
 * 云存储配置控制器类
 */
class CloudConfigController extends BaseController
{
  protected $_model = 'App\Models\Platform\System\Config';

  protected $_where = [];

  protected $_params = [];

  protected $_order = [
    ['key' => 'sort', 'value' => 'asc'],
  ];

  protected $_relevance = 'category';


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-07-13
   * ------------------------------------------
   * QQ登录配置
   * ------------------------------------------
   *
   * 配置QQ登录配置信息
   *
   * @param Request $request 请求数据
   * @return [type]
   */
  public function aliyun(Request $request)
  {
    if($request->isMethod('post'))
    {
      $response = Config::saveConfig($request->all());

      if($response)
      {
        return self::success();
      }

      return self::error(Code::HANDLE_FAILURE);
    }

    $where = [
      'title' => 'ALICLOUD_UPLOAD_CONFIG',
      'status' => 1
    ];

    $category = Category::getRow($where);
    $category_id = $category->id ?? 0;
    $where = [
      'category_id' => $category_id
    ];

    $response = $this->_model::getList($where);

    return self::success($response);
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-07-13
   * ------------------------------------------
   * 微信登录配置
   * ------------------------------------------
   *
   * 配置微信登录配置信息
   *
   * @param Request $request 请求数据
   * @return [type]
   */
  public function qiniu(Request $request)
  {
    if($request->isMethod('post'))
    {
      $response = Config::saveConfig($request->all());

      if($response)
      {
        return self::success();
      }

      return self::error(Code::HANDLE_FAILURE);
    }

    $where = [
      'title' => 'QINIU_UPLOAD_CONFIG',
      'status' => 1
    ];

    $category = Category::getRow($where);
    $category_id = $category->id ?? 0;
    $where = [
      'category_id' => $category_id
    ];

    $response = $this->_model::getList($where);

    return self::success($response);
  }

}
