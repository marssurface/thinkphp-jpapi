<?php
/**
 * Created by PhpStorm.
 * User: JP
 * Date: 2017/3/9
 * Time: 16:29
 */

namespace Api\Controller;
use Think\Controller;

class ReceiveMessageWXController extends Controller
{

    public function receiveMessage()
    {
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];

        libxml_disable_entity_loader(true);
        $msgObj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

        $msg = json_decode(json_encode($msgObj), true);
        $msgType = $msg['MsgType'];
        $from = $msg['FromUserName'];
        $to = $msg['ToUserName'];

        if ('text' == $msgType)
        {

        }elseif('event' == $msgType){

        }
    }

    /**
     * 微信配置 接收微信配置消息 回复微信消息
     */
    public function valid()
    {
        $str = $_GET['echostr'];
        if($this -> checkSignature())
        {
            header('content-type:text/html');
            echo $str;
            exit;
        }
    }

    /**
     * 校验微信TOKEN 检查signature
     */
    private function checkSignature()
    {
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        $signature = $_GET['signature'];
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }




}