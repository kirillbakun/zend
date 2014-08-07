<?php
    namespace Admin\Manager;

    class EntityManager extends AbstractManager
    {
        public function getOneActiveByTable($table, $active_flag = 'isActive')
        {
            return $this->entity_manager->getRepository($this->appropriate_entity)->findOneBy(array(
                'table' => $table,
                $active_flag => 1,
            ));
        }
    }