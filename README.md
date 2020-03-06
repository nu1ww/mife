 
# Laravel MIFE Handler (Laravel 5.5+)
Laravel MIFE Handler is a laravel plugin to handle REST API request for [MIFE](http://www.ideamart.lk/ideaBiz.html) APIs 

## Requirements

* PHP 7.0+
* Laravel 5.5+

## Installation

1) Install the package by running this command in your terminal/cmd:
```
composer require nu1ww/mife
```

2) You can import config file and sample token file by running this command in your terminal/cmd:
```
php artisan vendor:publish --provider="Layoutindex\Mife\RestAPIProvider"
```

3) Then set the configurations in **mife.php** file.

4) For the first time, token generate using **'grant_type' => 'password'** or manualy.
Verify that **token.json** file has the valid access token and refresh token

It has following functions:
* Generate access token
```php
MIFE::generateAccessToken();
```

* Get access token
```php
MIFE::getAccessToken();
````

* Make the request
```php
$access_token = MIFE::getAccessToken();
$url = "https://mife.lk/apicall/xyz"
$method = "POST";
$headers = [
                "Content-Type" => "application/json",
                "Authorization" => "Bearer ".$access_token,
                "Accept" => "application/json",
           ];
$request_body = [
                    "a" => 123,
                    "b" => "xyz",
                ];
 
// Rest API request and response get to a variable                
$response = MIFE::apiCall($url, $method, $headers, $request_body);

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

* [Laravel](https://laravel.com) Community
