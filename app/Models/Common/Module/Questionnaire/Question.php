<?php
namespace App\Models\Common\Module\Questionnaire;

use App\Models\Base;
use App\Enum\Module\Questionnaire\CategoryEnum;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2022-03-21
 *
 * 调研问卷列项模型类
 */
class Question extends Base
{
  // 表名
  protected $table = "module_questionnaire_question";

  // 隐藏的属性
  protected $hidden = [
    'update_time'
  ];

  // 追加到模型数组表单的访问器
  protected $appends = [
    'rowspan',
    'show',
    'rowspan2',
    'show2',
  ];

  // 批量添加
  protected $fillable = ['id'];


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2022-03-21
   * ------------------------------------------
   * 调研列项类型封装
   * ------------------------------------------
   *
   * 调研列项类型封装
   *
   * @param int $value [数据库存在的值]
   * @return 状态值
   */
  public function getTypeAttribute($value)
  {
    return CategoryEnum::getTypeStatus($value);
  }

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2022-03-21
   * ------------------------------------------
   * 调研列项参数类型封装
   * ------------------------------------------
   *
   * 调研列项参数类型封装
   *
   * @param int $value [数据库存在的值]
   * @return 状态值
   */
  public function getParamsAttribute($value)
  {
    if(2 == $this->type['value'] || 3 == $this->type['value'] || 4 == $this->type['value'] || 6 == $this->type['value'])
    {
      return explode(',', $value);
    }

    return $value;
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2022-03-21
   * ------------------------------------------
   * 是否红点类型封装
   * ------------------------------------------
   *
   * 是否红点类型封装
   *
   * @param int $value [数据库存在的值]
   * @return 状态值
   */
  public function getIsRedAttribute($value)
  {
    return CategoryEnum::getStatus($value);
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2022-03-21
   * ------------------------------------------
   * 纵向合并类型封装
   * ------------------------------------------
   *
   * 纵向合并类型封装
   *
   * @param int $value [数据库存在的值]
   * @return 状态值
   */
  public function getRowspanAttribute($value)
  {
    $where = [
      'category_id' => $this->category_id,
      ['type', '<>', 5]
    ];

    $response = self::getCount($where);

    return $response;
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2022-03-21
   * ------------------------------------------
   * 纵向合并类型封装
   * ------------------------------------------
   *
   * 纵向合并类型封装
   *
   * @param int $value [数据库存在的值]
   * @return 状态值
   */
  public function getShowAttribute($value)
  {
    $response = false;

    $order = [['key' => 'sort', 'value' => 'desc']];

    $where = [
      'category_id' => $this->category_id,
      ['type', [1,2,3,4,6]]
    ];

    $result = self::getRow($where, false, false, $order);

    if($result->id == $this->id)
    {
      $response = true;
    }

    return $response;
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2022-03-21
   * ------------------------------------------
   * 纵向合并类型封装
   * ------------------------------------------
   *
   * 纵向合并类型封装
   *
   * @param int $value [数据库存在的值]
   * @return 状态值
   */
  public function getRowspan2Attribute($value)
  {
    $where = [
      'parent_id' => $this->parent_id,
    ];

    $response = self::getCount($where);

    return $response;
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2022-03-21
   * ------------------------------------------
   * 纵向合并类型封装
   * ------------------------------------------
   *
   * 纵向合并类型封装
   *
   * @param int $value [数据库存在的值]
   * @return 状态值
   */
  public function getShow2Attribute($value)
  {
    $response = false;

    $order = [['key' => 'sort', 'value' => 'desc']];

    $where = [
      'parent_id' => $this->parent_id,
    ];

    $result = self::getRow($where, false, false, $order);

    if($result->id == $this->id)
    {
      $response = true;
    }

    return $response;
  }


  // 关联函数 ------------------------------------------------------

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2022-03-21
   * ------------------------------------------
   * 调研问卷列项与调研问卷关联函数
   * ------------------------------------------
   *
   * 调研问卷列项与调研问卷关联函数
   *
   * @return [关联对象]
   */
  public function questionnaire()
  {
    return $this->belongsTo(
      'App\Models\Common\Module\Questionnaire',
      'questionnaire_id',
      'id'
    );
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2022-03-21
   * ------------------------------------------
   * 调研问卷列项与调研问卷分类关联函数
   * ------------------------------------------
   *
   * 调研问卷列项与调研问卷分类关联函数
   *
   * @return [关联对象]
   */
  public function category()
  {
    return $this->belongsTo(
      'App\Models\Common\Module\Questionnaire\Category',
      'category_id',
      'id'
    );
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2022-03-21
   * ------------------------------------------
   * 调研问卷列项与上级调研问卷列项关联函数
   * ------------------------------------------
   *
   * 调研问卷列项与上级调研问卷列项关联函数
   *
   * @return [关联对象]
   */
  public function parent()
  {
    return $this->belongsTo(
      'App\Models\Common\Module\Questionnaire\Question',
      'parent_id',
      'id',
    );
  }
}
