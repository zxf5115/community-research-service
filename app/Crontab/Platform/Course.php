<?php
namespace App\Crontab\Platform;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Common\Module\Education\Courseware\Courseware;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-02-23
 *
 * 定时下架课程定时任务
 */
class Course extends Controller
{
  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-08-26
   * ------------------------------------------
   * 系统课礼包发货
   * ------------------------------------------
   *
   * 系统课礼包发货
   *
   * @return [type]
   */
  public function action()
  {
    DB::beginTransaction();

    try
    {
      $where = [
        'status'       => 1,
        'is_permanent' => 2,
        ['end_time', '<', time()]
      ];

      // 获取系统课课件信息
      $courseware = Courseware::getRow($where);

      if(empty($courseware))
      {
        \Log::error('暂无系统课');

        return false;
      }

      $courseware->status = 2;
      $courseware->save();

      DB::commit();

      \Log::info('循环课程下架');
    }
    catch(\Exception $e)
    {
      DB::rollback();

      \Log::error($e);
    }
  }
}
