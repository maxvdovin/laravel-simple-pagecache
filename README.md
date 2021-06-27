# Simple Page Cache

## Setup
```
composer require maxvdovin/laravel-simple-pagecache
```

Add ServiceProvider to the providers array in config/app.php:

```
MXJ\PageCache\PageCacheServiceProvider::class,
```

Make sure that in config/cache.php contains:
```
    'database' => [
        'driver' => 'database',
        'table' => 'cache',
        'connection' => null,
    ],
```

Make migration to add `cache` table:
```
php arthisan migrate
```

## Configuration
Optionally in /HTTP/Kernel.php in `$routeMiddleware` array you can add
```
'pagecahe' => PageCache::class,
```

To publish a configuration file use
```
php artisan vendor:publish
```

 Config file contains two options:
 * ``livetime`` option set cache live time in minutes. By default, two days: 60 * 24 * 2 = 2880
 * ``store`` option set default store. By default `database`.

## Usage
You can use `MXJ\PageCache\Middleware\PageCache` middleware in the route. 
This middleware will intercept request from unauthenticated users and caching response data in DB.

```
use MXJ\PageCache\Middleware\PageCache;
Route::post('contact', [AnyController::class, 'show'])->middleware(PageCache::class);
```
