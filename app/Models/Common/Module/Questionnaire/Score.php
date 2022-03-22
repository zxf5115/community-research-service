<?php
namespace App\Models\Common\Module\Questionnaire;

use App\Models\Base;
use App\Enum\Module\Questionnaire\ScoreEnum;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2022-03-22
 *
 * 调研问卷标准模型类
 */
class Score extends Base
{
  // 表名
  protected $table = "module_questionnaire_score";

  // 隐藏的属性
  protected $hidden = [
    'update_time'
  ];

  // 追加到模型数组表单的访问器
  protected $appends = [];

  // 批量添加
  protected $fillable = ['id'];


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2022-03-21
   * ------------------------------------------
   * 是否自动计算类型封装
   * ------------------------------------------
   *
   * 是否自动计算类型封装
   *
   * @param int $value [数据库存在的值]
   * @return 状态值
   */
  public function getIsAutoAttribute($value)
  {
    return ScoreEnum::getTypeStatus($value);
  }
}
