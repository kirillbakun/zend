<?php
    namespace Admin\Helper;

    use Admin\Manager\UserManager;
    use Doctrine\ORM\EntityManager;

    class SelectHelper
    {
        public static function getUsersData(EntityManager $entity_manager)
        {
            /**
             * @var \Admin\Entity\User $user
             */

            $user_manager = new UserManager($entity_manager);
            $users = $user_manager->getActiveList();

            $select_array = array('0' => 'User not specified');
            foreach($users as $user) {
                $select_array[$user->id] = $user->name;
            }

            return $select_array;
        }

        public static function getUsersFilter(EntityManager $entity_manager)
        {
            /**
             * @var \Admin\Entity\User $user
             */

            $user_manager = new UserManager($entity_manager);
            $users = $user_manager->getActiveList();

            $select_array = array(0);
            foreach($users as $user) {
                $select_array[] = $user->id;
            }

            return $select_array;
        }
    }