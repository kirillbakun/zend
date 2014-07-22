<?php
    namespace Admin\Controller;

    use Admin\Form\ArticleFilter;
    use Admin\Form\ArticleForm;
    use Admin\Manager\ArticleManager;
    use Zend\Mvc\Controller\AbstractActionController;
    use Zend\View\Model\ViewModel;

    class ArticleController extends AbstractActionController
    {
        public function indexAction()
        {
            $article_manager = new ArticleManager($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
            $articles = $article_manager->getList();

            return new ViewModel(array(
                'articles' => $articles,
            ));
        }

        public function addAction()
        {
            $entity_manager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $form = new ArticleForm(null, array('entity_manager' => $entity_manager));

            $view_model = new ViewModel(array(
                'form' => $form,
            ));

            return $view_model;
        }

        public function insertAction()
        {
            if(!$this->request->isPost()) {
                return $this->redirect()->toRoute('admin/default', array(
                    'controller' => 'article',
                    'action' => 'add',
                ));
            }

            $post = $this->request->getPost();
            $entity_manager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $form = new ArticleForm(null, array('entity_manager' => $entity_manager));
            $input_filter = new ArticleFilter($entity_manager);
            $form->setInputFilter($input_filter);
            $form->setData($post);

            if(!$form->isValid()) {
                $view_model = new ViewModel(array(
                    'error' => true,
                    'form' => $form,
                ));
                $view_model->setTemplate('admin/article/add');

                return $view_model;
            }

            $article_manager = new ArticleManager($entity_manager);
            $article_manager->insertArticle($form->getData());

            return $this->redirect()->toRoute('admin/default', array(
                'controller' => 'article',
                'action' => 'index'
            ));
        }

        public function editAction()
        {
            $article_id = $this->params()->fromRoute('id');

            if($article_id) {
                $article_manager = new ArticleManager($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
                $article = $article_manager->getOneById($article_id);

                if($article) {
                }
            }
        }

        public function updateAction()
        {}
    }