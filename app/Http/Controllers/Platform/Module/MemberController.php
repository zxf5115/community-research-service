<?php
namespace App\Http\Controllers\Platform\Module;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Constant\Code;
use App\TraitClass\ToolTrait;
use App\Http\Constant\Parameter;
use App\Http\Controllers\Platform\BaseController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-05-19
 *
 * 用户控制器类
 */
class MemberController extends BaseController
{
  // 模型名称
  protected $_model = 'App\Models\Platform\Module\Member';

  // 默认查询条件
  protected $_where = [
    // 'role_id' => 2
  ];

  // 客户端搜索字段
  protected $_params = [
    'username',
    'nickname'
  ];

  // 客户端关联查询字段
  protected $_addition = [
    'address' => [
      'address',
    ]
  ];


  // 关联对象
  protected $_relevance = [
    'list' => [
      'archive',
      'address',
      'certification',
    ],
    'select' => false,
    'view' => [
      'archive',
      'address',
      'asset',
    ],
  ];


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-12-12
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
      'nickname.required' => '请您输入用户昵称',
    ];

    $rule = [
      'nickname' => 'required',
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
        $model = $this->_model::firstOrNew(['id' => $request->id]);

        $organization_id = self::getOrganizationId();

        $model->organization_id = $organization_id;
        $model->nickname        = $request->nickname;
        $model->avatar          = $request->avatar ?: '';
        $model->save();

        $data = [
          'member_id'   => $model->id,
          'name'        => $request->name ?? '',
          'mobile'      => $request->mobile ?? '',
          'postcode'    => $request->postcode ?? '',
          'province_id' => $request->province_id ?? '',
          'city_id'     => $request->city_id ?? '',
          'region_id'   => $request->region_id ?? '',
          'address'     => $request->address ?? '',
        ];

        if(!empty($data))
        {
          $model->address()->delete();

          $model->address()->create($data);
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


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-25
   * ------------------------------------------
   * 禁用（解禁）学员账户
   * ------------------------------------------
   *
   * 禁用（解禁）学员账户
   *
   * @param Request $request [description]
   * @return [type]
   */
  public function enable(Request $request)
  {
    try
    {
      $model = $this->_model::find($request->id);

      $model->status = $model->status['value'] == 1 ? 2 : 1;

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
