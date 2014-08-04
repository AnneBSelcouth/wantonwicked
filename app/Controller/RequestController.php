<?php

/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 7/26/14
 * Time: 9:33 AM
 * @property mixed Permissions
 */

class RequestController extends AppController
{
    public $components = array(
        'Permissions'
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function admin_index()
    {
        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $storytellerMenu['Actions'] = array(
            'link' => '#',
            'submenu' => array(
                'List' => array(
                    'link' => array(
                        'action' => 'index'
                    )
                ),
                'Edit' => array(
                    'link' => array(
                        'action' => 'edit',
                        $id
                    )
                ),
                'New Template' => array(
                    'link' => array(
                        'action' => 'add'
                    )
                ),
            )
        );
        $this->set('submenu', $storytellerMenu);
    }

    public function isAuthorized($user)
    {
        return $this->Permissions->isAdmin();
    }
}