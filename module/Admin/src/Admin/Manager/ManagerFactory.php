<?php
    namespace Admin\Manager;

    class ManagerFactory implements IManagerFactory
    {
        public static function createByName($name)
        {
            $class_name = ucfirst($name) .'Manager';

            return class_exists($class_name) ? new $class_name() : null;
        }
    }
?>