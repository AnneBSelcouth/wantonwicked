<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PlayPreference Entity
 *
 * @property int $id
 * @property string $name
 * @property int $created_by_id
 * @property \Cake\I18n\Time $created_on
 * @property int $updated_by_id
 * @property \Cake\I18n\Time $updated_on
 * @property string $description
 * @property string $slug
 *
 * @property \App\Model\Entity\User $created_by
 * @property \App\Model\Entity\User $updated_by
 * @property \App\Model\Entity\PlayPreferenceResponseHistory[] $play_preference_response_history
 * @property \App\Model\Entity\PlayPreferenceResponse[] $play_preference_responses
 */
class PlayPreference extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
