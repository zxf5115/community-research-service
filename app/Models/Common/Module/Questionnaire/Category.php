<?php
namespace App\Models\Common\Module\Questionnaire;

use App\Models\Base;
use App\Enum\Module\Questionnaire\CategoryEnum;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2022-03-21
 *
 * 调研问卷分类模型类
 */
class Category extends Base
{
  // 表名
  protected $table = "module_questionnaire_category";

  // 隐藏的属性
  protected $hidden = [
    'update_time'
  ];

  // 追加到模型数组表单的访问器
  protected $appends = [
    'rowspan'
  ];

  // 批量添加
  protected $fillable = ['id'];


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
    $response = 0;

    $question = $this->question;

    foreach($question as $item)
    {
      if(5 != $item->type['value'])
      {
        $response++;
      }
    }

    unset($question);

    return $response;
  }


  // 关联函数 ------------------------------------------------------

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2022-03-21
   * ------------------------------------------
   * 调研问卷信息分类与调研问卷关联函数
   * ------------------------------------------
   *
   * 调研问卷信息分类与调研问卷关联函数
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
   * 调研问卷信息分类与调研问卷列项关联函数
   * ------------------------------------------
   *
   * 调研问卷信息分类与调研问卷列项关联函数
   *
   * @return [关联对象]
   */
  public function question()
  {
    return $this->hasMany(
      'App\Models\Common\Module\Questionnaire\Question',
      'category_id',
      'id'
    );
  }
}
