<?php
    namespace Admin\Controller;

    use Admin\Manager\EntityManager;
    use Zend\Mvc\Controller\AbstractActionController;
    use Zend\View\Model\ViewModel;

    class IndexController extends AbstractActionController
    {
        public function indexAction()
        {
            $entity_manager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            $entities_list_manager = new EntityManager($entity_manager);
            $entities_list = $entities_list_manager->getActiveList();

            return new ViewModel(array(
                'entities' => $entities_list,
            ));
        }
    }