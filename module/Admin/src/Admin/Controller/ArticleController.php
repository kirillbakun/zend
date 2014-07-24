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
            $entity_manager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            $article_manager = new ArticleManager($entity_manager);
            $articles = $article_manager->getList();

            return new ViewModel(array(
                'articles' => $articles,
            ));
        }

        public function addAction()
        {
            $entity_manager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            $form = new ArticleForm(null, array('entity_manager' => $entity_manager));
            $form->setData(array('isActive' => true));

            $view_model = new ViewModel(array(
                'form' => $form,
                'action' => 'insert',
            ));
            $view_model->setTemplate('admin/article/article_form');

            return $view_model;
        }

        public function insertAction()
        {
            $entity_manager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            if(!$this->request->isPost()) {
                return $this->redirect()->toRoute('admin/default', array(
                    'controller' => 'article',
                ));
            }

            $post = $this->request->getPost();
            $form = new ArticleForm(null, array('entity_manager' => $entity_manager));
            $input_filter = new ArticleFilter($entity_manager);
            $form->setInputFilter($input_filter);
            $form->setData($post);

            if(!$form->isValid()) {
                $view_model = new ViewModel(array(
                    'error' => true,
                    'form' => $form,
                    'action' => 'insert',
                ));
                $view_model->setTemplate('admin/article/article_form');

                return $view_model;
            }

            $article_manager = new ArticleManager($entity_manager);
            $article_manager->insert($form->getData());

            return $this->redirect()->toRoute('admin/default', array(
                'controller' => 'article',
            ));
        }

        public function editAction()
        {
            $entity_manager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            $article_id = $this->params()->fromRoute('id');

            if($article_id) {
                $article_manager = new ArticleManager($entity_manager);
                $article = $article_manager->getOneById($article_id);

                if($article) {
                    $form = new ArticleForm(null, array('entity_manager' => $entity_manager));
                    $input_filter = new ArticleFilter($entity_manager);
                    $data = $article_manager->populateArray($article);
                    $form->setInputFilter($input_filter);
                    $form->setData($data);

                    $view_model = new ViewModel(array(
                        'form' => $form,
                        'action' => 'update',
                    ));
                    $view_model->setTemplate('admin/article/article_form');

                    return $view_model;
                }
            }

            $this->getResponse()->setStatusCode(404);
            return false;
        }

        public function updateAction()
        {
            $entity_manager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            if(!$this->request->isPost()) {
                return $this->redirect()->toRoute('admin/default', array(
                    'controller' => 'article',
                ));
            }

            $post = $this->request->getPost();
            $form = new ArticleForm(null, array('entity_manager' => $entity_manager));
            $input_filter = new ArticleFilter($entity_manager);
            $form->setInputFilter($input_filter);
            $form->setData($post);

            if(!$form->isValid()) {
                $view_model = new ViewModel(array(
                    'error' => true,
                    'form' => $form,
                    'action' => 'update',
                ));
                $view_model->setTemplate('admin/article/article_form');

                return $view_model;
            }

            $article_manager = new ArticleManager($entity_manager);
            $article_manager->update($form->getData());

            return $this->redirect()->toRoute('admin/default', array(
                'controller' => 'article',
            ));
        }
    }