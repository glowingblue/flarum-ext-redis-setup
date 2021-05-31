# ⚡️ Redis Setup

[![MIT license](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/glowingblue/flarum-ext-redis-setup/blob/master/LICENSE.md) [![Latest Stable Version](https://img.shields.io/packagist/v/glowingblue/redis-setup.svg)](https://packagist.org/packages/glowingblue/redis-setup) [![Total Downloads](https://img.shields.io/packagist/dt/glowingblue/redis-setup.svg)](https://packagist.org/packages/glowingblue/redis-setup)

A [Flarum](http://flarum.org) extension.

Makes it easy to enable/disable Redis features:

- Cache
- Queue
- Sessions

For this to work, environment variables have to be set on your host:

```ini
REDIS_HOST=null # Required
REDIS_PORT=6379 # Optional, else uses default
REDIS_PASSWORD=null # Required, can be an empty string
REDIS_DATABASE_CACHE=1 # Optional, else uses default
REDIS_DATABASE_QUEUE=2 # Optional, else uses default
REDIS_DATABASE_SESSION=3 # Optional, else uses default
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

- [Flarum Discuss post](https://discuss.flarum.org/d/27455)
- [Source code on GitHub](https://github.com/glowingblue/flarum-ext-redis-setup)
- [Report an issue](https://github.com/glowingblue/flarum-ext-redis-setup/issues)
- [Download via Packagist](https://packagist.org/packages/glowingblue/redis-setup)