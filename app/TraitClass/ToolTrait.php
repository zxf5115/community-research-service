<?php
namespace App\TraitClass;

use App\Models\Platform\Module\Education\Course\Unit;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-10-22
 *
 * 工具特征
 */
trait ToolTrait
{
  protected static $complete_name = [];



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-10-26
   * ------------------------------------------
   * 生成唯一编号信息
   * ------------------------------------------
   *
   * 生成唯一编号信息
   *
   * @param integer $type 1 机构 2 班级 3 会员
   * @return [type]
   */
  public static function generateOnlyNumber($type = 1)
  {
    $params = [
      1 => '107',
      2 => '210',
      3 => '824',
    ];

    $number = $params[$type];

    $number .= date('ymd');

    $rand = mt_rand(1, 999);

    // 如果随机数长度不够3位，自动补0
    if(strlen($rand) < 3)
    {
      $rand = str_pad($rand, 3, 0, STR_PAD_LEFT);
    }

    $number .= $rand;

    return $number;
  }





  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-10-22
   * ------------------------------------------
   * 向上递归查找
   * ------------------------------------------
   *
   * 向上递归查找
   *
   * @param string $id 起始查询id
   * @return [type]
   */
  public static function upWardRecursive($id, &$response)
  {
    try
    {
      $model = Unit::find($id);

      if(empty($model))
      {
        return $response;
      }

      array_push($response, $model->title);

      if($model->parent_id > 0)
      {
        return self::upWardRecursive($model->parent_id, $response);
      }

      return $response;
    }
    catch (\Exception $e)
    {
      \Log::error($e);
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-10-22
   * ------------------------------------------
   * 获取课程单元目录信息
   * ------------------------------------------
   *
   * 获取课程单元目录信息
   *
   * @param [type] $data [description]
   * @return [type]
   */
  public static function getUnitDirectory($data, &$response)
  {
    foreach($data as $k => $item)
    {
      $response[$k] = $item->title;

      if(empty($item->children))
      {
        continue;
      }

      self::getUnitDirectory($item, $response[$k]);
    }
  }
}
