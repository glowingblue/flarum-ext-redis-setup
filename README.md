# ⚡️ Redis Setup

[![MIT license](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/glowingblue/flarum-ext-redis-setup/blob/master/LICENSE.md)
[![Latest Stable Version](https://img.shields.io/packagist/v/glowingblue/redis-setup.svg)](https://packagist.org/packages/glowingblue/redis-setup)
[![Total Downloads](https://img.shields.io/packagist/dt/glowingblue/redis-setup.svg)](https://packagist.org/packages/glowingblue/redis-setup)

A [Flarum](http://flarum.org) extension.

Makes it easy to enable/disable Redis features:

-   Cache
-   Queue
-   Sessions

If you are using a local redis setup, you will likely be able to simply use the defaults provided.
Any of these can be overridden using environment variables as follows:

```ini
REDIS_HOST='127.0.0.1 # Optional, else uses default
REDIS_PORT=6379 # Optional, else uses default
REDIS_PASSWORD=null # Optional, otherwise null
REDIS_DATABASE_CACHE=1 # Optional, else uses default
REDIS_DATABASE_QUEUE=2 # Optional, else uses default
REDIS_DATABASE_SESSION=3 # Optional, else uses default
REDIS_PREFIX='flarum_' # Optional, else uses default
```

## 📥 Installation

```bash
composer require glowingblue/redis-setup
```

## ♻ Updating

```bash
composer update glowingblue/redis-setup
php flarum cache:clear
```

## 🔗 Links

-   [Flarum Discuss post](https://discuss.flarum.org/d/27455)
-   [Source code on GitHub](https://github.com/glowingblue/flarum-ext-redis-setup)
-   [Report an issue](https://github.com/glowingblue/flarum-ext-redis-setup/issues)
-   [Download via Packagist](https://packagist.org/packages/glowingblue/redis-setup)
