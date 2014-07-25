<?php
    namespace Admin\Controller;

    use Admin\Manager\EntityManager;
    use Admin\Manager\FieldsListManager;
    use Zend\Mvc\Controller\AbstractActionController;
    use Zend\View\Model\ViewModel;

    class CrudController extends AbstractActionController
    {
        public function indexAction()
        {
            $entity_manager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $table_name = $this->params()->fromRoute('table');
            $entity_name = 'Admin\\Manager\\' .ucfirst($table_name) .'Manager';
            $list_manager = new EntityManager($entity_manager);
            $current_entity = $list_manager->getOneActiveByTable($table_name);

            if(!class_exists($entity_name) && $current_entity) {
                return $this->redirect()->toRoute('admin/default', array(
                    'controller' => 'index',
                ));
            }

            $list = $list_manager->getActiveList();

            $manager = new $entity_name($entity_manager);
            $entities = $manager->getList();

            $fields_list_manager = new FieldsListManager($entity_manager);
            $fields_list = $fields_list_manager->getListByEntityId($current_entity->id);

            $current_page = $this->params()->fromQuery('afewfw');

            $view_model = new ViewModel(array(
                'entities' => $entities,
                'current_entity' => $current_entity,
                'list' => $list,
                'fields_list' => $fields_list,
                'current_page' => ($current_page) ? $current_page : 1,
            ));
            $view_model->setTemplate('admin/crud/index');

            return $view_model;
        }

        public function addAction()
        {
            $entity_manager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $table_name = $this->params()->fromRoute('table');
            $form_name = 'Admin\\Form\\' .ucfirst($table_name) .'Form';

            if(!class_exists($form_name)) {
                return $this->redirect()->toRoute('admin/default', array(
                    'controller' => 'index',
                ));
            }

            $form = new $form_name(null, array('entity_manager' => $entity_manager));
            $form->setData(array('isActive'=> true));

            $view_model = new ViewModel(array(
                'form' => $form,
                'action' => 'insert',
            ));
            $view_model->setTemplate('admin/' .$table_name .'/' .$table_name .'_form');

            return $view_model;
        }

        public function insertAction()
        {
            $entity_manager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $table_name = $this->params()->fromRoute('table');
            $form_name = 'Admin\\Form\\' .ucfirst($table_name) .'Form';
            $filter_name = 'Admin\\Form\\' .ucfirst($table_name) .'Filter';
            $manager_name = 'Admin\\Manager\\' .ucfirst($table_name) .'Manager';

            if(!$this->request->isPost() || !class_exists($form_name) || !class_exists($filter_name) || !class_exists($manager_name)) {
                return $this->redirect()->toRoute('admin/default', array(
                    'controller' => 'index',
                ));
            }

            $post = $this->request->getPost();
            $form = new $form_name(null, array('entity_manager' => $entity_manager));
            $input_filter = new $filter_name($entity_manager);
            $form->setInputFilter($input_filter);
            $form->setData($post);

            if(!$form->isValid()) {
                $view_model = new ViewModel(array(
                    'error' => true,
                    'form' => $form,
                    'action' => 'insert',
                ));
                $view_model->setTemplate('admin/' .$table_name .'/' .$table_name .'_form');

                return $view_model;
            }

            $manager = new $manager_name($entity_manager);
            $manager->insert($form->getData());

            return $this->redirect()->toRoute('admin/default', array(
                'controller' => 'crud',
                'table' => $table_name,
            ));
        }

        public function editAction()
        {
            $entity_manager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $entity_id = $this->params()->fromRoute('id');
            $table_name = $this->params()->fromRoute('table');
            $form_name = 'Admin\\Form\\' .ucfirst($table_name) .'Form';
            $filter_name = 'Admin\\Form\\' .ucfirst($table_name) .'Filter';
            $manager_name = 'Admin\\Manager\\' .ucfirst($table_name) .'Manager';

            if(!class_exists($form_name) || !class_exists($filter_name) || !class_exists($manager_name) || !((int)$entity_id)) {
                return $this->redirect()->toRoute('admin/default', array(
                    'controller' => 'index',
                ));
            }

            $manager = new $manager_name($entity_manager);
            $entity = $manager->getOneById($entity_id);

            if($entity) {
                $form = new $form_name(null, array('entity_manager' => $entity_manager));
                $input_filter = new $filter_name($entity_manager);
                $data = $manager->populateArray($entity);
                $form->setInputFilter($input_filter);
                $form->setData($data);

                $view_model = new ViewModel(array(
                    'form' => $form,
                    'action' => 'update',
                ));
                $view_model->setTemplate('admin/article/article_form');

                return $view_model;
            }

            return $this->redirect()->toRoute('admin/default', array(
                'controller' => 'crud',
                'table' => $table_name,
            ));
        }

        public function updateAction()
        {
            $entity_manager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $table_name = $this->params()->fromRoute('table');
            $form_name = 'Admin\\Form\\' .ucfirst($table_name) .'Form';
            $filter_name = 'Admin\\Form\\' .ucfirst($table_name) .'Filter';
            $manager_name = 'Admin\\Manager\\' .ucfirst($table_name) .'Manager';

            if(!$this->request->isPost() || !class_exists($form_name) || !class_exists($filter_name) || !class_exists($manager_name)) {
                return $this->redirect()->toRoute('admin/default', array(
                    'controller' => 'index',
                ));
            }

            $post = $this->request->getPost();
            $form = new $form_name(null, array('entity_manager' => $entity_manager));
            $input_filter = new $filter_name($entity_manager);
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

            $manager = new $manager_name($entity_manager);
            $manager->update($form->getData());

            return $this->redirect()->toRoute('admin/default', array(
                'controller' => 'crud',
                'table' => $table_name,
            ));
        }

        public function deleteAction()
        {
            $entity_manager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $entity_id = $this->params()->fromRoute('id');
            $table_name = $this->params()->fromRoute('table');
            $manager_name = 'Admin\\Manager\\' .ucfirst($table_name) .'Manager';

            if(!class_exists($manager_name) || !$entity_id) {
                return $this->redirect()->toRoute('admin/default', array(
                    'controller' => 'index',
                ));
            }

            $manager = new $manager_name($entity_manager);
            $manager->deleteOneById($entity_id);

            return $this->redirect()->toRoute('admin/default', array(
                'controller' => 'crud',
                'table' => $table_name,
            ));
        }
    }