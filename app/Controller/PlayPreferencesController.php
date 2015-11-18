<?php
App::uses('AppController', 'Controller');

/**
 * PlayPreferences Controller
 *
 * @property PlayPreference $PlayPreference
 * @property PaginatorComponent $Paginator
 * @property PermissionsComponent Permissions
 * @property MenuComponent Menu
 */
class PlayPreferencesController extends AppController
{
    public function beforeFilter()
    {
        parent::beforeFilter(); // TODO: Change the autogenerated stub
    }

    public function beforeRender()
    {
        parent::beforeRender();
        $this->set('isHead', $this->Permissions->IsHead());
    }

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $preferences = $this->PlayPreference->PlayPreferenceResponse->listByUserId($this->Auth->user('user_id'));
        $this->set(compact('preferences'));
    }

    public function respond()
    {
        if ($this->request->is('post')) {
            if($this->PlayPreference->PlayPreferenceResponse->updateUserResponse(
                $this->Auth->user('user_id'),
                $this->request->data)) {
                $this->Session->setFlash('Updated Your Play Preferences');
                $this->redirect(['action' => 'index']);
            } else {
                $this->Session->setFlash('Error Updating Play Preferences');
            }
        }
        $userPreferences = $this->PlayPreference->PlayPreferenceResponse->listByUserId($this->Auth->user('user_id'));
        $userPrefs = [];
        foreach($userPreferences as $userPreference) {
            $userPrefs[$userPreference['PlayPreferenceResponse']['play_preference_id']] =
                $userPreference['PlayPreferenceResponse']['rating'];
        }
        $preferences = $this->PlayPreference->find(
            'all',
            [
                'order' => [
                    'name'
                ],
                'contain' => false
            ]
        );
        $this->PlayPreference->recursive = 0;
        $this->set(compact('preferences', 'userPrefs'));
    }

    public function manage()
    {
        $this->PlayPreference->recursive = 0;
        $this->set('playPreferences', $this->Paginator->paginate());
    }

    public function report_aggregate()
    {
        $this->set(
            'report',
            $this->PlayPreference->getAggregateReport()
        );
        $this->set(
            'submenu',
            $this->Menu->createStorytellerMenu()
        );
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null)
    {
        if (!$this->PlayPreference->exists($id)) {
            throw new NotFoundException(__('Invalid play preference'));
        }
        $options = array(
            'conditions' => array(
                'PlayPreference.' . $this->PlayPreference->primaryKey => $id
            ),
            'contain' => array(
                'CreatedBy' => array(
                    'username'
                ),
                'UpdatedBy' => array(
                    'username'
                )
            )
        );
        $this->set('playPreference', $this->PlayPreference->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $this->PlayPreference->create();
            $data = $this->request->data;
            $data['PlayPreference']['created_by_id'] = $this->Auth->user('user_id');
            $data['PlayPreference']['updated_by_id'] = $this->Auth->user('user_id');
            $data['PlayPreference']['updated_on'] = date('Y-m-d H:i:s');
            $data['PlayPreference']['created_on'] = date('Y-m-d H:i:s');

            if ($this->PlayPreference->save($data)) {
                $this->Session->setFlash(__('The play preference has been saved.'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The play preference could not be saved. Please, try again.'));
            }
        }
        $createdBies = $this->PlayPreference->CreatedBy->find('list');
        $updatedBies = $this->PlayPreference->UpdatedBy->find('list');
        $this->set(compact('createdBies', 'updatedBies'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null)
    {
        if (!$this->PlayPreference->exists($id)) {
            throw new NotFoundException(__('Invalid play preference'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $data = $this->request->data;
            $data['PlayPreference']['updated_by_id'] = $this->Auth->user('user_id');
            $data['PlayPreference']['updated_on'] = date('Y-m-d H:i:s');
            if ($this->PlayPreference->save($data)) {
                $this->Session->setFlash(__('The play preference has been saved.'));
                $this->redirect(array('action' => 'manage'));
            } else {
                $this->Session->setFlash(__('The play preference could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('PlayPreference.' . $this->PlayPreference->primaryKey => $id));
            $this->request->data = $this->PlayPreference->find('first', $options);
        }
        $createdBies = $this->PlayPreference->CreatedBy->find('list');
        $updatedBies = $this->PlayPreference->UpdatedBy->find('list');
        $this->set(compact('createdBies', 'updatedBies'));
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null)
    {
        $this->PlayPreference->id = $id;
        if (!$this->PlayPreference->exists()) {
            throw new NotFoundException(__('Invalid play preference'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->PlayPreference->delete()) {
            $this->Session->setFlash(__('The play preference has been deleted.'));
        } else {
            $this->Session->setFlash(__('The play preference could not be deleted. Please, try again.'));
        }
        $this->redirect(array('action' => 'index'));
    }

    public function isAuthorized($user)
    {
        switch($this->request->params['action'])
        {
            case 'index':
            case 'respond':
                return $this->Auth->loggedIn();
                break;
            default:
                return $this->Permissions->IsST();
        }

    }
}
