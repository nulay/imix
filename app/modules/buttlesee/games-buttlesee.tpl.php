<?php

/**
 * @file redact-procat.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess()
 * @see template_preprocess_redact_item_shop()
 */
global $user;
?>
<?php if($user->uid!=0) { ?>

<style>
.shipBah{
     background:url("/<?php print drupal_get_path('module', 'buttlesee'); ?>/images/shipBuh.gif") no-repeat gray;
}
.icShip.shipBah{
    background:url("/<?php print drupal_get_path('module', 'buttlesee'); ?>/images/shipBuh.gif") no-repeat gray;
}
.noBah{
    background:url("/<?php print drupal_get_path('module', 'buttlesee'); ?>/images/noBum.gif") no-repeat; 
}
.youMove{
	font-size: x-large;;
	color:red;
	text-decoration:blink;
	text-align:center;
}
.ui-bs-cell{
	width:40px;
	height:40px;
	float:left;
	display:block;
	border: 1px solid gray;
}
.endC{
	float:left;
}
.icShip{
	background:gray;
}
.contentBS{
	height:500px;
}
#buttlePlace{
	width:423px;
	cursor:pointer;
}

#buttlePlaceMy{
	width:423px;
	float:left;
}
#buttlePlaceEnemy{
	width:423px;
	cursor:pointer;
	float:left;
}
.selCellPl{
	border: 2px solid black;
    height: 38px;
    width: 38px;
}
.ui-num-a{
	float: left;
    font-size: large;
    height: 25px;
    text-align: center;
    width: 42px;
}
.ui-num-s{    
    width: 42px;	
	height:42px;
	text-align:right;
	vertical-aligne:middle;
	font-size:large;
}
#plnum1, #plnum2{
	float: left;
	padding-top: 37px;
	width: 40px;
	margin-right:10px;
}
#plalf1, #plalf2{
    height: 25px;
    width: 430px;
}
.blockPl{
	float:left;
}
.placebuttlesee{
	width: 960px;
	left: 50%;
    margin-left: -480px;
    position:relative;
 
}
</style>
<div class='contentBS'>
<!--Win for openGame-->
<div id='winOG' style='display:none;'>
   <div>Hello in ButtleSee</div>
   <div><button id='butNewGame'>Start New Game</button></div>
   <div id='openGameD' style='display:none;'>Open game: <select id='selOG'></select><button id='butPlayGame'>Play Game</button></div>
</div>
<!--Win for create place-->
<div id='winCrPl' style='display:none;'>
Create place and set ship in place
<div id='buttlePlace'></div>
<div><button id='butSavePlace'>Create game</button> <button id='selectInGame'>Connect to game</button></div>
</div>
<!--Win connect to game-->
<div id="contCTG" style='display:none;'>
<div id="loadData">load data...</div>
<div id="contentCTGW" style='display:none;'><div><select id='selopenGame' size=3 style="width:100%;"></select></div><div><button id='butconnectG'>Connect</button><button id='butrefrG'>Refresh</button></div></div>
</div>
<!--Win game-->
<div id='winGame' style='display:none;'>
<div style='height:40px;'>
<div style='text-align:center;'>Hello in game</div>
<div class='youMove' style='display:none;'>Your move!!!</div>
</div>
<div class='placebuttlesee'>
<div id='plnum1'></div>
<div class='blockPl'><div id='plalf1'></div><div id='buttlePlaceMy'></div></div>
<div id='plnum2'></div>
<div class='blockPl'><div id='plalf2'></div><div id='buttlePlaceEnemy'></div></div>
</div>
</div>

<!--Win connect to game-->
<div id="contCTG" style='display:none;'>
<div id="loadData">load data...</div>
<div id="contentCTGW" style='display:none;'><div><select id='selopenGame' size=3 style="width:100%;"></select></div><div><button id='butconnectG'>Connect</button><button id='butrefrG'>Refresh</button></div></div>
</div>
</div>


<script>
$(function(){
  bs=new ButtleSee();
});

var Class={create:function(){return function(){this.initialize.apply(this, arguments);}}}
Object.extend = function(d,s){for (var property in s) {d[property] = s[property];}return d;}

var ButtleSee=Class.create();
ButtleSee.prototype = {
    initialize: function(){           
		this.opgwin=new OpenGameWin(this);
		this.cplacewin=null;
		this.startGame();
		
	},
	startGame:function(){	    
		//заставка
		this.opgwin.showWin();				
	},
	openCreatePlace:function(data){	   
       if(this.cplacewin==null)
	       this.cplacewin=new CreatePlaceWin(this);
	   this.cplacewin.showWin(data);   
	},
	openGame:function(idGame,place,plgame){
		if(this.gameWind==null)
	       this.gameWind=new GameWind(this);
		this.gameWind.showWin(idGame,place,plgame);   
	}
}

//win create place
var GameWind=Class.create();
GameWind.prototype = {
    initialize: function(owner){ 
	   this.owner=owner;
	   this.winGame=$('#winGame');
	   
	   this.buttlePlaceMy=$('#buttlePlaceMy');
	   this.buttlePlaceEnemy=$('#buttlePlaceEnemy');
	   
	   this.myPlace=null;
	   this.listCellMy=null;
	   this.createPlace(this.buttlePlaceMy);
	   
	   this.enemyPlace=null;
	   this.listCellEnemy=null;	   
	   this.createPlace(this.buttlePlaceEnemy,true);
	   
	   this.youMove=$('.youMove');
	   
	   this.buttlePlaceEnemy.on('mouseover','.ui-bs-cell',function(){$(this).addClass('selCellPl');});
	   this.buttlePlaceEnemy.on('mouseout','.ui-bs-cell',function(){$(this).removeClass('selCellPl');});
	   this.createNum($('#plnum1'));this.createNum($('#plnum2'));
	   this.createAlf($('#plalf1'));this.createAlf($('#plalf2'));
	   this.cH=false;
	},
	showWin: function(idGame,place,plgame){	
        this.myPlace=place;	
		this.createPlaceInOb();
		if(plgame!=null){
		   this.enemyPlace=plgame
		   this.createPlaceInObE();
		}
		this.curIdGame=idGame;
		//this.createPlaceEnemy()
		this.winGame.show();
		var thisEl=this;
		setInterval(function(){thisEl.getDataOtherPl();},3000);
	},
	createPlaceInObE:function(){
		for(var i=0;i<10;i++){			
			for(var y=0;y<10;y++){
				var ob=this.listCellEnemy[i][y];				
				if(this.enemyPlace[i][y]==1){
					ob.cell.addClass('icShip');
				}else{
				    if(this.enemyPlace[i][y]==3){
					   ob.cell.addClass('noBah');
					}else{
					   if(this.enemyPlace[i][y]==4){
					       ob.cell.addClass('icShip shipBah');
					   }
					}
				}					
			}
		}
	},	
	createPlaceInOb:function(){
		for(var i=0;i<10;i++){			
			for(var y=0;y<10;y++){
				var ob=this.listCellMy[i][y];				
				if(this.myPlace[i][y]==1){
					ob.cell.addClass('icShip');
				}else{
				    if(this.myPlace[i][y]==3){
					   ob.cell.addClass('noBah');
					}else{
					   if(this.myPlace[i][y]==4){
					       ob.cell.addClass('icShip shipBah');
					   }
					}
				}					
			}
		}
	},
	createPlace:function(buttlePlace, keyEn){
		place=new Array();
		listCell=new Array();
		for(var i=0;i<10;i++){
			listCell[i]=new Array();
			place[i]=new Array();
			for(var y=0;y<10;y++){
				var ob=new Object();
				ob.cell=$('<div class="ui-bs-cell">');
				buttlePlace.append(ob.cell);
				if(y==9) ob.cell.addClass('endC');				
				listCell[i][y]=ob;
				place[i][y]=0;
				if(keyEn)ob.cell.click(this._clickInPlaceEn(i,y));                				
			}			
		}
        if(keyEn){
			this.enemyPlace=place;
			this.listCellEnemy=listCell;
		}else{
			this.myPlace=place;
			this.listCellMy=listCell;
		}
	},
	_clickInPlaceEn:function(i,y){
		var thisEl=this;
		return function(){thisEl.clickInPlaceEn(i,y);}
	},
    clickInPlaceEn:function(i,y){
		if(this.cH){		  		
		  var thisEl=this;
		  $.ajax({
		        url:'rgdatach',
		        type:'POST',
		        data:{"meth":"puh","gameId":thisEl.curIdGame,"pos":{"i":i,"y":y}},
		        dataType:'json',
		        async:false,
		        success:function(data){
					if(data){
					    var ob=thisEl.listCellEnemy[i][y];
					    if(data==4 | data==5){						  
						  ob.cell.addClass('shipBah');
						}
						if(data==3){
						  ob.cell.addClass('noBah'); 
						  thisEl.yourMove(false);                          						  
						}
						if(data==5){ 
                           if(thisEl.dPW3==null)
			                  thisEl.dPW3=$('<div>Ship gun....</div>').dialog({tytle:'Congratulaition!!!',buttons:{Ok:function(){$(this).dialog('close');}}});
			                  thisEl.dPW3.dialog('open');
                        }							  
					}else{
						if(thisEl.dPW2==null)
						   thisEl.dPW2=$('<div>Wait your go....</div>').dialog({tytle:'Wait!!!',buttons:{Ok:function(){$(this).dialog('close');}}});
                        thisEl.dPW2.dialog('open');						   
					}
			    }
		  });
		}else{
			if(this.dPW==null)
			     this.dPW=$('<div>please Wait....</div>').dialog({tytle:'Wait!!!',buttons:{Ok:function(){$(this).dialog('close');}}});
			this.dPW.dialog('open');	 
				 
		}
	},
	getDataOtherPl:function(){
		var thisEl=this;
		$.ajax({
		        url:'rgdatach',
		        type:'POST',
		        data:{"meth":"getpuh","gameId":thisEl.curIdGame},
		        dataType:'json',
		        async:true,
		        success:function(data){
					if(data){ 
					  if(data.hit!=null){					    
					     for(var i=0;i<data.hit.length;i++){
						    //data.hit[i].pos=$.parseJSON(data.hit[i].pos);
					        var ob=thisEl.listCellMy[data.hit[i].pos.i][data.hit[i].pos.y];
					        if(data.hit[i].typehit==4 | data.hit[i].typehit==5)ob.cell.addClass('shipBah');
					        if(data.hit[i].typehit==3) ob.cell.addClass('noBah');
                            thisEl.myPlace[data.hit[i].pos.i][data.hit[i].pos.y]=data.hit[i].typehit;
						 }
					  }
                      thisEl.yourMove(data.myhit);					  
					}
			    }
		});				
	},
	yourMove:function(key){
		this.cH=key;
		if(key){
		  this.youMove.show();
		}else{
          this.youMove.hide();		
		}		
	},
	createNum:function(place){
		for(var i=0;i<10;i++){
			place.append('<div class="ui-num-s">'+(i+1)+'</div>');
		}
	},
    createAlf:function(place){
		for(var i=0;i<10;i++){
			place.append('<div class="ui-num-a">&#'+(i+65)+';</div>');
		}
	}		
}

//win create place
var CreatePlaceWin=Class.create();
CreatePlaceWin.prototype = {
    initialize: function(owner){ 
	    this.owner=owner;
		this.winCrPl=$('#winCrPl');
		this.place=$('#buttlePlace');
		this.listCell=new Array();
		this.listCellD=new Array();
		this.createPlace();
		
		this.contCTG=$('#contCTG');
		this.loadData=$('#loadData');
		this.contentCTGW=$('#contentCTGW');
		this.selopenGame=$('#selopenGame');
		this.butconnectG=$('#butconnectG').click(function(){thisEl.connectToGame();});
		this.butrefrG=$('#butrefrG');
		
		var thisEl=this;
		this.butSavePlace=$('#butSavePlace').click(function(){thisEl.savePlace();});
		this.selectInGame=$('#selectInGame').click(function(){thisEl.goingame();});
	},
	createPlace:function(){
		for(var i=0;i<10;i++){
			this.listCell[i]=new Array();
			this.listCellD[i]=new Array();
			for(var y=0;y<10;y++){
				var ob=new Object();
				ob.cell=$('<div class="ui-bs-cell">');
				this.place.append(ob.cell);
				if(y==9) ob.cell.addClass('endC');				
				this.listCell[i][y]=ob;
				this.listCellD[i][y]=0;
				ob.cell.click(this.clickInPlace(i,y));                				
			}			
		}
		$('#buttlePlace').on('mouseover','.ui-bs-cell',function(){$(this).addClass('selCellPl');});
		$('#buttlePlace').on('mouseout','.ui-bs-cell',function(){$(this).removeClass('selCellPl');});
	},
	showWin:function(data){	   
	    var place=null;
	    if(data!=null){
            place=$.parseJSON(data.place);
		}		
	    for(var i=0;i<10;i++){
		    for(var y=0;y<10;y++){
			    var ob=this.listCell[i][y];
				if(place!=null){
				    this.listCellD[i][y]=place[i][y];
				    if(place[i][y]==0){
					   if(ob.cell.hasClass('icShip')) ob.cell.removeClass('icShip');
					}else{
					   if(!ob.cell.hasClass('icShip')) ob.cell.addClass('icShip');
					}
				}else{
				    this.listCellD[i][y]=0;
					if(ob.cell.hasClass('icShip')) ob.cell.removeClass('icShip');
				}				
			}
		}
		this.winCrPl.show();
		if(data.idgame!=null) this.showWaitDialog(data.idgame);
	},	
	showWaitDialog:function(idgame){
		var thisEl=this;
		thisEl.curidGame=idgame;
		if(this.waitDialog==null){
	      var butCancCrGame=$('<button>Cancal create game</button>');	
          this.waitDialog=$('<div>');		  
		  this.waitDialog.append($('<div style="position:absolute; z-index:1000; left:0;top:0; text-align:center; width:'+$(document).width()+'px;height:'+$(document).height()+'px;background:#000;">').css('opacity','0.4'));
		  this.waitDialog.append($('<div style="position:absolute; z-index:1001; left:0;top:0; text-align:center; width:'+$(document).width()+'px;height:'+$(document).height()+'"><div><img src="/<?php print drupal_get_path('module', 'buttlesee'); ?>/images/wait.gif"></div></div>').append($('<div align="center">').append(butCancCrGame)));
		  $('.contentBS').append(this.waitDialog);
		  
		  butCancCrGame.click(function(){
			$.ajax({
		        url:'rgdatach',
		        type:'POST',
		        data:{"meth":"canccreateGame","gameId":thisEl.curidGame},
		        dataType:'json',
		        async:false,
		        success:function(data){
					if(data)
					   thisEl.waitDialog.hide();
					else{
						thisEl.waitDialog.hide();
						thisEl.closeWin();
                       thisEl.owner.openGame(thisEl.curidGame,thisEl.listCellD);					
					}
			    }
		    });
		  });
		}
		this.waitDialog.show();
		this.waitConnect(idgame);
	},
	clickInPlace:function(i,y){
		var thisEl=this;
		return function(){
			var t=thisEl.listCell[i][y];
			if(t.cell.hasClass('icShip')){
			  t.cell.removeClass('icShip');
			  t.isShip=0;
			}else{
			  t.cell.addClass('icShip');
			  t.isShip=1;
			}
			thisEl.listCellD[i][y]=t.isShip;
		}
	},
	savePlace:function(){
		var thisEl=this;
		$.ajax({
			url:'rgdatach',
			type:'POST',
			data:{"meth":"createGame","dataplace":$.toJSON(thisEl.listCellD)},
			dataType:'json',
			async:false,
			success:function(data){
			    thisEl.showWaitDialog(data);
			}
		});
	},
	waitConnect:function(idGame){
	    var thisEl=this;
		thisEl.curidGame=idGame;
	    $.ajax({
			url:'rgdatach',
			type:'POST',
			data:{"meth":"connectGet","id":idGame},
			dataType:'json',
			async:false,
			success:function(data){
			    if(data){
				   thisEl.waitDialog.hide();
				   thisEl.closeWin();
                   thisEl.owner.openGame(thisEl.curidGame,thisEl.listCellD);
				}
				else setTimeout(function(){thisEl.waitConnect(idGame)},3000);
			}
		});
	},
	goingame:function(){
		var thisEl=this;
		if(this.dialogDGiG==null){
		   this.dialogDGiG=this.contCTG.dialog({			
			   buttons:{
				   Close:function(){$(this).dialog('close');}
			   },
			   title: 'Select game',
			   open: function(event, ui) {				  				   
				   thisEl.loadOpenGame();
			   }
			});		
		    this.selopenGame.change(function(){thisEl.butconnectG.attr('disabled',false);});
		    this.butrefrG.click(function(){thisEl.loadOpenGame();});
		}
		this.dialogDGiG.dialog('open');
	},
	loadOpenGame:function(){
		var data=null;
		this.contentCTGW.hide();
		this.loadData.show();		
		this.butconnectG.attr('disabled',true);
		this.selopenGame.empty();
		$.ajax({
			url:'rgdatach',
			type:'POST',
			data:{"meth":"getOpenGame"},
			dataType:'json',
			async:false,
			success:function(dataR){
				 data=dataR;
			}
		});
		this.loadData.hide();
		this.contentCTGW.show();
		for(var i=0;i<data.length;i++){
		    this.selopenGame.append('<option value="'+data[i].idgame+'">'+data[i].name+'</option>');			        	   			
		}
		this.butrefrG.attr('disabled',false);
		return data; 
	},
	connectToGame:function(){
		var thisEl=this;
		$.ajax({
			url:'rgdatach',
			type:'POST',
			data:{"meth":"connectToGame","gameId":thisEl.selopenGame.val(),"dataplace":$.toJSON(thisEl.listCellD)},
			dataType:'json',
			async:false,
			success:function(dataR){
				 if(!dataR)
				    alert('Ic game closed');
			     else{
                    thisEl.owner.openGame(thisEl.selopenGame.val(),thisEl.listCellD);
					thisEl.dialogDGiG.dialog('close');
					thisEl.closeWin();
                 }					
			}
		});
	},
	closeWin:function(){
		this.winCrPl.hide();		
	}
}

//win open game
var OpenGameWin=Class.create();
OpenGameWin.prototype = {
    initialize: function(owner){ 
	   this.owner=owner;
	   this.winOG=$('#winOG');
	   this.openGameD=$('#openGameD');
	   this.butNewGame=$('#butNewGame');
       this.selOG=$('#selOG');	 
       this.butPlayGame=$('#butPlayGame');	 	   
	},
	showWin:function(){
	   this.buildWin();
	},
	buildWin:function(){
	    this.winOG.show();
		this.dataS=null;
		var dataS=null;
	    $.ajax({
			url:'rgdatach',
			type:'POST',
			data:{"meth":"getGame"},
			dataType:'json',
			async:false,
			success:function(data){
			    dataS=data;							
			}
		});
		this.dataS=dataS;
		if(dataS.arrG.length!=0){
			for(var i=0;i<dataS.arrG.length;i++){			   
			   this.selOG.append('<option value='+i+'>Game '+(i+1)+'</option>');
			}
			this.openGameD.show();
        }	
        var thisEl=this;
		this.butNewGame.click(function(){thisEl.closeWin();thisEl.owner.openCreatePlace(null);});	
        this.butPlayGame.click(function(){var n=thisEl.selOG.val();thisEl.closeWin();n=thisEl.dataS.arrG[n]; if(n.type==null || n.type=='1')thisEl.owner.openCreatePlace(n);else thisEl.owner.openGame(n.idgame,$.parseJSON(n.place),$.parseJSON(n.plgame));});		
	},
	closeWin:function(){
	    this.openGameD.hide();
		this.selOG.empty();
		this.winOG.hide();
	}
}

</script>

<?php }else{?>
    <div>
           For start game plese registration.		   
   </div>
<?php }?>