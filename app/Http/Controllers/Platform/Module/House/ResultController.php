<?php
namespace App\Http\Controllers\Platform\Module\House;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Constant\Code;
use App\Http\Controllers\Platform\BaseController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2022-03-23
 *
 * 楼宇结果控制器类
 */
class ResultController extends BaseController
{
  protected $_model = 'App\Models\Platform\Module\House\Result';

  protected $_params = [
    'house_id',
    'question_id',
  ];
}
