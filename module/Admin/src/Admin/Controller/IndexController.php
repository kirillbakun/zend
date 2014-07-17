<?php
    namespace Admin\Controller;

    use Admin\Manager\ArticleManager;
    use Zend\Mvc\Controller\AbstractActionController;
    use Zend\View\Model\ViewModel;

    class IndexController extends AbstractActionController
    {
        public function indexAction()
        {
            $entity_manager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $article_manager = new ArticleManager($entity_manager);
            $articles = $article_manager->getList();

            return new ViewModel(array('articles' => $articles));
        }
    }