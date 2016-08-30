<?php

namespace RabbitCMS\Locate;

class Location
{
    public $service;

    public $ip;

    public $country;

    public $country_code;

    public $state;

    public $state_code;

    public $zipcode;

    public $city;

    public $lat = 0.0;

    public $lng = 0.0;

    public function __construct($service, $ip, array $options = [])
    {
        $this->service = $service;
        $this->ip = $ip;
        foreach ($options as $key => $value) {
            if (property_exists($this, $key)) {
            }
            $this->$key = $value;
        }
    }
}