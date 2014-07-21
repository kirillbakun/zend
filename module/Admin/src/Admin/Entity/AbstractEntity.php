<?php
    namespace Admin\Entity;

    abstract class AbstractEntity
    {
        public function __get($property)
        {
            $method = 'get' .ucfirst($property);
            if(method_exists($this, $method)) {
                return $this->$method();
            }

            return null;
        }

        public function __set($property, $value)
        {
            $method = 'set' .ucfirst($property);
            if(method_exists($this, $method)) {
                return $this->$method($value);
            }

            return null;
        }
    }