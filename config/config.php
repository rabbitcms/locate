<?php
return [
    /**
     * The number of minutes during which the location data should be cached.
     * Set to "0" to disable cache
     */
    'cache'            => 0,

    /**
     * MaxMind API Key (http://www.maxmind.com/app/web_services#city)
     */
    'maxmind_key'      => env('LOCATE_MAXMIND_KEY', ''),

    /**
     * IPInfoDB API Key (http://ipinfodb.com/register.php)
     */
    'ipinfodb_key'     => env('LOCATE_IPINFODB_KEY', ''),

    /**
     * Service priority
     * Options: 'MaxMind', 'IPInfoDB', 'FreeGeoIP'
     */
    'service_priority' => [
        'IPInfoDB',
        'MaxMind',
        'FreeGeoIP',
    ],

    'reserved_ips' => [
        ['0.0.0.0', '2.255.255.255'],
        ['10.0.0.0', '10.255.255.255'],
        ['127.0.0.0', '127.255.255.255'],
        ['169.254.0.0', '169.254.255.255'],
        ['172.16.0.0', '172.31.255.255'],
        ['192.0.2.0', '192.0.2.255'],
        ['192.168.0.0', '192.168.255.255'],
        ['255.255.255.0', '255.255.255.255'],
    ],
];