<?php
    namespace Admin\Helper;

    class RelatedEntityHelper
    {
        public static function createSelectDataArray($entities, $display_field, $allow_empty = true)
        {
            $result = array();
            if($allow_empty) {
                $result[] = 'User not specified';
            }

            foreach($entities as $entity) {
                if(isset($entity->$display_field)) {
                    $result[] = $entity->$display_field;
                } else {
                    break;
                }
            }

            return $result;
        }

        public static function createInputFilterDataArray($entities, $allow_empty = true)
        {
            $result = array();
            if($allow_empty) {
                $result[] = 0;
            }

            foreach($entities as $entity) {
                $result[] = $entity->id;
            }

            return $result;
        }
    }