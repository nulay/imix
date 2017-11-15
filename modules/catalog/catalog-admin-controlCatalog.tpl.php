<?php

/**
* @file catalog-admin-concretGoods.tpl.php
* Default theme implementation to present a linked feed item for summaries.
*
* @see template_preprocess()
*/
//dpm($user);
?>
<style type="text/css">
    .frameControl{
        background-color:#ddd;
        border:1px solid gray;
    }
    .panelControl{
        padding:3px;
    }
    .buttContr{
        cursor:pointer;
    }
    .contentControl{
        margin:3px;
        border:1px solid gray;
    }
    .cCCatalog{
        overflow: auto;
        background-color:#fff;
    }
    table.contentCatC td{
        vertical-align: top;
    }
    table.contentCatC thead td{
        vertical-align: middle;
    }
    .catValue{
        margin:3px;
        border:1px solid gray;
    }
    .catName{
        margin:10px;
    }
    .rootPrh{
        background-color:#aaa;
    }
    .propertyCat{
        border: 1px solid gray;
        margin: 10px;
        overflow: auto;
    }
    .propertyCat label{
        padding-left:10px;
    }
    .selButton{
        border: 1px #c33 solid; /* стили рамки */
        -moz-border-radius: 3px; /* закругление для старых Mozilla Firefox */
        -webkit-border-radius: 3px; /* закругление для старых Chrome и Safari */
        border-radius: 3px; /* закругление углов для всех, кто понимает */
        background-color:#aaa;
        margin: 4px;
    }
    .treeselProp{
        border: 1px solid gray;
        margin: 10px;
        overflow: auto;
        height:150px;
        background-color:#fff;
    }
    .treeCat{
        padding-left:20px;
        cursor:pointer;
    }
    .imCl.ui-icon{
        float:left;
    }
    .cCCatalog h3{
        font-weight: normal;
        margin:0;

    }
    .shitNode{
        padding-left:10px;
    }
</style>
<br><div>Управление</div><br>
<div class='frameControl'>
    <div class='panelControl'><button class='buttContr'>Каталог</button> <button class='buttContr'>Пользователи</button> <button class='buttContr'>Дополнительные списки</button></div>
    <div id='contentControl' class='contentControl'>
        <div class='panelCatalogContr'>
            <table width='100%' class='contentCatC'><tr><td style="width:300px">
				<div id='catalogBut' class='catalogBut'></div>
                <div id='uprCatalog' class='cCCatalog'></div>
            </td>
                <td><div class='buttContr' id='panContrCatal' style='display:none;'><button class='buttContr' id='settCat'>Настройка категории</button><button class='buttContr' id='listGoods' >Список товаров</button></div><div class='catValue' id='panContrCatalValue' style='display:none;'><div class='catName' class='catValue'><label>Название каталога: </label><input type='text' id='catName' value='' size='40'/></div>

                    <div id='propertyCat' class='propertyCat'></div>
                    <div id='uprCat' align='right'><button class='buttContr' style='margin-right:20px;' id='butSaveChInCat'>Сохранить изменения</button></div>

                    <div class='propertyCat'><label>Настройка полей для показа в превью</label><div id='labelCat' class='treeselProp'></div></div></div></td>
            </tr>
            </table>
        </div>
        <div class='panelCatalogUser' style='display:none;'>sdf</div>
        <div class='panelCatalogOtherList' style='display:none;'>sdf</div>
    </div>
</div>
<script type="text/javascript">
var Class={create:function(){return function(){this.initialize.apply(this, arguments);}}}
Object.extend = function(d,s){for (var property in s) {d[property] = s[property];}return d;}

var LoaderCat=Class.create();
LoaderCat.prototype = {
    initialize: function(){
        this.catalog=null;
        this.selEl=null;
        this.rootBut=null;
        this.loadListCateg();
        this.linkList=null;
        this.loadLinkList();
        this.panControlCat=new PanControlCat(this);
        this.sizeBody={'height':$(window).height(),'width':$(window).width()};
        $('#contentControl').height(this.sizeBody.height-200);
        $('.cCCatalog').height(this.sizeBody.height-205);		
    },
    loadLinkList:function(){
        var thisEl=this;
        jQuery.ajax({
            url: './changeData',
            data:{"method":"getListGenList"},
            async:false,
            dataType:"json",
            success:function(data){
                thisEl.linkList=data;
            }});
    },
    loadListCateg: function(){
        var thisEl=this;
        jQuery.ajax({
            url: './changeData',
            data:{"method":"getListCatalog"},
            async:false,
            success:function(data){
                data=jQuery.parseJSON(data);
                thisEl.catalog=data;
                thisEl.loadCatalog(jQuery('#uprCatalog'));
                jQuery('#panContrCatal').show();
                if(this.rootButCat!=null && this.rootButCat.hasClass("selButton"))this.rootButCat.removeClass("selButton");
                this.rootButCat=jQuery("#settCat");
                this.rootButCat.addClass("selButton");                
            }});
		this.butAddCat=jQuery("<img class='buttContr' width=20 height=20 src='/<?php print drupal_get_path('module', 'catalog'); ?>/images/addB.gif' alt='Добавление раздела' title='Добавление раздела'>").css('opacity','0.5').click(function(){if(thisEl.butAddCat.css('opacity')!='0.5'){thisEl.addCatalog();}});
        this.butRemCat=jQuery("<img class='buttContr' width=20 height=20 src='/<?php print drupal_get_path('module', 'catalog'); ?>/images/remB.gif' alt='Удалить раздел' title='Удалить раздел'>").css('opacity','0.5').click(function(){if(thisEl.butRemCat.css('opacity')!='0.5'){alert('Remove Catalog');}});
		jQuery('#catalogBut').append(this.butAddCat).append(this.butRemCat);
    },
    loadCatalog: function(elR,el){
        var thisEl=this;
        var tjk=function(el){return function(){thisEl.butAddCat.css('opacity',(el.id_parent==10 || el.id==10)?'1':0.5);thisEl.butRemCat.css('opacity',(el.id!=10)?'1':0.5);thisEl.panControlCat.showContrCatal(el);}};
        if(el==null)
            for(var i=0;i<this.catalog.length;i++){
                if(this.catalog[i].id_parent==0) this.loadCatalog(elR,this.catalog[i]);
                return;
            }
        var elC;
        if(el.id==10 || el.id_parent==10){
            elC=jQuery('<span>'+el.value+'</span>');
            var elP=jQuery('<div class="treeCat">');
            var elSp=jQuery('<span>');
			elSp.toggle(function(){
                    var b=$(this);b.parent().next().show();b.addClass('ui-icon-triangle-1-s').removeClass('ui-icon-triangle-1-e');
                },function(){
                    var b=$(this);b.parent().next().hide();b.addClass('ui-icon-triangle-1-e').removeClass('ui-icon-triangle-1-s');
                });
            if(el.id_parent==10){ elP.hide(); elSp.addClass('imCl ui-icon ui-icon-triangle-1-e');}else elSp.addClass('imCl ui-icon ui-icon-triangle-1-s');
            elR.append(jQuery('<div>').append(jQuery('<h3>').append(elSp).append(elC)).append(elP));
            for(var i=0;i<this.catalog.length;i++){
                if(el.id==this.catalog[i].id_parent) this.loadCatalog(elP,this.catalog[i]);
            }
        }else{
            elC=jQuery('<div class="shitNode">'+el.value+'</div>');
            elR.append(elC);
        }
        elC.mouseout(function(){jQuery(this).css('font-weight','');});
        elC.mouseover(function(){jQuery(this).css('font-weight','bold');});
        el.view=elC;
        elC.click(tjk(el));
    },
	addCatalog:function(){
		var thisEl=this;
		var inp=jQuery("<input type='text' value=''>");
        var dtk=jQuery("<div></div>").append('<label>Имя раздела: <label>').append(inp);
		dtk.dialog({
            dialogClass: "no-close",
            title:"Добавление раздела каталога",
            width:"400px",
            buttons: [{
                text: "OK",
                click: function() {   
					var el={'id_parent':thisEl.selEl.id,'name_prop':(thisEl.selEl.id_parent=='0')?'root':'name','value':inp.val(),'state':1};
					jQuery.ajax({
                        url: './changeData',
                        data:"method=saveOrUpdateCatalog&objRazd="+jQuery.toJSON(el),
                        async:false,
                        cache:'false',
                        dataType: "json",
                        success: function(data) {
							el=data;
							delete el.state;
                        }});
					thisEl.catalog[thisEl.catalog.length]=el;
                    thisEl.loadCatalog(thisEl.selEl.view.parents('div:first').find('div.treeCat:first'),el);
                    $( this ).dialog( "close" );
                }
            },
                {
                    text: "Cancal",
                    click: function() {
                        $( this ).dialog( "close" );
                    }
                }]
        });
	}
}

var PanControlCat=Class.create();
PanControlCat.prototype = {
    indkn:-1,//индекс новой рут записи
    initialize: function(owner){
        this.rootButCat=null;
        this.owner=owner;
		var thisEl=this;
		$('#listGoods').click(function(){thisEl.loadListGoods();});
    },
	loadListGoods:function(){
		var thisEl=this;
		jQuery.ajax({
            url: './changeData',
            data:{"method":"getListGoodsFromCatalog","id_catalog":thisEl.owner.selEl.id},
            async:false,
            success:function(data){
                data=jQuery.parseJSON(data);
				jQuery('#propertyCat').empty();
                thisEl.showWinListProduct(data);
            }});
	},
	showWinListProduct:function(data){
		var butAddGoods=jQuery("<a target='_blank' href='/catalog/admin/redactGoods/"+this.owner.selEl.id+"/-1'><img class='buttContr' width=20 height=20 src='/<?php print drupal_get_path('module', 'catalog'); ?>/images/add.jpg' alt='Добавить товар' title='Добавить товар'></a>");
		jQuery('#propertyCat').append(jQuery('<div></div>').append(butAddGoods));
		
		var tableG=jQuery('<table width="100%" border=1>');
		for(var i=0;i<data.length;i++){
			var butRemG=jQuery("<img class='buttContr' width=20 height=20 src='/<?php print drupal_get_path('module', 'catalog'); ?>/images/remB.gif' alt='Удалить позицию' title='Удалить позицию'>").click(function(){});
			var butEditGoods=jQuery("<a target='_blank' href='/catalog/admin/redactGoods/"+this.owner.selEl.id+"/"+data[i].id+"'><img class='buttContr' width=20 height=20 src='/<?php print drupal_get_path('module', 'catalog'); ?>/images/add.jpg' alt='Редактировать позицию' title='Редактировать позицию'></a>");
			data[i].el=jQuery('<tr>').append(jQuery('<td>').text(data[i].name+" "+data[i].model)).append(jQuery('<td>').append(butRemG).append(butEditGoods));			
			tableG.append(data[i].el)
		}
		jQuery('#propertyCat').append(tableG);
	},
    showContrCatal:function(el){
        jQuery('#propertyCat').height(this.owner.sizeBody.height-595);
        jQuery('#propertyCat').empty();
        if(this.owner.selEl!=null) this.owner.selEl.view.css('background-color','');
        this.owner.selEl=el;
        this.owner.selEl.view.css('background-color','#ccc');

        if(el.id==10 | el.id_parent==10){ jQuery('#listGoods').hide(); jQuery('.propertyCat').hide();
        }else{
            jQuery('#listGoods').show();
            this.loadPropGoods(el.id);
            jQuery('.propertyCat').show();
        }
        jQuery('#panContrCatalValue').show();
        jQuery('#catName').val(el.value);
        //alert(el.value);
    },
    loadPropGoods:function(id_cat){
        var ter=null;
        if(id_cat!=null){
            this.curCat=id_cat;
            var prt=jQuery.ajax({
                url: './changeData',
                data:"method=getListPropG&id_categ="+id_cat,
                async:false,
                cache:'false',
                dataType: "json",
                success: function(data) {
                    ter=data;
                }});
        }else{
            if(this.curentGoods==null){
                alert('Не известный тип товара');
                return;
            }
            ter=this.curentGoods;
        }
        this.curentGoods=cloneObject(ter);
        //ter=jQuery.parseJSON(ter.responseText);
        if(ter==null) ter=jQuery.parseJSON(prt.responseText);
        if(ter==null) return;
        this.selTer=ter;
        this.bildLabelCat(jQuery.parseJSON(ter.label));
        var body=jQuery('<tbody id="sortableB">');
        for(var i=0;i<ter.listPr.length;i++){
            if(ter.listPr[i].id_parent=="0"){
                this.addGroupWithGoods(ter,body,i);
                for(var y=0;y<ter.listPr.length;y++){
                    var c2=ter.listPr[y];
                    if(ter.listPr[i].id==c2.id_parent){
                        this.addPropInGroup(ter,y,i);
                    }
                }
            }
        }
        var butAddGr=jQuery("<img class='buttContr' width=20 height=20 src='/<?php print drupal_get_path('module', 'catalog'); ?>/images/addB.gif' alt='Добавить главное свойство' title='Добавить главное свойство'>").click(function(){var el={};el.id_parent="0";el.id=-1;el.id_Catalog=id_cat;el.state=1;el.el={};thisEl.redactPr(el);});
        var butRemAllPr=jQuery("<img class='buttContr' width=20 height=20 src='/<?php print drupal_get_path('module', 'catalog'); ?>/images/remB.gif' alt='Удалить все свойства' title='Удалить все свойства'>").click(function(){alert('Remove all Property');});
        jQuery('#propertyCat').append(jQuery("<table width='100%' border=1><thead align=center><tr><td rowspan=2 width='35%'>Название</td><td rowspan=2 width='10%'>Тип</td><td rowspan=2 width='10%'>Ссылка на список</td><td colspan=3>Операнд</td><td rowspan=2 width='10%'>Учавствовать в поиске</td><td rowspan=2 id='placeForAddB' width='5%'></td></tr><tr><td width='10%'>до</td><td width='10%'>между</td><td width='10%'>после</td></tr></thead></table>").append(body));
        thisEl=this;
        //alert(ter.listPr.length);
        $('#butSaveChInCat').click(function(){
            //alert(ter.listPr.length)
            var terSave=cloneObject(ter);
            for(var i=0; i<ter.listPr.length; i++){
                if(ter.listPr[i]==null) continue;
                var pr=terSave.listPr[i]; var prRoot=ter.listPr[i];
                delete pr.el;
                var prO=cloneObject(pr);
                pr.state=-1;
                thisEl.setValuePrH(pr,'name',prRoot.el.name.val(),0);
                if(pr.id_parent!=0){
                    thisEl.setValuePrH(pr,'uper',prRoot.el.uper.val(),0);
                    thisEl.setValuePrH(pr,'downer',prRoot.el.downer.val(),0);
                    thisEl.setValuePrH(pr,'inners',prRoot.el.inners.val(),0);
                    thisEl.setValuePrH(pr,'type',prRoot.el.listT.val(),0);
                    if(prRoot.el.listLL!=null){
                        pr.id_linklist=prRoot.el.listLL.val();
                    }
                    thisEl.setValuePrH(pr,'usesearch',prRoot.el.usesearch.prop('checked'),0);
                }
            }
            jQuery.ajax({
                url: './changeData',
                data:"method=saveGodsProp&objSave="+jQuery.toJSON(terSave),
                async:false,
                cache:'false',
                dataType: "json",
                success: function(data) {
                    alert(data);
                }});
        })
        jQuery('#placeForAddB').append(butAddGr).append('&nbsp;').append(butRemAllPr);
        //$( "#sortableB" ).sortable({
        //    cancel: ".state-disabled"
        //});
        //$( "#sortableB" ).disableSelection();	     
    },
    addGroupWithGoods:function(ter,body,i){
        var thisEl=this;
        ter.listPr[i].el=new Object();
        var tr=jQuery('<tr class="rootPrh">');
        ter.listPr[i].el.name=jQuery("<input type='text' size='70' value='"+ter.listPr[i].name+"'>");
        tr.append(jQuery("<td colspan=7 class='state-disabled'></td>").append(ter.listPr[i].el.name));
        var butAddPr=jQuery("<img class='buttContr' width=20 height=20 src='/<?php print drupal_get_path('module', 'catalog'); ?>/images/addB.gif' alt='Добавить свойство' title='Добавить свойство'>").click(function(){var el={};el.id_parent=ter.listPr[i].id;el.id=-1;el.id_Catalog=ter.listPr[i].id_Catalog;el.state=1;el.el={};thisEl.redactPr(el);});
        var butRemGr=jQuery("<img class='buttContr' width=20 height=20 src='/<?php print drupal_get_path('module', 'catalog'); ?>/images/remB.gif' alt='Удалить группу' title='Удалить группу'>").click(function(){
            thisEl.removePr(ter.listPr[i]);
        });
        tr.append(jQuery("<td class='state-disabled'></td>").append(butAddPr).append('&nbsp;').append(butRemGr));
        ter.listPr[i].el.tr=tr;
        body.append(tr);
    },
    addPropInGroup:function(ter,indC,indRoot){
        var c2=ter.listPr[indC];
        c2.el=new Object();
        this.addPropInGroupEl(c2,ter.listPr[indRoot].el);
    },
    addPropInGroupEl:function(c2,c2Root){
        var thisEl=this;
        var tr=jQuery('<tr>');
        c2Root.tr.after(tr);
        c2.el.name=jQuery("<input type='text' size='45' value='"+c2.name+"'>");
        tr.append(jQuery("<td>").append(c2.el.name));
        c2.el.listT=jQuery("<select><option value=null></option><option value=1>Список</option><option value=2>Флажок</option><option value=3>Поле от</option><option value=4>Поле до</option><option value=5>Поле от - до</option></select>");
        c2.el.listT.find('[value='+c2.type+']').attr("selected","selected");
        tr.append(jQuery("<td align=center></td>").append(c2.el.listT));
        tr.append(jQuery("<td align='center' class='tdfselel'>"));
        c2.el.listT.change(function(){
            if(jQuery(this).val()=="1"){
                c2.el.listLL=jQuery(thisEl.elLL);
                tr.find('td.tdfselel').append(c2.el.listLL);
            }else{
                if(c2.el.listLL!=null){
                    c2.el.listLL.remove();
                    c2.el.listLL=null;
                    c2.id_linklist=null;
                }
            }
        })
        if(this.elLL==null){
            this.elLL="<select style='width:100px;'>";
            for(var z=0;z<this.owner.linkList.length;z++){
                this.elLL+='<option value="'+this.owner.linkList[z].id+'">'+this.owner.linkList[z].value+'</option>';
            }
            this.elLL+='</select>';
        }
        if(c2.type==1){
            var ll=jQuery(this.elLL); ll.find('[value='+c2.id_linklist+']').attr("selected","selected");
            c2.el.listLL=ll;
            tr.find('td.tdfselel').append(c2.el.listLL);
        }
        c2.el.uper=jQuery("<input type='text' size=5 value='"+((c2.uper!=null)?c2.uper:"")+"'>");
        tr.append(jQuery("<td align=center>").append(c2.el.uper));
        c2.el.inners=jQuery("<input type='text' size=5 value='"+((c2.inners!=null)?c2.inners:"")+"'>");
        tr.append(jQuery("<td align=center>").append(c2.el.inners));
        c2.el.downer=jQuery("<input type='text' size=5 value='"+((c2.downer!=null)?c2.downer:"")+"'>");
        tr.append(jQuery("<td align=center>").append(c2.el.downer));
        c2.el.usesearch=jQuery("<input type='checkbox' "+((c2.usesearch==1)?"checked=\'checked\'":"")+">");
        tr.append(jQuery("<td align=center>").append(c2.el.usesearch));
        var butRemPr=jQuery("<img class='buttContr' width=20 height=20 src='/<?php print drupal_get_path('module', 'catalog'); ?>/images/remB.gif' alt='Удалить свойство' title='Удалить свойство'>").click(function(){thisEl.removePr(c2);});
        var butEditPr=jQuery("<img class='buttContr' width=20 height=20 src='/<?php print drupal_get_path('module', 'catalog'); ?>/images/add.jpg' alt='Редактировать свойство' title='Редактировать свойство'>").click(function(){c2.state=0;thisEl.redactPr(c2);});
        tr.append(jQuery("<td align=center>").append(butRemPr).append(butEditPr));
        c2.el.tr=tr;
    },
    addPropInGroupElD:function(c2){
        var thisEl=this;
        var tr=jQuery('<div>');
        c2.el.name=jQuery("<input type='text' size='25' value='"+c2.name+"'>");
        tr.append(jQuery("<div>").append(jQuery("<label>").text("Название").append(c2.el.name)));
		if(c2.id_parent!="0"){
        c2.el.listT=jQuery("<select><option value=null></option><option value=1>Список</option><option value=2>Флажок</option><option value=3>Поле от</option><option value=4>Поле до</option><option value=5>Поле от - до</option></select>");
        c2.el.listT.find('[value='+c2.type+']').attr("selected","selected");
        tr.append(jQuery("<div>").append(jQuery("<label>").text("Тип").append(c2.el.listT)));
        tr.append(jQuery("<div>").append(jQuery("<label class='tdfselel'>").text("Список")));
        c2.el.listT.change(function(){
            if(jQuery(this).val()=="1"){
                c2.el.listLL=jQuery(thisEl.elLL);
                tr.find('label.tdfselel').append(c2.el.listLL);
              //  c2.el.listLL.removeAttr('disabled');//jQuery('label.tdfselel select').
            }else{
                if(c2.el.listLL!=null){
                    c2.el.listLL.remove();
                    c2.el.listLL=null;
                    c2.id_linklist=0;
                }
            }
        })
        if(c2.type==1){
            var ll=jQuery(this.elLL); ll.find('[value='+c2.id_linklist+']').attr("selected","selected");
            c2.el.listLL=ll;
            tr.find('label.tdfselel').append(c2.el.listLL);
            //c2.el.listLL.removeAttr('disabled');//jQuery('label.tdfselel select').
        }
        c2.el.uper=jQuery("<input type='text' size=5 value='"+((c2.uper!=null)?c2.uper:"")+"'>");
        tr.append(jQuery("<div>").append(jQuery("<label>").text("До").append(c2.el.uper)));
        c2.el.inners=jQuery("<input type='text' size=5 value='"+((c2.inners!=null)?c2.inners:"")+"'>");
        tr.append(jQuery("<div>").append(jQuery("<label>").text("Между").append(c2.el.inners)));
        c2.el.downer=jQuery("<input type='text' size=5 value='"+((c2.downer!=null)?c2.downer:"")+"'>");
        tr.append(jQuery("<div>").append(jQuery("<label>").text("После").append(c2.el.downer)));
        c2.el.usesearch=jQuery("<input type='checkbox' "+((c2.usesearch==1)?"checked=\'checked\'":"")+">");
        tr.append(jQuery("<div>").append(jQuery("<label>").text("Уч. в поиске").append(c2.el.usesearch)));
		}
        c2.el.tr=tr;
        return tr;
    },
    removePr:function(el){
        var thisEl=this;
        var dtk=jQuery("<div></div>").append('<p>Внимание данное действие отменить не возможно!</p>').append("<p>Вы действительно хотите удалить "+((el.id_parent==0)?"эту группу свойств":"это свойство")+"?</p>");
		dtk.dialog({
            dialogClass: "no-close",
            title:"Удаление "+((el.id_parent==0)?"группы свойств.":"свойства."),
            width:"400px",
            buttons: [{
                text: "OK",
                click: function() {
                    delete el.el;
                    el.state=2;
                    jQuery.ajax({
                        url: './changeData',
                        data:"method=savePropHier&objSave="+jQuery.toJSON(el),
                        async:false,
                        cache:'false',
                        dataType: "json",
                        success: function(data) {

                        }});
                    jQuery('#propertyCat').empty();
                    thisEl.loadPropGoods(thisEl.curCat);
                    $( this ).dialog( "close" );
                }
            },
                {
                    text: "Cancal",
                    click: function() {
                        $( this ).dialog( "close" );
                    }
                }]
        });
    },
    redactPr:function(el){
        var thisEl=this;
        var c2el=cloneObject(el);
        var tel=this.addPropInGroupElD(c2el);
        tel.dialog({
            dialogClass: "no-close",
            title:(el.id==-1)?"Новое свойство":"Редактирование свойства",
            width:"400px",
            buttons: [{
                text: "OK",
                click: function() {
                    var prRoot=c2el;
                    var pr=cloneObject(c2el);
                    delete pr.el;
                    thisEl.setValuePrH(pr,'name',prRoot.el.name.val());
                    if(pr.id_parent!=0){
                        thisEl.setValuePrH(pr,'uper',prRoot.el.uper.val());
                        thisEl.setValuePrH(pr,'downer',prRoot.el.downer.val());
                        thisEl.setValuePrH(pr,'inners',prRoot.el.inners.val());
                        thisEl.setValuePrH(pr,'type',prRoot.el.listT.val());
                        if(prRoot.el.listLL!=null){
                            pr.id_linklist=prRoot.el.listLL.val();
                        }
                        thisEl.setValuePrH(pr,'usesearch',prRoot.el.usesearch.prop('checked'));
                    }
                    jQuery.ajax({
                        url: './changeData',
                        data:"method=savePropHier&objSave="+jQuery.toJSON(pr),
                        async:false,
                        cache:'false',
                        dataType: "json",
                        success: function(data) {

                        }});
                    jQuery('#propertyCat').empty();
                    thisEl.loadPropGoods(thisEl.curCat);
                    $( this ).dialog( "close" );
                }
            },
                {
                    text: "Cancal",
                    click: function() {
                        $( this ).dialog( "close" );
                    }
                }]
        });
    },
    setValuePrH:function(pr,namePr,val,prs){
        if(val!=pr[namePr]){
            if(prs!=null){pr.state=prs;}
            pr[namePr]=val;
        }
    },
    bildLabelCat:function(par){
        var labelCat=jQuery('#labelCat');
        labelCat.empty();
        labelCat.append('<div>Показывать</div>');
        this.selPr='<select>';
        for(var i=0;i<this.selTer.listPr.length;i++){
            if(this.selTer.listPr[i].id_parent!=0){
                this.selPr+='<option value='+this.selTer.listPr[i].id+'>'+this.selTer.listPr[i].name+'</option>';
            }
        }
        this.selPr+='</select>';
        if(par!=null)
            this._bildLabelCat(par,labelCat,7);
    },
    _bildLabelCat:function(par,labelCat,p){
        for(var i=0;i<par.length;i++){
            for (var key in par[i]) {
                switch(key){
                    case 'arrK':labelCat.append('<div style="padding-left:'+p+'px">Показать если все переменные не пустые</div>');this._bildLabelCat(par[i][key],labelCat,p+7); break;
                    case 'arrB':labelCat.append('<div style="padding-left:'+p+'px">Размещает элементы через запятую</div>');this._bildLabelCat(par[i][key],labelCat,p+7); break;
                    case 'string':labelCat.append('<div style="padding-left:'+p+'px">'+key+':'+par[i][key]+'</div>');break;
                    default: var selEl=jQuery(this.selPr); selEl.find('[value='+par[i][key]+']').attr("selected","selected"); labelCat.append(jQuery('<div style="padding-left:'+p+'px">'+key+':'+'</div>').append(selEl));break;
                }
            }
        }
    }
}

function cloneObject(o) {
    if(!o || "object" !== typeof o)  {
        return o;
    }
    var c = "function" === typeof o.pop ? [] : {};
    var p, v;
    for(p in o) {
        if(o.hasOwnProperty(p)) {
            v = o[p];
            if(v && "object" === typeof v) {
                c[p] = cloneObject(v);
            }
            else c[p] = v;
        }
    }
    return c;
}

var t=new LoaderCat();
</script>
