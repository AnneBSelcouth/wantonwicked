<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CharacterStatus Entity
 *
 * @property int $id
 * @property string $name
 * @property int $sort_order
 *
 * @property \App\Model\Entity\Character[] $characters
 */
class CharacterStatus extends Entity
{
    public const NEW_CHARACTER = 1;
    public const ACTIVE = 2;
    public const UNSANCTIONED = 3;
    public const INACTIVE = 4;
    public const DELETED = 5;
    public const IDLE = 6;

    public const NonDeleted = [
        self::NEW_CHARACTER,
        self::ACTIVE,
        self::UNSANCTIONED,
        self::INACTIVE,
        self::IDLE,
    ];

    public const Sanctioned = [
        self::ACTIVE,
        self::INACTIVE,
        self::IDLE
    ];


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
