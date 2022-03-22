<?php
namespace App\Http\Controllers\Platform\Module;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Constant\Code;
use App\Models\Platform\Module\House\Result;
use App\Http\Controllers\Platform\BaseController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2022-03-23
 *
 * 前台消息控制器类
 */
class HouseController extends BaseController
{
  protected $_model = 'App\Models\Platform\Module\House';


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-07-20
   * ------------------------------------------
   * 操作信息
   * ------------------------------------------
   *
   * 操作信息
   *
   * @param Request $request [请求参数]
   * @return [type]
   */
  public function handle(Request $request)
  {
    $messages = [
      'id.required'   => '请您输入楼宇自增编号',
    ];

    $rule = [
      'id'   => 'required',
    ];

    // 验证用户数据内容是否正确
    $validation = self::validation($request, $messages, $rule);

    if(!$validation['status'])
    {
      return $validation['message'];
    }
    else
    {
      DB::beginTransaction();

      try
      {
        $model = $this->_model::getRow(['id' => $request->id]);

        $data = $request->result;

        if(!is_null($data[1]))
        {
          $model->title = $data[1];
        }

        if(!is_null($data[2]))
        {
          $model->address = $data[2];
        }

        if(!is_null($data[3]))
        {
          $model->street = $data[3];
        }

        $model->save();

        foreach($data as $key => $item)
        {
          if(!is_null($item))
          {
            if(is_array($item) && count($item) == 0)
            {
              continue;
            }

            $where = [
              'house_id' => $request->id,
              'question_id' => $key
            ];

            if(is_array($item))
            {
              $item = implode(', ', $item);
            }

            $result = Result::firstOrNew($where);
            $result->house_id = $request->id;
            $result->question_id = $key;
            $result->result = $item;
            $result->save();
          }
        }

        DB::commit();

        return self::success(Code::message(Code::HANDLE_SUCCESS));
      }
      catch(\Exception $e)
      {
        DB::rollback();

        // 记录异常信息
        self::record($e);

        return self::error(Code::HANDLE_FAILURE);
      }
    }
  }
}
