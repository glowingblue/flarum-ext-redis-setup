<?php

/*
 * This file is part of glowingblue/redis-setup.
 *
 * Copyright (c) 2022 Glowing Blue AG.
 * Authors: iPurpl3x, Ian Morland, Rafael Horvat.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace GlowingBlue\RedisSetup\Provider;

use Flarum\Extend\Frontend;
use Flarum\Extension\ExtensionManager;
use Flarum\Foundation\AbstractServiceProvider;
use Flarum\Frontend\Document;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Queue\Events\Looping;
use Illuminate\Queue\RedisQueue;
use Illuminate\Contracts\Queue\Queue as QueueContract;

class QueueProvider extends AbstractServiceProvider
{
	private $connection = 'default';

	public function boot()
	{
		/** @var SettingsRepositoryInterface */
		$settings = resolve(SettingsRepositoryInterface::class);

		if (!(bool) $settings->get('glowingblue-redis.enableQueue', false)) {
			return;
		}

		/** @var ExtensionManager $extensions */
		$extensions = resolve(ExtensionManager::class);

		(new Frontend('admin'))
			->content([$this, 'adminWidgetAttributes'])
			->extend($this->container, $extensions->getExtension('glowingblue-redis-setup'));

		/** @var Dispatcher $dispatcher */
		$dispatcher = $this->container->make(Dispatcher::class);
		$dispatcher->listen(Looping::class, [$this, 'trackQueues']);
	}

	public function adminWidgetAttributes(Document $document)
	{
		/** @var Store $cache */
		$cache = resolve('cache.store');
		/** @var QueueContract $queue */
		$queue = resolve(QueueContract::class);

		$queues = $cache->get('blomstra.queue.queues-seen') ?? [];

		if ($queue instanceof RedisQueue) {
			$load = [];

			foreach ($queues as $name) {
				$load[$name] = $queue->getRedis()
					->connection($this->connection)
					->llen('queues:' . $name);
			}
		}

		$document->payload['blomstraQueuesSeen'] = $queues;
		$document->payload['blomstraQueuesLoad'] = $load ?? null;
	}

	public function trackQueues(Looping $event)
	{
		/** @var Store $cache */
		$cache = resolve('cache.store');

		$queues = $cache->get('blomstra.queue.queues-seen') ?? [];
		$queues = array_merge($queues, (array) explode(',', $event->queue));
		$cache->put('blomstra.queue.queues-seen', array_unique($queues), 60);
	}
}
