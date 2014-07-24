<?php
    namespace Admin\Manager;

    use Admin\Entity\AbstractEntity;
    use Doctrine\ORM\EntityManager;

    abstract class AbstractManager
    {
        protected $entity_manager;
        protected $appropriate_entity;

        public function __construct(EntityManager $entity_manager)
        {
            $this->entity_manager = $entity_manager;
            $class_name = get_called_class();
            $this->appropriate_entity = 'Admin\Entity\\' .str_replace('Manager', '', explode('\\', $class_name)[2]);

        }

        protected function populateEntity(AbstractEntity $entity, $data, $fields)
        {
            foreach($fields['fields'] as $field) {
                if(isset($entity->$field)) {
                    $entity->$field = (isset($data[$field]) && $data[$field]) ? $data[$field] : null;
                }
            }

            return $entity;
        }

        protected function populateArray(AbstractEntity $entity, $data, $fields)
        {
            foreach($fields as $part => $fields_group) {
                if($part == 'fk') {
                    continue;
                }

                foreach($fields[$part] as $field) {
                    if(isset($entity->$field)) {
                        $data[$field] = $entity->$field;
                    }
                }
            }

            foreach($fields['fk'] as $key => $field) {
                if(isset($entity->$field)) {
                    $data[$key] = isset($entity->$field->id) ? $entity->$field->id : null;
                }
            }

            return $data;
        }

        public function getOneById($id)
        {
            return $this->entity_manager->find($this->appropriate_entity, (int)$id);
        }

        public function getList()
        {
            return $this->entity_manager->getRepository($this->appropriate_entity)->findAll();
        }

        public function getActiveList($active_flag_name = 'isActive')
        {
            return $this->entity_manager->getRepository($this->appropriate_entity)->findBy(array($active_flag_name => 1));
        }

        public function deleteOneById($id)
        {
            $entity = $this->getOneById($id);

            if($entity) {
                $this->entity_manager->remove($entity);
                $this->entity_manager->flush();

                return true;
            }

            return false;
        }

        public abstract function insert($data);
        public abstract function update($data);
    }