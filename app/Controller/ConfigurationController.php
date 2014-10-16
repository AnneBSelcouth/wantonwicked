<?php

/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 5/20/14
 * Time: 8:21 PM
 * @property Configuration Configuration
 * @property PermissionsComponent Permissions
 */

class ConfigurationController extends AppController {
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->deny();
    }

    public function index() {
        $this->set('configs', $this->Configuration->find('all'));
    }

    public function read($configName)
    {
        App::uses('Configuration', 'Model');
        $config           = new Configuration();
        $configValue = $config->find(
            'first',
            array(
                'conditions' => array(
                    'key' => $configName
                ),
                'fields' => array(
                    'value'
                )
            )
        );

        $this->set(compact('configValue'));

        $this->set('_serialize', array('configValue'));
    }

    public function edit() {
        if($this->request->is('post')) {
            // try to save
            if($this->Configuration->saveAll($this->request->data)) {
                $this->Session->setFlash('Updated Configuration');
                $this->redirect(array('action' => 'index'));
            }
            else {
                $this->Session->setFlash('Error Saving');
            }
        }
        $this->set('configs', $this->Configuration->find('all'));
    }

    public function isAuthorized($user = null)
    {
        switch ($this->request->params['action']) {
            case 'index':
            case 'edit':
                return $this->Permissions->IsAdmin();
                break;
            case 'read':
                return true;
                break;
        }
        return false;
    }
}