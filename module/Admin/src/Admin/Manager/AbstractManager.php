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

        public function getOneById($id)
        {
            return $this->entity_manager->find('Admin\Entity\User', (int)$id);
        }

        public function getList()
        {
            return $this->entity_manager->getRepository($this->appropriate_entity)->findAll();
        }

        public function getActiveList($active_flag_name = 'isActive')
        {
            return $this->entity_manager->getRepository($this->appropriate_entity)->findBy(array($active_flag_name => 1));
        }
    }