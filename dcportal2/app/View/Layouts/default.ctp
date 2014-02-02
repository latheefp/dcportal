<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php echo $this->Html->charset();  ?>
        <title>
            <?php echo Configure::read('app.name').' ' . Configure::read('app.version') . ': ' . $title_for_layout; ?>
        </title>
        <?php
        echo $this->Html->meta('icon','favicon.png');
        
        echo $this->Html->css('dcportal');
        echo $this->Html->css('fg.menu');
        echo $this->Html->css('redmond/jquery.ui.all.css');
        echo $this->Html->script('jquery');
        echo $this->Html->script('jquery-ui-1.8.2.custom.min');
        
        echo $this->Html->script('fg.menu');
        echo $this->Html->script('themeswitchertool');
        echo $this->Html->script('jquery.blockUI');


        echo $scripts_for_layout;
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#switcher').themeswitcher();
                $('.fg-button').hover(
    		function(){ $(this).removeClass('ui-state-default').addClass('ui-state-focus'); },
    		function(){ $(this).removeClass('ui-state-focus').addClass('ui-state-default'); }
                );
                $.blockUI.defaults.message = '<p align=center>Please wait...<br /><img src="<?php echo $this->Html->url('/img/ajax-loader.gif');?>"></p>';
                $.blockUI.defaults.theme = true;
                $.blockUI.defaults.title = '<?php echo Configure::read('app.name').' ' . Configure::read('app.version') . ': ' . $title_for_layout; ?>';
                $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

                var doFadeIn = function() {
                    $('.fadein').css({ opacity:0, visibility:'visible'}).fadeTo(250,1);
                };
                $('body').one('mousemove',doFadeIn);
                $('#s').one('blur',doFadeIn);
            });

        </script>
<!--
/*
 * FIXME nav bar, menu always blue theme even if we change the theme!
 *
 */
-->
        <noscript>
            <style type="text/css">
                .fadein	{ visibility:visible; }
            </style>
        </noscript>
    </head>
    <body>
        <div id="container">
            <div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix" id="header">
                <span id="ui-dialog-title-dialog" class="ui-dialog-title">
                
                <a href="<?php echo $this->Html->url('/');?>"><img alt="DCPortal" title="<?php echo Configure::read('app.name').' ' . Configure::read('app.version'); ?>" src="<?php echo $this->Html->url('/img/dcportal-small.png');?>" border="0"></img></a>

<?php echo $this->element('menubar',
    array(
        "xcache" => array('time'=> "+7 days",'key'=>$this->Session->read('Group.id'))
    )
);
/*
 * TODO Enable the caching for the nav bar
 */
?>
</span>
<span style="float:right" id=page_links><form id="ServerIndexForm" method="get" action="/" accept-charset="utf-8">
<input name="qsearch" placeholder="Search"  autosave="your.domain.name" results="5" type="text" id="qinput" value="<?php echo @$qsearch;?>" />
</form>
</span>

</div>
            <div id="content">
                <?php echo $this->Session->flash(); ?>
                <?php echo $content_for_layout; ?>
            </div>
            
        </div>
        <?php// echo $this->element('sql_dump'); ?>
    </body>
</html>