<?php
App::uses('AppModel', 'Model');

/**
 * User Model
 *
 */
class User extends AppModel
{

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'username';
    public $primaryKey = 'user_id';
    public $useTable = 'phpbb_users';
    /**
     * hasAndBelongsToMany associations
     *
     * @var array
     */
    public $hasAndBelongsToMany = array(
        'Permission' => array(
            'className' => 'SitePermission',
            'joinTable' => 'permissions_users',
            'foreignKey' => 'user_id',
            'associationForeignKey' => 'permission_id',
            'unique' => 'keepExisting',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        )
    );

    public function CheckUserPermission($userId, $permissionIds)
    {
        if (!$userId || !$permissionIds) {
            return false;
        }
        if (!is_array($permissionIds)) {
            $permissionIds = array($permissionIds);
        }
        $permissions = implode(',', $permissionIds);
        $count = $this->query("SELECT count(*) AS Count FROM permissions_users where user_id = $userId AND permission_id IN ($permissions);");
        return $count[0][0]['Count'] > 0;
    }

    public function CheckUserSupporterStatus($userId)
    {
        if (!$userId) {
            return false;
        }

        $date = date('Y-m-d H:i:s');
        $sql = <<<EOQ
SELECT
    count(user_id) AS Count
FROM
    supporters
WHERE
    user_id = $userId
    AND expires_on > '$date'
EOQ;
        $count = $this->query($sql);
        return $count[0][0]['Count'] > 0;
    }

    public function listUserGroups($userId)
    {
        $userId = (int)$userId;

        $sql = <<<EOQ
SELECT
    User.user_id,
    UserGroup.group_id,
    1 as is_member,
    UserGroup.group_leader
FROM
    phpbb_users AS User
    LEFT JOIN phpbb_user_group AS UserGroup ON User.user_id = UserGroup.user_id
WHERE
    User.user_id = $userId
EOQ;

        return $this->query($sql);
    }

    public function saveUserGroups($data)
    {
        foreach ($data['group_id'] as $row => $groupId) {
            if ($data['is_member'][$groupId]) {
                // set member data
                $this->addUserGroupRole($data['user_id'], $groupId, $data['group_leader'][$groupId]);
            } else {
                // attempt to delete row
                $this->deleteUserGroup($data['user_id'], $groupId);
            }
        }
        $this->updateUserAclPermissions($data['user_id']);
        return true;
    }

    private function addUserGroupRole($userId, $groupId, $isGroupLeader)
    {
        $userId = (int)$userId;
        $groupId = (int)$groupId;
        $isGroupLeader = (int)$isGroupLeader;

        $sql = <<<EOQ
SELECT
    count(*) as access_rows
FROM
    phpbb_user_group
WHERE
    user_id = $userId
    AND group_id = $groupId;
EOQ;

        $result = $this->query($sql);
        if ($result[0][0]['access_rows'] > 0) {
            $sql = <<<EOQ
UPDATE
    phpbb_user_group
SET
    group_leader = $isGroupLeader
WHERE
    group_id = $groupId
    AND user_id = $userId;
EOQ;

        } else {
            $sql = <<<EOQ
INSERT INTO
    phpbb_user_group
    (group_id, user_id, group_leader, user_pending)
VALUES
  ($groupId, $userId, $isGroupLeader, 0);
EOQ;

        }

        $this->query($sql);
        return true;
    }

    private function deleteUserGroup($userId, $groupId)
    {
        $userId = (int)$userId;
        $groupId = (int)$groupId;

        $sql = <<<EOQ
DELETE FROM
  phpbb_user_group
WHERE
  user_id = $userId
  AND group_id = $groupId
EOQ;

        $this->query($sql);
        return true;
    }

    private function updateUserAclPermissions($userId)
    {
        $sql = <<<EOQ
UPDATE
  phpbb_users
SET
  user_permissions = '',
  user_perm_from = 0
WHERE
  user_id = $userId
EOQ;
        $this->query($sql);
    }

    public function addUserToSite($user)
    {
        $return = true;
        // find the registered user group
        $sql = <<<EOQ
SELECT
    G.group_id
FROM
    phpbb_groups AS G
WHERE
    group_name = 'REGISTERED USERS'
EOQ;

        $data = $this->query($sql);
        $groupId = $data[0]['G']['group_id'];

        // find the what?
        // add user to site as a basic registered user
        $sql = <<<EOQ
INSERT INTO
    phpbb_users
    ()
EOQ;
        // add them to the registered user group
        $params = [
            $user['user_id'],
            $groupId
        ];
        // return happy state
        return $return;
    }
}
