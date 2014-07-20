<?php
    namespace Admin\Controller;

    use Admin\Form\ArticleForm;
    use Zend\Mvc\Controller\AbstractActionController;
    use Zend\View\Model\ViewModel;

    class ArticleController extends AbstractActionController
    {
        public function indexAction()
        {
            return new ViewModel();
        }

        public function addAction()
        {
            $form = new ArticleForm();
            $view_model = new ViewModel(array('form' => $form));

            return $view_model;
        }
    }
?>