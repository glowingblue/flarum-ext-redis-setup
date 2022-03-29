<?php

/*
 * This file is part of glowingblue/redis-setup.
 *
 * Copyright (c) 2022 Glowing Blue AG.
 * Authors: Rafael Horvat.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace GlowingBlue\RedisSetup\Extend;

use Blomstra\Horizon\Extend\Horizon;
use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension;
use Flarum\Extension\ExtensionManager;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Container\Container;

class ConfigureHorizon implements ExtenderInterface
{
	public function extend(Container $container, Extension $extension = null)
	{
		$extensions = resolve(ExtensionManager::class);

		if (!$extensions->isEnabled('blomstra-horizon') || !class_exists(Horizon::class)) {
			return;
		}

		/** @var SettingsRepositoryInterface */
		$settings = resolve(SettingsRepositoryInterface::class);

		$config = json_decode($settings->get('glowingblue-redis.horizonConfig', '[]'), true);

		if (!is_array($config) || empty($config)) {
			return;
		}

		(new Horizon())
			->config($config)
			->extend($container, $extension);
	}
}
