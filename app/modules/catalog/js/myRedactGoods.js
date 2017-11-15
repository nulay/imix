var Class={create:function(){return function(){this.initialize.apply(this, arguments);}}}
Object.extend = function(d,s){for (var property in s) {d[property] = s[property];}return d;}

var LoaderCat=Class.create();
LoaderCat.prototype = {
    initialize: function(){
        
	}
}


$(document).ready(function() {	
	$('#loadData').change(loadFullListCompany);
	//$('#id_catalog').combobox();
});
		
var selM=null;	
var listCompanyDD=null;
function loadFullListCompany(){
    if(listCompanyDD!=null){ dfP(); return;}	
	$.ajax({
            url: '/catalog/admin/changeData',
			type:'POST',
            data:{"method":"getListAllCompany"},
            async:false,
			dataType: "json",
			success:function(data){				
				listCompanyDD=data;
			    bildSEl();
				dfP();
            }});
}

function dfP(){	     			
    var sel=$('#id_catalog');	
    if(selM==null) selM=sel.html();		
	sel.empty();
	if(!$('#loadData')[0].checked)
	    sel.html(selM);
	else
        sel.html(listCompanyDD);		
}

function bildSEl(){
	var strO='<option value=""></option>';
	for(var i=0;i<listCompanyDD.length;i++){
		strO+='<option value="'+listCompanyDD[i].id+'">'+listCompanyDD[i].name+'</option>';
	}
	listCompanyDD=strO;
}

/*
(function( $ ) {
    $.widget( "ui.combobox", {
        _create: function() {
            var self = this,
                select = this.element.hide(),
                selected = this.selected = select.children( ":selected" ),
                value = selected.val() ? selected.text() : "";
            var input = this.input = $( "<input>" )
                .insertAfter( select )
                .val( value )
                .autocomplete({
                    delay: 0,
                    minLength: 0,
                    source:function( request, response ) {
                        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
                        response( select.children( "option" ).map(function() {
                            var text = $( this ).text();
                            if ( this.value && ( !request.term || matcher.test(text) ) )
                                return {
                                    label: text.replace(
                                        new RegExp(
                                            "(?![^&;]+;)(?!<[^<>]*)(" +
                                                $.ui.autocomplete.escapeRegex(request.term) +
                                                ")(?![^<>]*>)(?![^&;]+;)", "gi"
                                        ), "<strong>$1</strong>" ),
                                    value: text,
                                    option: this
                                };
                        }) );
                    },
                    select: function( event, ui ) {
                        ui.item.option.selected = true;
                        self._trigger( "selected", event, {
                            item: ui.item.option
                        });
                    },
					search: function( event, ui ) {/*
						 var str=$(this).val();						
						$.ajax({url: '/catalog/admin/changeData',
            data:{"method":"getListCompanyByEx","str":str},
            async:false,
			dataType: "json",
			success:function(data){
			//	alert('333');
                //data=jQuery.parseJSON(data); 
				listCompany=data;
				
                var sel=$('#id_catalog');			
				sel.empty();
				for(var i=0;i<listCompany.length;i++){
					sel.append('<option value="'+listCompany[i].id+'">'+listCompany[i].name+'</option>');
				}
            }});	*/	/*			
					},
                    change: function( event, ui ) {
                        if ( !ui.item ) {
                            var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( $(this).val() ) + "$", "i" ),
                                valid = false;
                            select.children( "option" ).each(function() {
                                if ( $( this ).text().match( matcher ) ) {
                                    this.selected = valid = true;
                                    return false;
                                }
                            });
                            if ( !valid ) {
                                // remove invalid value, as it didn't match anything
                                $( this ).val( "" );
                                select.val( "" );
                                input.data( "autocomplete" ).term = "";
                                return false;
                            }
                        }
                    }
                })
                .addClass( "ui-widget ui-widget-content ui-corner-left" );

            input.data( "autocomplete" ).menu.element[0].style.maxHeight=400;
            input.data( "autocomplete" ).menu.element[0].style.overflowY="auto";
            input.data( "autocomplete" ).menu.element[0].style.overflowX="hidden";
            input.data( "autocomplete" ).menu.element[0].style.paddingRight="20px";
            input.data( "autocomplete" )._renderItem = function( ul, item ) {
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>" + item.label + "</a>" )
                    .appendTo( ul );
            };

            this.button = $( "<button type='button'></button>" )
                .attr( "tabIndex", -1 )
                .attr( "title", "Show All Items" )
                .attr( "style", "vertical-align:top;height:"+this.input.outerHeight(true)+"px;" )
                .insertAfter( input )
                .button({
                    icons: {
                        primary: "ui-icon-triangle-1-s"
                    }
                })
                .removeClass( "ui-corner-all" )
                .addClass( "ui-corner-right ui-button-icon" )
                .click(function() {
                    // close if already visible
                    if ( input.autocomplete( "widget" ).is( ":visible" ) ) {
                        input.autocomplete( "close" );
                        return;
                    }

                    // work around a bug (likely same cause as #5265)
                    $( this ).blur();

                    // pass empty string as value to search for, displaying all results
                    input.autocomplete( "search", "" );
                    input.focus();
                });
        },
        destroy: function() {
            this.input.remove();
            this.button.remove();
            this.element.show();
            $.Widget.prototype.destroy.call( this );
        },
        setDisabled:function(key){
            if(key){
                this.input.attr("disabled",true);
                this.button.attr("disabled",true);
                this.input.val("");
                this.element.prop('selectedIndex',0);
                this.selected = this.element.children( ":selected" );
//                this.input.attr("style","background:url(\"images/ui-icons_ffd27a_256x240.png\") repeat-x scroll 50% 50%");
//                this.button.attr("style","background:url(\"images/ui-bg_gloss-wave_45_817865_500x100.png\") repeat-x scroll 50% 50% #817865");
            }else{
                this.input.attr("disabled",false);
                this.button.attr("disabled",false);
            }
        },
        setValue:function(dat){
            if(dat==null) dat="null";
            var selOpt=this.element.find('[value='+dat+']');
            selOpt.attr('selected',true);
            this.input.val(selOpt.text());
        },
        setFocus:function(){
            this.input.focus();
        },
        change:function(){

        }
    });
})( jQuery );
*/