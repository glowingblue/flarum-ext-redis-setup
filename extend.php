<?php

/*
 * This file is part of glowingblue/redis-setup.
 *
 * Copyright (c) 2021 Ian Morland.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace GlowingBlue\RedisSetup;

use Flarum\Extend;
use GlowingBlue\RedisSetup\Extend\EnableRedis;

return [
	(new Extend\Frontend('admin'))
		->js(__DIR__.'/js/dist/admin.js'),

	new Extend\Locales(__DIR__.'/resources/locale'),

	new EnableRedis()
];
