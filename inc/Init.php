<?php 

namespace Inc;

use Inc\Pages\Admin;
use Inc\Base\Enqueue;
use Inc\Data\Api\Cron;


final class Init {

    public static function get_services () {


        return [
            new Admin(),
            new Enqueue(),
            new Cron()
        ];

    }

    

    public static function register_services () {
        foreach (self::get_services() as $class ) {
            $service = self::instantiate($class);
            if ( method_exists( $service, 'register' ) ) {
				$service->register();
			}
        }

    }

    protected static function instantiate ($class) {
        $service = new $class();
        return $service;

    }
}