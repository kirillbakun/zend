<?php
    namespace Admin\Helper;

    class EntityFieldsHelper
    {
        private static $fields = array(
            // Article config
            'article' => array(
                'is_active' => true,
                'table' => 'article',
                'name' => 'article',
                'plural_name' => 'articles',

                'fields' => array(
                    'field' => array(
                        'isActive' => array(
                            'name' => 'isActive',
                            'type' => 'bool',
                            'in_list' => true,
                            'display_name' => 'Published',
                            'position' => 2,
                        ),
                        'text' => array(
                            'name' => 'text',
                            'type' => 'text',
                            'in_list' => true,
                            'display_name' => 'Text',
                            'position' => 1,
                        ),
                    ),
                    'fk' => array(
                        'userId' => array(
                            'name' => 'userId',
                            'entity' => 'user',
                            'in_list' => false,
                            'display_name' => 'User name',
                            'related_entity_field' => 'name',
                        ),
                    ),
                ),
            ),

            // User config
            'user' => array(
                'is_active' => true,
                'table' => 'user',
                'name' => 'user',
                'plural_name' => 'users',

                'fields' => array(
                    'field' => array(
                        'isActive' => array(
                            'name' => 'isActive',
                            'type' => 'bool',
                            'in_list' => true,
                            'display_name' => 'Active',
                            'position' => 2,
                        ),
                        'name' => array(
                            'name' => 'name',
                            'type' => 'text',
                            'in_list' => true,
                            'display_name' => 'Name',
                            'position' => 2,
                        ),
                    ),
                    'fk' => array(),
                ),
            ),
        );





        public static function getId($entity_name)
        {
            $data = self::getEntityData($entity_name);
            if($data) {
                $fields = (isset($data['fields']['id'])) ? $data['fields']['id'] : null;

                return $fields;
            }

            return null;
        }

        public static function getFields($entity_name)
        {
            $data = self::getEntityData($entity_name);

            return (isset($data['fields']['field'])) ? $data['fields']['field'] : null;
        }

        public static function getForeignKeys($entity_name)
        {
            $data = self::getEntityData($entity_name);

            return (isset($data['fields']['fk'])) ? $data['fields']['fk'] : null;
        }

        public static function getListDisplayedFields($entity_name)
        {
            $result = array();
            $data = self::getEntityData($entity_name);
            if(!$data) {
                return null;
            }

            foreach($data['fields']['fk'] as $item) {
                if($item['in_list']) {
                    $item['fk'] = true;
                    $result[] = $item;
                }
            }

            foreach($data['fields']['field'] as $item) {
                if($item['in_list']) {
                    $item['fk'] = false;
                    $result[] = $item;
                }
            }

            return $result;
        }

        public static function getEntitiesList()
        {
            $result = array();
            $data = self::$fields;

            foreach($data as $key => $item) {
                if(isset($item['is_active']) && $item['is_active']) {
                    $result[$key]['name'] = (isset($item['name'])) ? $item['name'] : null;
                    $result[$key]['plural_name'] = (isset($item['plural_name'])) ? $item['plural_name'] : null;
                    $result[$key]['table'] = (isset($item['table'])) ? $item['table'] : null;
                }
            }

            return $result;
        }

        public static function getCurrentEntity($entities_list, $table_name)
        {
            return (isset($entities_list[$table_name])) ? $entities_list[$table_name] : null;
        }

        private static function getEntityData($entity_name)
        {
            $fields = self::$fields;

            if(isset($fields[$entity_name])) {
                return $fields[$entity_name];
            } else {
                return null;
            }
        }
    }