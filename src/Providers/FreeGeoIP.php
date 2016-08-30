<?php

namespace RabbitCMS\Locate\Providers;

use RabbitCMS\Locate\Exceptions\LocationNotFound;
use RabbitCMS\Locate\Location;
use RabbitCMS\Locate\Provider;

class FreeGeoIP extends Provider
{
    /**
     * @inheritdoc
     */
    public function location($ip)
    {
        $response = @file_get_contents('http://freegeoip.net/json/' . $ip);
        if ($response !== false) {
            $response = json_decode($response, true);
            // Verify fields
            $required_fields = ['city', 'region_name', 'latitude', 'longitude'];
            foreach ($required_fields AS $field) {
                if (!isset($response[$field]) || empty($response[$field])) {
                    return false;
                }
            }

            return new Location(
                'FreeGeoIP', $ip, [
                    'city'         => ucwords(strtolower($response['city'])),
                    'state'        => ucwords(strtolower($response['region_name'])),
                    'state_code'   => strtoupper($response['region_code']),
                    'country'      => ucwords(strtolower($response['country_name'])),
                    'country_code' => strtoupper($response['country_code']),
                    'zipcode'      => $response['zip_code'],
                    'lat'          => $response['latitude'],
                    'lng'          => $response['longitude'],
                ]
            );
        }

        throw new LocationNotFound();
    }
}