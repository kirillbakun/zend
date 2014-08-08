<?php
    namespace Admin\Helper;

    class MemcacheHelper
    {
        /**
         * @var \Memcache self::$memcache;
         */

        private static $memcache;
        private static $port = 11211;
        private static $host = 'localhost';
        private static $entities = array(
            'Article',
        );

        private static function createMemcacheInstance()
        {
            if(!self::$memcache) {
                self::$memcache = new \Memcache();
                self::$memcache->connect(self::$host, self::$port);
            }
        }

        public static function getItem($entity, $function = '', $params = array())
        {
            self::createMemcacheInstance();

            $key_hash = self::getKeyHashByParams($entity, $function, $params);
            $data = self::$memcache->get($key_hash);
            $is_data_valid = ($data && isset($data['created']) && $data['created'] && isset($data['value'])) ? true : false;

            if($is_data_valid) {
                $entity_update_time = self::getEntityUpdateTime($entity);
                if($data['value'] > $entity_update_time) {
                    return $data['value'];
                }
            }

            return null;
        }

        public static function setItem($value, $entity, $function = '', $params = array())
        {
            self::createMemcacheInstance();

            $key_hash = self::getKeyHashByParams($entity, $function, $params);
            $data = array(
                'value' => $value,
                'created' => time(),
            );

            return self::$memcache->set($key_hash, $data);
        }

        public static function removeItem($entity, $function = '', $params = array())
        {
            self::createMemcacheInstance();

            $key_hash = self::getKeyHashByParams($entity, $function, $params);

            return self::$memcache->delete($key_hash);
        }

        public static function flush()
        {
            self::createMemcacheInstance();

            self::$memcache->flush();

            return true;
        }

        public static function isEnableCaching($entity)
        {
            return in_array(str_replace('Admin\Entity\\', '', $entity), self::$entities);
        }

        private static function getKeyHashByParams($entity, $function = '', $params = array())
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

        private static function getEntityUpdateTime($entity)
        {
            $key_string = self::getKeyHashByParams($entity);
            $data = self::$memcache->get($key_string);

            return (isset($data['created']) && $data['created']) ? $data['created'] : 0;
        }
    }