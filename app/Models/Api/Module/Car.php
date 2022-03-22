<?php
namespace App\Models\Api\Module;

use App\Models\Api\Module\Car\Config;
use App\Models\Common\Module\Car as Common;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2021-05-20
 *
 * 汽车模型类
 */
class Car extends Common
{
  // 隐藏的属性
  public $hidden = [
    'organization_id',
    'status',
    'update_time',
  ];


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-07-02
   * ------------------------------------------
   * 获取组合查询条件
   * ------------------------------------------
   *
   * 获取组合查询条件
   *
   * @param [type] $request 用户请求数据
   * @return [type]
   */
  public static function getCombinationWhere($request)
  {
    try
    {
      $data  = false;
      $flag  = false;
      $where = [];

      // 如果存在价格搜索条件
      if(0 < $request->pay_money)
      {
        $where[] = self::getPayMoneyWhere($request->pay_money);
      }

      // 如果存在车操作类型搜索条件
      if(!empty($request->car_operation))
      {
        $where[] = self::getCarConfigWhere($request->car_operation);
      }

      // 如果存在车类型搜索条件
      if(!empty($request->car_type))
      {
        $where[] = self::getCarConfigWhere($request->car_type);
      }

      // 如果存在所属国家搜索条件
      if(!empty($request->car_country))
      {
        $where[] = self::getCarConfigWhere($request->car_country);
      }

      // 如果存在汽车座位数搜索条件
      if(!empty($request->car_seat))
      {
        $where[] = self::getCarConfigWhere($request->car_seat);
      }

      // 如果存在汽车排量数搜索条件
      if(!empty($request->car_displacement))
      {
        $where[] = self::getCarDisplacementWhere($request->car_displacement);
      }

      // 如果存在汽车驱动搜索条件
      if(!empty($request->car_drive))
      {
        $where[] = self::getCarConfigWhere($request->car_drive);
      }

      // 如果存在汽车动力搜索条件
      if(!empty($request->car_power))
      {
        $where[] = self::getCarConfigWhere($request->car_power);
      }

      // 如果存在汽车结构搜索条件
      if(!empty($request->car_structure))
      {
        $where[] = self::getCarConfigWhere($request->car_structure);
      }

      if(!empty($where))
      {
        $flag = true;

        $data = $where[0];

        $count = count($where);

        if(1 < $count)
        {
          for($i = 1; $i < $count; $i++)
          {
            $data = array_intersect($data, $where[$i]);
          }
        }
      }

      return [$flag, $data];
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
   * @dateTime 2021-05-24
   * ------------------------------------------
   * 获取支付金额查询条件
   * ------------------------------------------
   *
   * 获取支付金额查询条件
   *
   * @param [type] $type 支付金额区间类型
   * @return [type]
   */
  public static function getPayMoneyWhere($type)
  {
    try
    {
      $response = [];

      if(1 == $type)
      {
        $where = [
          ['sell_money', '>=', '0'],
          ['sell_money', '<', '50']
        ];
      }
      else if(2 == $type)
      {
        $where = [
          ['sell_money', '>=', '50'],
          ['sell_money', '<', '100']
        ];
      }
      else if(3 == $type)
      {
        $where = [
          ['sell_money', '>=', '100'],
          ['sell_money', '<', '150']
        ];
      }
      else if(4 == $type)
      {
        $where = [
          ['sell_money', '>=', '150'],
          ['sell_money', '<', '200']
        ];
      }
      else if(5 == $type)
      {
        $where = [
          ['sell_money', '>=', '200']
        ];
      }
      else
      {
        $where = [
          ['sell_money', '<', '-10000']
        ];
      }

      return self::getPluck('id', $where, false, false, true);
    }
    catch(\Exception $e)
    {
      self::record($e);

      return false;
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-05-24
   * ------------------------------------------
   * 获取汽车排量查询条件
   * ------------------------------------------
   *
   * 获取汽车排量查询条件
   *
   * @param [type] $type 汽车排量类型
   * @return [type]
   */
  public static function getCarDisplacementWhere($type)
  {
    try
    {
      $response = [];

      if(1 == $type)
      {
        $where = [
          ['value', '>=', '1.5L'],
          ['value', '<', '2.0L']
        ];
      }
      else if(2 == $type)
      {
        $where = [
          ['value', '>=', '2.0L'],
          ['value', '<', '2.5L']
        ];
      }
      else if(3 == $type)
      {
        $where = [
          ['value', '>=', '2.5L'],
          ['value', '<', '3.0L']
        ];
      }
      else if(4 == $type)
      {
        $where = [
          ['value', '>=', '3.0L'],
          ['value', '<', '4.0L']
        ];
      }
      else if(5 == $type)
      {
        $where = [
          ['value', '>=', '4.0L']
        ];
      }
      else if(6 == $type)
      {
        $where = [
          ['value', '<', '1.5L']
        ];
      }
      else
      {
        $where = [
          ['value', '<', '-10000']
        ];
      }

      return Config::getPluck('car_id', $where, false, false, true);
    }
    catch(\Exception $e)
    {
      record($e);

      return false;
    }
  }



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-05-24
   * ------------------------------------------
   * 获取车辆配置查询条件
   * ------------------------------------------
   *
   * 获取车辆配置查询条件
   *
   * @param [string] $value 车辆所属国家
   * @return [type]
   */
  public static function getCarConfigWhere($value)
  {
    try
    {
      $response = [];

      $where = [
        'status' => 1,
        'value' => $value
      ];

      return Config::getPluck('car_id', $where, false, false, true);
    }
    catch(\Exception $e)
    {
      record($e);

      return false;
    }
  }



  // 关联函数 ------------------------------------------------------


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-10-20
   * ------------------------------------------
   * 汽车与汽车地址关联表
   * ------------------------------------------
   *
   * 汽车与汽车地址关联表
   *
   * @return [关联对象]
   */
  public function member()
  {
    return $this->belongsTo(
      'App\Models\Api\Module\Member',
      'member_id',
      'id'
    );
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-05-23
   * ------------------------------------------
   * 汽车与汽车来源关联表
   * ------------------------------------------
   *
   * 汽车与汽车来源关联表
   *
   * @return [关联对象]
   */
  public function source()
  {
    return $this->belongsTo(
      'App\Models\Api\Module\Car\Source',
      'source_id',
      'id'
    );
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-05-23
   * ------------------------------------------
   * 汽车与汽车品牌关联表
   * ------------------------------------------
   *
   * 汽车与汽车品牌关联表
   *
   * @return [关联对象]
   */
  public function brand()
  {
    return $this->belongsTo(
      'App\Models\Api\Module\Car\Brand',
      'brand_id',
      'id'
    );
  }

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-05-23
   * ------------------------------------------
   * 汽车与汽车车型关联表
   * ------------------------------------------
   *
   * 汽车与汽车车型关联表
   *
   * @return [关联对象]
   */
  public function shape()
  {
    return $this->belongsTo(
      'App\Models\Api\Module\Car\Shape',
      'shape_id',
      'id'
    );
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-05-23
   * ------------------------------------------
   * 汽车与汽车资源关联表
   * ------------------------------------------
   *
   * 汽车与汽车资源关联表
   *
   * @return [关联对象]
   */
  public function resource()
  {
    return $this->hasMany(
      'App\Models\Api\Module\Car\Resource',
      'car_id',
      'id'
    );
  }



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-05-23
   * ------------------------------------------
   * 汽车与汽车配置关联表
   * ------------------------------------------
   *
   * 汽车与汽车配置关联表
   *
   * @return [关联对象]
   */
  public function config()
  {
    return $this->hasMany(
      'App\Models\Api\Module\Car\Config',
      'car_id',
      'id'
    );
  }



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-05-23
   * ------------------------------------------
   * 汽车与汽车浏览关联表
   * ------------------------------------------
   *
   * 汽车与汽车浏览关联表
   *
   * @return [关联对象]
   */
  public function browse()
  {
    return $this->hasMany(
      'App\Models\Api\Module\Car\Browse',
      'car_id',
      'id'
    );
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2021-05-23
   * ------------------------------------------
   * 汽车与汽车收藏关联表
   * ------------------------------------------
   *
   * 汽车与汽车收藏关联表
   *
   * @return [关联对象]
   */
  public function collection()
  {
    return $this->hasMany(
      'App\Models\Api\Module\Car\Collection',
      'car_id',
      'id'
    );
  }
}
