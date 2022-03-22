<?php
namespace App\Http\Controllers\Platform\Module\Questionnaire;

use Illuminate\Http\Request;

use App\Http\Constant\Code;
use App\Http\Controllers\Platform\BaseController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2022-03-21
 *
 * 调查问卷列项控制器类
 */
class QuestionController extends BaseController
{
  protected $_model = 'App\Models\Platform\Module\Questionnaire\Question';

  protected $_params = [
    'questionnaire_id',
    'category_id',
    'parent_id',
  ];

  // 关联对象
  protected $_relevance = [
    'list' => ['questionnaire', 'category'],
    'view' => ['questionnaire', 'category'],
    'select' => ['category', 'parent'],
  ];

  protected $_order = [
    ['key' => 'sort', 'value' => 'desc'],
  ];


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-12
   * ------------------------------------------
   * 获取列表信息
   * ------------------------------------------
   *
   * 获取列表信息
   *
   * @param Request $request [请求参数]
   * @return [type]
   */
  public function select(Request $request)
  {
    try
    {
      $condition = self::getBaseWhereData();

      $where = [
        ['type', [1,2,3,4,6]]
      ];

      // 对用户请求进行过滤
      $filter = $this->filter($request->all());

      $condition = array_merge($condition, $this->_where, $filter, $where);

      $relevance = self::getRelevanceData($this->_relevance, 'select');

      $response = $this->_model::getList($condition, $relevance, $this->_order);

      return self::success($response);
    }
    catch(\Exception $e)
    {
      // 记录异常信息
      self::record($e);

      return self::error(Code::ERROR);
    }
  }


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
      'title.required'  => '请您输入调研列项标题',
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

        $model->questionnaire_id = $request->questionnaire_id;
        $model->category_id = $request->category_id;
        $model->parent_id = $request->parent_id;
        $model->type = $request->type;
        $model->title = $request->title;
        $model->params = $request->params;
        $model->is_red = $request->is_red;
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
