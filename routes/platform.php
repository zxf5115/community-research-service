<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
  'namespace'  =>  'Platform',
  'middleware'  =>  'serializer:array'
], function ($api)
{
  $api->group([
    'middleware'  =>  'api.throttle', // 启用节流限制
    'limit'  =>  1000, // 允许次数
    'expires'  =>  1, // 分钟
  ], function ($api)
  {
    // --------------------------------------------------
    // 核心路由
    $api->group(['namespace' => 'System'], function ($api) {

      // 登录路由
      $api->post('login', 'LoginController@login');
      $api->post('register', 'LoginController@register');
      $api->get('logout', 'LoginController@logout');
      $api->get('check_user_login', 'LoginController@check_user_login'); // 验证是否登录

      $api->post('kernel', 'SystemController@kernel');
      $api->post('clear', 'SystemController@clear');

      // 首页路由
      $api->group(['prefix' => 'index'], function ($api) {
        $api->get('user', 'IndexController@user');
        $api->get('car', 'IndexController@car');
        $api->get('order', 'IndexController@order');
        $api->get('money', 'IndexController@money');
      });

      // 文件上传路由
      $api->group(['prefix' => 'file'], function ($api) {
        $api->post('file', 'FileController@file');
        $api->post('picture', 'FileController@picture');
        $api->post('data', 'FileController@data');
        $api->post('editor_file', 'FileController@editor_file');
        $api->post('editor_picture', 'FileController@editor_picture');
      });


      // 平台用户路由
      $api->group(['prefix'  =>  'user'], function ($api) {
        $api->any('list', 'UserController@list');
        $api->get('select', 'UserController@select');
        $api->get('view/{id}', 'UserController@view');
        $api->get('action', 'UserController@action');
        $api->post('handle', 'UserController@handle');
        $api->post('delete', 'UserController@delete');
        $api->get('tree', 'UserController@tree');
        $api->any('password', 'UserController@password');
        $api->any('change_password', 'UserController@change_password');

        // 平台用户消息路由
        $api->group(['namespace' => 'User', 'prefix'  =>  'message'], function ($api) {
          $api->any('list', 'MessageController@list');
          $api->post('unread', 'MessageController@unread');
          $api->post('readed', 'MessageController@readed');
          $api->post('delete', 'MessageController@delete');
        });
      });


      // 平台角色路由
      $api->group(['prefix'  =>  'role'], function ($api) {
        $api->any('list', 'RoleController@list');
        $api->get('select', 'RoleController@select');
        $api->get('view/{id}', 'RoleController@view');
        $api->post('handle', 'RoleController@handle');
        $api->post('delete', 'RoleController@delete');
        $api->any('permission/{id}', 'RoleController@permission');
      });


      // 平台菜单路由
      $api->group(['prefix'  =>  'menu'], function ($api) {
        $api->any('list', 'MenuController@list');
        $api->get('select', 'MenuController@select');
        $api->get('view/{id}', 'MenuController@view');
        $api->post('handle', 'MenuController@handle');
        $api->post('delete', 'MenuController@delete');

        $api->any('level', 'MenuController@level');
        $api->post('active', 'MenuController@active');
        $api->post('track', 'MenuController@track');
      });


       // 系统配置路由
      $api->group(['prefix'  =>  'config'], function ($api) {
        // 配置管理路由
        $api->any('list', 'ConfigController@list');
        $api->get('select', 'ConfigController@select');
        $api->get('view/{id}', 'ConfigController@view');
        $api->post('handle', 'ConfigController@handle');
        $api->post('delete/{id?}', 'ConfigController@delete');

        // 配置分类管理路由
        $api->group(['namespace' => 'Config', 'prefix'  =>  'category'], function ($api) {
          $api->any('list', 'CategoryController@list');
          $api->get('select', 'CategoryController@select');
          $api->get('view/{id}', 'CategoryController@view');
          $api->get('level', 'CategoryController@level');
          $api->post('handle', 'CategoryController@handle');
          $api->post('delete/{id?}', 'CategoryController@delete');
        });
      });


      // 系统设置路由
      $api->group(['prefix'  =>  'setting'], function ($api) {
        $api->any('data', 'SettingController@data');
        $api->any('agreement', 'SettingController@agreement');
        $api->any('about', 'SettingController@about');
      });
    });


    // --------------------------------------------------
    // 模块路由
    $api->group(['namespace' => 'Module'], function ($api) {

      // 公共路由
      $api->group(['namespace' => 'Common', 'prefix'  =>  'common'], function ($api) {

        $api->get('area/list', 'AreaController@list'); // 地区路由

      });


      // 消费会员路由
      $api->group(['prefix'  => 'member'], function ($api) {
        $api->any('list', 'MemberController@list');
        $api->get('select', 'MemberController@select');
        $api->get('view/{id}', 'MemberController@view');
        $api->post('handle', 'MemberController@handle');
        $api->post('enable', 'MemberController@enable');
        $api->post('delete', 'MemberController@delete');
      });

      // 楼宇路由
      $api->group(['prefix'  => 'house'], function ($api) {
        $api->any('list', 'HouseController@list');
        $api->get('select', 'HouseController@select');
        $api->get('view/{id}', 'HouseController@view');
        $api->post('handle', 'HouseController@handle');
        $api->post('delete', 'HouseController@delete');

        $api->group(['namespace' => 'House'], function ($api) {

          // 楼宇记录人路由
          $api->group(['prefix' => 'record'], function ($api) {
            $api->any('list', 'RecordController@list');
            $api->get('select', 'RecordController@select');
            $api->get('data', 'RecordController@data');
            $api->get('view/{id}', 'RecordController@view');
            $api->post('handle', 'RecordController@handle');
            $api->post('delete/{id?}', 'RecordController@delete');
          });

          // 楼宇结果路由
          $api->group(['prefix' => 'result'], function ($api) {
            $api->any('list', 'ResultController@list');
            $api->get('select', 'ResultController@select');
            $api->get('view/{id}', 'ResultController@view');
            $api->post('handle', 'ResultController@handle');
            $api->post('delete/{id?}', 'ResultController@delete');
          });


          $api->group(['namespace' => 'Business', 'prefix' => 'business'], function ($api) {

            // 商务楼宇记录人路由
            $api->group(['prefix' => 'record'], function ($api) {
              $api->any('list', 'RecordController@list');
              $api->get('select', 'RecordController@select');
              $api->get('data', 'RecordController@data');
              $api->get('view/{id}', 'RecordController@view');
              $api->post('handle', 'RecordController@handle');
              $api->post('delete/{id?}', 'RecordController@delete');
            });

            // 商务楼宇结果路由
            $api->group(['prefix' => 'result'], function ($api) {
              $api->any('list', 'ResultController@list');
              $api->get('select', 'ResultController@select');
              $api->get('view/{id}', 'ResultController@view');
              $api->post('handle', 'ResultController@handle');
              $api->post('delete/{id?}', 'ResultController@delete');
            });
          });


          $api->group(['namespace' => 'Level', 'prefix' => 'level'], function ($api) {

            // 楼宇等级记录人路由
            $api->group(['prefix' => 'record'], function ($api) {
              $api->any('list', 'RecordController@list');
              $api->get('select', 'RecordController@select');
              $api->get('data', 'RecordController@data');
              $api->get('view/{id}', 'RecordController@view');
              $api->post('handle', 'RecordController@handle');
              $api->post('delete/{id?}', 'RecordController@delete');
            });

            // 楼宇等级结果路由
            $api->group(['prefix' => 'result'], function ($api) {
              $api->any('list', 'ResultController@list');
              $api->get('select', 'ResultController@select');
              $api->get('view/{id}', 'ResultController@view');
              $api->post('handle', 'ResultController@handle');
              $api->post('delete/{id?}', 'ResultController@delete');
            });
          });


          $api->group(['namespace' => 'Score', 'prefix' => 'score'], function ($api) {

            // 商务楼宇结果路由
            $api->group(['prefix' => 'result'], function ($api) {
              $api->any('list', 'ResultController@list');
              $api->get('select', 'ResultController@select');
              $api->get('view/{id}', 'ResultController@view');
              $api->post('handle', 'ResultController@handle');
              $api->post('delete/{id?}', 'ResultController@delete');
            });
          });
        });
      });


      $api->group(['prefix' => 'questionnaire'], function ($api) {
        // 调查问卷路由
        $api->any('list', 'QuestionnaireController@list');
        $api->get('select', 'QuestionnaireController@select');
        $api->get('view/{id}', 'QuestionnaireController@view');
        $api->post('handle', 'QuestionnaireController@handle');
        $api->post('delete', 'QuestionnaireController@delete');

        $api->group(['namespace' => 'Questionnaire'], function ($api) {

          // 调查问卷评分路由
          $api->group(['prefix' => 'score'], function ($api) {
            $api->any('list', 'ScoreController@list');
            $api->get('select', 'ScoreController@select');
            $api->get('view/{id}', 'ScoreController@view');
            $api->post('handle', 'ScoreController@handle');
            $api->post('delete/{id?}', 'ScoreController@delete');
          });

          // 调查问卷分类路由
          $api->group(['prefix' => 'category'], function ($api) {
            $api->any('list', 'CategoryController@list');
            $api->get('select', 'CategoryController@select');
            $api->get('view/{id}', 'CategoryController@view');
            $api->post('handle', 'CategoryController@handle');
            $api->post('delete/{id?}', 'CategoryController@delete');
          });

          // 调查问卷列项路由
          $api->group(['prefix' => 'question'], function ($api) {
            $api->any('list', 'QuestionController@list');
            $api->get('select', 'QuestionController@select');
            $api->get('view/{id}', 'QuestionController@view');
            $api->post('handle', 'QuestionController@handle');
            $api->post('delete/{id?}', 'QuestionController@delete');
          });
        });
      });
    });
  });
});
