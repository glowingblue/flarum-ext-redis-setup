<?php

namespace GlowingBlue\RedisSetup\Extend;

use Blomstra\Redis\Extend\Redis;
use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Arr;

class EnableRedis implements ExtenderInterface
{
    const CACHE_KEY = 'connections.cache';
    const QUEUE_KEY = 'connections.queue';
    const SESSION_KEY = 'connections.session';
    
    public function extend(Container $container, Extension $extension = null)
    {
        $config = $this->buildConfig();
        
        if (Arr::hasAny($config, [self::CACHE_KEY, self::QUEUE_KEY, self::SESSION_KEY])) {
            (new Redis($config))->disable($this->getDisabledServices())->extend($container, $extension);
        }
    }

    private function getDisabledServices(): array
    {
        /** @var SettingsRepositoryInterface */
        $settings = resolve(SettingsRepositoryInterface::class);

        $disabled = [];

        if (!(bool) $settings->get('glowingblue-redis.enableCache', false)) {
            $disabled[] = 'cache';
        }

        if (!(bool) $settings->get('glowingblue-redis.enableQueue', false)) {
            $disabled[] = 'queue';
        }

        if (!(bool) $settings->get('glowingblue-redis.redisSessions', false)) {
            $disabled[] = 'session';
        }
        
        return $disabled;
    }

    private function buildConfig(): array
    {
        if ($this->getHost() === null) {
            return [];
        }

        $config = [];

        $cache = [
            'host' => $this->getHost(),
            'password' => $this->getPassword(),
            'port' => $this->getPort(),
            'database' => $this->getCacheDatabase(),
        ];

        $queue = [
            'host' => $this->getHost(),
            'password' => $this->getPassword(),
            'port' => $this->getPort(),
            'database' => $this->getQueueDatabase(),
        ];

        $session = [
            'host' => $this->getHost(),
            'password' => $this->getPassword(),
            'port' => $this->getPort(),
            'database' => $this->getSessionDatabase(),
        ];

        $config = Arr::add($config, self::CACHE_KEY, $cache);
        $config = Arr::add($config, self::QUEUE_KEY, $queue);
        $config = Arr::add($config, self::SESSION_KEY, $session);

        return $config;
    }

    private function getHost(): ?string
    {
        return getenv('REDIS_HOST') ? getenv('REDIS_HOST') : null;
    }

    private function getPassword(): ?string
    {
        return getenv('REDIS_PASSWORD') ? getenv('REDIS_PASSWORD') : null;
    }

    private function getPort(): string
    {
        return getenv('REDIS_PORT') ? getenv('REDIS_PORT') : '6379';
    }

    private function getCacheDatabase(): int
    {
        return (int) getenv('REDIS_DATABASE_CACHE') ? getenv('REDIS_DATABASE_CACHE') : 1;
    }

    private function getQueueDatabase(): int
    {
        return (int) getenv('REDIS_DATABASE_QUEUE') ? getenv('REDIS_DATABASE_QUEUE') : 2;
    }

    private function getSessionDatabase(): int
    {
        return (int) getenv('REDIS_DATABASE_SESSION') ? getenv('REDIS_DATABASE_SESSION') : 3;
    }
}
