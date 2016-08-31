<?php

namespace RabbitCMS\Locate\Providers;

use MaxMind\Db\Reader;
use RabbitCMS\Locate\Exceptions\LocationNotFound;
use RabbitCMS\Locate\Location;
use RabbitCMS\Locate\Provider;

class MaxMindDB extends Provider
{
    public function location($ip)
    {
        // Jump ship if no key has been specified
        if (!array_key_exists('maxmind_path', $this->config)) {
            throw new LocationNotFound();
        }

        $reader = new Reader($this->config['maxmind_path']);
        $data = $reader->get($ip);
        if (!is_array($data)) {
            throw new LocationNotFound();
        }

        return new Location(
            'MaxMindDB', $ip, [
                'city'         => array_key_exists('city', $data) ? $data['city']['names']['en'] : null,
                'state'        => array_key_exists('subdivisions', $data) ? $data['subdivisions'][0]['names']['en'] : null,
                'state_code'   => array_key_exists('subdivisions', $data) ? $data['subdivisions'][0]['iso_code'] : null,
                'country'      => $data['country']['names']['en'],
                'country_code' => $data['country']['iso_code'],
                'lat'          => $data['location']['latitude'],
                'lng'          => $data['location']['longitude'],
            ]
        );
    }
}