<?php
    namespace Admin\Manager;

    use Doctrine\ORM\EntityManager;

    abstract class AbstractManager
    {
        protected $entity_manager;
        protected $appropriate_entity;

        public function __construct(EntityManager $entity_manager)
        {
            $this->entity_manager = $entity_manager;
            $class_name = get_called_class();
            $this->appropriate_entity = str_replace('Manager', '', explode('\\', $class_name)[2]);
        }

        public function getOneById($id)
        {
            $query_builder = $this->entity_manager->createQueryBuilder();
            $query_builder->add('select', 'e')
                ->add('from', 'Admin\Entity\\' .$this->appropriate_entity .' e')
                ->add('where', 'e.id = :identifier')
                ->setParameter('identifier', (int)$id);
            $query = $query_builder->getQuery();

            return $query->getResult();
        }

        public function getList()
        {
            $query_builder = $this->entity_manager->createQueryBuilder();
            $query_builder->add('select', 'e')
                ->add('from', 'Admin\Entity\\' .$this->appropriate_entity .' e');
            $query = $query_builder->getQuery();

            return $query->getResult();
        }
    }