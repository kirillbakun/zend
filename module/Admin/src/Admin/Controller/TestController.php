<?php
    namespace Admin\Controller;

    use Admin\Helper\MemcacheHelper;
    use Zend\Mvc\Controller\AbstractActionController;
    use Zend\View\Model\ViewModel;

    class TestController extends AbstractActionController
    {
        public function clearCacheAction()
        {
            MemcacheHelper::flush();

            $view_model = new ViewModel(array(
                'message' => 'Cache has been cleared',
            ));
            $view_model->setTemplate('admin/test/index');

            return $view_model;
        }
    }