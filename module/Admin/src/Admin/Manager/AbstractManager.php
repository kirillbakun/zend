<?php
    namespace Admin\Manager;

    use Admin\Entity\AbstractEntity;
    use Admin\Helper\ConfigHelper;
    use Admin\Helper\MemcacheHelper;
    use Doctrine\ORM\EntityManager;

    abstract class AbstractManager
    {
        protected $entity_manager;
        protected $appropriate_entity;
        protected $per_page;
        protected $cache_enable;

        public function __construct(EntityManager $entity_manager)
        {
            $this->entity_manager = $entity_manager;
            $class_name = get_called_class();
            $this->appropriate_entity = 'Admin\Entity\\' .str_replace('Manager', '', explode('\\', $class_name)[2]);
            $this->per_page = ConfigHelper::getParam('per_page');
            $this->cache_enable = ConfigHelper::getParam('cache_enable');
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

        protected function getValueFromCache($function = '', $params = array())
        {
            if(MemcacheHelper::isEnableCaching($this->appropriate_entity)) {
                $data = MemcacheHelper::getItem($this->appropriate_entity, $function, $params);

                if($data) {
                    return $data;
                }
            }

            return null;
        }

        public function getOneById($id)
        {
            return $this->entity_manager->find($this->appropriate_entity, (int)$id);
        }

        public function getList($order = null)
        {
            if($this->cache_enable) {
                $value_from_cache = $this->getValueFromCache(__FUNCTION__);
                if($value_from_cache) {
                    return $value_from_cache;
                }
            }

            $data = $this->entity_manager->getRepository($this->appropriate_entity)->findBy(array(), $order);

            if($this->cache_enable && $data) {
                MemcacheHelper::setItem($data, $this->appropriate_entity, __FUNCTION__);
            }

            return $data;
        }

        public function getActiveList($active_flag_name = 'isActive', $order = null)
        {
            return $this->entity_manager->getRepository($this->appropriate_entity)->findBy(array($active_flag_name => 1), $order);
        }

        public function getListByPageNumber($page_number, $order = null)
        {
            $offset = ($page_number - 1)*$this->per_page;

            return $this->entity_manager->getRepository($this->appropriate_entity)->findBy(array(), $order, $this->per_page, $offset);
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

        public function getCount()
        {
            $query_builder = $this->entity_manager->createQueryBuilder();
            $query_builder->select($query_builder->expr()->count('e'))->from($this->appropriate_entity, 'e');
            $query = $query_builder->getQuery();

            return $query->getSingleScalarResult();
        }

        public function getPerPage()
        {
            return $this->per_page;
        }

        public abstract function insert($data);
        public abstract function update($data);
    }