<?php
    namespace Admin\Controller;

    use Admin\Manager\ArticleManager;
    use Zend\Mvc\Controller\AbstractActionController;
    use Zend\View\Model\ViewModel;

    class IndexController extends AbstractActionController
    {
        public function indexAction()
        {
            return new ViewModel();
        }
    }