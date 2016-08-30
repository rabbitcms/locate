<?php

namespace RabbitCMS\Locate;

use RabbitCMS\Locate\Exceptions\LocationNotFound;

abstract class Provider
{
    const CACHE_KEY = 'rabbitcms.locate';

    protected $config;

    public function __construct()
    {
        $this->config = \Config::get('locate');
    }

    /**
     * Get location from IP.
     *
     * @param  string $ip Optional
     *
     * @return Location
     */
    public static function getLocation($ip)
    {
        //return \Cache::remember(
        //    self::CACHE_KEY . '.' . $ip,
        //    \Config::get('locate.cache'),
        //    function () use ($ip) {
                if (!self::checkIp($ip)) {
                    throw new LocationNotFound("IP $ip is reserved.");
                }
                $p = null;
                foreach (\Config::get('locate.service_priority', []) as $provider) {
                    try {
                        return self::factory($provider)->location($ip);
                    } catch (LocationNotFound $p) {
                        continue;
                    }
                }
                throw new LocationNotFound('', 0, $p);
        //    }
        //);
    }

    /**
     * Checks if the ip is not local or empty.
     *
     * @param string $ip
     *
     * @return bool
     */
    protected static function checkIp($ip)
    {
        $reserved_ips = \Config::get('locate.reserved_ips', []);

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $longip = ip2long($ip);

            if (!empty($ip)) {
                foreach ($reserved_ips as $r) {
                    $min = ip2long($r[0]);
                    $max = ip2long($r[1]);

                    if ($longip >= $min && $longip <= $max) {
                        return false;
                    }
                }

                return true;
            }
        } else if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return true;
        }

        return false;
    }

    /**
     * Get location by IP.
     *
     * @param string $ip
     *
     * @return Location
     * @throws LocationNotFound
     */
    abstract public function location($ip);

    /**
     * Get provider instance.
     *
     * @param string $provider
     *
     * @return Provider
     */
    public static function factory($provider)
    {
        $class = new \ReflectionClass(__NAMESPACE__ . '\\Providers\\' . $provider);
        /* @var Provider $provider */
        $provider = $class->newInstance();

        return $provider;
    }
}