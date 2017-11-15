<?php

/**
 * @file catalog-concretGoods.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess()
 */ 
//dpm($goods);
//print_r($goods->listRootPr);
?>
<?php     
    preg_match('/[0-9]$/',$goods->count,$matches);
	$matches=$matches[0];
	if($matches==0 | $matches>4) $strP=t(' продовцов');
	if($matches>1 & $matches<5) $strP=t(' продовца');
	if($matches==1) $strP=t(' продовец');
	
?>
<style type="text/css">
  h1.title{
	display:none;
  }
</style>
<div class='goodsContent'>	 
	 <div class='headerGoods'><h1 class='nameGoods'><?php print($goods->type." ".$goods->company." ".$goods->model); ?></h1>
     <?php if($goods->count!=null){ ?>
		  <div class='priceG'>
		    <?php print $goods->min."$".(($goods->count>1 && $goods->min!=$goods->max)?(" - ".$goods->max."$"):"")." ("; ?><a href='../shopsGoods/<?php print $goods->id; ?>'><?php print $goods->count.$strP; ?></a>)
		  </div>			  
	<?php } else{ ?>
	<div class='priceG'>
	   Нет в продаже
	</div>
	<?php } ?>
	<br>
	</div>
	
	<div class='propertyGoods'>

	<table width="100%" cellpadding=0 cellspacing=0 id='tFG'>
	<tr><td rowspan='<?php print count($goods->listPr); ?>' width='2%' class='imageblock'><div class='scroller'>
		    <?php for($y=0;$y<count($goods->images);$y++){ ;
			    $l=getimagesize($goods->images[$y]);				 
			?>
			      <div class='scpollerInner'>
		          <a title="<?php $tGt=$goods->company.' '.$goods->model; print $tGt; ?>" href="/<?php print $goods->images[$y]; ?>" rel='gallery1' class='fancyboxGoods'><img src="/<?php print $goods->images[$y]; ?>" alt="<?php print $tGt; ?>" <?php print ($l[0]>=$l[1])?'width':'height'; ?>="120px"/></a></div>
		    <?php } ?>			
		    </div></td><td>
	<table width="100%" cellpadding=4 cellspacing=4 >	
	<?php for($y=0;$y<count($goods->listRootPr);$y++){ ?>  
         <tr class='onePrG'><td class='namePrRootG' colspan=2><?php print $goods->listRootPr[$y]["name"]; ?></td></tr>	
	  </tr>
	<?php for($i=0;$i<count($goods->listPr);$i++){ if($goods->listPr[$i]['id_parent']==$goods->listRootPr[$y]['id']){?>     
		<tr class='onePrG'><td class='namePrG'><?php print $goods->listPr[$i]["name"]; ?></td><td class='valuePrG'><?php print $goods->listPr[$i]["value"] ?></td></tr>
	<?php }} }?>
	</table>
	</td>
	</tr>
	</table>
	</div><br><br>
	<div class='infoCentr'>
	  <p>
	  Техническое описания <?php print($goods->type." ".$goods->company." ".$goods->model); ?> каталога imix.by взято из сайтов производителей.
      </p>
	  <p>
К сожалению, информация не всегда достоверна и может содержать ошибки, опечатки, не точности, либо не полные сведения. Также многие производители оставляют за собой право вносить изменения в характеристики или комплектацию товара без предварительного уведомления.
</p>
	  <p>
Поэтому мы рекомендуем узнавать все параметры и нюансы по товару <?php print($goods->type." ".$goods->company." ".$goods->model); ?> у продавца, при совершении покупки.
</p>
	  <p>
Все информация в каталоге imix.by является собственностью imix, публикация или копирование без предварительного согласия запрещена. 
</p>
	</div>
</div>
<?php drupal_add_js('$(document).ready(function(){ $("#tFG .scroller").height($("#tFG").height()); });', inline); ?>

<script type="text/javascript">
$(function() {	
	$('.fancyboxGoods').fancybox({
		openEffect	: 'none',
		closeEffect	: 'none',
		nextEffect  : 'none',
		prevEffect  : 'none',		
		helpers : {
         title : {
          type : 'inside'
         },
         buttons : {}
        },
		afterLoad : function() {
            this.title+='</br>Рисунок ' + (this.index + 1) + ' из ' + this.group.length;
        },
		loop: false
	});
});
</script>