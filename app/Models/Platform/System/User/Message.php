<?php
namespace App\Models\Platform\System\User;

use App\Models\Common\System\User\Message as Common;


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-07-17
 *
 * 系统用户消息模型类
 */
class Message extends Common
{
  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-28
   * ------------------------------------------
   * 设置为已读消息
   * ------------------------------------------
   *
   * 设置为已读消息
   *
   * @param int $id [消息id]
   */
  public static function setReaded($id, $current_id)
  {
    try
    {
      $model = self::where(['user_id' => $current_id]);

      // id 为0时表示为全部已读
      if($id > 0)
      {
        $model->where(['id' => $id]);
      }

      return $model->update(['status' => 2]);
    }
    catch(\Exception $e)
    {
      return self::log($e);
    }
  }
}
