<?php
/* @var array $userdata */

use classes\character\data\Character;
use classes\character\repository\CharacterRepository;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\core\helpers\UserdataHelper;
use classes\core\repository\RepositoryManager;
use classes\log\CharacterLog;
use classes\log\data\ActionType;
use classes\request\data\RequestStatus;
use classes\request\repository\RequestRepository;

$characterId = Request::GetValue('character_id', 0);

$character_type = "";

$characterRepository = RepositoryManager::GetRepository('classes\character\data\Character');
/* @var CharacterRepository $characterRepository */
$character = $characterRepository->GetById($characterId);
/* @var Character $character */
if($character->Id == 0) {
    SessionHelper::SetFlashMessage("Invalid Character");
    Response::Redirect('chat.php');
}

if($character->IsNpc == 'Y') {
    if(!UserdataHelper::IsSt($userdata)) {
        CharacterLog::LogAction($characterId, ActionType::InvalidAccess, 'Attempted access to character interface', $userdata['user_id']);
        SessionHelper::SetFlashMessage("You're not authorized to view that character.");
        Response::Redirect('');
    }
}
else {
    if($character->UserId != $userdata['user_id']) {
        CharacterLog::LogAction($characterId, ActionType::InvalidAccess, 'Attempted access to character interface', $userdata['user_id']);
        SessionHelper::SetFlashMessage("You're not authorized to view that character.");
        Response::Redirect('');
    }
}


//CharacterLog::LogAction($character->CharacterId, ActionType::Login, '', $userdata['user_id']);
// found a character
$page_title = "Interface for $character->CharacterName";
$contentHeader = $character->CharacterName;
$character_type = $character->CharacterType;



// set up user information
$extraLinks = "";
switch ($character->CharacterType) {
    case 'Mortal':
        $morality = "Morality";
        break;
    case 'Vampire':
        $morality = "Humanity";

        $abpRating = $character->AveragePowerPoints;

        $extraLinks = <<<EOQ
<div style="margin-bottom:10px;text-align:center;">
    <a href="/territory.php?action=list_territories&character_id=$characterId" target="_blank">View Tenancy for Character</a>
</div>
EOQ;

        $extra_rows = <<<EOQ
<tr>
    <td>
        Coterie
    </td>
    <td>
        $character->Friends
    </td>
</tr>
<tr>
    <td>
        Vitae
    </td>
    <td>
        $character->PowerPoints
    </td>
</tr>
<tr>
    <td>
        ABP
    </td>
    <td>
        $abpRating
        <a href="abp.php?action=show_modifiers&character_id=$characterId" target="_blank">Details</a>
    </td>
</tr>
EOQ;
        break;
    case 'Ghoul':
        $morality = "Morality";
        $extra_rows = <<<EOQ
<tr>
    <td>
        Domitor
    </td>
    <td>
        $character->Friends
    </td>
</tr>
<tr>
    <td>
        Vitae
    </td>
    <td>
        $character->PowerPoints
    </td>
</tr>
EOQ;
        break;
    case 'Werewolf':
        $morality = "Harmony";
        $extra_rows = <<<EOQ
<tr>
    <td>
        Pack
    </td>
    <td>
        $character->Friends
    </td>
</tr>
<tr>
    <td>
        Essence
    </td>
    <td>
        $character->PowerPoints
    </td>
</tr>
EOQ;
        break;
    case 'Mage':
        $morality = "Wisdom";

        $extra_rows = <<<EOQ
<tr>
    <td>
        Cabal
    </td>
    <td>
        $character->Friends
    </td>
</tr>
<tr>
    <td>
        Mana
    </td>
    <td>
        $character->PowerPoints
    </td>
</tr>
EOQ;
        break;

    case 'Thaumaturge':
        $morality = "Morality";
        break;

    default:
        $morality = "Morality";
        break;
}

$characterInfo = <<<EOQ
<table>
    <tr>
        <td colspan="2" align="center">
            <a href="/view_sheet.php?action=view_own_xp&character_id=$characterId" target="_blank">View Sheet</a>
        </td>
    </tr>
    <tr>
        <td>
            Virtue
        </td>
        <td>
            $character->Virtue
        </td>
    </tr>
    <tr>
        <td>
            Vice
        </td>
        <td>
            $character->Vice
        </td>
    </tr>
    <tr>
        <td>
            $morality
        </td>
        <td>
            $character->Morality
        </td>
    </tr>
    <tr>
        <td>
            Willpower
        </td>
        <td>
            $character->WillpowerTemp
        </td>
    </tr>
    <tr>
        <td>
            Initiative Mod
        </td>
        <td>
            $character->InitiativeMod
        </td>
    </tr>
    <tr>
        <td>
            Defense
        </td>
        <td>
            $character->Defense
        </td>
    </tr>
    <tr>
        <td>
            Armor
        </td>
        <td>
            $character->Armor
        </td>
    </tr>
    <tr>
        <td>
            Wounds
        </td>
        <td>
            A: $character->WoundsAgg
            L: $character->WoundsLethal
            B: $character->WoundsBashing
        </td>
    </tr>
    <tr>
        <td>
            Experience
        </td>
        <td>
            $character->CurrentExperience
        </td>
    </tr>
    $extra_rows
</table>
EOQ;

$requestRepository = new RequestRepository();
$newRequests = $requestRepository->CountRequestsByCharacterIdAndStatus($characterId, RequestStatus::NewRequest);
$stRequests = $requestRepository->CountRequestsByCharacterIdAndStatus($characterId, RequestStatus::Submitted);
$stViewedRequests = $requestRepository->CountRequestsByCharacterIdAndStatus($characterId, RequestStatus::InProgress);
$returnedRequests = $requestRepository->CountRequestsByCharacterIdAndStatus($characterId, RequestStatus::Returned);
$approvedRequests = $requestRepository->CountRequestsByCharacterIdAndStatus($characterId, RequestStatus::Approved);
$rejectedRequests = $requestRepository->CountRequestsByCharacterIdAndStatus($characterId, RequestStatus::Denied);

require_once('helpers/character_menu.php');
/* @var array $characterMenu */
$menu = MenuHelper::GenerateMenu($characterMenu);
ob_start();
?>
    <?php echo $menu; ?>
    <form id="character-login" method="get" action="/chat" target="_blank">
        <input type="hidden" name="character_id" value="<?php echo $characterId; ?>"/>
    </form>
    <div style="min-width:870px;width:100%;overflow:auto;">
        <div style="float:left;width:290px;min-height:300px;border:solid 0 #333333;">
            <div class="tableRowHeader" style="width:100%;">
                <div style="text-align: center;font-weight: bold;">
                    Character Information
                </div>
            </div>
            <?php echo $characterInfo; ?>
        </div>
        <div style="float:left;width:290px;min-height:300px;border:solid 0 #333333;">
            <div class="tableRowHeader" style="width:100%;">
                <div style="text-align: center;font-weight: bold;">
                    Requests
                </div>
                <table>
                    <tr>
                        <td>
                            New
                        </td>
                        <td>
                            <a href="/request.php?filter[title]=&filter[request_type_id]=0&filter[request_status_id]=<?php echo RequestStatus::NewRequest; ?>&character_id=<?php echo $characterId; ?>&action=list">
                                <?php echo $newRequests; ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Sent to STs
                        </td>
                        <td>
                            <a href="/request.php?filter[title]=&filter[request_type_id]=0&filter[request_status_id]=<?php echo RequestStatus::Submitted; ?>&character_id=<?php echo $characterId; ?>&action=list">
                                <?php echo $stRequests; ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Viewed by ST
                        </td>
                        <td>
                            <a href="/request.php?filter[title]=&filter[request_type_id]=0&filter[request_status_id]=<?php echo RequestStatus::InProgress; ?>&character_id=<?php echo $characterId; ?>&action=list">
                                <?php echo $stViewedRequests; ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Returned
                        </td>
                        <td>
                            <a href="/request.php?filter[title]=&filter[request_type_id]=0&filter[request_status_id]=<?php echo RequestStatus::Returned; ?>&character_id=<?php echo $characterId; ?>&action=list">
                                <?php echo $returnedRequests; ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Approved
                        </td>
                        <td>
                            <a href="/request.php?filter[title]=&filter[request_type_id]=0&filter[request_status_id]=<?php echo RequestStatus::Approved; ?>&character_id=<?php echo $characterId; ?>&action=list">
                                <?php echo $approvedRequests; ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Denied
                        </td>
                        <td>
                            <a href="/request.php?filter[title]=&filter[request_type_id]=0&filter[request_status_id]=<?php echo RequestStatus::Denied; ?>&character_id=<?php echo $characterId; ?>&action=list">
                                <?php echo $rejectedRequests; ?>
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div style="float:left;width:290px;min-height:300px;border:solid 0 #333333;">
            <div class="tableRowHeader" style="width:100%;">
                <div style="text-align: center;font-weight: bold;">
                    Widgets
                </div>
                <a href="http://www.accuweather.com/en/us/savannah-ga/31401/weather-forecast/446" class="aw-widget-legal">
                    <!--
                    By accessing and/or using this code snippet, you agree to AccuWeather’s terms and conditions (in English) which can be found at http://www.accuweather.com/en/free-weather-widgets/terms and AccuWeather’s Privacy Statement (in English) which can be found at http://www.accuweather.com/en/privacy.
                    -->
                </a><div id="awcc1386293817605" class="aw-widget-current"  data-locationkey="446" data-unit="f" data-language="en-us" data-useip="false" data-uid="awcc1386293817605"></div><script type="text/javascript" src="http://oap.accuweather.com/launch.js"></script>
                <!-- // Begin Current Moon Phase HTML (c) MoonConnection.com // -->
                <div style="width:142px;margin: 0 auto;"><div style="padding:2px;background-color:#000000;border: 1px solid #000000"><div style="padding:15px;padding-bottom:5px;padding-top:11px;border: 1px solid #AFB2D8" align="center"><script language="JavaScript" type="text/javascript">var ccm_cfg = { pth:"http://www.moonmodule.com/cs/", fn:"ccm_v1.swf", lg:"en", hs:1, tf:"12hr", scs:0, df:"std", dfd:0, tc:"FFFFFF", bgc:"000000", mc:"000000", fw:104, fh:153, js:0, msp:0, u:"mc" }</script><script language="JavaScript" type="text/javascript" src="http://www.moonmodule.com/cs/ccm_fl.js"></script><div style="padding-top:5px" align="center"><a href="http://www.moonconnection.com/moon_cycle.phtml" target="mc_moon_ph" style="font-size:10px;font-family:arial,verdana,sans-serif;color:#7F7F7F;text-decoration:underline;background:#000000;border:none;"><span style="color:#7F7F7F">moon cycles</span></a></div></div></div></div><!-- // end moon phase HTML // -->
            </div>
        </div>
    </div>
    <script>
        $(function () {
        });

        function loginCharacter() {
            $("#character-login").submit();
        }
    </script>

<?php
$page_content = ob_get_clean();