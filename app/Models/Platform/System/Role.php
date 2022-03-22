<?php
namespace App\Models\Platform\System;

use App\Models\Common\System\Role as Common;


/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-07-15
 *
 * 角色模型类
 */
class Role extends Common
{

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-07-16
   * ------------------------------------------
   * 获取菜单编号
   * ------------------------------------------
   *
   * 获取菜单编号
   *
   * @param array $request 菜单编号
   * @param array $organization_id 机构编号
   * @return 菜单编号
   */
  public static function getMenuId($request, $organization_id)
  {
    $response = [];

    // 下标0 为半选中  下标1 为全选中
    // $requ

    foreach($request as $key => $item)
    {
      $response[$key]['menu_id']     = $item;
      $response[$key]['organization_id'] = $organization_id;
    }

    return $response;
  }
}
