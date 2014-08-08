<?php
    namespace Admin\Helper;

    class StringHelper
    {
        public static function limitLength($string, $limit = 20)
        {
            if(mb_strlen($string, 'utf-8') > $limit) {
                return substr($string, 0, $limit) .'...';
            }

            return $string;
        }
    }