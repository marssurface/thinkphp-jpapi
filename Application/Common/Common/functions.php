<?php
/**
 * Created by PhpStorm.
 * User: JP
 * Date: 2017/3/9
 * Time: 15:31
 */

/**
 * curl post 请求
 * @param $url  请求链接
 * @param $data post参数
 * @return mixed
 */
function curlPost($url, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_PORT, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false );
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $output = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($output,true);
    return $output;


}