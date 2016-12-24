<?php /* @var View $this */ ?>
<?php /* @var string $title_for_layout */ ?>
<?php /* @var string $buildNumber */ ?>

<!DOCTYPE html>
<html>
<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php echo $title_for_layout; ?>
    </title>
    <?php echo $this->Html->meta('icon'); ?>
    <META NAME="copyright" content="(c) <?php echo date('Y'); ?> Jeff Vandenberg">
    <META NAME="ROBOTS" CONTENT="noimageindex,follow">
    <?php
    echo $this->Html->css(array(
        'app'
    ));

    echo $this->Html->script(array(
        'jquery.min',
        'jquery.autocomplete.min',
        'tinymce/tinymce.min',
        'server_time',
        'foundation.min',
        'wanton',
    ));

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>
    <link href="https://fonts.googleapis.com/css?family=Marcellus+SC" rel="stylesheet">
    <script type="application/javascript">
        wantonWickedTime.serverTime = <?php echo $serverTime; ?>;
    </script>
</head>
<body>
<div id="header">
    <div class="widthsetter">
        <div id="logo"></div>
        <div id="userpanel"><?php echo $this->UserPanel->Create($this->Html->url()); ?></div>
        <div id="nav" data-sticky-container>
            <div class="title-bar" data-responsive-toggle="example-menu" data-hide-for="medium">
                <button class="menu-icon" type="button" data-toggle></button>
                <div class="title-bar-title">Menu</div>
            </div>

            <div class="top-bar" data-sticky data-options="marginTop:0;" style="width:100%" data-top-anchor="main-content">
                <div class="top-bar-left">
                    <?php echo $this->MainMenu->Create($menu); ?>
                </div>
                <div class="top-bar-right">
                </div>
            </div>
        </div>
    </div>
</div>
<div id="main-content" class="widthsetter">
    <div id="content">
        <div id="pagetitle">
            <?php if (isset($header_for_layout)): ?>
                <?php echo $header_for_layout; ?>
            <?php else: ?>
                <?php echo $title_for_layout; ?>
            <?php endif; ?>
        </div>
        <div id="contenta" class="contentbox">
            <?php if (isset($submenu)): ?>
                <?php echo $this->SubMenu->Create($submenu); ?>
            <?php endif; ?>
            <?php echo $this->Session->flash(); ?>

            <?php echo $this->fetch('content'); ?>
        </div>
    </div>
</div>
<div id="footer">
    <div class="row">
        <div class="small-12 column text-center">
            <div style="font-size: 9px;">The Storytelling System, Beast the Primordial, Changeling
                the Lost, Chronicles of Darkness, Demon the Descent, Mage the Awakening, Vampire the Requiem, and
                Werewolf the Forsaken
                &copy;2014-2016 CCP hf and published by <a href="http://theonyxpath.com/" target="_blank">Onyx Path
                    Publishing</a>.<br>
                Produced by Jeff Vandenberg. Layout and Design by Jill Arden &copy;<?php echo date('Y'); ?>
                Build # <?php echo $buildNumber; ?>
            </div>
        </div>
    </div>
</div>
<?php
echo $this->Html->image(
    'indicator.gif',
    array('id' => 'busy-indicator')
);
?>
</body>
</html>
