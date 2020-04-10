<?php

namespace Mife\DialogSms;


use Mife\ConfigHelper;
use Mife\MIFE;

class DialogSms
{
    private $appId;
    private $appPassword;
    private $accessToken;
    private $senderAddress;
    /**
     * @var ConfigHelper
     */
    private $helper;

    public function __construct()
    {
        $this->appId = getenv('MIFE_APP_ID');
        $this->appPassword = getenv('MIFE_APP_PASSWORD');
        $this->accessToken = MIFE::getAccessToken();
        $this->senderAddress = '444';
        $this->helper = new ConfigHelper();
    }

    public function subscribeV3($mobileNumber)
    {
        $mobileNumber = (int)$mobileNumber;

        $endPoint = "https://ideabiz.lk/apicall/subscription/v3/subscribe";
        $method = "POST";
        $headers = [
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . $this->accessToken,
            "Accept" => "application/json",
        ];

        $request_body = [
            "method" => "WEB",
            "msisdn" => "tel:+94$mobileNumber"
        ];

        //dd($endPoint, $method, $headers, $request_body, 'query');
        // Rest API request and response get to a variable
        $response = MIFE::apiCall($endPoint, $method, $headers, $request_body, 'json');


        // Get response body
        //dd($response->getStatusCode(),$response->getBody());
        // return $response;
        // Get status code validate
        if ($response->getStatusCode() == 200 || $response->getStatusCode() == 201) {
            return (object)[
                "status" => true,
                "data" => json_decode($response->getBody())
            ];
        }

        return (object)[
            "status" => false,
            "data" => []
        ];
    }

    public function sendSms($mobileNumber, $message)
    {

       // $this->subscribeV3($mobileNumber);

        $mobileNumber = (int)$mobileNumber;
        //$endPoint = "https://extmife.dialog.lk/extapi/api_crm_0000120181025/outbound/444/requests";
        $endPoint = "https://extmife.dialog.lk/extapi/api_admin_0000120180220/outbound/$this->senderAddress/requests";

        $method = "POST";
        $headers = [
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . $this->accessToken,
            "Accept" => "application/json",
        ];

        $request_body = [
            "outboundSMSMessageRequest" => [
                "address" => ["tel:+94$mobileNumber"],
                "senderAddress" => "tel:$this->senderAddress",
                "outboundSMSTextMessage" => [
                    "message" => $message
                ],
                "clientCorrelator" => "123456",
                "receiptRequest" => [
                    'notifyURL' => "http://128.199.174.220:1080/sms/report",
                    'callbackData' => "some-data-useful-to-the-requester",
                ],
                "senderName" => $this->senderAddress
            ]
        ];

        //dd($endPoint, $method, $headers, $request_body, 'query');
        // Rest API request and response get to a variable
        $response = MIFE::apiCall($endPoint, $method, $headers, $request_body, 'json');


        // Get response body
        //dd($response->getStatusCode(),$response->getBody());
        // return $response;
        // Get status code validate
        if ($response->getStatusCode() == 200 || $response->getStatusCode() == 201) {
            return (object)[
                "status" => true,
                "data" => json_decode($response->getBody())
            ];
        }

        return (object)[
            "status" => false,
            "data" => []
        ];
    }


}