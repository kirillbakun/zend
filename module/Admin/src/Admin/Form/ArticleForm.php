<?php
    namespace Admin\Form;

    use Admin\Helper\SelectHelper;
    use Zend\Form\Form;

    class ArticleForm extends Form
    {
        protected $entity_manager;

        public function __construct($name = null, $options)
        {
            $this->entity_manager = $options['entity_manager'];

            parent::__construct('Article');
            $this->setAttribute('method', 'post');

            $this->add(array(
                'name' => 'id',
                'type' => 'Zend\Form\Element\Hidden',
            ));

            $this->add(array(
                'name' => 'text',
                'type' => 'Zend\Form\Element\TextArea',
                'options' => array(
                    'label' => 'Article text',
                ),
                'attributes' => array(
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
            ));

            $this->add(array(
                'name' => 'userId',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => 'User',
                    'value_options' => SelectHelper::getUsersData($this->entity_manager),
                    'disable_inarray_validator' => true,
                ),
                'attributes' => array(
                    'class' => 'form-control',
                ),
            ));

            $this->add(array(
                'name' => 'isActive',
                'type' => 'Zend\Form\Element\Checkbox',
                'options' => array(
                    'label' => 'Published',
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
                    'class' => 'btn btn-primary with-offset save-button'
                ),
            ));
        }
    }