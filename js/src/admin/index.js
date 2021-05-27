import app from 'flarum/admin/app';

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
				'glowingblue-redis-setup.admin.settings.enable_redis_sessions'
			),
		})
		.registerSetting({
			setting: 'glowingblue-redis.enableQueue',
			type: 'boolean',
			label: app.translator.trans('glowingblue-redis-setup.admin.settings.enable_queue'),
		});
});
