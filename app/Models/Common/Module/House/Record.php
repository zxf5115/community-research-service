<?php
namespace App\Models\Common\Module\House;

use App\Models\Base;
use App\Http\Constant\Status;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2022-03-23
 *
 * 楼宇记录人类
 */
class Record extends Base
{
  // 表名
  protected $table = "module_house_record";

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
  ];


  // 关联函数 ------------------------------------------------------


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2022-03-23
   * ------------------------------------------
   * 楼宇记录人与楼宇关联函数
   * ------------------------------------------
   *
   * 楼宇记录人与楼宇关联函数
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
}
