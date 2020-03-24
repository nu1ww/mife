<?php

namespace Mife;


use GuzzleHttp\Client;
use phpDocumentor\Reflection\Types\Self_;

/**
 * Class IDEABIZ.
 */
class MIFE
{
    /**
     * @param $url
     * @param $method
     * @param $headers
     * @param $request_body
     * @param string $guzzle_body_type
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws Exceptions\InvalidFileContentException
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\TokenGenerateException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     */
    public static function apiCall($endPoint, $method, $headers, $request_body, $guzzle_body_type = 'json')
    {
        $auth = new Authentication\Authentication();
        $helper = new ConfigHelper();
        $request = [
            'headers' => $headers,
            $guzzle_body_type => $request_body,
        ];
        $client = new Client(['http_errors' => false]);
        $response = $client->request($method, $endPoint, $request);

        $helper->logService($endPoint, $method, $headers, $request_body, $response);

        $status_code = $response->getStatusCode();

        if ($status_code == 401) {

            $auth->generateAccessToken();
            $accessToken = MIFE::getAccessToken();
            $headers["Authorization"] = "Bearer " . $accessToken;

            $request = [
                'headers' => $headers,
                $guzzle_body_type => $request_body,
            ];

            $response = $client->request($method, $endPoint, $request);

            $helper->logService($endPoint, $method, $headers, $request_body, $response);
        }

        return $response;
    }

    /**
     * @return mixed
     * @throws Exceptions\InvalidFileContentException
     *
     */
    public static function getAccessToken()
    {
        $auth = new Authentication\Authentication();

        return $auth->getAccessToken();
    }

    /**
     * @throws Exceptions\InvalidFileContentException
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\TokenGenerateException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function generateAccessToken()
    {
        $auth = new Authentication\Authentication();
        return $auth->generateAccessToken();
    }
}
