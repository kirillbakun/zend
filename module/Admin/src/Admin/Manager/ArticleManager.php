<?php
    namespace Admin\Manager;

    use Admin\Entity\Article;

    class ArticleManager extends AbstractManager
    {
        private function populateEntity(Article $article, $data)
        {
            $article->isActive = ($data['isActive']) ? 1 : 0;
            $article->text = $data['text'];

            if($data['userId']) {
                $user_manager = new UserManager($this->entity_manager);
                $user = $user_manager->getOneById($data['userId']);

                if($user) {
                    $article->user = $user;
                }
            }

            return $article;
        }

        public function insertArticle($data)
        {
            $article = new Article();
            $article = $this->populateEntity($article, $data);

            $this->entity_manager->persist($article);
            $this->entity_manager->flush();

            return $article->id;
        }
    }