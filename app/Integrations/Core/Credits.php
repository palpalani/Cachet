<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Integrations\Core;

use CachetHQ\Cachet\Integrations\Contracts\Credits as CreditsContract;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Contracts\Cache\Repository;

/**
 * This is the credits class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author James Brooks <james@alt-three.com>
 */
class Credits implements CreditsContract
{
    /**
     * The default url.
     *
     * @var string
     */
    const URL = 'https://cachethq.io/credits';

    /**
     * The failed status indicator.
     *
     * @var int
     */
    const FAILED = 1;

    /**
     * The cache repository instance.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * The url to use.
     *
     * @var string|null
     */
    protected $url;

    /**
     * Are outbound HTTP requests to the internet allowed by
     * this installation
     *
     * @var bool
     */
    protected $enabled;

    /**
     * Creates a new credits instance.
     *
     * @param \Illuminate\Contracts\Cache\Repository $cache
     * @param bool                                   $enabled
     * @param string|null                            $url
     *
     * @return void
     */
    public function __construct(Repository $cache, bool $enabled = true, $url = null)
    {
        $this->cache = $cache;
        $this->enabled = $enabled;
        $this->url = $url ?: static::URL;
    }

    /**
     * Returns the latest credits.
     *
     * @return array|null
     */
    public function latest()
    {
        if (! $this->enabled) {
            return null;
        }

        $result = $this->cache->remember('credits', 2880, function () {
            try {
                return json_decode((new Client())->get($this->url, [
                    'headers' => ['Accept' => 'application/json', 'User-Agent' => defined('CACHET_VERSION') ? 'cachet/'.constant('CACHET_VERSION') : 'cachet'],
                    'timeout' => 5,
                    'connect_timeout' => 5,
                ])->getBody(), true);
            } catch (Exception $e) {
                return self::FAILED;
            }
        });

        return $result === self::FAILED ? null : $result;
    }
}
