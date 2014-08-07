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
            $current_page = $this->params()->fromQuery('page');
            $current_page = ((int)$current_page) ? (int)$current_page : 1;

            if(!class_exists($entity_name) && $current_entity) {
                return $this->redirect()->toRoute('admin/default');
            }

            $list = $list_manager->getActiveList();

            $manager = new $entity_name($entity_manager);
            $per_page = $manager->getPerPage();
            $entities_total_count = $manager->getCount();

            if(($entities_total_count/$per_page) <= $current_page - 1) {
                $current_page--;
            }

            $entities = $manager->getListByPageNumber($current_page, array('id' => 'DESC'));

            $fields_list_manager = new FieldsListManager($entity_manager);
            $fields_list = $fields_list_manager->getListByEntityId($current_entity->id);

            $view_model = new ViewModel(array(
                'entities' => $entities,
                'current_entity' => $current_entity,
                'list' => $list,
                'fields_list' => $fields_list,
                'current_page' => $current_page,
                'per_page' => $per_page,
                'total_count' => $entities_total_count,
            ));
            $view_model->setTemplate('admin/crud/index');

            return $view_model;
        }

        public function addAction()
        {
            $entity_manager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $table_name = $this->params()->fromRoute('table');
            $form_name = 'Admin\\Form\\' .ucfirst($table_name) .'Form';
            $page = $this->params()->fromQuery('page');
            $page = ((int)$page) ? (int)$page : 1;

            if(!class_exists($form_name)) {
                return $this->redirect()->toRoute('admin/default');
            }

            $form = new $form_name(null, array('entity_manager' => $entity_manager));
            $form->setData(array(
                'isActive'=> true,
                'page' => $page,
            ));

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
            ), array(
                'query' => array(
                    'page' => $post['page'],
                ),
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
            $page = $this->params()->fromQuery('page');
            $page = ((int)$page) ? (int)$page : 1;

            if(!class_exists($form_name) || !class_exists($filter_name) || !class_exists($manager_name) || !((int)$entity_id)) {
                return $this->redirect()->toRoute('admin/default');
            }

            $manager = new $manager_name($entity_manager);
            $entity = $manager->getOneById($entity_id);

            if($entity) {
                $form = new $form_name(null, array('entity_manager' => $entity_manager));
                $input_filter = new $filter_name($entity_manager);
                $data = $manager->populateArray($entity);
                $data['page'] = $page;
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
                return $this->redirect()->toRoute('admin/default');
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
            ), array(
                'query' => array(
                    'page' => $post['page'],
                ),
            ));
        }

        public function deleteAction()
        {
            $entity_manager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $entity_id = $this->params()->fromRoute('id');
            $table_name = $this->params()->fromRoute('table');
            $manager_name = 'Admin\\Manager\\' .ucfirst($table_name) .'Manager';
            $page = $this->params()->fromQuery('page');
            $page = ((int)$page) ? (int)$page : 1;

            if(!class_exists($manager_name) || !$entity_id) {
                return $this->redirect()->toRoute('admin/default');
            }

            $manager = new $manager_name($entity_manager);
            $manager->deleteOneById($entity_id);

            return $this->redirect()->toRoute('admin/default', array(
                'controller' => 'crud',
                'table' => $table_name,
            ), array(
                'query' => array(
                    'page' => $page,
                ),
            ));
        }
    }