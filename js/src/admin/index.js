/*
 * This file is part of glowingblue/redis-setup.
 *
 * Copyright (c) 2021 Ian Morland.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

import app from 'flarum/admin/app';
import { extend } from 'flarum/common/extend';
import StatusWidget from 'flarum/admin/components/StatusWidget';

app.initializers.add('glowingblue-redis-setup', () => {
	app.extensionData
		.for('glowingblue-redis-setup')
		.registerSetting({
			setting: 'glowingblue-redis.enableCache',
			type: 'boolean',
			label: app.translator.trans('glowingblue-redis-setup.admin.settings.enable_cache'),
		})
		.registerSetting({
			setting: 'glowingblue-redis.redisSessions',
			type: 'boolean',
			label: app.translator.trans(
				'glowingblue-redis-setup.admin.settings.enable_redis_sessions',
			),
		})
		.registerSetting({
			setting: 'glowingblue-redis.enableQueue',
			type: 'boolean',
			label: app.translator.trans('glowingblue-redis-setup.admin.settings.enable_queue'),
		});

	extend(StatusWidget.prototype, 'items', (items) => {
		const loads = app.data.blomstraQueuesLoad;

		if (loads === undefined) {
			return;
		}

		for (let queue of app.data.blomstraQueuesSeen) {
			const load = loads[queue] || null;
			items.add('blomstra-queue-size-' + queue, [
				<strong>Queue {queue}</strong>,
				<br />,
				load || '0',
			]);
		}
	});
});
