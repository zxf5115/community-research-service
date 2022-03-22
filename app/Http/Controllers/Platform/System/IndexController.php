<?php
namespace App\Http\Controllers\Platform\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\TraitClass\StatisticalTrait;
use App\Http\Controllers\Platform\BaseController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-12-28
 *
 * 首页控制器
 */
class IndexController extends BaseController
{
  use StatisticalTrait;

}
