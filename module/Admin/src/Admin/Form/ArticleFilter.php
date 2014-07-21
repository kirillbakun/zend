<?php
    namespace Admin\Form;

    use Zend\InputFilter\InputFilter;

    class ArticleFilter extends InputFilter
    {
        public function __construct()
        {
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
                'name' => 'user_id',
                'validators' => array(
                    array(
                        'name' => 'InArray',
                        'options' => array(
                            'haystack' => array(6),
                            'messages' => array(
                                'notInArray' => 'Select right user',
                            ),
                        ),
                    ),
                ),
            ));
        }
    }