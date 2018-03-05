<?php

namespace App\Core;

interface AuthContract
{

    /*
     * 描述：用户注册方法，只允许内部使用
     * 参数：array[ string(account:账号), string(password：密码),....其它需要插入到user表中的参数]
     * 返回: boolean(result:注册结果)
     * 日期：2017年11月15日
     * 作者: xiaojian
     */
    public function signup($params);
        
    /*
     * 描述：用户登入方法(登入不会更新token,也不能获取用户token,必须使用相关的方法去那么做)
     * 参数：array[ string(account:账号), string(password：密码)]
     * 返回: boolean(result:登入结果)
     * 日期：2017年11月15日
     * 作者: xiaojian
     */
    public function signin($params);
        
    /*
     * 描述：用户退出登入方法（必须是登入的用户才可以退出）
     * 参数：string(platform:平台名称)
     * 返回: void
     * 日期：2017年11月15日
     * 作者: xiaojian
     */
    public function signout($platform=null);
        
    /*
     * 描述：权限令牌校验方法(校验通过后可以获取用户的个人信息)
     * 参数：string(secret:加密的索引ID),string(token:权限令牌)
     * 返回：boolean(result:校验结果)
     * 日期：2017年11月15日
     * 作者: xiaojian
     */
    public function checkToken($secret, $token, $platform);
        
    /*
     * 描述：权限令牌更新方法(必须登入后/令牌校验后才能使用)
     * 参数：string(platform:平台名称)
     * 返回：string(secret:加密的索引ID),string(token:权限令牌)
     * 日期：2017年11月15日
     * 作者: xiaojian
     */
    public function updateToken($platform);
}
