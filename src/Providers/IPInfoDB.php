<?php

namespace RabbitCMS\Locate\Providers;

use RabbitCMS\Locate\Exceptions\LocationNotFound;
use RabbitCMS\Locate\Location;
use RabbitCMS\Locate\Provider;

class IPInfoDB extends Provider
{
    public function location($ip)
    {
        // Jump ship if no key has been specified
        if (!array_key_exists('ipinfodb_key', $this->config)) {
            return false;
        }
        $options = [
            'key'    => $this->config['ipinfodb_key'],
            'ip'     => $ip,
            'format' => 'json',
        ];
        $response = @file_get_contents('http://api.ipinfodb.com/v3/ip-city/?' . http_build_query($options));
        if ($response !== false) {
            $response = json_decode($response, true);
            // Verify fields
            if (!isset($response['statusCode']) || $response['statusCode'] != 'OK') {
                return false;
            }
            $required_fields = ['cityName', 'regionName', 'latitude', 'longitude'];
            foreach ($required_fields AS $field) {
                if (!isset($response[$field]) || empty($response[$field])) {
                    return false;
                }
            }

            return new Location(
                'IPInfoDB', $ip, [
                    'city'         => ucwords(strtolower($response['cityName'])),
                    'state'        => ucwords(strtolower($response['regionName'])),
                    'state_code'   => ucwords(strtolower($response['regionName'])),
                    'country'      => ucwords(strtolower($response['countryName'])),
                    'country_code' => strtoupper($response['countryCode']),
                    'zipcode'      => $response['zipCode'],
                    'lat'          => $response['latitude'],
                    'lng'          => $response['longitude'],
                ]
            );
        }

        throw new LocationNotFound();
    }
}