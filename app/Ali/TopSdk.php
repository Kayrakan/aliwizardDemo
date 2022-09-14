<?php
/**
 * TOP SDK 入口文件
 * 请不要修改这个文件，除非你知道怎样修改以及怎样恢复
 * @author xuteng.xt
 */

/**
 * 定义常量开始
 * 在include("TopSdk.php")之前定义这些常量，不要直接修改本文件，以利于升级覆盖
 */
/**
 * SDK工作目录
 * 存放日志，TOP缓存数据
 */
if (!defined("TOP_SDK_WORK_DIR")) {
    define("TOP_SDK_WORK_DIR", "/tmp/");
}

/**
 * 是否处于开发模式
 * 在你自己电脑上开发程序的时候千万不要设为false，以免缓存造成你的代码修改了不生效
 * 部署到生产环境正式运营后，如果性能压力大，可以把此常量设定为false，能提高运行速度（对应的代价就是你下次升级程序时要清一下缓存）
 */
if (!defined("TOP_SDK_DEV_MODE")) {
    define("TOP_SDK_DEV_MODE", true);
}

if (!defined("TOP_AUTOLOADER_PATH")) {
    define("TOP_AUTOLOADER_PATH", dirname(__FILE__));
}

/**
 * 注册autoLoader,此注册autoLoader只加载top文件
 * 不要删除，除非你自己加载文件。
 **/
require("Autoloader.php");

function ae_loaded()
{
    return true;
}

function getClient(): TopClient
{
    $client = new TopClient();
    $client->appkey = env('AE_APP_KEY');
    $client->secretKey = env('AE_APP_SECRET');
    $client->format = 'json';
    return $client;
}
function getOrderDetails(int $order_id, $sk): stdClass{
    $client = getClient();
    $req = new AliexpressSolutionOrderInfoGetRequest;
    $param1 = new OrderDetailQuery;
    $param1->order_id = $order_id;
    $req->setParam1(json_encode($param1));
    return $client->execute($req, $sk);
}
function getMerchantProfile($sk)
{
    $aeClient = getClient();
    $req = new AliexpressSolutionMerchantProfileGetRequest;
    $response =  $aeClient->execute($req, $sk);
    if (property_exists($response,'shop_id')){
        return $response;
    }
    dump($response);
    return false;
}


