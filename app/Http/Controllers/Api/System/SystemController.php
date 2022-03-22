<?php
namespace App\Http\Controllers\Api\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Http\Constant\Code;
use App\Http\Controllers\Api\BaseController;

use App\Models\Api\Module\Member\Member;
use App\Models\Api\Module\Education\Course\Course;
use App\Models\Api\Module\Organization\Organization;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-10-24
 *
 * 系统控制器类
 */
class SystemController extends BaseController
{
  protected $_model = 'App\Models\Api\System\Config';

  protected $_where = [];

  protected $_params = [];

  protected $_order = [];

  protected $_relevance = [];


  /**
   * @api {get} /api/system/kernel 01. 获取系统信息
   * @apiDescription 获取系统配置内容信息
   * @apiGroup 01. 公共模块
   *
   * @apiSuccess (Fields Explain) {String} web_chinese_name 网站中文名称
   * @apiSuccess (Fields Explain) {String} web_english_name 网站英文名字
   * @apiSuccess (Fields Explain) {String} web_url 站点链接
   * @apiSuccess (Fields Explain) {String} keywords 网站关键字
   * @apiSuccess (Fields Explain) {String} description 网站描述
   * @apiSuccess (Fields Explain) {String} logo 网站logo
   * @apiSuccess (Fields Explain) {String} mobile 公司电话
   * @apiSuccess (Fields Explain) {String} email 公司邮箱
   * @apiSuccess (Fields Explain) {String} copyright 备案号
   * @apiSuccess (Fields Explain) {String} web_status 站点状态
   * @apiSuccess (Fields Explain) {String} web_close_info 站点关闭原因
   *
   * @apiSampleRequest /api/system/kernel
   * @apiVersion 1.0.0
   */
  public function kernel(Request $request)
  {
    try
    {
      $where = [
        'category_id' => 1,
        'status' => 1
      ];

      $field = ['value', 'title'];

      $response = $this->_model::getPluck($field, $where);

      return self::success($response);
    }
    catch(\Exception $e)
    {
      // 记录异常信息
      self::record($e);

      return self::error(Code::ERROR);
    }
  }
}
