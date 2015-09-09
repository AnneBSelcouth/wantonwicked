<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/8/2015
 * Time: 9:35 PM
 */

namespace classes\character\sheet;


use classes\core\helpers\FormHelper;

class Geist extends SheetRenderer
{
    public function render(WodSheet $sheet, $character_name, $character_type_select, $location, $sex, $virtue, $vice,
                           $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                           $public_effects, $safe_place, $character_merit_list, $character_flaw_list,
                           $characterMiscList, $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated,
                           $defense, $morality_dots, $initiative_mod, $willpower_perm_dots, $speed,
                           $willpower_temp_dots, $armor, $st_notes_table, $history_table, $skill_table,
                           $attribute_table, $show_sheet_table, $splat1, $splat2, $friends, $power_trait_dots,
                           $power_points_dots)
    {
        ob_start();
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>">
            <tr>
                <th colspan="4" align="center">
                    Vitals
                </th>
            </tr>
            <tr>
                <td style="width:15%;">
                    <b>Name</b>
                </td>
                <td style="width:35%;">
                    <?php echo $character_name; ?>
                </td>
                <td style="width:15%;">
                    <b>Character Type</b>
                </td>
                <td style="width:35%;">
                    <?php echo $character_type_select; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Location</b>
                </td>
                <td>
                    <?php echo $location; ?>
                </td>
                <td>
                    <b>Sex:</b>
                </td>
                <td>
                    <?php echo $sex; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Virtue</b>
                </td>
                <td>
                    <?php echo $virtue; ?>
                </td>
                <td>
                    <b>Vice</b>
                </td>
                <td>
                    <?php echo $vice; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Archetype</b>
                </td>
                <td>
                    <?php echo $splat1; ?>
                </td>
                <td>
                    <b>Threshold</b>
                </td>
                <td>
                    <?php echo $splat2; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Age</b>
                </td>
                <td>
                    <?php echo $age; ?>
                </td>
                <td>
                    <b>Icon</b>
                </td>
                <td>
                    <?php echo $icon; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Is NPC</b>
                </td>
                <td>
                    <?php echo $is_npc; ?>
                </td>
                <td>
                    <b>Status</b>
                </td>
                <td>
                    <?php echo $status; ?>
                </td>
            </tr>
        </table>
        <?php
        $vitals_table = ob_get_clean();

        ob_start();
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>">
            <tr>
                <th colspan="2" align="center">
                    Information
                </th>
            </tr>
            <tr>
                <td width="25%">
                    <b>Concept</b>
                </td>
                <td width="75%">
                    <?php echo $concept; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Description</b>
                </td>
                <td>
                    <?php echo $description; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Daily Equipment</b>
                </td>
                <td>
                    <?php echo $equipment_public; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Other Equipment</b>
                </td>
                <td>
                    <?php echo $equipment_hidden; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Public Effects</b>
                </td>
                <td>
                    <?php echo $public_effects; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Krewe</b>
                </td>
                <td>
                    <?php echo $friends; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Haunt</b>
                </td>
                <td>
                    <?php echo $safe_place; ?>
                </td>
            </tr>
        </table>
        <?php
        $information_table = ob_get_clean();

        // set for later use
        $supernatural_xp_js = "";
        if ($sheet->viewOptions['xp_create_mode']) {
            $supernatural_xp_js = ' onBlur="updateXP(' . WodSheet::SUPERNATURAL . ')" ';
        }

        // keys
        $powers = $sheet->getPowers($sheet->stats['id'], 'Key', WodSheet::NAMENOTE, 2);
        ob_start();
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>" id="key_list">
            <tr>
                <th>
                    Keys
                    <?php if ($sheet->viewOptions['edit_powers']): ?>
                        <a href="#" onClick="addKey();return false;">
                            <img src="/img/plus.png" title="Add Key"/>
                        </a>
                    <?php endif; ?>
                </th>
            </tr>
            <tr>
                <td class="header-row">
                    Name
                </td>
            </tr>
            <?php foreach($powers as $i => $power): ?>
                <tr>
                    <td>
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
                            <label for="key<?php echo $i; ?>_name"></label>
                            <input type="text"
                                   name="key<?php echo $i; ?>_name"
                                   id="key<?php echo $i; ?>_name"
                                   size="20"
                                   value="<?php echo $power->getPowerName(); ?>"
                                <?php echo $supernatural_xp_js; ?>>
                        <?php else: ?>
                            <?php echo $power->getPowerName(); ?>
                        <?php endif; ?>
                        <input type="hidden" name="key<?php echo $i; ?>_id" id="key<?php echo $i; ?>_id"
                               value="<?php echo $power->getPowerID(); ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?
        $keyList = ob_get_clean();

        $powers = $sheet->getPowers($sheet->stats['id'], 'manifestation', WodSheet::NAMENOTE, 4);
        ob_start();
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>" id="manifestation_list">
            <tr>
                <th colspan="2">
                    Manifestations
                    <?php if ($sheet->viewOptions['edit_powers']): ?>
                        <a href="#" onClick="addManifestation();return false;">
                            <img src="/img/plus.png" title="Add Manifestation"/>
                        </a>
                    <?php endif; ?>
                </th>
            </tr>
            <tr>
                <td class="header-row">
                    Name
                </td>
                <td class="header-row">
                    Level
                </td>
            </tr>
            <?php foreach ($powers as $i => $power): ?>
                <?php $dots = FormHelper::Dots("manifestation${i}", $power->getPowerLevel(),
                    WodSheet::SUPERNATURAL, $sheet->stats['character_type'], $sheet->max_dots,
                    $sheet->viewOptions['edit_powers'], false, $sheet->viewOptions['xp_create_mode']); ?>
                <tr>
                    <td>
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
                            <label for="manifestation<?php echo $i; ?>_name"></label>
                            <input type="text"
                                   name="manifestation<?php echo $i; ?>_name"
                                   id="manifestation<?php echo $i; ?>_name"
                                   size="20"
                                   value="<?php echo $power->getPowerName(); ?>">
                        <?php else: ?>
                            <?php echo $power->getPowerName(); ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo $dots; ?>
                        <input type="hidden" name="manifestation<?php echo $i; ?>_id" id="manifestation<?php echo $i; ?>_id"
                               value="<?php echo $power->getPowerID(); ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
        $manifestationList = ob_get_clean();

        // Ceremonies
        $powers = $sheet->getPowers($sheet->stats['id'], 'Ceremonies', WodSheet::NAMENOTE, 2);
        ob_start();
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>" id="ceremony_list">
            <tr>
                <th colspan="2">
                    Ceremonies
                    <?php if ($sheet->viewOptions['edit_powers']): ?>
                        <a href="#" onClick="addCeremony();return false;">
                            <img src="/img/plus.png" title="Add Ceremony"/>
                        </a>
                    <?php endif; ?>
                </th>
            </tr>
            <tr>
                <td class="header-row">
                    Name
                </td>
                <td class="header-row">
                    Level
                </td>
            </tr>
            <?php foreach ($powers as $i => $power): ?>
                <?php $dots = FormHelper::Dots("ceremony${i}", $power->getPowerLevel(),
                    WodSheet::SUPERNATURAL, $sheet->stats['character_type'], $sheet->max_dots,
                    $sheet->viewOptions['edit_powers'], false, $sheet->viewOptions['xp_create_mode']); ?>
                <tr>
                    <td>
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
                            <label for="ceremony<?php echo $i; ?>_name"></label>
                            <input type="text"
                                   name="ceremony<?php echo $i; ?>_name"
                                   id="ceremony<?php echo $i; ?>_name"
                                   size="20"
                                   value="<?php echo $power->getPowerName(); ?>">
                        <?php else: ?>
                            <?php echo $power->getPowerName(); ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo $dots; ?>
                        <input type="hidden" name="ceremony<?php echo $i; ?>_id" id="ceremony<?php echo $i; ?>_id"
                               value="<?php echo $power->getPowerID(); ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
        $ceremonyList = ob_get_clean();

        ob_start();
        ?>
        <div style="width:50%;float:left;">
            <?php echo $character_merit_list; ?>
            <?php echo $character_flaw_list; ?>
            <?php echo $characterMiscList; ?>
        </div>
        <div style="width:50%;float:left;">
            <?php echo $keyList; ?>
            <?php echo $manifestationList; ?>
            <?php echo $ceremonyList; ?>
        </div>
        <table class="character-sheet <?php echo $sheet->table_class; ?>">
            <tr>
                <th colspan="6">
                    Traits
                </th>
            </tr>
            <tr>
                <td style="width:15%">
                    Health
                </td>
                <td colspan="2" style="width:50%">
                    <?php echo $health_dots; ?>
                </td>
                <td colspan="1" style="width:15%">
                    Wounds
                </td>
                <td colspan="2" style="width:20%;white-space: nowrap;">
                    Bashing: <?php echo $wounds_bashing; ?>
                    Lethal: <?php echo $wounds_lethal; ?>
                    Agg: <?php echo $wounds_aggravated; ?>
                </td>
            </tr>
            <tr>
                <td colspan="1">
                    Psyche
                </td>
                <td colspan="2">
                    <?php echo $power_trait_dots; ?>
                </td>
                <td colspan="1">
                    Size
                </td>
                <td colspan="2">
                    <?php echo $size; ?>
                </td>
            </tr>
            <tr>
                <td colspan="1">
                    Synergy
                </td>
                <td colspan="2">
                    <?php echo $morality_dots; ?>
                </td>
                <td colspan="1">
                    Defense
                </td>
                <td colspan="2">
                    <?php echo $defense; ?>
                </td>
            </tr>
            <tr>
                <td>
                    Willpower Perm
                </td>
                <td colspan="2">
                    <?php echo $willpower_perm_dots; ?>
                </td>
                <td colspan="1">
                    Initiative Mod
                </td>
                <td colspan="2">
                    <?php echo $initiative_mod; ?>
                </td>
            </tr>
            <tr>
                <td>
                    Willpower Temp
                </td>
                <td colspan="2">
                    <?php echo $willpower_temp_dots; ?>
                </td>
                <td colspan="1">
                    Speed
                </td>
                <td colspan="2">
                    <?php echo $speed; ?>
                </td>
            </tr>
            <tr>
                <td>
                    Plasm
                </td>
                <td colspan="2">
                    <?php echo $power_points_dots; ?>
                </td>
                <td colspan="1">
                    Armor
                </td>
                <td colspan="2">
                    <?php echo $armor; ?>
                </td>
            </tr>
        </table>
        <?php
        $traits_table = ob_get_clean();

        return $this->renderSheet($sheet, $show_sheet_table, $vitals_table, $information_table, $attribute_table,
            $skill_table, $traits_table, $history_table, $st_notes_table);
    }
}