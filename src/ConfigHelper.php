<?php


namespace Mife;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class ConfigHelper.
 */
class ConfigHelper
{


    /**
     * ConfigHelper constructor.
     */
    public function __construct()
    {

        $this->base_url =  getenv('MIFE_BASE_URL');
        $this->grant_type =  getenv('MIFE_GRANT_TYPE');
        $this->username = getenv('MIFE_APP_ID');
        $this->password = getenv('MIFE_APP_PASSWORD');
        $this->log_enable = getenv('MIFE_LOG');


        //Database
        $this->servername = getenv('DB_HOST');
        $this->dbUserName = getenv('DB_USERNAME');
        $this->dbPassword = getenv('DB_PASSWORD');
        $this->dbName = getenv('DB_DATABASE');
    }

    /**
     * @param string $part
     *
     * @return string $base_url
     */
    public function getUrl($part)
    {
        $base_url = $this->base_url . $part;

        return $base_url;
    }

    /**
     * @return string $authorization_code
     */
    public function getAuthorizationCode()
    {
        //$authorization_code = $this->consumer_key . ':' . $this->consumer_secret;
        $authorization_code = getenv('MIFE_BASE_64_AUTH');
        return $authorization_code;
    }

    /**
     * @return array $headers
     */
    public function getAccessTokenGenerateRequestHeaders()
    {
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic ' . $this->getAuthorizationCode(),
        ];

        return $headers;
    }

    /**
     * @return array
     * @throws Exceptions\InvalidFileContentException
     *
     */
    public function getAccessTokenGenerateRequestBody()
    {

        $auth = new Authentication\Authentication();
        $request_body = [
            'grant_type' => $this->grant_type,
        ];


        return $request_body;
    }

    /**
     * @param null $url
     * @param null $method
     * @param null $request_headers
     * @param null $request_body
     * @param null $response
     * @param null $file_name
     *
     * @throws \Exception
     */
    public function logService($url = null, $method = null, $request_headers = null, $request_body = null, $response = null, $file_name = null)
    {
        $request_headers = json_encode($request_headers);
        $request_body = json_encode($request_body);

        /*
           CREATE TABLE `mife_log`  (
          `id` int(0) NOT NULL AUTO_INCREMENT,
          `request_url` text,
          `request_method` text,
          `request_header` text,
          `request_body` text,
          `http_status` text,
          `response_header` text,
          `response_body` text,
          `date_time` datetime(0) DEFAULT now(),
          PRIMARY KEY (`id`)
        );
         */


        if ($this->log_enable) {
            $content = [
                'request_url' => $url,
                'request_method' => $method,
                'request_header' => $request_headers,
                'request_body' => $request_body,
                'http_status' => $response->getStatusCode(),
                'response_header' => json_encode($response->getHeaders()),
                'response_body' => json_encode(json_decode($response->getBody()) != null ? json_decode($response->getBody()) : $response->getBody()),
            ];

            $content = (object)$content;

            try {
                $conn = new  \mysqli($this->servername, $this->dbUserName, $this->dbPassword, $this->dbName);

                $sql = "INSERT INTO mife_log (request_url, request_method, request_header, request_body, http_status, response_header, response_body) 
                        VALUES ('$content->request_url','$content->request_method', '$content->request_header','$content->request_body' ,'$content->http_status','$content->response_header','$content->response_body')";

                $conn->query($sql);
                $conn->close();
            } catch (\Exception $e) {
                return false;
            }

        }
        return true;
    }

    public function setAccessToken($accessToken)
    {
        /**
         * CREATE TABLE `mife_access_token`  (
         * `id` int(0) NOT NULL AUTO_INCREMENT,
         * `json` text,
         * `date_time` datetime(0) DEFAULT now(),
         * PRIMARY KEY (`id`)
         * );
         */
        try {

            $conn = new  \mysqli($this->servername, $this->dbUserName, $this->dbPassword, $this->dbName);

            $sql = "TRUNCATE TABLE mife_access_token;";
            $conn->query($sql);

            $sql = "INSERT INTO mife_access_token (json) VALUES ('$accessToken');";
            $conn->query($sql);

            $conn->close();
        } catch (\Exception $e) {
            return false;
        }


    }

    public function getAccessToken()
    {

        try {

            $conn = new  \mysqli($this->servername, $this->dbUserName, $this->dbPassword, $this->dbName);

            $sql = "select json from mife_access_token;";

            $res = $conn->query($sql);
            $row = $res->fetch_assoc();
            $conn->close();

            if (isset($row['json'])) {
                return json_decode($row['json']);
            } else {
                return (object)["access_token" => ""];
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }


    }
}
