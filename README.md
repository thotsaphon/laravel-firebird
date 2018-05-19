# laravel-firebird เขียนเองใช้เอง

To use this package:

**For Firebird 3.0 or above**

สร้าง Generator / Sequence สำหรับ Primary key เอาเอง เพราะ Auto increment ของ Firebird มันรันข้ามกันไม่ได้เหมือน MySQL จึงไม่อยากสร้าง Identity ผูกกับ Primary Key

Installation
------------

Install the Firebird PDO driver for PHP.


Install using composer:
```json
composer require thotsaphon/laravel-firebird
```

Update the `app/config/app.php`, add the service provider:
```json
'Firebird\FirebirdServiceProvider'.
```

You can remove the original DatabaseServiceProvider, as the original connection factory has also been extended.

Declare your connection in the database config, using 'firebird' as the
connecion type.
Other keys that are needed:
```php
'firebird' => [
    'driver'   => 'firebird',
    'host'     => env('DB_HOST', 'localhost'),
    'database' => env('DB_DATABASE','/storage/firebird/APPLICATION.FDB'),
    'username' => env('DB_USERNAME', 'sysdba'),
    'password' => env('DB_PASSWORD', 'masterkey'),
    'charset'  => env('DB_CHARSET', 'UTF8'),
    'collation'  => env('DB_COLLATION', 'UTF8'),
],
```