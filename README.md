# Csrf

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
![PHP Version](https://img.shields.io/badge/PHP-7.1%2B-blue.svg)
![Release](https://img.shields.io/github/v/release/gokhankurtulus/csrf.svg)

A simple PHP CSRF class that provides functionality for operating CSRF tokens.

## Installation

You can install the Csrf class using [Composer](https://getcomposer.org/). Run the following command in your project's root directory:

```bash
composer require gokhankurtulus/csrf
```

## Usage

To use the Csrf class in your PHP script, you need to include the Composer autoloader:

```php
require_once 'vendor/autoload.php';
```

### Creating a New Token

You can generate a new CSRF token using the `newToken` method. The method accepts two parameters: the token name and an optional expiry time in seconds (default is 600 seconds = 10 minutes).

```php
use Csrf\Csrf;

$token = Csrf::newToken('my_token', 1200); // Generate a token named 'my_token' that expires in 20 minutes
```

The `newToken` method returns a `stdClass` object containing the token information. The object has the following properties:

- `name`: The name of the token.
- `expiry`: The expiry timestamp of the token.
- `value`: The token value.

### Getting a Token

To retrieve a previously generated token, you can use the `getToken` method. It accepts the token name as a parameter and returns the token object if found, or `null` if the token does not exist.

```php
$token = Csrf::getToken('my_token'); // Get the token object for 'my_token'
```

### Creating an HTML Input Field

The `createInput` method generates an HTML input field with the CSRF token embedded. It accepts the token name and an optional expiry time (default is 600 seconds or 10 minutes) as parameters. The
method returns the HTML input field as a string or `null` if the session is not started or the token name is empty.

```php
$input = Csrf::createInput('my_token', 1800); // Generate an HTML input field for 'my_token' that expires in 30 minutes
echo $input; // Output the HTML input field
```

The generated HTML input field can be used in forms to send the CSRF token value along with other form data.

### Verifying a Token

To verify if a submitted token is valid, you can use the `verify` method. It accepts the token name, an optional parameter to unset the token if it is verified (default is `false`), and the token
value submitted with the request (can be retrieved from the `$_POST` superglobal by default).

```php
$isVerified = Csrf::verify('my_token', true); // Verify the submitted token for 'my_token' and unset it if verified
if ($isVerified) {
    // Token is valid
} else {
    // Token is invalid
}
```

The `verify` method returns a boolean value indicating whether the token is valid or not.

### Unsetting a Token

To unset a token manually, you can use the `unsetToken` method. It accepts the token name as a parameter and returns `true` if the token is successfully unset or `false` if the session is not started
or the token name is empty.

```php
Csrf::unsetToken('my_token'); // Unset the token named 'my_token'
```

## Session Status

The Csrf class relies on PHP sessions to store and retrieve CSRF tokens. The `isSessionStarted` method can be used to check if a session is already started.

```php
$isSessionStarted = Csrf::isSessionStarted(); 

// Check if a session is started
if ($isSessionStarted) {
    // Session is active
} else {
    // Session is not active
}
```

## License

Csrf is open-source software released under the [MIT License](LICENSE). Feel free to modify and use it in your projects.

## Contributions

Contributions to Csrf are welcome! If you find any issues or have suggestions for improvements, please create an issue or submit a pull request on
the [GitHub repository](https://github.com/gokhankurtulus/csrf).