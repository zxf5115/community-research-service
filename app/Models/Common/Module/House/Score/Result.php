<?php
namespace App\Models\Common\Module\House\Score;

use App\Models\Base;
use App\Http\Constant\Status;
use App\Models\Common\Module\Questionnaire\Score;
use App\Models\Common\Module\Questionnaire\Question;
use App\Models\Common\Module\House\Result as HouseResult;
use App\Models\Common\Module\House\Level\Result as LevelResult;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2022-03-23
 *
 * 商务楼宇结果类
 */
class Result extends Base
{
  // 表名
  protected $table = "module_house_score_result";

  // 隐藏的属性
  protected $hidden = [
    'update_time'
  ];

  // 追加到模型数组表单的访问器
  protected $appends = [];

  // 批量添加
  protected $fillable = [
    'id',
    'house_id',
    'question_id',
  ];

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2022-03-21
   * ------------------------------------------
   * 调研列项参数类型封装
   * ------------------------------------------
   *
   * 调研列项参数类型封装
   *
   * @param int $value [数据库存在的值]
   * @return 状态值
   */
  public function getResultAttribute($value)
  {
    $response = '';

    if(1 == $this->question_id)
    {
      $id_5 = Question::getValue('id', ['title' => '05.楼宇类型（单选）']);

      $id_13 = Question::getValue('id', ['title' => '13.总建筑面积（平方米）']);

      $data = HouseResult::getValue('result', ['question_id' => $id_5]);

      $result = HouseResult::getValue('result', ['question_id' => $id_13]);

      if('商务办公' == $data || '商住两用' == $data || '办公或经营自用' == $data)
      {
        $total = bcdiv($result, 10000, 1);
      }
      else
      {
        $total = bcmul(bcdiv($result, 10000, 1), 2, 1);
      }

      if(6 <= $total)
      {
        return 5;
      }
      else if(6 > $total && 4.5 <= $total)
      {
        return 4;
      }
      else if(4.5 > $total && 3 <= $total)
      {
        return 3;
      }
      else if(3 > $total && 1.5 <= $total)
      {
        return 2;
      }
      else
      {
        return 1;
      }
    }
    else if(2 == $this->question_id)
    {
      $id_5 = Question::getValue('id', ['title' => '05.楼宇类型（单选）']);

      $id_50 = Question::getValue('id', ['title' => '50.商务办公面积（平方米）（不含自用办公面积）']);

      $data = HouseResult::getValue('result', ['question_id' => $id_5]);

      $result = HouseResult::getValue('result', ['question_id' => $id_50]);

      if('商务办公' == $data || '商住两用' == $data || '办公或经营自用' == $data)
      {
        $total = bcdiv($result, 10000, 1);
      }
      else
      {
        $total = bcmul(bcdiv($result, 10000, 1), 2, 1);
      }

      if(6 <= $total)
      {
        return 5;
      }
      else if(6 > $total && 4.5 <= $total)
      {
        return 4;
      }
      else if(4.5 > $total && 3 <= $total)
      {
        return 3;
      }
      else if(3 > $total && 1.5 <= $total)
      {
        return 2;
      }
      else
      {
        return 1;
      }
    }
    else if(3 == $this->question_id)
    {
      $id_16 = Question::getValue('id', ['title' => '16.楼宇总层数']);

      $total = HouseResult::getValue('result', ['question_id' => $id_16]);

      if(10 <= $total)
      {
        return 5;
      }
      else if(10 > $total && 8 <= $total)
      {
        return 4;
      }
      else if(8 > $total && 6 <= $total)
      {
        return 3;
      }
      else if(6 > $total && 4 <= $total)
      {
        return 2;
      }
      else
      {
        return 1;
      }
    }
    else if(4 == $this->question_id)
    {
      $id_11 = Question::getValue('id', ['title' => '11.最新整体投入使用时间（具体到月，如2020年1月）']);

      $data = HouseResult::getValue('result', ['question_id' => $id_11]);

      if(!empty($data))
      {
        $now = date('Y');

        $total = bcsub($now, $data);

        if(2 >= $total)
        {
          return 10;
        }
        else if(4 >= $total && 2 < $total)
        {
          return 9;
        }
        else if(6 >= $total && 4 < $total)
        {
          return 8;
        }
        else if(8 >= $total && 6 < $total)
        {
          return 7;
        }
        else if(10 >= $total && 8 < $total)
        {
          return 6;
        }
        else if(12 >= $total && 10 < $total)
        {
          return 5;
        }
        else if(14 >= $total && 12 < $total)
        {
          return 4;
        }
        else if(16 >= $total && 14 < $total)
        {
          return 3;
        }
        else if(18 >= $total && 16 < $total)
        {
          return 2;
        }
        else if(20 >= $total && 18 < $total)
        {
          return 1;
        }
        else
        {
          return 0;
        }
      }
      else
      {
        $id_10 = Question::getValue('id', ['title' => '10.建成年份（仅填写年份信息，如2020年）']);

        $data = HouseResult::getValue('result', ['question_id' => $id_10]);

        $now = date('Y');

        $total = bcsub($now, $data);

        if(5 >= $total)
        {
          return 10;
        }
        else if(10 >= $total && 5 < $total)
        {
          return 9;
        }
        else if(15 >= $total && 10 < $total)
        {
          return 8;
        }
        else if(20 >= $total && 15 < $total)
        {
          return 7;
        }
        else if(25 >= $total && 20 < $total)
        {
          return 6;
        }
        else if(30 >= $total && 25 < $total)
        {
          return 5;
        }
        else if(35 >= $total && 30 < $total)
        {
          return 4;
        }
        else if(40 >= $total && 35 < $total)
        {
          return 3;
        }
        else if(45 >= $total && 40 < $total)
        {
          return 2;
        }
        else if(50 >= $total && 45 < $total)
        {
          return 1;
        }
        else
        {
          return 0;
        }
      }
    }
    else if(5 == $this->question_id)
    {
      $id_14 = Question::getValue('id', ['title' => '14.产权情况（单选）']);

      $data14 = HouseResult::getValue('result', ['question_id' => $id_14]);

      if($data14 == '自有产权' || $data14 == '单一产权')
      {
        return 3;
      }
      else if($data14 == '多产权')
      {
        $id_15 = Question::getValue('id', ['title' => '15.产权占比情况（仅多产权楼宇填写）']);

        $data15 = HouseResult::getValue('result', ['question_id' => $id_15]);

        $data15 = intval($data15);

        if($data15 >= 50)
        {
          return 2;
        }
        else
        {
          return 1;
        }
      }
      else
      {
        return '';
      }
    }
    else if(11 == $this->question_id)
    {
      $id_13 = Question::getValue('id', ['title' => '13.总建筑面积（平方米）']);

      $data13 = HouseResult::getValue('result', ['question_id' => $id_13]);

      $id_21 = Question::getValue('id', ['title' => '21.停车位总数量']);

      $data21 = HouseResult::getValue('result', ['question_id' => $id_21]);

      $total = bcmul(bcdiv($data21, $data13, 4), 10000);

      if(60 <= $total)
      {
        return 5;
      }
      else if(60 > $total && 45 <= $total)
      {
        return 4;
      }
      else if(45 > $total && 30 <= $total)
      {
        return 3;
      }
      else if(30 > $total && 15 <= $total)
      {
        return 2;
      }
      else
      {
        return 1;
      }
    }
    else if(13 == $this->question_id)
    {
      $id = Question::getValue('id', ['title' => '公共空间范围内绿化面积百分比']);

      $data = LevelResult::getValue('result', ['question_id' => $id]);

      $total = intval($data);

      if(10 == $total)
      {
        return 3;
      }
      else if(5 == $total)
      {
        return 1;
      }
      else
      {
        if(strpos($data, '15'))
        {
          return 5;
        }
        else
        {
          return 0;
        }
      }
    }
    else if(14 == $this->question_id)
    {
      $id = Question::getValue('id', ['title' => '54.出租价格（元/天/平方米）']);

      $total = HouseResult::getValue('result', ['question_id' => $id]);

      if(12 <= $total)
      {
        return 5;
      }
      else if(12 > $total && 10 <= $total)
      {
        return 4;
      }
      else if(10 > $total && 6 <= $total)
      {
        return 3;
      }
      else
      {
        return 2;
      }
    }
    else if(17 == $this->question_id)
    {
      $id = Question::getValue('id', ['title' => '46.物业方资质等级（单选）']);

      $data = HouseResult::getValue('result', ['question_id' => $id]);

      if($data == '一级物业')
      {
        return 10;
      }
      else if($data == '二级物业')
      {
        return 8;
      }
      else if($data == '三级物业')
      {
        return 5;
      }
    }
    else if(20 == $this->question_id)
    {
      // "需添加科目：全口径税收（目前不在表中）
      // （单位：万元）"  "
      // 全口径税收>=100000，输出15，无需调整结果
      // 100000>全口径税收>=50000，输出14，无需调整结果
      // 50000>全口径税收>=10000，输出13，无需调整结果
      // 10000>全口径税收>=9000，输出12，无需调整结果
      // 9000>全口径税收>=8000，输出11，无需调整结果
      // 8000>全口径税收>=7000，输出10，无需调整结果
      // 7000>全口径税收>=6000，输出9，无需调整结果
      // 6000>全口径税收>=5000，输出8，无需调整结果
      // 5000>全口径税收>=4000，输出7，无需调整结果
      // 4000>全口径税收>=3000，输出6，无需调整结果
      // 3000>全口径税收>=2000，输出5，无需调整结果
      // 2000>全口径税收>=1000，输出4，无需调整结果
      // 1000>全口径税收>0，输出0-4,管理员手动调整结果
      // 全口径税收=0，输出0，无需调整结果"

    }
    else if(21 == $this->question_id)
    {
      // "需添加科目：区级税收规模（目前不在表中）
      // （单位：万元）"  "
      // 区级税收规模>=10000，输出25，无需调整结果
      // 10000>区级税收规模>=5000，输出24，无需调整结果
      // 5000>区级税收规模>=3000，输出23，无需调整结果
      // 3000>区级税收规模>=2850，输出22，无需调整结果
      // 2850>区级税收规模>=2700，输出21，无需调整结果
      // 2700>区级税收规模>=2550，输出20，无需调整结果
      // 2550>区级税收规模>=2400，输出19，无需调整结果
      // 2250>区级税收规模>=2100，输出18，无需调整结果
      // 2100>区级税收规模>=1950，输出17，无需调整结果
      // 1950>区级税收规模>=1800，输出16，无需调整结果
      // 1800>区级税收规模>=1650，输出15，无需调整结果
      // 1650>区级税收规模>=1500，输出14，无需调整结果
      // 1500>区级税收规模>=1350，输出13，无需调整结果
      // 1350>区级税收规模>=1200，输出12，无需调整结果
      // 1200>区级税收规模>=1050，输出11，无需调整结果
      // 1050>区级税收规模>=900，输出10，无需调整结果
      // 900>区级税收规模>=750，输出9，无需调整结果
      // 750>区级税收规模>=600，输出8，无需调整结果
      // 600>区级税收规模>=450，输出7，无需调整结果
      // 450>区级税收规模>=300，输出6，无需调整结果
      // 300>区级税收规模>=150，输出5，无需调整结果
      // 150>区级税收规模>=0.1，输出0-5,管理员手动调整结果
      // 区级税收规模=0，输出0，无需调整结果"

    }
    else if(26 == $this->question_id)
    {
      // "已补充科目：全口径税收（目前不在表中）
      // （单位：亿元）
      // 表一，（一）楼宇信息的13总建筑面积（平方米）"  "
      // 全口径税收/总建筑面积*10000>=5000，输出25，无需调整结果
      // 5000>全口径税收/总建筑面积*10000>=4500，输出23，无需调整结果
      // 4500全口径税收/总建筑面积*10000>=4000，输出21，无需调整结果
      // 4000>全口径税收/总建筑面积*10000>=3500，输出19，无需调整结果
      // 3500>全口径税收/总建筑面积*10000>=3000，输出17，无需调整结果
      // 3000>全口径税收/总建筑面积*10000>=2500，输出15，无需调整结果
      // 2500>全口径税收/总建筑面积*10000>=2000，输出13，无需调整结果
      // 2000>全口径税收/总建筑面积*10000>=1500，输出11，无需调整结果
      // 1500>全口径税收/总建筑面积*10000>=1000，输出9，无需调整结果
      // 1000>全口径税收/总建筑面积*10000>=500，输出7，无需调整结果
      // 500>全口径税收/总建筑面积*10000>0，输出0-7，管理员手动调整结果
      // 全口径税收/总建筑面积*10000=0，输出0，无需调整结果"

    }
    else if(27 == $this->question_id)
    {
      // "已补充科目：全口径税收（目前不在表中）
      // （单位：亿元）
      // 需补充科目：所有企业员工总数" "全口径税收/所有企业员工总数>=15，输出15，无需调整结果
      // 15>全口径税收/所有企业员工总数>=14，输出23，无需调整结果
      // 14>全口径税收/所有企业员工总数>=13，输出21，无需调整结果
      // 13>全口径税收/所有企业员工总数>=12，输出19，无需调整结果
      // 12>全口径税收/所有企业员工总数>=11，输出17，无需调整结果
      // 11>全口径税收/所有企业员工总数>=10，输出15，无需调整结果
      // 10>全口径税收/所有企业员工总数>=9，输出13，无需调整结果
      // 9>全口径税收/所有企业员工总数>=8，输出11，无需调整结果
      // 8>全口径税收/所有企业员工总数>=7，输出9，无需调整结果
      // 7>全口径税收/所有企业员工总数>=500，输出7，无需调整结果
      // 6>全口径税收/所有企业员工总数>0，输出0-7，管理员手动调整结果
      // 全口径税收/所有企业员工总数=0，输出0，无需调整结果"

    }
    else if(28 == $this->question_id)
    {
      $id_13 = Question::getValue('id', ['title' => '13.总建筑面积（平方米）']);

      $data13 = HouseResult::getValue('result', ['question_id' => $id_13]);

      $id_57 = Question::getValue('id', ['title' => '57.空置面积（平方米）']);

      $data57 = HouseResult::getValue('result', ['question_id' => $id_57]);

      $total = bcdiv($data57, $data13, 2);

      if(0.9 <= $total)
      {
        return 10;
      }
      else if(0.9 > $total && 0.8 <= $total)
      {
        return 9;
      }
      else if(0.8 > $total && 0.7 <= $total)
      {
        return 8;
      }
      else if(0.7 > $total && 0.6 <= $total)
      {
        return 7;
      }
      else if(0.6 > $total && 0.5 <= $total)
      {
        return 6;
      }
      else if(0.5 > $total && 0.45 <= $total)
      {
        return 5;
      }
      else
      {
        return 0;
      }
    }

    return $value;
  }


  // 关联函数 ------------------------------------------------------


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2022-03-23
   * ------------------------------------------
   * 楼宇结果与楼宇关联函数
   * ------------------------------------------
   *
   * 楼宇结果与楼宇关联函数
   *
   * @return [关联对象]
   */
  public function house()
  {
    return $this->belongsTo(
      'App\Models\Common\Module\House',
      'house_id',
      'id',
    );
  }

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2022-03-23
   * ------------------------------------------
   * 楼宇结果与调研试题关联函数
   * ------------------------------------------
   *
   * 楼宇结果与调研试题关联函数
   *
   * @return [关联对象]
   */
  public function question()
  {
    return $this->belongsTo(
      'App\Models\Common\Module\Questionnaire\Score',
      'question_id',
      'id',
    );
  }
}
