<?php
namespace App\Crontab\Platform;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Common\System\Config;
use App\Models\Common\System\Message;
use App\Models\Common\Module\Order\Course;
use App\Models\Common\System\User\UserRoleRelevance;
use App\Models\Common\System\User\Message as UserMessage;
use App\Models\Common\Module\Education\Courseware\Courseware;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-02-23
 *
 * 定时发货定时任务
 */
class Notice extends Controller
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
      // 获取系统课id
      $courseware_id = Config::getConfigValue('system_class');

      $where = [
        'status' => 1,
        'id'     => $courseware_id
      ];

      // 获取系统课课件信息
      $courseware = Courseware::getRow($where);

      if(empty($courseware))
      {
        \Log::error('暂无系统课');

        return false;
      }

      // 获取课件编号
      $courseware_id = $courseware->id;

      $where = [
        'pay_status'    => 1,
        'courseware_id' => $courseware_id
      ];

      // 获取课程订单
      $course = Course::getList($where, ['member', 'course']);

      if(empty($course))
      {
        \Log::error('暂无系统课');

        return false;
      }

      // 循环
      foreach($course as $item)
      {
        // 获取订单创建的时间戳
        $timestamp = strtotime($item->create_time);

        // 验证时间是否正确
        $status = $this->validationDate($timestamp);

        // 如果时间正确，发送站内信
        if($status)
        {
          $this->sendNotice($item);
        }
      }

      DB::commit();

      \Log::info('系统课礼包定时发送完成');
    }
    catch(\Exception $e)
    {
      DB::rollback();

      \Log::error($e);
    }
  }


  private function sendNotice($data)
  {
    try
    {
      $message = new Message();

      $content = "{$data->member->nickname} 的 {$data->course->title} 课程, 应该发送新的课程礼包啦！";

      $message->type        = 4;
      $message->title       = 4;
      $message->content     = $content;
      $message->accept_type = 'role';
      $message->author      = '定时通知';

      $message->save();

      $where = [
        'role_id' => 1
      ];

      // 获取系统管理员人员
      $user = UserRoleRelevance::getList($where);

      foreach($user as $item)
      {
        $userMessage = new UserMessage();

        $userMessage->user_id    = $item->user_id;
        $userMessage->message_id = $message->id;

        $userMessage->save();
      }
    }
    catch(\Exception $e)
    {
      \Log::error($e);
    }


  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-02-24
   * ------------------------------------------
   * 验证是否可以发送站内信
   * ------------------------------------------
   *
   * 验证是否可以发送站内信
   *
   * @param [type] $timestamp [description]
   * @return [type]
   */
  private function validationDate($timestamp)
  {
    try
    {
      $where = [
        'category_id' => 10
      ];

      // 系统课发货提现
      $config = Config::getPluck(['value', 'title'], $where);

      if(empty($config))
      {
        \Log::error('暂无系统课发货配置信息');

        return false;
      }

      $remind_interval = $config['remind_interval'] * 86400;
      $remind_number   = $config['remind_number'];

      for($i = 1; $i <= $remind_number; $i++)
      {
        if(time() > ($timestamp + ($remind_interval * $i)) && time() < ($timestamp + ($remind_interval * $i) + 86400))
        {
          return true;
        }
      }

      return false;
    }
    catch(\Exception $e)
    {
      \Log::error($e);
    }
  }
}
