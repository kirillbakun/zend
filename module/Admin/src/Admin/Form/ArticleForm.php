<?php
    namespace Admin\Form;

    use Zend\Form\Form;

    class ArticleForm extends Form
    {
        public function __construct($name = null)
        {
            parent::__construct('Article');
            $this->setAttribute('method', 'post');

            $this->add(array(
                'name' => 'text',
                'attributes' => array(
                    'type' => 'text',
                ),
                'options' => array(
                    'label' => 'Article text',
                ),
            ));

            $this->add(array(
                'name' => 'submit',
                'attributes' => array(
                    'type' => 'submit',
                    'value' => 'Save',
                ),
            ));
        }
    }