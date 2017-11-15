<style type="text/css">
    .redactGoods{
        border:1px solid lightgray;
        color:#333333;
        font-family:Arial,verdana,sans-serif;
        font-size:12px;
        text-align:left;
    }
    .redactMenu{
        background-color: threedlightshadow;
        margin: 10px;
        padding: 5px;
    }

    .redactMenu label, .redactMenu select, .redactMenu img, .redactMenu input{
        margin-left:12px;
    }
    #butRefresh{
        cursor:pointer;
        vertical-align:bottom;
    }
    .poleForTable{
        padding:15px 30px;
	}
    .butPr{
        display: inline-block;
        vertical-align: text-top;
        cursor:pointer;
    }
    td input.inputPr{
        height: 12px;
        margin: 4px 0 4px 0;
    }
    .saveComplete{
        background-image:url("/<?php print drupal_get_path('module', 'catalog'); ?>/images/save.png");
    }
    .categVlog0{
        background-color:gray;
    }
    .categVlog1{
        background-color:lightgray;
    }
	.ui-jqgrid tr.jqgrow td {
       font-weight: normal;
       white-space: normal;
    }
	#listGS{
		padding:10px 0 0 10px
	}	
</style>

<div class="redactGoods">
    <div id='listGS'></div>
    <div class="redactMenu">
	<div><label>Глобальный поиск</label><input type='text' id='glSearch'></div> 
        <label>Категория</label><select id='catalog'></select><label>Фирма</label><select id='company'><option value='null'>Не выбрана</option></select><label>Модель</label><input type='text' id='model'><label>Свои товары</label><input id='checkAllG' type="checkbox" checked="checked"/><img id="butRefresh" src="/<?php print drupal_get_path('module', 'catalog'); ?>/images/Refresh24.gif" width="20" height="20" alt="Обновить"/>
    </div>
    <div class='poleForTable'>
        <div id='poleForTable' >
		   <div style="display:block; width:97%"><table id="list2"></table></div>            
           <div id="pager2"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
var activeElGoods=null;
var listCateg=null;
var jGrid=jQuery("#list2").jqGrid({ url:'listGoods&allG=true', datatype: "json",
    colNames:['№','Категория товара', 'Фирма', 'Модель','Цена'],
    colModel:[ {name:'id',index:'id', width:20, sortable:false},
    {name:'type',index:'type', width:60},
    {name:'company',index:'company', width:60},
    {name:'model',index:'model', width:60 },
    {name:'price',index:'price', width:30, align:"center", sortable:false}],
    rowNum:50, rowList:[50,100,500],
    pager: '#pager2',
    sortname: 'type',
    viewrecords: true,
    sortorder: "desc",
    caption:"Таблица товаров",
    autowidth: true,
    height: 500
});
jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false});
jQuery("#butRefresh").click(function(){
    var glSearch=jQuery('#glSearch').val();
	if(glSearch!=""){
	    var str="fast_s="+glSearch;
	    jQuery("#list2").jqGrid('setGridParam',{url:"listGoods?"+str}).trigger("reloadGrid");
	    return;
	}
    var str="allG="+jQuery('#checkAllG')[0].checked;
    var id_cat=jQuery('#catalog').val();
    if(id_cat!="10"){
        str+=getCategQuery(id_cat);
    }
    var id_comp=jQuery('#company').val();
    if(id_comp!="null")
        str+="&id_Comp="+id_comp;
	var model=jQuery('#model').val();
	if(model!="")
        str+="&model="+model;
    jQuery("#list2").jqGrid('setGridParam',{url:"listGoods?"+str}).trigger("reloadGrid");
});
$.ajax({
    url: 'listCatalogGoods',
    success: function(data) {
		data=jQuery.parseJSON(data);
        var cat=jQuery('#catalog');
        createTreeCat(cat,data,0,0);
    }
});
$.ajax({
    url: 'listCompany',
    success: function(data) {
		data=jQuery.parseJSON(data);
        var cat=jQuery('#company');
        for(var i=0;i<data.length;i++){
            data[i];
            cat.append('<option value="'+data[i].id+'">'+data[i].name+'</option>')
        }
    }
});

function createTreeCat(el,listC,numVl,id){
    listCateg=listC;
    for(var i=0;i<listC.length;i++){
        if(listC[i].id_parent==id){
            el.append('<option class="categVlog'+numVl+'" style="padding-left:'+(numVl*6)+'px;" value="'+listC[i].id+'">'+listC[i].value+'</option>')
            createTreeCat(el,listC,numVl+1,listC[i].id);
        }
    }
}

function getCategQuery(idCat){
    var cat=null;
    for(var i=0;i<listCateg.length;i++){
        if(listCateg[i].id==idCat){ cat=listCateg[i]; break;}
    }
    var str="";
    if(cat.id_parent==10){
        var str="";
        for(var i=0;i<listCateg.length;i++){
            if(listCateg[i].id_parent==cat.id)                
                str+='&id_Catal[]='+listCateg[i].id;            
        }       
    }else str+="&id_Catal[]="+cat.id;    
    return str;
}
var sobr={
	postr:function(){
	  jQuery("#listGS").empty();		  
	  var tA=jQuery('<textarea rows=15 columns=5 style="width:100%;"></textarea>');
	  jQuery("#listGS").append(tA);
	  var tE=this;	  
	  var dialog=jQuery('<div>').append(tA);  
      dialog.dialog({
            autoOpen: false,
            width: 600,
            buttons: {
               "Ok": function() {
			        var re=new RegExp(".+","g");
		            tE.sobrlistGS(tA.val().match(re));
                    $(this).dialog("close");
               },
              "Cancel": function() {
                    $(this).dialog("close");
              }
       }
      });	  
	  var bP=jQuery('<button ><<</button>');
	  var bN=jQuery('<button >>></button>');
	  this.sd=jQuery('<select size="10" style="width:600px;">');	 
	  this.sd.change(function(){	       
		   jQuery('#glSearch').val(jQuery(this.options[this.selectedIndex]).text());
		   tE.onoffBut(bP[0],bN[0],this);
		   jQuery('#butRefresh').click();		   
		});
	  jQuery("#listGS").append(this.sd).append('<br>');	 
	  var tE=this;	
	  bP.click(function(){		
		if(tE.sd[0].selectedIndex!=0){
	      tE.sd[0].options[tE.sd[0].selectedIndex-1].selected=true;
		  tE.sd.change();
		}	  
	  });
	  bN.click(function(){		
		if(tE.sd[0].selectedIndex!=tE.sd[0].options.length){
	       tE.sd[0].options[tE.sd[0].selectedIndex+1].selected=true; 		  
		   tE.sd.change();
		}
	  });
	  jQuery("#listGS").append(bP);
      bP[0].disabled=true;	
      bN[0].disabled=true;	  	  
	  jQuery("#listGS").append(bN);
	  var bIinBO=jQuery('<button>Вставить список</button>');
	  bIinBO.click(function(){
	      dialog.dialog('open');
          return false;		  
	  });
	  jQuery("#listGS").append(bIinBO);	  
  },
  onoffBut:function(b1,b2,sel){
	 b1.disabled=(sel.selectedIndex==0);
	 b2.disabled=(sel.selectedIndex+1==sel.options.length);
  },
  sobrlistGS:function(str){	    
    this.sd.empty();	
	for(var i=0;i<str.length;i++){
		this.sd.append(jQuery('<option>'+str[i]+'</option>'));
	}
	//jQuery("#listGS").append(this.sd);
  }
}
sobr.postr();

function savePrice(el){
    var str="id_goods="+jQuery(el).parent().parent().attr('id')+"&price="+el.value;
    $.ajax({
        url: 'savePrice',
        data:str,
        asunc:true,
        success:function(data){
			data=jQuery.parseJSON(data);
			if(data.save=='complete')
                jQuery(el).addClass("saveComplete");
			else alert('Oшибка сохранения данных.');	
        }
    })
}

function changeActEl(el){
    activeElGoods=el.value;
    if(jQuery(el).hasClass("saveComplete"))jQuery(el).removeClass("saveComplete");
    jQuery(el).focusout(function(){
        jQuery(this).unbind('focusout');
        if(this.value==0) this.value='';
        if(isNaN(this.value)){alert('Здесь может быть только число'); this.value=activeElGoods; return;}
        if(this.value!='' && (parseInt(this.value)>1000000000)){alert('Очень сомневаюсь что за такую сумму это кто нибудь купит.'); this.value=activeElGoods; return;}
        var re=/\.+/;
        if(re.test(this.value)){alert('дробные числа отстой');this.value=activeElGoods;return;}
        if(activeElGoods==this.value){return;}
        savePrice(this);
    });
}

function clearPrice(el){
    el=jQuery(el).prev()[0];
    if(el.value!='') el.value='';
    savePrice(el);
}
</script>