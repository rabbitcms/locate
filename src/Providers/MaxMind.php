<?php

namespace RabbitCMS\Locate\Providers;

use RabbitCMS\Locate\Exceptions\LocationNotFound;
use RabbitCMS\Locate\Location;
use RabbitCMS\Locate\Provider;

class MaxMind extends Provider
{
    /**
     * @inheritdoc
     */
    public function location($ip)
    {
        // Jump ship if no key has been specified
        if (!array_key_exists('maxmind_key', $this->config)) {
            throw new LocationNotFound();
        }
        $options = [
            'l' => $this->config['maxmind_key'],
            'i' => $ip,
        ];
        $response = @file_get_contents('http://geoip.maxmind.com/b?' . http_build_query($options));
        if ($response !== false) {
            $response = explode(',', $response);
            // Verify fields
            if (isset($response[5]) && $response[5] == 'IP_NOT_FOUND') {
                throw new LocationNotFound();
            }
            $required_fields = [1, 2, 3, 4];
            foreach ($required_fields AS $field) {
                if (!isset($response[$field]) || empty($response[$field])) {
                    throw new LocationNotFound();
                }
            }

            return new Location(
                'MaxMind', $ip,
                [
                    'city'         => $response[2],
                    'state'        => $response[1],
                    'state_code'   => $response[1],
                    'country'      => $response[0],
                    'country_code' => $response[0],
                    'zipcode'      => null,
                    'lat'          => $response[3],
                    'lng'          => $response[4],
                ]
            );
        }

        throw new LocationNotFound();
    }
}