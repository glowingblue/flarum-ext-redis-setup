<?php

/*
 * This file is part of glowingblue/redis-setup.
 *
 * Copyright (c) 2022 Glowing Blue AG.
 * Authors: Ian Morland, iPurpl3x, Rafael Horvat.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace GlowingBlue\RedisSetup;

use Flarum\Extend;
use GlowingBlue\RedisSetup\Extend as GBExtend;
use GlowingBlue\RedisSetup\Provider\QueueProvider;

return [
	(new Extend\Frontend('admin'))
		->js(__DIR__ . '/js/dist/admin.js'),

	new Extend\Locales(__DIR__ . '/resources/locale'),

	new GBExtend\ConfigureHorizon(),
	new GBExtend\EnableRedis(),

	(new Extend\ServiceProvider())
		->register(QueueProvider::class),
];
