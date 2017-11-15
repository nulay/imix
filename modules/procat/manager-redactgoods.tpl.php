<?php

/**
 * @file manager-redactgoods.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess()
 * @see template_preprocess_redact_item_shop()
 */
//print_r ($listItTh);
?>
<style>
    .rootCateg{
        background-color: lightGrey;
    }
    .childCateg{
        padding-left:20px;
    }
	.tbIm div.imBl{        
		padding: 5px;		
        margin: 5px;
    }
	#listIm{
		 border: 1px solid gray;
         height: 220px;
         overflow-x: auto;
         overflow-y: hidden;
         width: 620px;
	}
	.tbIm img.imWim{
		height:150px;
	}
	.boxShadow {
	   -moz-box-shadow: 3px 3px 10px #969696;
	   -webkit-box-shadow: 3px 3px 10px #969696;
	   box-shadow: 3px 3px 10px #969696;
	   filter: progid:DXImageTransform.Microsoft.Shadow(color='#969696', Direction=145, Strength=10);
    }
    .contrIm{
		padding:7px;
	}	
</style>
<div id='content'>
    <br><br>
    <div>
        Категория: <select id='idcateg'><option value='null'>Все</option>
        <?php for($i=0;$i<count($procatCateg);$i++){ ?>
        <option class='rootCateg' value=<?php print $procatCateg[$i]->id; ?>><?php print $procatCateg[$i]->value; ?></option>
        <?php for($y=0;$y<count($procatCateg[$i]->listTypeGoods);$y++){ ?>
        <option class='childCateg' <?php if($procatData!=null && $procatData['idcateg']!=null && $procatCateg[$i]->listTypeGoods[$y]->id==$procatData['idcateg']) print 
'selected=selected'; ?> value=<?php print $procatCateg[$i]->listTypeGoods[$y]->id; ?>><?php print $procatCateg[$i]->listTypeGoods[$y]->value; ?></option>
    <?php }} ?>
</select>
компания производитель: <input type='text' id='company'>
    модель: <input type='text' id='model'>
    <img id="butRefresh" src="/<?php print drupal_get_path('module', 'catalog'); ?>/images/Refresh24.gif" width="20" height="20" alt="Обновить"/>
    <input id='butCreate' type='button' value='Создать новый'>
    <input id='dedata' type='button' value='Удалить выделеные'>
</div>
<br>
    <div>
        <div style='width:98%;'><table id='list2' ></table></div>
        <div id="pager2"></div>
    </div>
</div>

<div id="dialog2" title="Изменение товара">
     <div id="tabs1">
        <ul>
         <li><a href="#fragment-1"><span>Данные товара</span></a></li>  
         <li><a href="#fragment-2"><span>Изображения</span></a></li> 		 
       </ul>
	   <div id="fragment-1" style='overflow:hidden;'>
	       <div style='height:440px; overflow:auto;' class='ui-widget-content ui-corner-bottom'>
		   <div id='errorField'></div>
		   <form id="formEditGoods"><input type="hidden" value="edit" name="meth"><input type="hidden" value="" name="id" id="inpid"><table style="width: 100%; padding: 10px;"
><tbody><tbody><tr><td class="namePrG"><label> Категория товара*: </label></td><td class="valuePrG"><div id='inpSelCateg'></div></td></tr><tr><td class="namePrG"
><label> Ключевое слово (используйте при необходимости уточнения категории товара): </label></td><td class="valuePrG"><input type="text" value="" name="keyword"id=
"inpkeyword"></td></tr><tr><td class="namePrG"><label> Фирма производитель*: </label></td><td class="valuePrG"><input type="text" value="" name="company" id=
"inpcompany"></td></tr><tr><td class="namePrG"><label> Модель*: </label></td><td class="valuePrG"><input type="text" value="" name="model" id="inpmodel"
></td></tr><tr><td class="namePrG"><label> Описание товара: </label></td><td class="valuePrG"><div><table><tbody id="bodyTP"></tbody></table><input type="button" value=
"Добавить свойство" id="inpbutAddProp"></div></td></tr><tr><td class="namePrG"><label> Дополнительное описание: </label></td><td class="valuePrG"><textarea name=
"additiontext" id="inpadditiontext" cols="50" rows="7"></textarea></td></tr><tr><td class="namePrG"><label> Стоимость в сутки*: </label></td><td class="valuePrG"><input
 type="text" value="" name="priceday" id="inppriceday"></td></tr><tr><td class="namePrG"><label> Стоимость за неделю: </label></td><td class="valuePrG"><input type=
"text" value="" name="priceweek" id="inppriceweek"></td></tr><tr><td class="namePrG"><label> Стоимость за месяц: </label></td><td class="valuePrG"><input type="text" 
value="" name="pricemonth" id="inppricemonth"></td></tr><tr><td class="namePrG"><label> Оценочная стоимость*: </label></td><td class="valuePrG"><input type="text" value
="" name="priceestimated" id="inppriceestimated"></td></tr></tbody></tbody></table></form></div>
		  <br> <div align='right'><input type="button" value="Сохранить" id="saveGoods"></div>
	   </div>
	   <div id="fragment-2">
	       <div><div id="res"></div><br><div id="listIm"></div><br><form action="/procat/manager/savegoodsimg" method="post" target="hiddenframe" enctype="multipart/form-data" 
onsubmit="return hideBtn();"><input type="hidden" id="fsiidGoods" name="idGoods" /><input type="file" id="goodsImg" name="goodsImg"/><input type="submit" name="upload" 
id="upload" value="Загрузить" /><label id='labUpload'>Вы загрузили максимальное количество изображений.</label></form></div><iframe id="hiddenframe" name="hiddenframe" style="width:0px; height:0px; border:0px"></iframe>
	   </div>
     </div>
</div>
<?php print $numPage; ?>

<script>
var Class={create:function(){return function(){this.initialize.apply(this, arguments);}}}
Object.extend = function(d,s){for (var property in s) {d[property] = s[property];}return d;}

var TableGoods=Class.create();
TableGoods.prototype = {
    initialize: function(){        
        this.build();
		this.winRG=new WinRedactGoods(this);
    },
    build: function(){
        var thisEl=this;
        this.jGrid=jQuery("#list2").jqGrid({ url:'../rgdata&allG=true', datatype: "json",
            colNames:['№','Категория товара', 'Фирма', 'Модель','Цены'],
            colModel:[{name:'id',index:'id', width:5, sortable:false},
            {name:'type',index:'id_catalog', width:60,editable: true},
            {name:'company',index:'company', width:60,editable: true},
            {name:'model',index:'model', width:60,editable: true },
            {name:'priceday',index:'priceday', width:30, align:"center", sortable:false,editable: true}],
            rowNum:50, rowList:[50,100,500],
            pager: '#pager2',
            sortname: 'type',
            viewrecords: true,
            sortorder: "desc",
            caption:"Таблица товаров",
            autowidth: true,
            height: 500,
            afterInsertRow: function(row_id, row_data){
                $('#list2').setCell(row_id,'type',jQuery('#idcateg [value='+row_data.type+']').text());
            },
            ondblClickRow: function(id) {
                //if( id != null ) var t=jQuery("#list2").jqGrid('getCell',id); 
                thisEl.winRG.show(id);
                //jQuery("#list2").jqGrid('editGridRow', id);		
            }
        });
        jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false});
        jQuery("#dedata").click(function(){
            var gr = jQuery("#list2").jqGrid('getGridParam','selrow');
            if( gr != null ) {
				jQuery.ajax({
                    url: '../rgdatach',
                    data:{"meth":"del","id":gr},
                    async:false,
					dataType :'json',
                    success:function(data){                        						                
                        alert((data.err==null)?data.mess:data.err);                       					
                }});
		        var su=jQuery("#list2").jqGrid('delRowData',gr);			   
		      }else alert("Не выделен ряд для удаления!"); });
            jQuery("#butCreate").click(function(){ thisEl.winRG.show(null); });
            jQuery("#butRefresh").click(function(){
            var str="";
            var id_cat=jQuery('#idcateg').val();
            if(id_cat!="null")
                str+="id_catalog="+id_cat;
            var company=jQuery('#company').val();
            if(company!="")
                str+="&company="+company;
            var model=jQuery('#model').val();
            if(model!="")
                str+="&model="+model;
            jQuery("#list2").jqGrid('setGridParam',{url:"../rgdata?"+str}).trigger("reloadGrid");
        });
		
    },
	addRowsInTable:function(data){
		var datarow = {id:data.id,type:data.idcateg,company:data.company,model:data.model,priceday:data.priceday};
		var su=jQuery("#list2").jqGrid('addRowData',data.id,datarow);
		$("#list2").jqGrid('setSelection', data.id);
	//	if(su) alert("Товар сохранен"); else alert("Не удалось обновить таблицу");
	}
}

var WinRedactGoods=Class.create();
WinRedactGoods.prototype = {
    initialize:function(owner){
		this.owner=owner;
        this.build();		
    },
    build:function(){
		var thisEl=this;
		this.dialogErrId=jQuery('<div>Перед загрузкой изображений необходимо сохранить товар.</div>').dialog({
			  autoOpen: false,
              width: 300,
		      modal:true,
			  buttons: {                
                "Закрыть": function() {					
                    jQuery(this).dialog("close");
                }
			}
        });	  						
		$("#tabs1").tabs({
              select: function(event, ui) {
				jQuery('#listIm').empty();
				if(ui.index==1 && thisEl.inpid.val()==""){
					thisEl.dialogErrId.dialog('open');
					return false;
				}
				if(ui.index==1){
					jQuery('#fsiidGoods').val(thisEl.inpid.val());
					jQuery('#res').empty();
					jQuery('#goodsImg').val("");
					var dsv=jQuery('<tr>')
					if(thisEl.curentObj!=null && thisEl.curentObj.listImg!=null && thisEl.curentObj.listImg.length!=0){					   	
					   for(var i=0; i<thisEl.curentObj.listImg.length;i++){
						   dsv.append(thisEl._addNewIm(thisEl.curentObj.listImg[i],thisEl.inpid.val()));
					   }					   
				    }
					thisEl.tbIm=jQuery('<tbody class="tbIm">').append(dsv);
					jQuery('#listIm').append(jQuery('<table>').append(thisEl.tbIm));
					if(thisEl.tbIm.children().children().length>2){
					   jQuery('#upload').hide();
					   jQuery('#goodsImg').hide();
					   jQuery('#labUpload').show();
					}else{
					   jQuery('#upload').show();
					   jQuery('#goodsImg').show();
					   jQuery('#labUpload').hide();
					}
				}
				return true;
			  }
        });
        this.inpidcateg=jQuery('#idcateg').clone();
		this.inpidcateg.removeAttr('id');
		this.inpidcateg.attr('name','idcateg');
        this.inpidcateg.change(function(){
            if(jQuery(this).find(':selected').hasClass('rootCateg'))
                 jQuery(this)[0].selectedIndex=0;			
        });		
		jQuery('#inpSelCateg').append(this.inpidcateg)
		
		this.inpkeyword=jQuery('#inpkeyword');
		this.inpcompany=jQuery('#inpcompany');
		this.inpmodel=jQuery('#inpmodel');
		
		this.inpadditiontext=jQuery('#inpadditiontext');
		this.inppriceday=jQuery('#inppriceday');
		this.inppriceweek=jQuery('#inppriceweek');
		this.inppricemonth=jQuery('#inppricemonth');
		this.inppriceestimated=jQuery('#inppriceestimated');
		
		this.bodyTP=jQuery('#bodyTP');
		this.inpbutAdd=jQuery('#inpbutAddProp');
		this.inpbutAdd.click(function(){thisEl.createNewProp();});
		
		this.inpid=jQuery('#inpid');
		this.dialogCS=jQuery('<div>Товар успешно сохранен</div>').dialog({
							  autoOpen: false,
                              width: 300,
		                      modal:true,
							  buttons: {                
                                 "Закрыть": function() {					
                                 jQuery(this).dialog("close");
                              }
                              }
                            });	  
		
		this.butsaveGoods=jQuery('#saveGoods');	
        this.butsaveGoods.click(function() {
			        jQuery('#errorField').empty();
                    var options = {                       
                       url: "../rgdatach",
					   type:'POST',
					   dataType :'json',
                       success : function(objJ) {
						    if(objJ.err!=null && objJ.err.length!=0){
							for(var i=0;i<objJ.err.length;i++)
							   jQuery('#errorField').append(jQuery('<ul>').append(jQuery('<li>').append(objJ.err[i])));
						    }else{
							   if(thisEl.curentObj==null){
								   thisEl.owner.addRowsInTable(objJ);
							   }	
							   thisEl.curentObj=objJ;
							   if(thisEl.curentObj.listImg==null)
							       thisEl.curentObj.listImg=new Array();
							   thisEl.inpid.val(objJ.id);
						       jQuery('#errorField').empty();  
                               thisEl.dialogCS.dialog('open');
						    }
                       }
                    };
                    jQuery("#formEditGoods").ajaxSubmit(options);                    
                })		
	
        this.dialog=jQuery('#dialog2').dialog({
            autoOpen: false,
            width: 700,
			modal:true,
            buttons: {                
                "Закрыть": function() {
					jQuery('#errorField').empty();
                    jQuery(this).dialog("close");
                }
            }
        });
    },
	_addNewIm:function(el,objId){
		var thisEl=this;
		var b=jQuery('<img src="/<?php print drupal_get_path('module', 'procat'); ?>/images/remB.gif"  title="Удалить изображение" width=20 height=20>');
		var td=jQuery('<td>').append(jQuery('<div class="imBl"><img class="imWim boxShadow" src="/'+el+'?a='+new Date().getTime()+'"></div>').append(jQuery('<div class="contrIm">').append(jQuery(b))))
		b.click(function(){
			var g=thisEl.tbIm.children().children();
			var num=null;
			for(var i=0;i<g.length;i++){
				if(g[i]==td[0]){
					num=i;
					var n=td.find('.imWim').attr('src');
					var reg=/\_\d{1,2}_(\d{1,2})./;
					n=reg.exec(n);
					if(n!=null && n.length>1) num=n[1]; else num='null';
				}
			}
			if(num==null){alert('Ошибка! Обновите окно.');return;}
			jQuery.ajax({
              url: '../rgdatach',
              data:{"meth":"delIm","id":objId,"num":num},
              async:false,
			  dataType :'json',
              success:function(data){
                  if(data.err!=null){
					alert(data.err);
				  }else{
				    td.remove();
					alert(data.mess);
					jQuery('#upload').show();
	                jQuery('#goodsImg').show();
					jQuery('#labUpload').hide();
				  }
              }});
		});
		return td;	
	},
	createNewProp:function(prop,val){
		var butR=jQuery("<img src='/<?php print drupal_get_path('module', 'procat'); ?>/images/remB.gif'  title='Удалить свойство' width=20 height=20>");
		var tr=jQuery("<tr><td><input type='text' name='prop[]' value='"+prop+"'></td><td><input type='text' name='value[]' value='"+val+"'></td></tr>").append(jQuery('<td>').
append(butR));		
		this.bodyTP.append(tr);			
		butR.click(function(){tr.remove();});
	},
    show:function(objId){
        this.dialog.dialog('open');
		jQuery('#errorField').empty();
        this.setVariables(objId);
        return false;
    },
    setVariables:function(objId){
		var objM=null;
		$("#tabs1").tabs("select",0);
		this.bodyTP.empty();
		if(objId!=null){		
		  jQuery.ajax({
              url: '../rgdatach',
              data:{"meth":"getData","id":objId},
              async:false,
			  dataType :'json',
              success:function(data){
                  objM=data;                
              }});
          this.curentObj=objM;			  
		  this.inpidcateg.find('[value='+objM.id_catalog+']')[0].selected=true;
          for(var i=0;i<objM.listPr.length;i++){
		  	this.createNewProp(objM.listPr[i].property,objM.listPr[i].value);
		  }
		}else{
			this.curentObj=null;
			this.inpidcateg.find('[value=null]')[0].selected=true;
		}		
		this.inpid.val((objM!=null)?objM.Id:'');	
		this.inpkeyword.val((objM!=null)?objM.keyword:'');		
		this.inpcompany.val((objM!=null)?objM.company:'');
        this.inpmodel.val((objM!=null)?objM.model:'');	
        this.inpadditiontext.text((objM!=null)?objM.additiontext:'');		
		this.inppriceday.val((objM!=null)?objM.priceday:'');
		this.inppriceweek.val((objM!=null)?objM.priceweek:'');
		this.inppricemonth.val((objM!=null)?objM.pricemonth:'');
		this.inppriceestimated.val((objM!=null)?objM.priceestimated:'');
    },
	saveImg:function(){	    
	    jQuery('#res').empty();
		if(this.tbIm && this.tbIm.children().children().length>3){
		   jQuery('#res').append('<ul><li>Нельзя загружать больше 3 изображений.</li></ul>');  
		   return false;
		}		
		jQuery('#upload').hide();
		jQuery('#goodsImg').hide();
		jQuery('#res').html("Идет загрузка файла");
	},
	saveImgCompl:function(mes){
		mes=jQuery.parseJSON(mes);
		jQuery('#res').empty();
		if(mes.err!=null && mes.err.length>0){
			var ul=jQuery('<ul>');
			for(var i=0;i<mes.err.length;i++){
				ul.append('<li>'+mes.err[i]+'</li>');
			}	
			jQuery('#res').append(ul);	
		}else{
		  this.tbIm.children().append(this._addNewIm(mes.fileI,this.inpid.val()));
          this.curentObj.listImg[this.curentObj.listImg.length]=mes.fileI;		  
		}
		if(this.tbIm && this.tbIm.children().children().length<3){
		   jQuery('#upload').show();
	       jQuery('#goodsImg').show();		   
		}else jQuery('#labUpload').show();
			
	}
}

var tg=new TableGoods();
function hideBtn(){return tg.winRG.saveImg();}
function handleResponse(mes) {return tg.winRG.saveImgCompl(mes);   }

</script>