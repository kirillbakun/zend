<?php
    namespace Admin\Manager;

    interface IManagerFactory
    {
        public static function createByName($name);
    }