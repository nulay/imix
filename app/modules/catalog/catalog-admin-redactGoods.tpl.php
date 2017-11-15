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
 //dpm($res);
?>
 <style>
    .ui-combobox {
        position: relative;
        display: inline-block;
    }
    .ui-combobox-toggle {
        position: absolute;
        top: 0;
        bottom: 0;
        margin-left: -1px;
        padding: 0;
        /* adjust styles for IE 6/7 */
        *height: 1.7em;
        *top: 0.1em;
    }
    .ui-combobox-input {
        margin: 0;
        padding: 0.3em;
    }
	.ui-autocomplete{
		width:80px;
		height:150px;
	}
	.cellOne{
		display: table-cell;
		vertical-align: middle;
	}
	.rowOne{
	    padding:3px 0;	
    }
	.rootPr{
		background:Wheat;
		margin: 3px 0;
		padding: 6px 10px;
		vertical-align: text-bottom;
		font-weight: bold;
	}
</style>
<form action="/catalog/admin/redactGoods/saveGoods" enctype="multipart/form-data" method="POST">
<input type='hidden' name='idG' value='<?php print(($res->goods==null)?"":$res->goods->id);?>'>
<input type='hidden' name='id_catalog' value='<?php print(($res->fullCateg->id==null)?"":$res->fullCateg->id);?>'>
<h1><?php print($res->fullCateg->value);?></h1>
<div><label>Компания производитель: <select id="id_company" name="id_company"><option value=""></option>
<?php
   for ($i=0;$i<count($res->listCompany);$i++){
	 $s=''; if($res->goods!=null && $res->listCompany[$i]->name == $res->goods->company) $s.='selected="selected"';
?>
<option <?php print($s);?> value="<?php print($res->listCompany[$i]->id_company);?>"><?php print($res->listCompany[$i]->name);?></option>
<?php } ?>
</select><select id="id_companyF" style="display:none;" name="id_company2"><option value=""></option></select><label><input type='checkbox' id='loadAllCompany'>Все компании</label></label></div>

<div><label>Модель: <input type='text' name='model' value="<?php if($res->goods!=null) print($res->goods->model ); ?>"></label></div>
<?php
function getElCARC($res,$prD){
	$strNP="name='pr_".$prD->id."'";
    $prG=null;	
	if($res->goods!=null){
		for($i=0;$i<count($res->goods->listPr);$i++){			
			if($res->goods->listPr[$i][id_PrHierar]==$prD->id){
				$prG=$res->goods->listPr[$i];break;				
			}
		}	    
	}	
	
	switch($prD->type){
		case '1':$strOp='';
		   for($i=0;$i<count($res->linkList[$prD->id_linklist]);$i++){			
			$s=""; if($prG!=null && $res->linkList[$prD->id_linklist][$i]->value==$prG[value]){$s.='selected';}
			$strOp.="<option ".$s." value='".$res->linkList[$prD->id_linklist][$i]->value."'>".$res->linkList[$prD->id_linklist][$i]->value."</option>";
		   } 
		   return "<select ".$strNP."><option value=''></option>".$strOp."</select>";
		case '2': $s=''; if($prG!=null && $prG[value]=='Да') $s='checked="checked"'; return "<input ".$strNP." ".$s." type='checkbox'/>";		
		default: $s=''; if($prG!=null && $prG[value]!='') $s='value="'.$prG[value].'"'; return "<input ".$strNP." type='text' ".$s.">";
	}
}
//метод проверяет заполнино ли данное свойство в БД
function onPropertyCARC($res,$prD){
	$prG=null;
	if($res->goods!=null){
		for($i=0;$i<count($res->goods->listPr);$i++){			
			if($res->goods->listPr[$i][id_PrHierar]==$prD->id){
				$prG=$res->goods->listPr[$i];break;				
			}
		}	    
	}	
	$s='';
	if($prG!=null) $s='checked="checked"';
	return "<input ".$s." type='checkbox' name='on_".$prD->id."'/>";
}
   for ($i=0;$i<count($res->fullCateg->listPr);$i++){
	if($res->fullCateg->listPr[$i]->id_parent==0){
		$pr=$res->fullCateg->listPr[$i];
?>
    <div class='rootPr'><?php print($pr->name);?></div>
<?php
   for ($y=0;$y<count($res->fullCateg->listPr);$y++){
	if($res->fullCateg->listPr[$y]->id_parent==$pr->id){
		$prD=$res->fullCateg->listPr[$y];
?>
    <div class='rowOne'><div style='width:50px;' class='cellOne'><?php print(onPropertyCARC($res,$prD));?></div><div  class='cellOne' style='width:200px;'><?php print($prD->name);?></div><div  class='cellOne'><?php print(getElCARC($res,$prD));?></div></div>
<?php }}}} ?>
<div>
<div class='scroller'>
		    <?php for($y=0;$y<count($res->images);$y++){ ;
			    $l=getimagesize($res->images[$y]);				 
			?>
			      <div class='scpollerInner'>
				  <div><img class='buttRemImg' width=20 height=20 src='/<?php print drupal_get_path('module', 'catalog'); ?>/images/remB.gif' alt='Удалить свойство' title='Удалить свойство'></div>
		          <a title="<?php $tGt=$res->goods->company.' '.$res->goods->model; print $tGt; ?>" href="/<?php print $res->images[$y]; ?>" rel='gallery1' class='fancyboxGoods'><img class="imgGoods" src="/<?php print $res->images[$y]; ?>" alt="<?php print $tGt; ?>" <?php print ($l[0]>=$l[1])?'width':'height'; ?>="120px"/></a></div>
		    <?php } ?>			
		    </div>
<script type="text/javascript">
var listAllC=null;
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
	$('.buttRemImg').click(function(){
		var df=$(this);
		jQuery.ajax({
            url: '/catalog/admin/changeData',
            data:"method=removeImgGoods&fileName="+df.parents('.scpollerInner').find('img.imgGoods').attr('src').substr(1),
            cache:'false',
            dataType: "json",
            success: function(data) {
				df.parents('.scpollerInner').remove();
            }});
		});
	$('#buttAddImg').click(function(){
		$('#imgBlock').append('<div><input name="goodsImg[]" type="file"></div>');
	});
	
	$('#loadAllCompany').change(function(){
		if(listAllC==null){
			jQuery.ajax({
				url: '/catalog/admin/changeData',
				data:"method=getListAllCompany",
				async:false,
				cache:'false',
				dataType: "json",
				success: function(data) {
					listAllC=$('#id_companyF');
					for(var i=0;i<data.length;i++){
						listAllC.append('<option value="'+data[i].id+'">'+data[i].name+'</option>')
					}
				}});
		}
		if($(this).is(':checked')){
			$('#id_company').attr('name','id_company2').hide();	
			listAllC.attr('name','id_company');
			listAllC.show();			
		}else{
			listAllC.attr('name','id_company2').hide();
			$('#id_company').attr('name','id_company').show();
		}
    });
});
</script>
</div>
<div id='imgBlock'>
Прикрепить файлы:<br>
 <div><div><input name="goodsImg[]" type="file"> <img id='buttAddImg' width=20 height=20 src='/<?php print drupal_get_path('module', 'catalog'); ?>/images/add.jpg' alt='Добавить поле' title='Добавить поле'></div></div>
  
</div>  
<input type='submit' value='Сохранить'/>
</form>
