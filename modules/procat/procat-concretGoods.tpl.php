<?php

/**
 * @file procat-concretGoods.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess()
 * @see template_preprocess_procat-concretGoods()
 *Собираем страницу с конкретным товаром
 */
 //print_r($goods);
?>
<style>
table{
     border-collapse:separate;
  }
#tFG td {
    vertical-align: top;
}
</style>
<div class='goodsContent'><div class='headerGoods'>
   <H1 class='nameGoods'><?php print $goods[company].' '.$goods[model]; ?> </H1>
   <H2><?php print $goods[procat]->named; ?> </H2>
   <H3>Телефон: <?php print $goods[procat]->tel1; ?> </H3>
   <?php if($goods[procat]->tel2!=null){ ?>
   <H3>Телефон2: <?php print $goods[procat]->tel2; ?> </H3>
   <?php } ?>
   <?php if($goods[procat]->tel3!=null){ ?>
   <H3>Телефон3: <?php print $goods[procat]->tel3; ?> </H3>
   <?php } ?>
   <?php if($goods[procat]->tel4!=null){ ?>
   <H3>Телефон4: <?php print $goods[procat]->tel4; ?> </H3>
   <?php } ?>
   <?php if($goods[procat]->tel5!=null){ ?>
   <H3>Телефон5: <?php print $goods[procat]->tel5; ?> </H3>
   <?php } ?>
   <H3>Цена за сутки: <?php print $goods['priceday']; ?></H3>
   <?php if($goods[priceweek]!=null){ ?>
   <H3>Цена за неделю: <?php print $goods['priceweek']; ?></H3>
   <?php } ?>
   <?php if($goods[pricemonth]!=null){ ?>
   <H3>Цена за месяц: <?php print $goods['pricemonth']; ?></H3>
    <?php } ?>
	<?php if($goods[priceestimated]!=null){ ?>
   <H3>Оценочная стоимость: <?php print $goods['priceestimated']; ?></H3>
    <?php } ?></div >
   <table width="100%" cellpadding=0 cellspacing=0 id='tFG'>
	<tr><td rowspan='<?php print count($goods[listPr]); ?>' width='2%' class='imageblock'><div class='scroller'>
		    <?php for($y=0;$y<count($goods[images]);$y++){ ;
			    $l=getimagesize($goods[images][$y]);				 
			?>
			      <div class='scpollerInner'>
		          <a title="<?php $tGt=$goods[company].' '.$goods[model]; print $tGt; ?>" href="/<?php print $goods[images][$y]; ?>" rel='gallery1' class='fancyboxGoods'><img src="/<?php print $goods[images][$y]; ?>" alt="<?php print $tGt; ?>" <?php print ($l[0]>=$l[1])?'width':'height'; ?>="120px"/></a></div>
		    <?php } ?>			
		    </div></td><td>
			<?php if(count($goods[listPr])>0){ ?>
	<table width="100%" cellpadding=4 cellspacing=4 >		
	<?php for($i=0;$i<count($goods[listPr]);$i++){ ?>     
		<tr class='onePrG'><td class='namePrG'><?php print $goods[listPr][$i]["property"]; ?></td><td class='valuePrG'><?php print $goods[listPr][$i]["value"] ?></td></tr>
<?php } ?> 
<?php if($goods[additiontext]!=null){ ?>
<tr class='onePrG'><td class='namePrG'>Дополнительное описание</td><td class='valuePrG'><?php print $goods[additiontext] ?></td></tr> 
<?php } ?>
</table>
<?php } ?>
</td>
</tr>
</table>
</div>

<?php drupal_add_js('$(function() { $("#tFG .scroller").height($("#tFG").height()); };', inline); ?>
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