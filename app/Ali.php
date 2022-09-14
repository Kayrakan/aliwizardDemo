<?php


namespace App;


use AliexpressSolutionOrderInfoGetRequest;
use App\Exceptions\AliexpressApiException;
use App\Models\Store;
use OrderDetailQuery;
use TopClient;

class Ali
{
    private $client;
    /**
     * @var string
     */
    private $accessToken;

    public function __construct(string $accessToken)
    {
        $client = new TopClient();
        $client->appkey = env('AE_APP_KEY');
        $client->secretKey = env('AE_APP_SECRET');
        $client->format = 'json';
        $this->client = $client;
        $this->accessToken = $accessToken;
    }

    static function createClient(Store $store): Ali
    {
        return new Ali($store->access_token);
    }

    /**
     * @throws AliexpressApiException
     */
    public function doRequest(object $request)
    {

        $result = $this->client->execute($request, $this->accessToken);
//        dump($result);
        if (property_exists($result, 'code')) {
            throw new AliExpressApiException($result->msg, $result->code);
        }

        return $result;
    }

    public function getOrderDetails(int $orderId)
    {
        $req = new AliexpressSolutionOrderInfoGetRequest;
        $param1 = new OrderDetailQuery;
        $param1->order_id = $orderId;
        $req->setParam1(json_encode($param1));
        return $this->doRequest($req);
    }
}
