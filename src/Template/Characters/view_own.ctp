<?php
use App\View\AppView;
use classes\character\data\Character;

/* @var AppView $this */

/* @var Character $character */
$this->set('title_for_layout', 'View Character: ' . $character->CharacterName);
echo $this->Html->script('create_character_nwod2');
$this->start('script');
?>
<script defer>
    $(function() {
        $('.remove-character-row').hide();
    })
</script>
<?php
$this->end();
?>
<form method="post" data-abide novalidate id="character-form">
    <div data-abide-error class="alert callout" style="display: none;">
        <p><i class="fi-alert"></i> There are some errors in your character.</p>
    </div>
    <?php echo $this->Character->render($character, $icons, $options); ?>
    <div class="row">
        <div class="small-12 columns text-center">
            <?php echo $this->Form->button('Save', [
                'class' => 'button',
                'id' => 'save',
                'name' => 'action'
            ]); ?>
        </div>
    </div>
</form>
