<?php
// $Id: page.tpl.php,v 1.28.2.1 2009/04/30 00:13:31 goba Exp $
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
     if (!defined('_SAPE_USER')){
        define('_SAPE_USER', '7a01e82437cf41f9a0f88f6f6cc10db3');
     }
     require_once($_SERVER['DOCUMENT_ROOT'].'/'._SAPE_USER.'/sape.php');
	 $o['charset'] = 'UTF-8'; 
     $sape = new SAPE_client($o); 
	 unset($o);
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php print $language->language ?>" xml:lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">

<head>
  <?php print $head ?>
  <title><?php print $head_title ?></title>
  <meta name="wot-verification" content="9471bd4cf63cfa25c29e"/> 
  <?php print $styles ?>
  <?php print $scripts ?>
  <script type="text/javascript"><?php /* Needed to avoid Flash of Unstyle Content in IE */ ?> </script>
  <link type="text/css" rel="stylesheet" media="all" href="/modules/catalog/css/jquery-ui-1.7.3.custom.css?1" />
  <script type="text/javascript" src="/modules/catalog/js/jquery-ui-1.8.20.custom.min.js?1"></script>
</head>

<body>


<div class="upHeader">
    <div class="upHeader1">
        <div class="upHeader11">
             <?php if ($logo) { ?><a href="<?php print $front_page ?>" title="<?php print t('Home') ?>"><img src="<?php print $logo ?>" alt="<?php print t('Home') ?>" /></a><?php } ?>
        </div>
        <div class="userSelect">
            <div class="span6px">&nbsp;</div>
                 	<?php print $search_box ?>
			<?php print $header ?>
        </div>
    </div>        
    <div style="margin-top:3px;">
        <div align="left" class="plainForText">
            <a href="<?php print $front_page ?>" title="<?php print t('Home') ?>"><div class="plainForText0"><div class="span6px">&nbsp;</div>ДОБРО ПОЖАЛОВАТЬ НА САЙТ!</div><div class="plainForText1"><div class="span6px">&nbsp;</div><span class="plainForText2">ПРИЯТНОГО ПОИСКА ТОВАРОВ</span><span class="plainForText3">ИЩИ ДЕШЕВЛЕ</span></div></a>
        </div>
    </div>
    <div align="left" class="span3px">&nbsp;</div>
    <div>
        <div align="center">
            <div class="rootMenu">
		<?php if (isset($primary_links)) { ?><?php print theme('links', $primary_links, array('class' => 'lavaLamp1 lavaLampWithImage', 'id' => 'navlist')) ?><?php } ?>
            </div>
        </div>
    </div>
</div>

<?php if (isset($secondary_links)) { ?><?php print theme('links', $secondary_links, array('class' => 'links', 'id' => 'subnavlist')) ?><?php } ?>


<div>
<br>
               

<table border="0" cellpadding="0" cellspacing="0" id="content">
  <tr>
    <?php if ($left) { ?><td id="sidebar-left">
    <div class="block block-block">
	           <div class="content">
      <div id='sapeBlock' style=' font-size: 0.8em;'> 
         <? echo $sape->return_links(); ?>
         </div></div>
      </div>
      <?php print $left ?>
    </td><?php } ?>
    <td valign="top">
      <?php if ($mission) { ?><div id="mission"><?php print $mission ?></div><?php } ?>
      <table width="100%" cellpadding="0" cellspacing="0" class="borderCC">
	  <tr><td class="borderLT borderrArr" width="10">&nbsp;</td><td class="borderT borderrArr">&nbsp;</td><td width="7" class="borderrArr borderRT">&nbsp;</td></tr>
	  <tr><td class="borderL borderrArr">&nbsp;</td><td>
	 <div id="main">
	    <div>
        <?php print $breadcrumb ?>
        <h1 class="title"><?php print $title ?></h1>
        <div class="tabs"><?php print $tabs ?></div>
        <?php if ($show_messages) { print $messages; } ?>
        <?php print $help ?>       
        <?php print $content; ?>
        <table width="100%" border="0" cellpadding="0" cellspacing="10" id="tableForBlock">
  <tr>
    <td width="33%">
    	<?php if ($firstb != ""): ?>
        	<div id="first_region"><?php print $firstb ?></div>
        <?php endif; ?>
    </td>
    <td width="33%">
		<?php if ($secondb != ""): ?>
        	<div id="second_region"><?php print $secondb ?></div>
        <?php endif; ?>
       </td>
    <td width="33%">
		<?php if ($thirdb != ""): ?>
        	<div id="third_region"><?php print $thirdb ?></div>
        <?php endif; ?>
    </td>
  </tr>
</table>
        <?php print $feed_icons; ?>
		<!--
		<td width='250px' valign="top">
		  <?php if ($leftcontbl) { ?>
                    <?php print $leftcontbl ?>			
                   <?php } ?>
		</td>-->
		</div>
      </div>
	  </td><td class="borderrArr borderR">&nbsp;</td></tr>
	  <tr><td class="borderrArr borderLB">&nbsp;</td><td class="borderrArr borderB">&nbsp;</td><td class="borderrArr borderRB">&nbsp;</td></tr>
	  </table>
	  
    </td>
    <?php if ($right) { ?>
    <td id="sidebar-right"><?php print $right ?></td>
    <?php } ?>
  </tr>
</table>

<div id="footer">  
  <?php print $footer ?>  
  <?php print $footer_message ?>
</div>


<?php print $closure ?>
</body>
</html>
