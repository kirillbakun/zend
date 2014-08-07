<?php
    namespace Admin\Manager;

    use Admin\Entity\AbstractEntity;
    use Admin\Helper\ConfigHelper;
    use Admin\Helper\EntityFieldsHelper;
    use Admin\Helper\MemcacheHelper;
    use Doctrine\ORM\EntityManager;

    abstract class AbstractManager
    {
        protected $entity_manager;
        protected $appropriate_entity;
        protected $appropriate_table;
        protected $per_page;
        protected $cache_enable;

        public function __construct(EntityManager $entity_manager)
        {
            $this->entity_manager = $entity_manager;
            $full_class_name = get_called_class();
            $class_name = explode('\\', $full_class_name)[2];
            $this->appropriate_entity = 'Admin\Entity\\' .str_replace('Manager', '', $class_name);
            $this->appropriate_table = mb_strtolower(str_replace('Manager', '', $class_name), 'utf-8');
            $this->per_page = ConfigHelper::getParam('per_page');
            $this->cache_enable = ConfigHelper::getParam('cache_enable');
        }

        protected function populateEntity(AbstractEntity $entity, $data)
        {
            $fields = EntityFieldsHelper::getFields($this->appropriate_table);
            $foreign_keys = EntityFieldsHelper::getForeignKeys($this->appropriate_table);

            foreach($fields as $field) {
                if(isset($entity->$field['name'])) {
                    if($field['type'] == 'bool') {
                        $entity->$field['name'] = (isset($data[$field['name']]) && $data[$field['name']]) ? $data[$field['name']] : null;
                    } else {
                        $entity->$field['name'] = (isset($data[$field['name']])) ? $data[$field['name']] : null;
                    }
                }
            }

            foreach($foreign_keys as $fk) {
                if(isset($entity->$fk['entity'])) {
                    if(isset($data[$fk['name']]) && $data[$fk['name']]) {
                        $manager = 'Admin\Manager\\'  .ucfirst($fk['entity']) .'Manager';
                        if(class_exists($manager)) {
                            $fk_manager = new $manager($this->entity_manager);
                            $fk_entity = $fk_manager->getOneById($data[$fk['name']]);
                            $entity->$fk['entity'] = $fk_entity;
                        } else {
                            $entity->$fk['entity'] = null;
                        }
                    } else {
                        $entity->$fk['entity'] = null;
                    }
                }
            }

            return $entity;
        }

        public function populateArray(AbstractEntity $entity)
        {
            $fields = EntityFieldsHelper::getFields($this->appropriate_table);
            $foreign_keys = EntityFieldsHelper::getForeignKeys($this->appropriate_table);

            $data = array();
            $data['id'] = isset($entity->id) ? $entity->id : null;

            foreach($fields as $field) {
                if(isset($entity->$field['name'])) {
                    $data[$field['name']] = $entity->$field['name'];
                }
            }

            foreach($foreign_keys as $fk) {
                if(isset($entity->$fk['entity'])) {
                    $data[$fk['name']] = $entity->$fk['entity']->id;
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

        public function insert($data)
        {
            if(class_exists($this->appropriate_entity)) {
                $entity = new $this->appropriate_entity();
                $this->populateEntity($entity, $data);
                $this->entity_manager->persist($entity);
                $this->entity_manager->flush();

                return $entity->id;
            }

            return null;
        }

        public function update($data)
        {
            $manager_class = 'Admin\Manager\\' .ucfirst($this->appropriate_table) .'Manager';
            if(class_exists($manager_class)) {
                $manager = new $manager_class($this->entity_manager);
                $entity = $manager->getOneById($data['id']);
                if($entity) {
                    $this->populateEntity($entity, $data);
                    $this->entity_manager->persist($entity);
                    $this->entity_manager->flush();

                    return $entity->id;
                }
            }

            return null;
        }
    }