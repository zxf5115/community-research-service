<?php
namespace App\Http\Controllers\Api\System;

use Illuminate\Http\Request;

use zxf5115\Upload\File;
use App\Http\Controllers\Api\BaseController;
use App\Http\Constant\Common\System\FileCode;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-04-20
 *
 * 文件上传接口控制器类
 */
class FileController extends BaseController
{
  /**
   * @api {post} /api/file/file 01. 上传文件
   * @apiDescription 上传文件
   * @apiGroup 03. 上传模块
   * @apiPermission jwt
   * @apiHeader {String} Authorization 身份令牌
   * @apiHeaderExample {json} Header-Example:
   * {
   *   "Authorization": "Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiO"
   * }
   *
   * @apiParam {string} file 文件数据
   * @apiParam {string} category 文件分类 excel word pdf video audio ...
   *
   * @apiSuccess (响应) {string} data 文件地址
   *
   * @apiSampleRequest /api/file/file
   * @apiVersion 1.0.0
   */
  public function file(Request $request)
  {
    try
    {
      $category = $request->category ?? 'file';

      $response = File::file('file', $category);

      // 如果返回错误代码
      if(false === strpos($response, 'http'))
      {
        return self::message($response);
      }

      return self::success($response);
    }
    catch(\Exception $e)
    {
      // 记录异常信息
      record($e);

      return self::error(FileCode::FILE_UPLOAD_ERROR);
    }
  }


  /**
   * @api {post} /api/file/picture 02. 上传图片
   * @apiDescription 上传图片
   * @apiGroup 03. 上传模块
   * @apiPermission jwt
   * @apiHeader {String} Authorization 身份令牌
   * @apiHeaderExample {json} Header-Example:
   * {
   *   "Authorization": "Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiO"
   * }
   *
   * @apiParam {string} file 图片数据
   * @apiParam {string} category 图片分类 picture avatar ...
   *
   * @apiSuccess (响应) {string} data 图片地址
   *
   * @apiSampleRequest /api/file/picture
   * @apiVersion 1.0.0
   */
  public function picture(Request $request)
  {
    try
    {
      $category = $request->category ?? 'picture';

      $response = File::picture('file', $category);

      // 如果返回错误代码
      if(false === strpos($response, 'http'))
      {
        return self::message($response);
      }

      return self::success($response);
    }
    catch(\Exception $e)
    {
      // 记录异常信息
      record($e);

      return self::error(FileCode::FILE_UPLOAD_ERROR);
    }
  }


  /**
   * @api {post} /api/file/batchPicture 03. 批量上传图片
   * @apiDescription 上传图片
   * @apiGroup 03. 上传模块
   * @apiPermission jwt
   * @apiHeader {String} Authorization 身份令牌
   * @apiHeaderExample {json} Header-Example:
   * {
   *   "Authorization": "Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiO"
   * }
   *
   * @apiParam {string} file 图片数据
   * @apiParam {string} category 图片分类 picture avatar ...
   *
   * @apiSuccess (响应) {string} data 图片地址
   *
   * @apiSampleRequest /api/file/batchPicture
   * @apiVersion 1.0.0
   */
  public function batchPicture(Request $request)
  {
    try
    {
      $category = $request->category ?? 'picture';

      $response = File::batchRichTextFile('file', $category);

      // 如果返回错误代码
      if(!is_array($response))
      {
        return self::message($response);
      }

      return self::success($response);
    }
    catch(\Exception $e)
    {
      // 记录异常信息
      self::record($e);

      return self::error(FileCode::FILE_UPLOAD_ERROR);
    }
  }
}
