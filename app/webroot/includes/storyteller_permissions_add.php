<?php
/* @var array $userdata */

use classes\core\data\Permission;
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\core\helpers\UserdataHelper;
use classes\core\repository\Database;
use classes\core\repository\PermissionRepository;
use classes\request\repository\GroupRepository;

$page_title = "Add ST";
$contentHeader = $page_title;

$page = "";
$page_content = "";
$alert = "";
$js = "";
$show_form = true;
$mode = "debug";

// form variables
$login_name = "";
$login_id = 0;
$userId = 0;
$userPermissions = array();
$selectedGroups = array();

// test if submitting values
$permissionRepository = new PermissionRepository();
$groupsRepository = new GroupRepository();

if (Request::IsPost() && UserdataHelper::IsHead($userdata)) {
    $userId = Request::GetValue('user_id');
    $selectedGroups = $_POST['groups'];
    $userPermissions = $_POST['permissions'];
    if (!$userId) {
        SessionHelper::SetFlashMessage('No User Indicated');
    } else {
        $permissionRepository->SavePermissionsForUser($userId, $userPermissions);
        $groupsRepository->SaveGroupsForUser($userId, $selectedGroups);
        Response::Redirect('/storyteller_index.php?action=permissions', 'Set Permissions for ' . Request::GetValue('login_name'));
    }
}

$groups = $groupsRepository->SimpleListAll();
$permissions = $permissionRepository->SimpleListAll();

ob_start();
?>
    <form id="permission-form" method="post"
          action="<?php echo $_SERVER['PHP_SELF']; ?>?action=permissions_add">
        <table class="normal_text">
            <tr>
                <td style="width: 100px;">
                    Login Name:
                </td>
                <td>
                    <?php echo FormHelper::Text('login_name', $login_name); ?>
                    <?php echo FormHelper::Hidden('user_id', $userId); ?>
                </td>
            </tr>
            <tr>
                <td>
                    Groups:
                </td>
                <td>
                    <?php echo FormHelper::Multiselect($groups, 'groups[]', $selectedGroups); ?>
                </td>
            </tr>
            <tr>
                <td>
                    Permissions
                </td>
                <td>
                    <?php echo FormHelper::CheckboxList('permissions[]',
                        $permissions,
                        $userPermissions);
                    ?>
                </td>
            </tr>
        </table>
        <div style="text-align: center;">
            <?php echo FormHelper::Button('action', 'Submit', 'submit'); ?>
        </div>
    </form>
    <script>
        $(function () {
            $(function () {
                $("#login-name").autocomplete({
                    source: '/users.php?action=search&email=0',
                    minLength: 2,
                    autoFocus: true,
                    focus: function () {
                        return false;
                    },
                    select: function (e, ui) {
                        $("#user-id").val(ui.item.value);
                        console.debug(ui);
                        $("#login-name").val(ui.item.label);
                        return false;
                    }
                });
                $('#permission-form').submit(function (e) {
                    var userId = parseInt($("#user-id").val());

                    if (isNaN(userId) || (userId == 0)) {
                        alert('Please type a user name');
                        e.preventDefault();
                        return false;
                    }
                    return true;
                });
            });
        });
    </script>
<?php
$page_content .= ob_get_clean();