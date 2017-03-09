<?php

/**
 * Created by PhpStorm.
 * User: JP
 * Date: 2017/3/9
 * Time: 15:13
 */
namespace Api\Controller;
use Think\Controller;
class WeiXinApiController extends Controller
{
    protected $appId;
    protected $appSecret;

    /**
     * 获取 access_token
     * @return mixed
     */
    public function getAccessTOKEN()
    {
        $appid = $this -> appId;
        $appsecret = $this -> appSecret;
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
        $res = file_get_contents($url);
        $res = json_decode($res, true);
        return $res['access_token'];
    }

    /**
     *创建菜单
     */
    public function createMenu()
    {
        $access_token = $this ->getAccessTOKEN();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access_token";

        $menu_data['button'] = array(

            array(
                'name' => '菜单一',
                'sub_button' => array(
                    array(
                        'type' => 'view',
                        'name' => '子菜单一',
                        'url'  => ''
                    ),
                    array(
                        'type' => 'view',
                        'name' => '子菜单二',
                        'url'  => ''
                    ),
                ),
            ),
            array(
                'type' => 'click',
                'name' => '菜单二',
                'key'  => ''
            ),
            array(
                'type' => 'location_select',
                'name' => '菜单三',
                'key'  => ''
            )
        );
        $data = json_encode($menu_data);
        $res =  curlPost($url, $data);
    }

    /**
     * 网页授权第一步 获取code
     */
    public function authCode()
    {
        $redirect_uri = '';
        $scope = 'snsapi_base'; //snsapi_userinfo
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=$redirect_uri
                    &response_type=code&scope=$scope&state=STATE#wechat_redirect";
        redirect($url);
    }

    /**
     * 授权第二步 用code 换取信息 access_token openid refresh_token
     * @param $code
     */
    public function getAuthAccessToken($code)
    {
        $appid = $this -> appId;
        $appsecret = $this -> appSecret;
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
        $access = file_get_contents($url);
        $access = json_decode($access, true);
        $access_token = $access['access_token'];
        $refresh_token = $access['refresh_token'];
        $openid = $access['openid'];
    }

    /**
     * 获取用户信息 需scope为 snsapi_userinfo
     * @param $access_token
     * @param $openid
     *
     */
    public function getUserInfo($access_token, $openid)
    {
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
        $info = file_get_contents($url);
    }

    /**
     * 刷新access_token
     * @param $refresh_token
     */
    public function refreshToken($refresh_token)
    {
        $appid = $this -> appId;
        $url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=$appid&grant_type=refresh_token&refresh_token=$refresh_token";
        $access = file_get_contents($url);
        $access = json_decode($access, true);
        $access_token = $access['access_token'];
        $refresh_token = $access['refresh_token'];
        $openid = $access['openid'];
    }
}