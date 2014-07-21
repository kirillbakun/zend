<?php
    namespace Admin\Form;

    use Zend\Form\Form;

    class ArticleForm extends Form
    {
        public function __construct($name = null, $options = array())
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

            $user_options = array();
            foreach($options['users'] as $user) {
                $user_options[$user->id] = $user->name;
            }
            $this->add(array(
                'name' => 'user_id',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'User',
                    'value_options' => $user_options,
                    'disable_inarray_validator' => true,
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