# EncryptsAttributes

Purpose: This package will be used to encrypt attributes of a model.

## For using this package

You can install the package in your laravel installation by firing up the below command:

`composer require bvipul/laravel-encrypts-attributes`

After that, add provider to `config/app.php`

### Configuration

After installing the package, register the `Bvipul\EncryptsAttributes\EncryptsServiceProvider::class` in your `config/app.php` configuration file:

```php
'providers' => [
    // Other service providers...

    Bvipul\EncryptsAttributes\EncryptsServiceProvider::class
],
```
After this, you can add the trait this package provides, like below I have done in User.php model file.

```php
<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Bvipul\EncryptsAttributes\Traits\EncryptsAttributes;

class User extends Authenticatable
{
    use Notifiable,
        EncryptsAttributes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}

```
## Issues

If you come across any issues please report them [here](https://github.com/bvipul/LaravelEncryptsAttributes/issues).

## Contributing
Feel free to create any pull requests for the project. For propsing any new changes or features you want to add to the project, you can send us an email me at basapativipulkumar@gmail.com.

## License

[MIT LICENSE](https://github.com/bvipul/LaravelEncryptsAttributes/blob/master/LICENSE.txt)
