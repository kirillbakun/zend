<?php
    namespace Admin\Controller;

    use Admin\Form\ArticleFilter;
    use Admin\Form\ArticleForm;
    use Admin\Manager\ArticleManager;
    use Admin\Manager\UserManager;
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
            $user_manager = new UserManager($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
            $users = $user_manager->getList();

            $form = new ArticleForm(null, array('users' => $users));
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
            $form = new ArticleForm();
            $input_filter = new ArticleFilter();
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

            return $this->redirect()->toRoute('admin/default', array(
                'controller' => 'article',
                'action' => 'index'
            ));
        }
    }