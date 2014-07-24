<?php
    namespace Admin\Manager;

    use Admin\Entity\AbstractEntity;
    use Admin\Entity\Article;
    use Doctrine\ORM\Query\ResultSetMapping;

    class ArticleManager extends AbstractManager
    {
        private $fields = array(
            'id' => array(
                'id',
            ),
            'fk' => array(
                'userId' => 'user',
            ),
            'fields' => array(
                'isActive',
                'text',
            ),
        );

        public function populateEntity(AbstractEntity $entity, $data, $fields)
        {
            parent::populateEntity($entity, $data, $fields);

            if($data['userId']) {
                $user_manager = new UserManager($this->entity_manager);
                $user = $user_manager->getOneById($data['userId']);

                if($user) {
                    $entity->user = $user;
                }
            } else {
                $entity->user = null;
            }

            return $entity;
        }

        public function populateArray(AbstractEntity $entity, $data = array(), $fields = null)
        {
            if(!$fields) {
                $fields = $this->fields;
            }

            $data = parent::populateArray($entity, $data, $fields);
            return $data;
        }

        public function insert($data)
        {
            $article = new Article();
            $this->populateEntity($article, $data, $this->fields);

            $this->entity_manager->persist($article);
            $this->entity_manager->flush();

            return $article->id;
        }

        public function update($data)
        {
            $article = $this->getOneById($data['id']);
            if($article) {
                $this->populateEntity($article, $data, $this->fields);

                $this->entity_manager->persist($article);
                $this->entity_manager->flush();

                return $article->id;
            }

            return null;
        }
    }