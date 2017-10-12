<?php

return [ 'WECHAT' => [
    /**
     * Debug 模式，bool 值：true/false
     *
     * 当值为 false 时，所有的日志都不会记录
     */
    'debug'  => false,
    /**
     * 账号基本信息，请从微信公众平台/开放平台获取
     */
//    'app_id'  => 'wx66ff4796d8606ac2',         // AppID
//    'secret'  =>   'd84caad9b4ad1245286c905e749033ca',     // AppSecret  //deb7dd0f3832ce0f90a40bda76da2764

//    'app_id'  => 'wxc85b787e93dd616e',         // 测试
//    'secret'  => 'be8caf5d0dfb5e9d0b4cd804ac136101',

   // bibicar
//    'app_id' => "wx8bac6dd603d47d15",
//    'secret' => "dcc4740804b9c5b19686cb4b1fd5eb8e",
//    'token'   => 'weixin',          // Token
//    'aes_key' => 'qFrfgqJzE27Sp9Wd4ozSArKAKQIC7Q3xRYIaytVMfdj',// EncodingAESKey，安全模式下请一定要填写！！！

    //'token'   => 'bibicar',          // Token
   // 'aes_key' => 'dJ865B7OLD7Mdm85D5h7dcloDC67i76z59Di8B95cMC',// EncodingAESKey，安全模式下请一定要填写！！！
   //yingshiluyou
    'app_id' => "wx3b342900d05a419c",
    'secret' => "e0fdcfc8fe795e2cbb56e7f042b0114d",
    'token'   => 'token',          // Token
    'aes_key' => 'Fb2VgdvwWPUTn6KmuktRqruTuNOeNfZeTM9S6tgvNwt',// EncodingAESKey，安全模式下请一定要填写！！！
    /**
     * 日志配置
     *
     * level: 日志级别, 可选为：
     *         debug/info/notice/warning/error/critical/alert/emergency
     * permission：日志文件权限(可选)，默认为null（若为null值,monolog会取0644）
     * file：日志文件位置(绝对路径!!!)，要求可写权限
     */
    'log' => [
        'level'      => 'debug',
        'permission' => 0777,
        'file'       => '/tmp/easywechat.log',
    ],
    /**
     * OAuth 配置
     *
     * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
     * callback：OAuth授权完成后的回调页地址
     */
    'oauth' => [
        'scopes'   => ['snsapi_userinfo'],
        'callback' => '/base/callback',
    ],
    /**
     * 微信支付
     */
    'payment' => [
        // bibicar
//        'merchant_id'        => '1424297802',
//        'key'                => 'dcc4740804b9c5b19686cb4b1fd5eb8e',
//        'cert_path'          => '/usr/local/nginx/html/cert/apiclient_cert.pem', // XXX: 绝对路径！！！！
//        'key_path'           => '/usr/local/nginx/html/cert/apiclient_key.pem',      // XXX: 绝对路径！！！！
       //yinshi
        'merchant_id'        => '1374250902',
        'key'                => 'b92P13OnVkAyRqXvcfeKGd8rwF6zTjpL',
        'cert_path'          => '/usr/local/nginx/html/cert/yinshi/apiclient_cert.pem', // XXX: 绝对路径！！！！
        'key_path'           => '/usr/local/nginx/html/cert/yinshi/apiclient_key.pem',      // XXX: 绝对路径！！！！
        // 'device_info'     => '013467007045764',
        // 'sub_app_id'      => '',
        // 'sub_merchant_id' => '',
        // ...
    ],
    /**
     * Guzzle 全局设置
     *
     * 更多请参考： http://docs.guzzlephp.org/en/latest/request-options.html
     */
    'guzzle' => [
        'timeout' => 3.0, // 超时时间（秒）
        //'verify' => false, // 关掉 SSL 认证（强烈不建议！！！）
    ],
]
];