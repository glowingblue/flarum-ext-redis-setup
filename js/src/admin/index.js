/*
 * This file is part of glowingblue/redis-setup.
 *
 * Copyright (c) 2022 Glowing Blue AG.
 * Authors: Ian Morland, iPurpl3x, Rafael Horvat.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

import app from 'flarum/admin/app';
import { extend } from 'flarum/common/extend';
import StatusWidget from 'flarum/admin/components/StatusWidget';
import { slug } from '../common';

// Make translation calls shorter
const t = app.translator.trans.bind(app.translator);
const prfx = `${slug}.admin.settings`;

app.initializers.add(slug, () => {
	app.extensionData
		.for(slug)
		.registerSetting({
			setting: 'glowingblue-redis.enableCache',
			type: 'boolean',
			label: t(`${prfx}.enable_cache`),
		})
		.registerSetting({
			setting: 'glowingblue-redis.redisSessions',
			type: 'boolean',
			label: t(`${prfx}.enable_redis_sessions`),
		})
		.registerSetting({
			setting: 'glowingblue-redis.enableQueue',
			type: 'boolean',
			label: t(`${prfx}.enable_queue`),
		});

	if (app.initializers.has('blomstra/horizon')) {
		app.extensionData.for(slug).registerSetting({
			setting: 'glowingblue-redis.horizonConfig',
			type: 'textarea',
			label: t(`${prfx}.horizon_config`),
			help: t(`${prfx}.horizon_help_text`),
		});
	}

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
