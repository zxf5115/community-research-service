<?php
namespace App\Http\Controllers\Platform\System\User;

use App\Http\Constant\Code;
use Illuminate\Http\Request;
use App\Models\Platform\System\Message;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Platform\BaseController;


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-07-14
 *
 * 用户控制器类
 */
class MessageController extends BaseController
{
  protected $_model = 'App\Models\Platform\System\User\Message';

  protected $_where = [];

  protected $_params = [];

  protected $_order = [
    ['key' => 'create_time', 'value' => 'desc'],
  ];

  protected $_relevance = [];


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-27
   * ------------------------------------------
   * 当前用户消息
   * ------------------------------------------
   *
   * 当前用户消息
   *
   * @param Request $request [description]
   * @return [type]
   */
  public function list(Request $request)
  {
    try
    {
      $condition = [
        'user_id' => self::getCurrentId(),
        'status'  => $request->status
      ];

      $response = $this->_model::getPaging($condition, 'message');

      return self::success($response);
    }
    catch(\Exception $e)
    {
      return self::error(Code::ERROR);
    }
  }



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-09-22
   * ------------------------------------------
   * 是否存在未读消息
   * ------------------------------------------
   *
   * 是否存在未读消息
   *
   * @param Request $request [description]
   * @return [type]
   */
  public function unread(Request $request)
  {
    try
    {
      $user_id = $this->user->id;

      $response = $this->_model::getCount(['user_id' => $user_id, 'status' => 1]);

      return self::success($response);
    }
    catch(\Exception $e)
    {
      return self::error(Code::$message[Code::ERROR]);
    }
  }



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-２8
   * ------------------------------------------
   * 消息设置为已读
   * ------------------------------------------
   *
   * 消息设置为已读
   *
   * @param Request $request [请求参数]
   * @return [type]
   */
  public function readed(Request $request)
  {
    try
    {
      $id = $request->id ?? '';

      $current_id = self::getCurrentId();

      // 设置为已读
      $response = $this->_model::setReaded($id, $current_id);

      return self::success($response);
    }
    catch(\Exception $e)
    {
      return self::error(Code::$message[Code::ERROR]);
    }
  }
}
