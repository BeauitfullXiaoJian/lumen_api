<?php
/*
 * 描述：登入授权服务
 * 文件：AuthService.php
 * 日期：2017年11月15日
 * 作者: xiaojian
 */
namespace App\Core;

use App\Classes\UserInfo;
use App\Core\AuthContract;
use App\Models\AccessLogin;
use App\Models\AccessUser;
use Illuminate\Support\Facades\Crypt;

class AuthService implements AuthContract
{

    //用户ORM对象
    public $user;

    //用户信息对象
    public $info;

    //登入日志
    public $log;

    public function __construct()
    {
        $this->user = new AccessUser;
    }

    /*
     * 描述：用户注册方法，只允许内部使用
     * 参数：array[ string(account:账号), string(password：密码),....其它需要插入到user表中的参数]
     * 返回: boolean(result:注册结果)
     * 日期：2017年11月15日
     * 作者: xiaojian
     */
    public function signup($params)
    {

        // 加密密码
        $params['password'] = $this->secretPassword($params['password']);

        // 查询账号是否已经被注册
        $user = $this->user->where(['account' => $params['account']])->value('id');

        if (isset($user)) {
            // 该账号已经被注册
            return false;
        } else {
            // 尝试添加账号(数据库中账号必须设置成唯一字段)
            $uid = $this->user->insertGetId($params);
            if (isset($uid) && $uid > 0) {
                $this->user = $this->user->find($uid);
                return true;
            }
            // 账户已经被注册
            else {
                return false;
            }
        }
    }

    /*
     * 描述：用户登入方法(登入不会更新token,也不能获取用户token,必须使用相关的方法去那么做)
     * 参数：array[ string(account:账号), string(password：密码)]
     * 返回: boolean(result:登入结果)
     * 日期：2017年11月15日
     * 作者: xiaojian
     */
    public function signin($params)
    {

        // 查询用户是否存在
        $user = $this->user->select('id', "password as secret")->where(['account' => $params['account']])->first();

        // 用户不存在，退出
        if (empty($user)) {
            return false;
        }

        // 用户密码不正确，退出
        if (!$this->checkPassword($params['password'], $user->secret)) {
            return false;
        }

        // 获取用户信息
        $this->user = $this->user->find($user->id);
        $this->info = new UserInfo($this->user);

        return true;
    }

    /*
     * 描述：用户退出登入方法（必须是登入的用户才可以退出）
     * 参数：string(platform:平台名称)
     * 返回: void
     * 日期：2017年11月15日
     * 作者: xiaojian
     */
    public function signout($platform = null)
    {

        $platform = isset($platform) ? $platform : $this->log->platform;

        AccessLogin::where(['uid' => $this->user->id, 'platform' => $platform])->update(['token' => '']);
    }

    /*
     * 描述：删除用户（同时会清空登入状态）
     * 参数：int(user:用户id)
     * 返回: boolean(result:删除结果)
     * 日期：2017年11月15日
     * 作者: xiaojian
     */
    public function destroy($uid)
    {
        AccessLogin::where(['uid' => $uid])->delete();
        return !empty(AccessUser::where(['id' => $uid])->delete());
    }

    /*
     * 描述：清空指定用户登入状态
     * 参数：int(user:用户id)
     * 返回: void
     * 日期：2017年11月15日
     * 作者: xiaojian
     */
    public function clean($uid)
    {
        AccessLogin::where(['uid' => $uid])->update(['token' => '']);
    }

    /*
     * 描述：权限令牌校验方法(校验通过后可以获取用户的个人信息)
     * 参数：string(secret:加密的索引ID),string(token:权限令牌)
     * 返回：boolean(result:校验结果)
     * 日期：2017年11月15日
     * 作者: xiaojian
     */
    public function checkToken($secret, $token, $platform)
    {

        // 查询权限令牌是否存在
        $secret = $this->decodeSecretId($secret);

        if ($secret < 0) {
            return false;
        }

        $log = AccessLogin::where(['id' => $secret, 'token' => $token, 'platform' => $platform])->first();

        if (empty($log)) {
            return false;
        }

        if (empty($log->token)) {
            return false;
        }

        $this->user = $this->user->find($log->uid);
        $this->info = new UserInfo($this->user);
        $this->log = $log;

        return true;
    }

    /*
     * 描述：权限令牌更新方法(必须登入后/令牌校验后才能使用)
     * 参数：string(platform:平台名称)
     * 返回：string(secret:加密的索引ID),string(token:权限令牌)
     * 日期：2017年11月15日
     * 作者: xiaojian
     */
    public function updateToken($platform)
    {

        $log = AccessLogin::where(['uid' => $this->user->id, 'platform' => $platform])->first();

        if (empty($log)) {
            $log = new AccessLogin;
        }

        // 新增/更新令牌
        $log->uid = $this->user->id;
        $log->platform = $platform;
        $log->token = $this->getOneToken();
        $log->save();

        // 记录当前登入记录
        $this->log = $log;

        return ['secret' => $this->encodeSecretId($log->id), 'token' => $log->token];
    }

    // 生成一个新的权限令牌
    public function getOneToken()
    {
        return base64_encode(sha1(uniqid() . 'anasit'));
    }

    // 加密的索引ID
    public function encodeSecretId($login_id)
    {
        return base64_encode($login_id);
    }

    // 解密索引ID
    public function decodeSecretId($secret)
    {
        $id = base64_decode($secret);
        return (bool)preg_match('/^[1-9][0-9]*$/', $id) ? $id : 0;
    }

    /*
     * 描述：用户密码加密方法
     * 参数：string(password:密码)
     * 返回：string(password:加密后的密码)
     * 日期：2017年11月15日
     * 作者: xiaojian
     */
    public function secretPassword($password)
    {
        return Crypt::encrypt($password);
    }

    /*
     * 描述：用户密码校验方法
     * 参数：string(password:原始密码),string(secret:加密后的密码)
     * 返回：boolean(result:校验结果)
     * 日期：2017年11月15日
     * 作者: xiaojian
     */
    public function checkPassword($password, $secret)
    {
        return Crypt::decrypt($secret) === $password;
    }
}
