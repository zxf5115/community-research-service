<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
  'namespace'  =>  'Api',
  'middleware'  =>  'serializer:array'
], function ($api)
{
  $api->group([
    'middleware'  =>  'api.throttle', // 启用节流限制
    'limit'  =>  1000, // 允许次数
    'expires'  =>  1, // 分钟
  ], function ($api)
  {
    $api->group(['namespace' => 'System'], function ($api) {
      $api->post('weixin_login', 'LoginController@weixin_login'); // 微信登录
      $api->post('register', 'LoginController@register');
      $api->get('logout', 'LoginController@logout'); // 退出

      // 系统基础数据路由
      $api->group(['prefix' => 'system'], function ($api) {
        $api->get('kernel', 'SystemController@kernel'); // 系统信息路由
      });

      // 上传路由
      $api->group(['prefix' => 'file', 'middleware' => ['auth:api', 'refresh.token']], function ($api) {
        // 上传文件
        $api->post('file', 'FileController@file');
        // 上传图片
        $api->post('picture', 'FileController@picture');
        $api->post('batchPicture', 'FileController@batchPicture');
      });
    });



    $api->group(['namespace' => 'Module'], function ($api) {

      // 公共路由
      $api->group(['namespace' => 'Common', 'prefix' => 'common'], function ($api) {

        // 省市县路由
        $api->group(['prefix' => 'area'], function ($api) {
          $api->get('list', 'AreaController@list');
        });
      });



      // 会员路由
      $api->group(['prefix'  => 'member', 'middleware' => ['auth:api', 'refresh.token', 'failure']], function ($api) {
        $api->get('archive', 'MemberController@archive');
        $api->get('data/{id}', 'MemberController@data');
        $api->post('handle', 'MemberController@handle');
        $api->post('personal', 'MemberController@personal');
        $api->post('bank', 'MemberController@bank');
        $api->post('teacher', 'MemberController@teacher');
        $api->post('datum', 'MemberController@datum');
        $api->get('status', 'MemberController@status');

        // 会员关联内容路由
        $api->group(['namespace' => 'Member'], function ($api) {

          // 会员认证路由
          $api->group(['prefix'  => 'certification'], function ($api) {
            $api->post('status', 'CertificationController@status');
            $api->post('personal', 'CertificationController@personal');
            $api->post('company', 'CertificationController@company');
            $api->post('bankcard', 'CertificationController@bankcard');
            $api->post('sms_code', 'CertificationController@sms_code');
            $api->get('data', 'CertificationController@data');
          });

          // 会员邀请路由
          $api->group(['prefix'  => 'invitation'], function ($api) {
            $api->get('data', 'InvitationController@data');
          });


          // 会员资产路由
          $api->group(['prefix'  => 'asset'], function ($api) {
            $api->get('center', 'AssetController@center');
            $api->get('lollipop', 'AssetController@lollipop');
            $api->get('money', 'AssetController@money');
            $api->get('production', 'AssetController@production');
          });

          // 会员送货地址路由
          $api->group(['prefix'  => 'address'], function ($api) {
            $api->get('list', 'AddressController@list');
            $api->get('select', 'AddressController@select');
            $api->get('view/{id}', 'AddressController@view');
            $api->get('default', 'AddressController@default');
            $api->post('handle', 'AddressController@handle');
            $api->post('delete', 'AddressController@delete');
          });

          // 会员关注路由
          $api->group(['prefix'  => 'attention'], function ($api) {
            $api->get('list', 'AttentionController@list');
            $api->post('status', 'AttentionController@status');
            $api->post('handle', 'AttentionController@handle');
          });

          // 会员消息路由
          $api->group(['prefix'  => 'message'], function ($api) {
            $api->get('list', 'MessageController@list');
            $api->get('view/{id}', 'MessageController@view');
            $api->post('read', 'MessageController@read');
          });


          // 会员粉丝路由
          $api->group(['prefix'  => 'fans'], function ($api) {
            $api->get('list', 'FansController@list');
            $api->get('select', 'FansController@select');
          });

          // 会员点赞路由
          $api->group(['prefix'  => 'approval'], function ($api) {
            $api->get('list', 'ApprovalController@list');
            $api->get('select', 'ApprovalController@select');
            $api->post('status', 'ApprovalController@status');
            $api->post('handle', 'ApprovalController@handle');
          });

          // 会员评论路由
          $api->group(['prefix'  => 'comment'], function ($api) {
            $api->get('list', 'CommentController@list');
            $api->get('select', 'CommentController@select');
            $api->post('handle', 'CommentController@handle');
          });

          // 会员邀请路由
          $api->group(['prefix'  => 'invitation'], function ($api) {
            $api->get('list', 'InvitationController@list');
            $api->get('select', 'InvitationController@select');
            $api->post('status', 'InvitationController@status');
            $api->post('handle', 'InvitationController@handle');
          });

          // 会员投诉路由
          $api->group(['prefix'  => 'complain'], function ($api) {
            $api->get('list', 'ComplainController@list');
            $api->get('select', 'ComplainController@select');
            $api->get('view/{id}', 'ComplainController@view');
            $api->post('handle', 'ComplainController@handle');
          });
        });
      });
    });
  });
});
