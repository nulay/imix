<?php
/**
 * @file catalog-admin-concretGoods.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess()     
    preg_match('/[0-9]$/',$goods->count,$matches);
	$matches=$matches[0];
	if($matches==0 | $matches>4) $strP=t(' продовцов');
	if($matches>1 & $matches<5) $strP=t(' продовца');
	if($matches==1) $strP=t(' продовец');	
  */ 
 dpm($res)
?>
<?php print($res->idG.", ".$res->idCatal); ?>
<div class='goodsContent'>
	 <div class='headerGoods'><div class='nameGoods'><?php print($goods->type." ".$goods->company." ".$goods->model); ?></div>
     <?php if($goods->count!=null){ ?>
		  <div class='priceG'>
		    <?php print $goods->min."$".(($goods->count>1 && $goods->min!=$goods->max)?(" - ".$goods->max."$"):"")." ("; ?><a href='../shopsGoods/<?php print $goods->id; ?>'><?php print $goods->count.$strP; ?></a>)
		  </div>			  
	<?php } ?>
	</div>
	<div class='propertyGoods'>
	<table width="100%" cellpadding=4 cellspacing=4>
	<?php for($i=0;$i<count($goods->listPr);$i++){ ?>     
		<tr class='onePrG'><td class='namePrG'><?php print $goods->listPr[$i]["name"]; ?></td><td class='valuePrG'><?php print $goods->listPr[$i]["value"] ?></td></tr>
	<?php } ?>
	</table>
	</div>
</div>
<script>
var Class={create:function(){return function(){this.initialize.apply(this, arguments);}}}
Object.extend = function(d,s){for (var property in s) {d[property] = s[property];}return d;}

var LoaderCat=Class.create();
LoaderCat.prototype = {
    initialize: function(){
        this.catalog=null;
	}
}
</script>