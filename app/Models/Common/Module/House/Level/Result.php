<?php
namespace App\Models\Common\Module\House\Level;

use App\Models\Base;
use App\Http\Constant\Status;
use App\Models\Common\Module\Questionnaire\Question;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2022-03-23
 *
 * 商务楼宇结果类
 */
class Result extends Base
{
  // 表名
  protected $table = "module_house_level_result";

  // 隐藏的属性
  protected $hidden = [
    'update_time'
  ];

  // 追加到模型数组表单的访问器
  protected $appends = [];

  // 批量添加
  protected $fillable = [
    'id',
    'house_id',
    'question_id',
  ];

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
  public function getResultAttribute($value)
  {
    $type = Question::getValue('type', ['id' => $this->question_id]);

    if(3 == $type['value'] || 6 == $type['value'])
    {
      return explode(',', $value);
    }

    return $value;
  }


  // 关联函数 ------------------------------------------------------


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2022-03-23
   * ------------------------------------------
   * 楼宇结果与楼宇关联函数
   * ------------------------------------------
   *
   * 楼宇结果与楼宇关联函数
   *
   * @return [关联对象]
   */
  public function house()
  {
    return $this->belongsTo(
      'App\Models\Common\Module\House',
      'house_id',
      'id',
    );
  }

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2022-03-23
   * ------------------------------------------
   * 楼宇结果与调研试题关联函数
   * ------------------------------------------
   *
   * 楼宇结果与调研试题关联函数
   *
   * @return [关联对象]
   */
  public function question()
  {
    return $this->belongsTo(
      'App\Models\Common\Module\Questionnaire\Question',
      'question_id',
      'id',
    );
  }
}
