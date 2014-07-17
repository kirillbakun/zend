<?php
    namespace Admin\Controller;
    use Zend\Mvc\Controller\AbstractActionController;
    use Zend\View\Model\ViewModel;

    class IndexController extends AbstractActionController
    {
        public function indexAction()
        {
            $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
            $qb = $em->createQueryBuilder();
            $qb->add('select', 'a')
                ->add('from', 'Admin\Entity\Article a')
                ->add('where', 'a.id = :identifier')
                ->setParameter('identifier', 1);
            $query = $qb->getQuery();
            $articles = $query->getResult();


            //$articles = $em->getRepository('\Admin\Entity\Article')->findAll();


            return new ViewModel(array('articles' => $articles));
        }
    }