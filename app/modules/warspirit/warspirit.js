var Class={create:function(){return function(){this.initialize.apply(this, arguments);}}}
Object.extend = function(d,s){for (var property in s) {d[property] = s[property];}return d;}

var WinPanelS=Class.create();
WinPanelS.prototype = {
    initialize: function(){
         this.rootURL="http://phinc.ru/modules/warspirit/";
         this.imURL=this.rootURL+"game/warspirit/images/";
    },
    show:function(place,title,content,option){
       var d=jQuery('<div style="position:absolute; top:'+option.t+'px;left:'+option.l+'px;"><img src="'+this.imURL+'win.png" height='+option.h+' width='+option.w+'></div>').append(jQuery('<img src="'+this.imURL+'head.png" height=154 width=151 style="position:absolute; top:15px;left:15px;">')).append(jQuery('<div style="position:absolute;left:160px;top:10px; width:'+(option.w-170)+'px; height:'+(option.h-20)+'px;color:white;font:18px comic Sans MS;"></div>').append(content));       
       d.hide();
       place.append(d);
       return d;
    }
}


var WinPanel=Class.create();
WinPanel.prototype = {
    initialize: function(){
         this.rootURL="http://phinc.ru/modules/warspirit/";
         this.imURL=this.rootURL+"game/warspirit/images/";
    },
    show:function(place,title,content,option){
       var d=jQuery('<div style="position:absolute; top:0;left:0;"><nobr><img src="'+this.imURL+'topBord01.png" height=24 width=44><img src="'+this.imURL+'topBord02.png" height=24 width='+(option.w-47)+'><img src="'+this.imURL+'topBord03.png" height=24 width=3></nobr><div style="position:absolute;left:44px;top:4px; width:'+(option.w-44)+'px;text-align:center;">'+title+'</div></div>');
       var c=jQuery('<div style="height:'+(option.h-24)+'px;border:1px solid gray;background:#999999;margin-top:-3px;owerflow:auto;"></div>').css({'opacity':'0.6'});
       var e=jQuery('<div style="position:absolute; top:24px;left:0; height:'+(option.h-25)+'px;width:'+option.w+'px;background:none;owerflow:auto;"></div>').append(content);
       d.append(c).append(e);
       var t= jQuery('<div></div>').css({'position':'absolute','left':option.l+'px','top':option.t+'px','width':option.w+'px','height':option.h+'px','color':'#dddddd'}).append(d);
       
       place.append(t);
    }
}

var WarSpiritGame=Class.create();
WarSpiritGame.prototype = {
    initialize: function(){
        this.getUser();
        this.place=jQuery('#contentForWarSpirit').css({'position':'relative','width':'800px','height':'800px','background':'#beb5b1'});
        this.rootURL="http://phinc.ru/modules/warspirit/";
        this.imURL=this.rootURL+"game/warspirit/images/";
       // this.messr=new Messanger(this);
    },
    getUser:function(){
        this.user=null;
        var thisEl=this;
        this.f=function(json){
            if(json.error){
                if(json.error=='003') this.showErrorGame('Ошибка','Для доступа в игру необходима авторизация пользователя на сайте');
                if(json.error=='004') this.showErrorGame('Ошибка','Простите в данное время сервер недоступен, попробуйте зайти через пару минут');
                return;
            }
            this.user=json;  
             jQuery.ajax({
             type: "GET",
             url: "/warspiritgamedata",
             data: "method=getData&data={\"data\":\"getDialog\"}",
             success: function(msg){
			  //  alert(msg);
                thisEl.f2(jQuery.parseJSON(msg));
             }
           });
        }
	this.f2=function(json){
	   this.showWindSelectGame(json);
	}
        jQuery.ajax({
            type: "GET",
            url: "/warspiritgamedata",
            data: "method=getData&data={\"data\":\"getUser\"}",
            success: function(msg){
                thisEl.f(jQuery.parseJSON(msg));
            },
            error: function(){
                this.showErrorGame('Ошибка','Связи, автоматическое повторение запроса произойдет через секунду.');
                thisEl.getUser();
            }
        });
	
         
        

      //  jQuery.ajax({
      //      type: "GET",
      //      url: "/warspiritgamedata",
      //      data: "method=getData&data={\"data\":\"setValue\",\"property\":\"dialog\",\"value_type\":\"1\",\"type_class\":\"game_dialog\",\"value\":\"<br> О док $name!<br> Добро пожаловать на базу, мы еще вчера вас ждали.<br><br>\"}",
      //      success: function(msg){
      //          thisEl.f(jQuery.parseJSON(msg));
      //      }
      //  });
    },
    searchUser:function(funct){ 
        var thisEl=this;
        jQuery.ajax({
            type: "GET",
            url: "/warspiritgamedata",
            data: "method=getData&data={\"data\":\"serchGame\"}",
            success: function(msg){
                arr=jQuery.parseJSON(msg);
                thisEl.listActiveGame.empty();
                for(var i=0; i<arr.length;i++){ 
                   var usA=arr[i];
                   var us=jQuery('<tr><td>'+usA.name+'</td><td></td><td></td></tr>');
                   thisEl.listActiveGame.append(us);
                }  
                if(funct)funct(); 
            }
        });
    },
    nextDialog:function(){
       var thisEl=this;
       jQuery.ajax({
             type: "GET",
             url: "/warspiritgamedata",
             data: "method=getData&data={\"data\":\"nextDialog\"}",
             success: function(msg){
                thisEl.f2(jQuery.parseJSON(msg));
             }
           });
    },
	showWindSelectGame:function(text){
	    var thisEl=this;
            this.buildWin(); 
	    var but=jQuery("<span style='cursor:pointer;padding-left:200px;color:DarkGray;'>Дальше</span>");		 
	    text=text[0].value.replace("$name",this.user.name);		 
	    var cont=jQuery('<div style=" width:90%;margin:0 auto;"></div>').append(text).append(but);
            var op=new Object();op.l=140;op.t=240;op.w=500;op.h=200;        
            var p=new WinPanelS(); var t=p.show(this.place,'',cont, op); t.fadeIn('slow'); 
	    but.click(function(){
		    t.fadeOut('slow');
                    thisEl.nextDialog();
	    });
	},
    showWindSelectGame2:function(){
        var thisEl=this;
        this.buildWin();  
        var butCrGame=jQuery('<img src="'+this.imURL+'sgm.png" style="width:30px;height:30px;cursor:pointer;" title="Создать игру">');
        var butUpdate=jQuery('<img src="'+this.imURL+'updm.png" style="width:30px;height:30px;cursor:pointer;" title="Обновить">');
        butUpdate.click(function(){
                          butUpdate.attr('src',thisEl.imURL+'updm_push.png');
                          thisEl.searchUser(function(){butUpdate.attr('src',thisEl.imURL+'updm.png');});});  
        butCrGame.click(function(){
         butCrGame.attr('src',thisEl.imURL+'sgm_push.png');
         jQuery.ajax({
            type: "GET",
            url: "/warspiritgamedata",
            data: "method=getData&data={\"data\":\"startGame\"}",
            success: function(msg){                
                thisEl.searchUser();
                butCrGame.attr('src',thisEl.imURL+'sgm.png');
            }
          });
        });
        var butNextHod=jQuery('<img src="'+this.imURL+'updm.png" style="width:30px;height:30px;cursor:pointer;" title="Слудующий ход">');       
        butNextHod.click(function(){ 
         jQuery.ajax({
            type: "GET",
            url: "/warspiritgamedata",
            data: "method=getData&data={\"data\":\"getCurentRes\"}",
            success: function(msg){                
                alert(msg);
            }
          });
        });
        this.listActiveGame=jQuery('<tbody style="background:lightGrey;color:black;"></tbody>');     
        var s=jQuery('<table width="100%" bgcolor="gray" border="1" style="margin-top:7px;"><thead><tr><td width=60%>Имя</td><td width=30%> ... </td><td width=10%>Действие</td></tr></thead></table>').append(this.listActiveGame);
        var cont=jQuery('<div style=" width:90%;margin:0 auto;"></div>').append(butCrGame).append(butUpdate).append(butNextHod).append(jQuery('<br>')).append(s);
        var op=new Object();op.l=30;op.t=100;op.w=460;op.h=300;        
        var p=new WinPanel();p.show(this.place, 'Список желающих сыграть', jQuery('<br>').after(cont) ,op);  
        this.searchUser(); 
        this.messr.show();
    },
    showErrorGame:function(title,error){
        this.buildWin();
        var op=new Object();op.l=145;op.t=100;op.w=510;op.h=300;        
        var p=new WinPanel();p.show(this.place, title, error,op);  
    },
    buildWin:function(){
        this.place.empty();
        this.place.append('<img src="'+this.imURL+'bgG2.jpg" style="position:absolute;top:0;left:0;width:100%;height:100%">');
        var t1=jQuery('<div><img src="'+this.imURL+'topTogle.png" width=800 height=45></div>').css({'position':'absolute','left':'0','top':'0'});
        var t3=jQuery('<div><img src="'+this.imURL+'buttomTogle.png" width=800 height=67></div>').css({'position':'absolute','left':'0','top':'733px'});
        this.place.append(t1).append(t3);
        var t2=jQuery('<div style="font-weight:bold;">War Spirits!</div>').css({'position':'absolute','left':'350px','top':'10px','color':'#dddddd'});
        this.place.append(t2);
    }
}

var Messanger=Class.create();
Messanger.prototype = {
    initialize: function(owner){
        this.owner=owner;
        this.rootURL="http://phinc.ru/modules/warspirit/";
        this.imURL=this.rootURL+"game/warspirit/images/";
    },
	getListMFU:function(user,key){	
        var thisEl=this;	
		var t=",\"dataMOb\":{\"user_out\":\""+user.uid+"\"}";
		jQuery.ajax({
            type: "GET",
            url: "/warspiritgamedata",
            data: "method=getData&data={\"data\":\"messanger\",\"dataM\":\"getListMFU\""+t+"}",
            success: function(msg){ 
                var arr=jQuery.parseJSON(msg); 
				if(!key)thisEl.winDialog.textMess.empty();
				for(var i=0; i<arr.length;i++)
				  thisEl.winDialog.textMess.append(jQuery('<span style="font-weight:bold;color:red">'+arr[i].date+'</span><br><span style="color:blue">'+arr[i].message+'</span><br>'));
				          
            }
          });		
	},
    getListMessageNR:function(key){
	    var t=",\"dataMOb\":\"";
		if(key) t=t+"1\"";
		else t=t+"0\"";
        var thisEl=this;
        jQuery.ajax({
            type: "GET",
            url: "/warspiritgamedata",
            data: "method=getData&data={\"data\":\"messanger\",\"dataM\":\"getLMessageNR\""+t+"}",
            success: function(msg){ 
                var arr=jQuery.parseJSON(msg); 
                for(var i=0;i<arr.length;i++){
				  if(thisEl.winDialog!=null && thisEl.winDialog.user!=null && arr[i]==thisEl.winDialog.user.uid){thisEl.getListMFU(thisEl.winDialog.user,true);}else{
				  var el=jQuery("#userMim_"+arr[i]);
				  el.empty();
				  el.append('<img src="'+thisEl.imURL+'inmessage.gif" style="cursor:pointer; width:10%; height:10%;" title="Входящее сообщение">');
				  }
				}
                thisEl.delayMessage();             
            }
          });
    },
    //в этом методе еще нужно заложить метод обработки ошибки сервера или обрыва связи
    delayMessage:function(){ 
        var thisEl=this;
        jQuery.ajax({
            type: "GET",
            url: "/warspiritgamedata",
            data: "method=getData&data={\"data\":\"messanger\",\"dataM\":\"delayMessage\"}",
            success: function(msg){ 
                var mess=jQuery.parseJSON(msg);               
                if(mess.message!="0") thisEl.getListMessageNR(); else  thisEl.delayMessage();
             },
	     error :function(){ thisEl.delayMessage();}
          });
    },
    getListUser:function(){	
        var thisEl=this;   
        var tf=function(us,usA){
		    return function(){
			      us.click(function(){
				        jQuery(this).find("img").remove();
						thisEl.winDialog.user=usA;	
                        thisEl.openWinDialog(this);						
						thisEl.getListMFU(usA);                        					
                   });} 
		}		
        jQuery.ajax({
            type: "GET",
            url: "/warspiritgamedata",
            data: "method=getData&data={\"data\":\"messanger\",\"dataM\":\"getListUser\"}",
            success: function(msg){
                arr=jQuery.parseJSON(msg);
                thisEl.lUser.empty();
                for(var i=0; i<arr.length;i++){ 
                   var usA=arr[i];
                   var us=jQuery('<div style="cursor:pointer;" id="userM_'+usA.uid+'"><span id="userMim_'+usA.uid+'"></span>&nbsp;'+usA.name+'</div>');
                   us.mouseover(function(){
                        jQuery(this).css({"font-size":"150%"});
                   });
                   us.mouseout(function(){
                        jQuery(this).css({"font-size":"100%"});
                   });	
                   tf(us,usA)();
                   thisEl.lUser.append(us);
                }
                thisEl.winDialog();    
            }
        });
    },
    showUserWin:function(){
        var thisEl=this;
        this.lUser=jQuery('<div style="width:90%; height:250px; overflow:auto; margin:0 auto;"></div>')
        this.getListUser();
        var op=new Object();op.l=550;op.t=100;op.w=210;op.h=300;        
        var p=new WinPanel();p.show(this.owner.place, 'Список пользователей', jQuery('<br>').after(this.lUser) ,op);        
    },
   winDialog:function(){
        var thisEl=this;
        this.winDialog=new Object();
        this.winDialog.user=null;
        this.winDialog.butSend=jQuery('<img src="'+this.imURL+'sendM.png" style="cursor:pointer;padding-left:500px;" title="Отправить">');
        this.winDialog.butSend.click(function(){thisEl.sendMessage();});
	this.winDialog.textMess=jQuery('<div style="margin:0 auto;width:90%; height:100px;overflow:auto; background:#ffffff;"></div>');
        this.winDialog.textMess.css({"background":"lightgray"});
        this.winDialog.sendMess=jQuery('<textarea  rows=3 style="width:99%">');
        var t=jQuery('<div style="margin:0 auto;width:90%;"></div>').append(this.winDialog.sendMess);
        this.winDialog.els=jQuery('<div style="width:90%; margin:0 auto;"></div>');        
        this.winDialog.els.append(this.winDialog.textMess).append(t).append(this.winDialog.butSend);
        this.winDialog.els.css({'display':'none'});
        var op=new Object();op.l=30;op.t=450;op.w=730;op.h=270;        
        var p=new WinPanel();p.show(this.owner.place, 'Диалог', jQuery('<br>').after(this.winDialog.els) ,op); 
        this.getListMessageNR(true);
    },
    openWinDialog:function(el){
        this.winDialog.els.css({'display':''});
         
    },
    show:function(){
       this.showUserWin();        
    },
    sendMessage:function(){
	   var t=",\"dataMOb\":{\"user_out\":\""+this.winDialog.user.uid+"\",\"message\":\""+this.winDialog.sendMess.val()+"\"}";
        var thisEl=this;
        jQuery.ajax({
            type: "GET",
            url: "/warspiritgamedata",
            data: "method=getData&data={\"data\":\"messanger\",\"dataM\":\"sendMessage\""+t+"}",
            success: function(msg){ 
                var arr=jQuery.parseJSON(msg);
                thisEl.winDialog.textMess.append(jQuery('<span style="font-weight:bold;color:red">'+new Date()+'<span><br><span style="font-weight:bold;color:red">'+thisEl.winDialog.sendMess.val()+'<span><br>'));  
                thisEl.winDialog.sendMess.val("");				
            }
        });
    }	
}

