<?php
namespace App\Http\Controllers\Platform\Module\Questionnaire;

use Illuminate\Http\Request;

use App\Http\Constant\Code;
use App\Http\Controllers\Platform\BaseController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2022-03-22
 *
 * 调查问卷分类控制器类
 */
class ScoreController extends BaseController
{
  protected $_model = 'App\Models\Platform\Module\Questionnaire\Score';

  protected $_order = [
    ['key' => 'sort', 'value' => 'desc'],
  ];

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2022-03-21
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
      'title.required'  => '请您输入信指标要求',
    ];

    $rule = [
      'title' => 'required',
    ];

    // 验证用户数据内容是否正确
    $validation = self::validation($request, $messages, $rule);

    if(!$validation['status'])
    {
      return $validation['message'];
    }
    else
    {
      try
      {
        $model = $this->_model::firstOrNew(['id' => $request->id]);

        $model->title = $request->title;
        $model->score = $request->score;
        $model->standard = $request->standard;
        $model->is_auto = $request->is_auto;
        $model->sort = $request->sort;
        $model->save();

        return self::success(Code::message(Code::HANDLE_SUCCESS));
      }
      catch(\Exception $e)
      {
        // 记录异常信息
        self::record($e);

        return self::error(Code::HANDLE_FAILURE);
      }
    }
  }
}
