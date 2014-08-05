<?php
    namespace Admin\Helper;

    class ConfigHelper
    {
        private static $cache_enable = false;
        private static $per_page = 5;

        public static function getParam($key)
        {
            return (isset(self::$$key)) ? self::$$key : null;
        }
    }