<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{PAGE_TITLE}</title>
    <META NAME="copyright" content="(c) <?php echo date('Y'); ?> Jeff Vandenberg">
    <META NAME="ROBOTS" CONTENT="noimageindex,follow">
    <link type="text/css" href="/css/app.css" rel="Stylesheet"/>
    <link type="text/css" href="/css/wanton/jquery-ui.min.css" rel="Stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Marcellus+SC" rel="stylesheet">
    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <script type="text/javascript" src="/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/js/jquery.autocomplete.min.js"></script>
    <script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="/js/server_time.js"></script>
    <script type="text/javascript" src="/js/foundation.min.js"></script>
    <script type="text/javascript" src="/js/wanton.js"></script>
</head>
<body>
<div id="header">
    <div class="widthsetter">
        <div id="logo"></div>
        <div id="userpanel">{USER_PANEL}</div>
        <div id="nav" data-sticky-container>
            <div class="title-bar" data-responsive-toggle="example-menu" data-hide-for="medium">
                <button class="menu-icon" type="button" data-toggle></button>
                <div class="title-bar-title">Menu</div>
            </div>

            <div class="top-bar" data-sticky data-options="marginTop:0;" style="width:100%" data-top-anchor="main-content">
                <div class="top-bar-left">
                    {MENU_BAR}
                </div>
                <div class="top-bar-right">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="widthsetter" id="main-content">
    <div id="content">
        <div id="pagetitle">
            {CONTENT_HEADER}
        </div>
        <div id="contenta" class="contentbox">
            <!-- IF FLASH_MESSAGE -->
            <div class="flash-message">
                {FLASH_MESSAGE}
            </div>
            <!-- ENDIF -->
            {PAGE_CONTENT}
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
                Produced by Jeff Vandenberg. Layout and Design by Jill Arden &copy; 2016
                Build # {BUILD_NUMBER}
            </div>
        </div>
    </div>
</div>
<img src="/img/indicator.gif" id="busy-indicator" alt=""/>
{JAVA_SCRIPT}
<script>
    wantonWickedTime.serverTime = {SERVER_TIME};
</script>
</body>
</html>
