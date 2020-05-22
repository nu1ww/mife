<?php

namespace Mife\DialogAddToBill;


use Mife\ConfigHelper;
use Mife\MIFE;

class DialogAddToBill
{
    private $appId;
    private $appPassword;
    private $accessToken;

    public function __construct()
    {
        $this->appId = getenv('MIFE_APP_ID');
        $this->appPassword = getenv('MIFE_APP_PASSWORD');
        $this->accessToken = MIFE::getAccessToken();
        $this->helper = new ConfigHelper();
    }

    /**
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Mife\Exceptions\InvalidFileContentException
     * @throws \Mife\Exceptions\InvalidResponseException
     * @throws \Mife\Exceptions\TokenGenerateException
     */
    public function getSessionKey()
    {
        $endPoint = "https://extmife.dialog.lk/extapi/api_crm_0000120181025/accounts/payments/session";

        $method = "GET";
        $headers = [
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . $this->accessToken,
            "Accept" => "application/json",
        ];
        $request_body = [
            'appId' => $this->appId,
            'password' => $this->appPassword
        ];


        // Rest API request and response get to a variable
        $response = MIFE::apiCall($endPoint, $method, $headers, $request_body, 'query');
        // Get response body


        // Get status code validate validate
        if ($response->getStatusCode() == 200) {
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

    /**
     * @param $mobileNumber
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Mife\Exceptions\InvalidFileContentException
     * @throws \Mife\Exceptions\InvalidResponseException
     * @throws \Mife\Exceptions\TokenGenerateException
     */
    public function creditCheck($mobileNumber)
    {
        $endPoint = "https://extmife.dialog.lk/extapi/api_crm_0000120181025/accounts/$mobileNumber/creditcheck";

        $method = "GET";
        $headers = [
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . $this->accessToken,
            "Accept" => "application/json",
        ];

        $getSessionKey = $this->getSessionKey();

        if (!$getSessionKey->status) {
            dd('fail');
        }


        $request_body = [
            'appid' => $this->appId,
            'authKey' => $getSessionKey->data->getSessionKeyResponse->result,
            'domain' => 'GSM',
            'trxid' => $this->appId . time()
        ];

        //dd($endPoint, $method, $headers, $request_body, 'query');
        // Rest API request and response get to a variable
        $response = MIFE::apiCall($endPoint, $method, $headers, $request_body, 'query');
        // Get response body

        // return $response;
        // Get status code validate
        if ($response->getStatusCode() == 200) {
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

    /**
     * @param $mobileNumber
     * @param $invoiceNumber
     * @param $reasonCode
     * @param $amount
     * @param bool $taxable
     * @return object
     * @throws \Mife\Exceptions\InvalidFileContentException
     * @throws \Mife\Exceptions\InvalidResponseException
     * @throws \Mife\Exceptions\TokenGenerateException
     */

    public function chargeToBill($mobileNumber, $invoiceNumber, $reasonCode, $amount, $taxable = false)
    {
        $endPoint = "https://extmife.dialog.lk/extapi/api_crm_0000120181025/accounts/$mobileNumber/charge";

        $method = "POST";
        $headers = [
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . $this->accessToken,
            "Accept" => "application/json",
        ];

        $getSessionKey = $this->getSessionKey();

        if (!$getSessionKey->status) {
           // dd('fail');
        }


        $request_body = [
            "chargeToBill" => [
                "ChargedToBillRequest_1" => [
                    'appID' => $this->appId,
                    'authKey' => $getSessionKey->data->getSessionKeyResponse->result,
                    'domainID' => 'GSM',
                    'transactionID' => $invoiceNumber,
                    "amount" => $amount,
                    "reasonCode" => $reasonCode,
                    "taxable" => $taxable
                ]]
        ];

        // Rest API request and response get to a variable
        $response = MIFE::apiCall($endPoint, $method, $headers, $request_body, 'json');


        // Get response body
        // return $response;
        // Get status code validate
        if ($response->getStatusCode() == 200) {

            // if chargeToBillResponse->transResult = 0 Its success transaction

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