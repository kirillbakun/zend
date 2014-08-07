<?php
    namespace Admin\Form;

    use Doctrine\ORM\EntityManager;
    use Zend\InputFilter\InputFilter;

    class UserFilter extends InputFilter
    {
        private $entity_manager;

        public function __construct(EntityManager $entity_manager)
        {
            $this->entity_manager = $entity_manager;

            $this->add(array(
                'name' => 'name',
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
                            'min' => 3,
                            'max' => 255,
                        ),
                    ),
                ),
            ));
        }
    }