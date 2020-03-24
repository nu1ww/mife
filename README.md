 
# Dialog MIFE Package
MIFE API Handler is a plugin to handle REST API request for Dialog External and Internal MIFE APIs 

* Dialog SMS Gateway
* Dialog Charging Gateway

## Requirements

* PHP 5.6 +
* Phalcon / CI / CakePhp / Laravel 5.5+

## Installation

=> Install the package by running this command in your terminal/cmd:
```
composer require nu1ww/mife
```

=> You need to setup below values in .env file
```
MIFE_BASE_URL=https://extmife.dialog.lk/extapi/xxxxx
MIFE_GRANT_TYPE=client_credentials
MIFE_BASE_64_AUTH=xxxxVHVFd2NGNm9Qc2ZCemR2UUhBYTpmR3VqT3BN*********************
MIFE_APP_ID=xxx
MIFE_APP_PASSWORD=xxxxx
MIFE_LOG=true // You can disable the logs using true and false
MIFE_ACCESS_TOKEN_STORE=DB // You can select the either "DB" or "FILE" to store the access_token
```

=> If you need keep record API request response logs in database set the below values in database and run MySql queries.
```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=test
DB_USERNAME=root
DB_PASSWORD=
```
=> Please run below mysql queries
```
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

#---------------------------------------

CREATE TABLE `mife_access_token`  (
`id` int(0) NOT NULL AUTO_INCREMENT,
`json` text,
`date_time` datetime(0) DEFAULT now(),
PRIMARY KEY (`id`)
);

```
=> Send a SMS
```php
$sendSms = new  \Mife\DialogSms\DialogSms();
$data = $sendSms->sendSms('779233884', 'This is test');
```
=> To get session key
```php
$bill = new  \Mife\DialogAddToBill\DialogAddToBill();
$data = $bill->getSessionKey(); 
```

=> To check the credit balance
```php
$bill = new  \Mife\DialogAddToBill\DialogAddToBill();
$data = $bill->creditCheck(779233884); 
```

=> Add to bill
```php
$mobileNumber='777313687';
$invoiceNumber=3522;
$reasonCode=2071;
$amount=1;
$bill = new  \Mife\DialogAddToBill\DialogAddToBill();
$data = $d->chargeToBill($mobileNumber, $invoiceNumber,$reasonCode, $amount); 
```

It has following functions:
* Generate access token
```php
MIFE::generateAccessToken();
```

* Get access token
```php
MIFE::getAccessToken();
````

* Make the custom request
```php
$accessToken = MIFE::getAccessToken();
$url = "https://extmife.dialog.lk/extapi/xyz";
$method = "POST";
$headers = [
    "Content-Type" => "application/json",
    "Authorization" => "Bearer " . $accessToken,
    "Accept" => "application/json",
];
$requestBody = [
    "param1" => 1234,
    "param2" => "param1",
];

// Rest API request and response get to a variable                
$response = MIFE::apiCall($url, $method, $headers, $requestBody);

// Get response body
$response->getBody();

// Get status code
$response->getStatusCode();

// Get response headers
$response->getHeaders();
```

## Author

* [**Nuwan Wijethilaka**](https://github.com/nu1ww)

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details

## Special Thanks to

* [LAYOUTindex](https://www.layoutindex.com/) Team
