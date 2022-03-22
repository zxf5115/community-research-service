<?php
namespace App\Http\Controllers\Platform\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Common\Module\Car;
use App\Models\Common\Module\Order;
use App\Models\Common\Module\Member;
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

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-02-19
   * ------------------------------------------
   * 用户统计数据
   * ------------------------------------------
   *
   * 用户统计数据
   *
   * @return [type]
   */
  public function user(Request $request)
  {
    try
    {
      $_where = ['status' => 1];

      $today_member_total = 0;
      $member_total       = 0;
      $merchant_total     = 0;

      // 统计时间区间
      $where = self::getWhereCondition($request->type);

      $condition = array_merge($_where, $where, ['role_id' => 2]);

      // 今日会员
      $today_member_total = Member::getCount($condition);

      // 全部会员
      $member_total = Member::getCount(['status' => 1, 'role_id' => 2]);

      $condition = array_merge($_where, $where, ['role_id' => 1]);

      // 今日车商
      $merchant_total = Member::getCount($condition);

      // 自行注册用户
      $condition = array_merge($_where, $where, ['inviter_id' => 0]);

      $un_inviter_total = Member::getCount($condition);

      $response['today_member_total'] = $today_member_total;
      $response['member_total']       = $member_total;
      $response['merchant_total']     = $merchant_total;
      $response['un_inviter_total']   = $un_inviter_total;

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
   * @dateTime 2021-02-19
   * ------------------------------------------
   * 汽车统计数据
   * ------------------------------------------
   *
   * 汽车统计数据
   *
   * @return [type]
   */
  public function car(Request $request)
  {
    try
    {
      $_where = ['status' => 1];

      $car_out_total = 0;
      $car_in_total  = 0;
      $car_old_total = 0;
      $carData       = [];

      // 统计时间区间
      $where = self::getWhereCondition($request->type);

      $condition = array_merge($_where, $where, ['source_id' => 52]);

      // 海外车源
      $car_out_total = Car::getCount($condition);

      $condition = array_merge($_where, $where, ['source_id' => 51]);

      // 国内车源
      $car_in_total = Car::getCount($condition);

      $condition = array_merge($_where, $where, ['source_id' => 53]);

      // 今日车商
      $car_old_total = Car::getCount($condition);

      $response['car_out_total'] = $car_out_total;
      $response['car_in_total']  = $car_in_total;
      $response['car_old_total'] = $car_old_total;

      $condition = array_merge($_where, $where);

      $car = Car::getList($condition);

      $carDate = [];

      foreach($car as $item)
      {
        $carDate[] = date('Y-m-d', strtotime($item->create_time));
      }

      $carDate = array_count_values($carDate);

      foreach($carDate as $key => $item)
      {
        $carData[] = [
          'title' => $key,
          '汽车数' => $item,
        ];
      }

      $sort = array_column($carData, 'title');

      array_multisort($carData, SORT_ASC, $sort);


      $response['data'] = $carData;

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
   * @dateTime 2021-02-19
   * ------------------------------------------
   * 订单统计数据
   * ------------------------------------------
   *
   * 订单统计数据
   *
   * @return [type]
   */
  public function order(Request $request)
  {
    try
    {
      $_where = ['status' => 1];

      $wait_order_total = 0;
      $finish_order_total  = 0;
      $wait_take_order_total = 0;
      $orderData       = [];

      // 统计时间区间
      $where = self::getWhereCondition($request->type);

      $condition = array_merge($_where, $where, ['order_status' => 0]);

      // 待支付
      $wait_order_total = Order::getCount($condition);

      $order = Order::getList($condition);

      $waitDate = [];

      foreach($order as $item)
      {
        $waitDate[] = date('Y-m-d', strtotime($item->create_time));
      }

      $waitDate = array_count_values($waitDate);

      foreach($waitDate as $key => $item)
      {
        $orderData[] = [
          'title' => $key,
          '新增待支付订单' => $item,
        ];
      }

      $date = array_keys($waitDate);


      $condition = array_merge($_where, $where, ['order_status' => 2]);

      // 已完成
      $finish_order_total = Order::getCount($condition);

      $order = Order::getList($condition);

      $finishDate = [];

      foreach($order as $item)
      {
        $finishDate[] = date('Y-m-d', strtotime($item->create_time));
      }

      $finishDate = array_count_values($finishDate);

      foreach($finishDate as $k => $v)
      {
        foreach($orderData as $key => $item)
        {
          if($k == $item['title'])
          {
            $orderData[$key] = array_merge($orderData[$key], ['title' => $k , '新增完成订单' => $v]);
          }
          else if(!in_array($k, $date))
          {
            $orderData[] = ['title' => $k, '新增完成订单' => $v];

            break;
          }
        }
      }

      $condition = array_merge($_where, $where, ['order_status' => 1]);

      // 待提货
      $wait_take_order_total = Order::getCount($condition);

      $order = Order::getList($condition);

      $takeDate = [];

      foreach($order as $item)
      {
        $takeDate[] = date('Y-m-d', strtotime($item->create_time));
      }

      $takeDate = array_count_values($takeDate);

      foreach($takeDate as $k => $v)
      {
        foreach($orderData as $key => $item)
        {
          if($k == $item['title'])
          {
            $orderData[$key] = array_merge($orderData[$key], ['title' => $k , '新增待提货订单' => $v]);
          }
          else if(!in_array($k, $date))
          {
            $orderData[] = ['title' => $k, '新增待提货订单' => $v];

            break;
          }
        }
      }

      $response['wait_order_total'] = $wait_order_total;
      $response['finish_order_total']  = $finish_order_total;
      $response['wait_take_order_total'] = $wait_take_order_total;

      $sort = array_column($orderData, 'title');

      array_multisort($orderData, SORT_ASC, $sort);

      $response['data'] = $orderData;

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
   * @dateTime 2021-02-19
   * ------------------------------------------
   * 成交量统计数据
   * ------------------------------------------
   *
   * 成交量统计数据
   *
   * @return [type]
   */
  public function money(Request $request)
  {
    try
    {
      $_where = ['status' => 1];

      $money_out_total = 0;
      $money_in_total  = 0;
      $money_old_total = 0;
      $moneyData       = [];

      // 统计时间区间
      $where = self::getWhereCondition($request->type);

      $condition = array_merge($_where, $where, ['source_id' => 52]);

      // 国外车源
      $order = Order::getList($condition);

      $outDate = [];

      foreach($order as $item)
      {
        $money_out_total = bcadd($money_out_total, $item->pay_money, 2);

        $field = date('Y-m-d', strtotime($item->create_time));

        if(empty($outDate[$field]))
        {
          $outDate[$field] = $item->pay_money;
        }
        else
        {
          $outDate[$field] = bcadd($outDate[$field], $item->pay_money, 2);
        }
      }

      foreach($outDate as $key => $item)
      {
        $moneyData[] = [
          'title' => $key,
          '国外车源' => $item,
        ];
      }

      $date = array_keys($outDate);

      $condition = array_merge($_where, $where, ['source_id' => 51]);

      // 国内车源
      $order = Order::getList($condition);

      $inDate = [];

      foreach($order as $item)
      {
        $money_in_total = bcadd($money_in_total, $item->pay_money, 2);

        $field = date('Y-m-d', strtotime($item->create_time));

        if(empty($inDate[$field]))
        {
          $inDate[$field] = $item->pay_money;
        }
        else
        {
          $inDate[$field] = bcadd($inDate[$field], $item->pay_money, 2);
        }
      }

      foreach($inDate as $k => $v)
      {
        foreach($moneyData as $key => $item)
        {
          if($k == $item['title'])
          {
            $moneyData[$key] = array_merge($moneyData[$key], ['title' => $k , '国内车源' => $v]);
          }
          else if(!in_array($k, $date))
          {
            $moneyData[] = ['title' => $k, '国内车源' => $v];

            break;
          }
        }
      }

      $condition = array_merge($_where, $where, ['source_id' => 53]);

      // 二手车源
      $order = Order::getList($condition);

      $oldDate = [];

      foreach($order as $item)
      {
        $money_old_total = bcadd($money_old_total, $item->pay_money, 2);

        if(empty($oldDate[$field]))
        {
          $oldDate[$field] = $item->pay_money;
        }
        else
        {
          $oldDate[$field] = bcadd($oldDate[$field], $item->pay_money, 2);
        }
      }

      foreach($oldDate as $k => $v)
      {
        foreach($moneyData as $key => $item)
        {
          if($k == $item['title'])
          {
            $moneyData[$key] = array_merge($moneyData[$key], ['title' => $k , '二手车源' => $v]);
          }
          else if(!in_array($k, $date))
          {
            $moneyData[] = ['title' => $k, '二手车源' => $v];

            break;
          }
        }
      }

      $response['money_out_total'] = $money_out_total;
      $response['money_in_total']  = $money_in_total;
      $response['money_old_total'] = $money_old_total;

      $sort = array_column($moneyData, 'title');

      array_multisort($moneyData, SORT_ASC, $sort);

      $response['data'] = $moneyData;

      return self::success($response);
    }
    catch(\Exception $e)
    {
      // 记录异常信息
      self::record($e);

      return self::error(Code::ERROR);
    }
  }
}
