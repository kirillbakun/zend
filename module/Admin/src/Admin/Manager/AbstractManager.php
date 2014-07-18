<?php
    namespace Admin\Manager;

    use Doctrine\ORM\EntityManager;

    abstract class AbstractManager
    {
        protected $entity_manager;
        private $appropriate_entity;

        public function __construct(EntityManager $entity_manager)
        {
            $this->entity_manager = $entity_manager;
            $class_name = get_called_class();
            $this->appropriate_entity = 'Admin\Entity\\' .str_replace('Manager', '', explode('\\', $class_name)[2]);
        }

        public function setEntityManager(EntityManager $entity_manage)
        {}

        public function getOneById($id)
        {
            return $this->entity_manager->getRepository($this->appropriate_entity)->findBy(array('id' => (int)$id));
        }

        public function getList()
        {
            return $this->entity_manager->getRepository($this->appropriate_entity)->findAll();
        }
    }