<?php
/*----------------------------------------
 *  CSRF 配置文件
 *  可以配置忽略校验的接口URL
 */
return [
    'ignore' => [
        'signin',//登入接口
        'check',//校验接口
        'signup',//注册接口
    ],
    'regx' => [
        '^wapapp/'
    ]
];