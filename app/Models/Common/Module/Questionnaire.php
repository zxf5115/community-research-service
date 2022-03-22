<?php
namespace App\Models\Common\Module;

use App\Models\Base;
use App\Http\Constant\Status;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2022-03-21
 *
 * 调研问卷模型类
 */
class Questionnaire extends Base
{
  // 表名
  protected $table = "module_questionnaire";

  // 隐藏的属性
  protected $hidden = [
    'update_time'
  ];

  // 追加到模型数组表单的访问器
  protected $appends = [];

  // 批量添加
  protected $fillable = ['id'];


  // 关联函数 ------------------------------------------------------


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2022-03-21
   * ------------------------------------------
   * 调研问卷与信息分类关联函数
   * ------------------------------------------
   *
   * 调研问卷与信息分类关联函数
   *
   * @return [关联对象]
   */
  public function category()
  {
    return $this->hasMany(
      'App\Models\Common\Module\Questionnaire\Category',
      'questionnaire_id',
      'id'
    );
  }
}
