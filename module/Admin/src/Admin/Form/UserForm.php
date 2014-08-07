<?php
    namespace Admin\Form;

    use Zend\Form\Form;

    class UserForm extends Form
    {
        private $entity_manager;

        public function __construct($name = null, $options)
        {
            $this->entity_manager = $options['entity_manager'];

            parent::__construct('User');

            $this->setAttribute('method', 'post');
            $this->setAttribute('data-parsley-validate', '');

            $this->add(array(
                'name' => 'id',
                'type' => 'Zend\Form\Element\Hidden',
            ));

            $this->add(array(
                'name' => 'page',
                'type' => 'Zend\Form\Element\Hidden',
            ));

            $this->add(array(
                'name' => 'name',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' => 'User name',
                ),
                'attributes' => array(
                    'class' => 'form-control',
                    'maxlength' => 255,
                    'id' => 'name',
                    'required' => 'required',
                    'data-parsley-length' => "[3, 255]",
                    'data-parsley-error-message' => '3 chars min, 255 max',
                    'data-parsley=group' => 'name',
                ),
            ));

            $this->add(array(
                'name' => 'isActive',
                'type' => 'Zend\Form\Element\Checkbox',
                'options' => array(
                    'label' => 'Acitve',
                    'checked_value' => true,
                    'unchecked_value' => null,
                ),
                'attributes' => array(
                    'id' => 'isActive',
                ),
            ));

            $this->add(array(
                'name' => 'submit',
                'type' => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'value' => 'Save',
                    'class' => 'btn btn-primary with-offset save-button',
                    'id' => 'submit',
                ),
            ));
        }
    }