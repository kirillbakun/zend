<?php
    namespace Admin\Controller;

    use Zend\Mvc\Controller\AbstractActionController;
    use Zend\View\Model\JsonModel;

    class AjaxController extends AbstractActionController
    {
        public function deleteDataAction()
        {
            if($this->request->isXmlHttpRequest()) {
                $table_name = $this->params()->fromPost('table_name');
                $manager_name = 'Admin\\Manager\\' .ucfirst($table_name) .'Manager';
                $id = $this->params()->fromPost('id');

                if(class_exists($manager_name) && $id) {
                    /**
                     * @var \Admin\Manager\AbstractManager $manager;
                     */

                    $entity_manager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                    $manager = new $manager_name($entity_manager);

                    if($manager->deleteOneById($id)) {
                        return new JsonModel(array(
                            'success' => true,
                        ));
                    }
                }
            }

            return new JsonModel(array(
                'success' => false,
            ));
        }
    }