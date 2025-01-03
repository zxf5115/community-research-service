<?php
namespace App\Http\Middleware;

use Closure;
use App\Models\Api\Module\Member;

/**
 * 会员失效
 */
class FailureRequest
{
  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-03-19
   * ------------------------------------------
   * 监听会员是否失效
   * ------------------------------------------
   *
   * 监听会员是否失效
   *
   * @param [type] $request [description]
   * @param Closure $next [description]
   * @return [type]
   */
  public function handle($request, Closure $next)
  {
    try
    {
      $where = [
        'id'     => auth()->user()->id,
        'status' => 1
      ];

      $result = Member::getRow($where);

      if(empty($result->id))
      {
        $headers = ['content-type' => 'application/json'];

        $response = \Response::json(['status' => -102, 'message' => '请重新登录']);
        return $response->withHeaders($headers);
      }

      return $next($request);
    }
    catch(\Exception $e)
    {
      \Log::error($e);
    }
  }
}
