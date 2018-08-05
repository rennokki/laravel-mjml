[![Build Status](https://travis-ci.org/rennokki/laravel-mjml.svg?branch=master)](https://travis-ci.org/rennokki/laravel-mjml)
[![codecov](https://codecov.io/gh/rennokki/laravel-mjml/branch/master/graph/badge.svg)](https://codecov.io/gh/rennokki/laravel-mjml/branch/master)
[![StyleCI](https://github.styleci.io/repos/143601238/shield?branch=master)](https://github.styleci.io/repos/143601238)
[![Latest Stable Version](https://poser.pugx.org/rennokki/laravel-mjml/v/stable)](https://packagist.org/packages/rennokki/laravel-mjml)
[![Total Downloads](https://poser.pugx.org/rennokki/laravel-mjml/downloads)](https://packagist.org/packages/rennokki/laravel-mjml)
[![Monthly Downloads](https://poser.pugx.org/rennokki/laravel-mjml/d/monthly)](https://packagist.org/packages/rennokki/laravel-mjml)
[![License](https://poser.pugx.org/rennokki/laravel-mjml/license)](https://packagist.org/packages/rennokki/laravel-mjml)

[![PayPal](https://img.shields.io/badge/PayPal-donate-blue.svg)](https://paypal.me/rennokki)

# Laravel MJML
Laravel MJML is a simple API wrapper for the [MJML.io Render API](https://mjml.io/api). In case you don't know what [MJML.io](https://mjml.io) is, it is a language that helps
building mails easier and faster without messing up with inline HTML. It has its own syntax that can be later rendered using their apps, online editor
or their API.

This API wrapper comes with Mustache Engine integrated, so you can both render the MJML to HTML with applied values from Mustache.

If you don't know what Mustache is check [this Medium article](https://medium.com/@alexrenoki/dynamic-content-in-your-mails-using-mustache-9f3a660462ad) that explains better Mustahce and gets you started on how to use it in your email.

# Installation
Install the package:
```bash
$ composer require rennokki/laravel-mjml
```

If your Laravel version does not support package discovery, add this line in the `providers` array in your `config/app.php` file:
```php
Rennokki\LaravelMJML\LaravelMJMLServiceProvider::class,
```

# Setting up the API
Since it is an API, you'll need credentials. For this, you will have to request yours from their API page: [https://mjml.io/api](https://mjml.io/api) by clicking `Join the beta`. It will take some time to get yours, so be patient.  

To authenticate the API, you will have to call the `Rennokki\LaravelMJML\LaravelMJML` class and then, by chaining methods, to add your `App ID` and your `Secret Key`.
```php
use Rennokki\LaravelMJML\LaravelMJML;

$api = (new LaravelMJML())->setAppId('app_id')->setSecretKey('secret_key');
```

Note: when making requests from the backend, just the `Secret Key` is required. If you plan to do it from the frontend, you will have to use your provided `Public Key` instead, since storing sensitive credentials in frontend is not possible.

# Starting MJML
As MJML code, we'll use this throughout the readme:
```mjml
<mjml>
  <mj-body>
    <mj-section>
      <mj-column>
        <mj-text font-size="20px" color="#F45E43" font-family="helvetica">
            Hello World
        </mj-text>
      </mj-column>
    </mj-section>
  </mj-body>
</mjml>
```

# Rendering
When rendering, simply calling the `render()` method will do the work for you:
```php
$html = $api->render($mjml);
```

As a return, you will get the compiled HTML. In case this rendering failed, due to reasons, you will get `null`, for example:
```php
$html = $api->render('<h1>MJML</h1>'); // null
```

# Rendering with Mustache
If you got started with Mustache, you can render the MJML to HTML and then render the Mustache variables in your compiled HTML using the same method.

For this example, our MJML would look like this:
```mjml
<mjml>
  <mj-body>
    <mj-section>
      <mj-column>
        <mj-text font-size="20px" color="#F45E43" font-family="helvetica">
            {{message}}
        </mj-text>
      </mj-column>
    </mj-section>
  </mj-body>
</mjml>
```

You can call `renderWithMustache` method with MJML and an array which consist the parameters that need to injected:
```php
$html = $api->renderWithMustache($mjml, ['message' => 'Hello World!']);
```
