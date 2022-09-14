<?php

namespace App;

use GuzzleHttp\Client;
use GuzzleHttp;
use http\Exception;
use Illuminate\Support\Carbon;

class Pts
{

    private $baseUrl = 'https://connect-api.pts.net';
    private $username;
    private $password;
    private $access_token;

    function __construct($username, $password, $access_token)
    {
        $this->username = $username;
        $this->password = $password;
        $this->access_token = $access_token;
    }



    public function doRequest($endpoint, $payload, $request_type)
    {
        if ( $this->checkAccessToken() == 200) {
            $c = new Client([
                'headers' => [
                    "accept" => "application/json, text/plain, */*",
                    "accept-language" => "tr",
                    "authorization" => "Bearer " . $this->access_token,
                    "sec-fetch-dest" => "empty",
                    "sec-fetch-mode" => "cors",
                    "sec-fetch-site" => "same-site",
                ],
                'body'=>json_encode($payload),

            ]);

            if($request_type == 'GET') {
                $resp = $c->get("$this->baseUrl${endpoint}");
//                dd(json_decode($resp->getBody()->getContents()));
                return json_decode($resp->getBody()->getContents());
            } else if ( $request_type == 'POST') {


                $resp = $c->post("$this->baseUrl${endpoint}");

                return json_decode($resp->getStatusCode());
            }


        } else {


            try {

                if($this->login() == 200) {

                    return $this->doRequest($endpoint,$payload, $request_type );
                }

            } catch(\GuzzleHttp\Exception\RequestException $er) {

                return $er->getResponse();

            }


        }


    }

    public function login()
    {
        $multipart_form = [
            [
                'name' => 'username',
                'contents' => $this->username,
            ],
            [
                'name' => 'password',
                'contents' => $this->password,
            ],
            [
                'name' => 'grant_type',
                'contents' => 'password'
            ], [
                'name' => 'scope',
                'contents' => 'read write'
            ]];
        $k = new GuzzleHttp\Psr7\MultipartStream($multipart_form, 'WebKitFormBoundaryGorrSQ0v2dMN65Gn');

        $c = new Client([
            'headers' => [
                "accept" => "application/json, text/plain, */*",
                "accept-language" => "tr",
                "authorization" => "Basic cHRzY29ubmVjdGp3dGNsaWVudGlkOkFGWDNWTUVQcUNIZ3E3Tg==",
                "content-type" => "multipart/form-data; boundary=----WebKitFormBoundaryGorrSQ0v2dMN65Gn",
                "sec-ch-ua" => "\" Not A;Brand\";v=\"99\", \"Chromium\";v=\"92\", \"Opera\";v=\"78\"",
                "sec-ch-ua-mobile" => "?0",
                "sec-fetch-dest" => "empty",
                "sec-fetch-mode" => "cors",
                "sec-fetch-site" => "same-site"
            ],
            'body' => $k,
        ]);
        try {

            $resp = $c->post('https://connect-api.pts.net/oauth/token');

            $status = $resp->getStatusCode();
            $resp = json_decode($resp->getBody()->getContents());
            $this->access_token = $resp->access_token;

            return $status;

        } catch (\GuzzleHttp\Exception\RequestException $er){

            return $er->getResponse()->getStatusCode();

        }

    }

    public function checkAccessToken()
    {
        $c = new Client([
            'headers' => [
                "accept" => "application/json, text/plain, */*",
                "accept-language" => "tr",
                "authorization" => "Bearer " . $this->access_token,
                "content-type" => "multipart/form-data; boundary=----WebKitFormBoundaryGorrSQ0v2dMN65Gn",
                "sec-ch-ua" => "\" Not A;Brand\";v=\"99\", \"Chromium\";v=\"92\", \"Opera\";v=\"78\"",
                "sec-ch-ua-mobile" => "?0",
                "sec-fetch-dest" => "empty",
                "sec-fetch-mode" => "cors",
                "sec-fetch-site" => "same-site"
            ]
        ]);
        try {
            $resp = $c->get('https://connect-api.pts.net/store?pageNumber=0&pageSize=40&status=ENABLED');
//            dump('trying checkaccess');
//            dump($resp->getStatusCode());
            return json_decode($resp->getStatusCode());
        } catch(\GuzzleHttp\Exception\RequestException $er) {
//            dump('error checkaccess');
//            dump($er->getResponse()->getStatusCode());

            return $er->getResponse()->getStatusCode();
        }

    }




    public function getPtsOrder($order_id)
    {

        return  $this->doRequest("/order/${order_id}", '', 'GET');
    }

    public function create_shipment($order_id) {

        $info = [

            'orderId' => $order_id,
            'savedBankCardId' => '-1',
        ];

        $c = new Client([
            'headers' => [
                "accept" => "application/json, text/plain, */*",
                "accept-language" => "tr",
                "authorization" => "Bearer " . $this->access_token,
                "content-type" => "application/json",
                "sec-fetch-dest" => "empty",
                "sec-fetch-mode" => "cors",
                "sec-fetch-site" => "same-site",
            ],
            'body' => json_encode($info),
        ]);



        $order = $this->getPtsOrder($order_id)->response;

        if($order->status == 'NO_LOGISTICS') {
            try {

                $resp = $this->doRequest('/shipment/order', $info,'POST');

                return $resp->getStatusCode();

            } catch (\GuzzleHttp\Exception\RequestException $er){

                return $er->getResponse()->getStatusCode();
            }
        } else {
            return response()->json('already shipped', 401);
        }
//            $resp = $c->post('https://connect-api.pts.net/shipment/order');


    }

    public function way_bill($order_id) {

        $res = $this->getPtsOrder($order_id);
        $tracking_code =  $res->response->shipmentDetails->externalShipmentId;

        return "https://pts.net/api/repo/consignment.php?ref=${$tracking_code}&lcode=18&ug=ax";


    }

    public function  create_proforma($order_id) {

        $res = $this->getPtsOrder($order_id)->response;

//        dd($res);

        return [

            'invoice_number' => $res->platformOrderId,
            'date_issued' => Carbon::now('Europe/Istanbul'),
            'billing_to' => $res->customerContactName,
            'customer_tel' => $res->customerPhoneNumber,
            'customer_address'=> $res->customerAddress,
            'customer_city' => $res->customerCity,
            'customer_province' => $res->customerProvince,
            'customer_country_code' => $res->customerCountryCode,
            'order_items'=> $res->orderItems,
            'total_amount' => $res->totalAmount,
            'customer_postcode'=>$res->customerPostCode

        ];



    }

}




