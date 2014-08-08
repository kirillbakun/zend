<?php
    namespace Admin\Form;

    use Admin\Helper\RelatedEntityHelper;
    use Admin\Manager\UserManager;
    use Doctrine\ORM\EntityManager;
    use Zend\InputFilter\InputFilter;

    class ArticleFilter extends InputFilter
    {
        private $entity_manager;

        public function __construct(EntityManager $entity_manager)
        {
            $this->entity_manager = $entity_manager;

            $this->add(array(
                'name' => 'text',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags',
                    ),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 5,
                            'max' => 255,
                        ),
                    ),
                ),
            ));

            $this->add(array(
                'name' => 'userId',
                'validators' => array(
                    array(
                        'name' => 'InArray',
                        'options' => array(
                            'haystack' => RelatedEntityHelper::createInputFilterDataArray($this->getUsers()),
                            'messages' => array(
                                'notInArray' => 'Select right user',
                            ),
                        ),
                    ),
                ),
            ));
        }

        public function getUsers()
        {
            $user_manager = new UserManager($this->entity_manager);

            return $user_manager->getActiveList();
        }
    }