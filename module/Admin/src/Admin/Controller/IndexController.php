<?php
    namespace Admin\Controller;

    use Admin\Helper\EntityFieldsHelper;
    use Zend\Mvc\Controller\AbstractActionController;
    use Zend\View\Model\ViewModel;

    class IndexController extends AbstractActionController
    {
        public function indexAction()
        {
            $entities_list = EntityFieldsHelper::getEntitiesList();

            return new ViewModel(array(
                'entities' => $entities_list,
            ));
        }
    }