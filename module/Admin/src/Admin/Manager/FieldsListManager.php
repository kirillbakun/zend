<?php
    namespace Admin\Manager;

    class FieldsListManager extends AbstractManager
    {
        public function getListByEntityId($id, $order_by = array('position' => 'ASC'))
        {
            return $this->entity_manager
                ->getRepository($this->appropriate_entity)
                ->findBy(array('entity' => (int)$id), $order_by);
        }
    }