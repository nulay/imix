<?php

/**
 * @file critSearch.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess()
 * @see template_preprocess_critSearch()
 */
//dpm($sC);
// dpm($plg);
  function isSelected($sC){
     if( $sC->listIdComp!=null && count($sC->listIdComp)!=0) return true;
	 if( $sC->minPrice>0 | $sC->maxPrice>0) return true;
	 if( $sC->listDopProp!=null && count($sC->listDopProp)!=0) return true;
	 return false;
  }
?>
<form action="listGoods" method="get">
    <table cellspacing="3" cellpadding="3" width="150px" class=".ramkaBlock">
        <tr>
            <td colspan="2">
			  <?php for($i=0;$i<count($sC->listIdCatG);$i++){ ?>
			    <input type="hidden" name="id_Catal[]" value="<?php print $sC->listIdCatG[$i]; ?>"/>
			  <?php }?>
			     <input type="hidden" name="orderName" value="<?php print $sC->orderName; ?>"/>
			  <?php if($sC->sord!=true){ ?>
			     <input type="hidden" name="sord" value="false"/>
			  <?php } ?>
            </td>
        </tr>        
        <?php if (isSelected($sC)) { ?>
		<tr>
            <td colspan="2" bgcolor="gainsboro" class='searchEl'> Выбрано <input type="hidden" name="numPage" value="<?php print $sC->numPage;?>"/></td>
        </tr>

		<?php if($sC->listIdComp!=null && count($sC->listIdComp)!=0){ $key=true; ?>
        <tr>
            <td class='searchEl'>Производитель </td>
            <td class='searchEl'>
			    <?php for ($y=0; $y<count($sC->listIdComp); $y++){?>
                  <nobr><select name="id_Comp[]">
                    <option value="null"></option>
                    <?php for ($i=0; $i<count($plg->listCompany); $i++){?>
                       <option value="<?php print $plg->listCompany[$i]->id_company;?>" <?php if($plg->listCompany[$i]->id_company==$sC->listIdComp[$y]){?>selected=""<?php } ?>><?php print $plg->listCompany[$i]->name;?></option>
                    <?php } ?>
                  </select><img src="../<?php print drupal_get_path('module', 'catalog'); ?>/images/trash.png" alt='удалить' title='удалить'><img src="../<?php print drupal_get_path('module', 'catalog'); ?>/images/add.png" alt='добавить' title='добавить'></nobr>
			    <?php } ?>
            </td>
        </tr>
        <?php } 		 
		  if($sC->minPrice>0 | $sC->maxPrice>0){ $key2=true;
		?>
        <tr>
            <td class='searchEl'>Цена </td>
            <td class='searchEl'>
               <nobr>от <input type='text' name='minPrice' value='<?php print ($sC->minPrice>0)?$sC->minPrice:''; ?>' size=3> до <input type='text' name='maxPrice' value='<?php print ($sC->maxPrice>0)?$sC->maxPrice:''; ?>' size=3></nobr>
            </td>
        </tr>
       <?php } ?>

        <?php if(($sC->phfs && count($sC->phfs)>0) & ($sC->listDopProp && count($sC->listDopProp)>0)){
			foreach ($sC->listDopProp as $dp){
			foreach ($sC->phfs as $phfs){ 
				if($dp->type==$phfs->type && $dp->id==$phfs->id){ $phfs->active=true; if($dp->value1) $phfs->value1=$dp->value1; if($dp->value2) $phfs->value2=$dp->value2;
				?>
		<tr>
            <td class='searchEl'><?php print $phfs->name;?> </td>
            <td class='searchEl'>
               <?php $elS=ElementSearch::getElementSearch($phfs->type);
			         print  $elS->getElementsHTML($phfs);?>
            </td>
        </tr>
        <?php }}}}?>

		<tr>
            <td colspan="2" align="right" class='searchEl'><input class='searchEl' type="submit" value="Обновить"/> </td>
        </tr>
		<?php } ?>
		
		<tr>
            <td colspan="2" bgcolor="gainsboro" class='searchEl'>&nbsp;&nbsp; Поиск <input type="hidden" name="numPage" value="<?php print $sC->numPage;?>"/></td>
        </tr>
		<?php if(!$key){?>
        <tr>
            <td class='searchEl'>Производитель </td>
            <td class='searchEl'>
                <select name="id_Comp[]">
                    <option value="null"></option>
                    <?php for ($i=0; $i<count($plg->listCompany); $i++){?>
                    <option value="<?php print $plg->listCompany[$i]->id_company;?>"><?php print $plg->listCompany[$i]->name;?></option>
                   <?php } ?>
                </select>
            </td>
        </tr>
        <?php } if(!$key2){?>
        <tr>
            <td class='searchEl'>Цена </td>
            <td class='searchEl'>
               <nobr>от <input type='text' name='minPrice' value='' size=3> до <input type='text' name='maxPrice' value='' size=3></nobr>
            </td>
        </tr>
		<?php }?>
		<?php if($sC->phfs && count($sC->phfs)>0){
			foreach ($sC->phfs as $phfs){ if(!$phfs->active){?>
		<tr>
            <td class='searchEl'><?php print $phfs->name;?> </td>
            <td class='searchEl'>
               <?php $elS=ElementSearch::getElementSearch($phfs->type);
			         print  $elS->getElementsHTML($phfs);?>
            </td>
        </tr>
        <?php }}}?>
		<tr>
            <td colspan="2" align="right" class='searchEl'><input class='searchEl' type="submit" value="Поиск"/> </td>
        </tr>
    </table>
</form>