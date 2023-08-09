<?php

/*
 * This file is part of glowingblue/redis-setup.
 *
 * Copyright (c) 2023 Glowing Blue AG.
 * Authors: Ian Morland, iPurpl3x, Rafael Horvat.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

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

		(new Redis($config))
			->disable($this->getDisabledServices())
			->extend($container, $extension);
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

	private function buildConfig($config = []): array
	{
		$base = [
			'host' => $this->getHost(),
			'password' => $this->getPassword(),
			'port' => $this->getPort(),
			'prefix' => $this->getPrefix(),
		];

		$cache = $base + [
			'database' => static::getCacheDatabase(),
		];

		$queue = $base + [
			'database' => static::getQueueDatabase(),
		];

		$session = $base + [
			'database' => static::getSessionDatabase(),
		];

		$config = Arr::add($config, self::CACHE_KEY, $cache);
		$config = Arr::add($config, self::QUEUE_KEY, $queue);
		$config = Arr::add($config, self::SESSION_KEY, $session);

		return $config;
	}

	public static function getHost(): string
	{
		return getenv('REDIS_HOST') ? getenv('REDIS_HOST') : '127.0.0.1';
	}

	public static function getPassword(): ?string
	{
		return getenv('REDIS_PASSWORD') ? getenv('REDIS_PASSWORD') : null;
	}

	public static function getPort(): string
	{
		return getenv('REDIS_PORT') ? getenv('REDIS_PORT') : '6379';
	}

	public static function getCacheDatabase(): int
	{
		return (int) getenv('REDIS_DATABASE_CACHE') ? getenv('REDIS_DATABASE_CACHE') : 1;
	}

	public static function getQueueDatabase(): int
	{
		return (int) getenv('REDIS_DATABASE_QUEUE') ? getenv('REDIS_DATABASE_QUEUE') : 2;
	}

	public static function getSessionDatabase(): int
	{
		return (int) getenv('REDIS_DATABASE_SESSION') ? getenv('REDIS_DATABASE_SESSION') : 3;
	}

	public static function getPrefix(): string
	{
		return getenv('REDIS_PREFIX') ? getenv('REDIS_PREFIX') : 'flarum_';
	}
}
