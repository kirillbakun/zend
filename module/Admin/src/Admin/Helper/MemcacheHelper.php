<?php
    namespace Admin\Helper;

    class MemcacheHelper
    {
        /**
         * @var \Memcache self::$memcache;
         */

        private static $memcahce;

        public static function getInstance()
        {
            if(!self::$memcahce) {
                self::$memcahce = new \Memcache();
                self::$memcahce->connect('localhost', 11211);
            }

            return self::$memcahce;
        }

        public static function getItem($entity, $function, $params = array())
        {}

        public static function setItem($entity, $function, $params = array())
        {}

        public static function removeItem($entity, $function, $params = array())
        {}

        public static function flush()
        {
            self::$memcahce->flush();
        }

        private static function getKeyHashByParams($entity, $function, $params = array())
        {
            $key_string = $entity .$function;

            if(!$key_string) {
                return null;
            }

            foreach($params as $key => $value) {
                $key_string .= ($key .$value);
            }

            return md5($key_string);
        }
    }