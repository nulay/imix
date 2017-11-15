// JavaScript Document
var LoadingPan=Class.create();
LoadingPan.prototype = {
    initialize: function(options){
        var o=new Object(); if(!options) options=o;
        o.cP=options.cP || 2;o.cS=options.cS || 2;o.q=options.q || 5;o.cB=options.cB || "#72d4da";o.cQ=options.cQ || "#d26099";
        o.cC=options.cC || "#666";o.count=options.count || 5;o.dalay=options.dalay || 200;
        this.option=o;
        this.el = new Array();
        this.intl=null;
        this.curEl=-1;
    },
    _buildP:function(){
        var elTR=new Element('tr',{style:'background:'+this.option.cB+';font-size:1px;'});
        var p=new Element('table',{border:'0',cellpadding:this.option.cP,cellspacing:this.option.cS}).
                insert(elTR);
        for(var i=0;i<this.option.count;i++){
            this.el[i]=new Element('td').insert(new Element('div',{style:'background:'+this.option.cQ+';width:'+this.option.q+'px;height:'+this.option.q+'px;'}).insert('&nbsp;'));
            elTR.insert(this.el[i]);
        }
        return p;
    },
    start:function(){
        if(this.intl==null){
            var thisEl=this;
            this.intl=setInterval(function(){
                if(thisEl.curEl!=-1)thisEl.el[thisEl.curEl].style.backgroundColor='';
                if(thisEl.curEl==thisEl.el.size()-1) thisEl.curEl=0; else thisEl.curEl++;
                thisEl.el[thisEl.curEl].style.backgroundColor=thisEl.option.cC;
            },this.option.dalay);
        }
    },
    stop:function(){
        if(this.intl!=null){clearInterval(this.intl); this.intl=null;
            if(this.curEl!=-1)this.el[this.curEl].style.backgroundColor='';this.curEl=-1;}
    }
}

var ImLoading=Class.create();
ImLoading.prototype = {
    initialize: function(owner,nameDir,countIm,nameGal,numPage){
        this.pS=getPageSize();
        var thisEl=this;
        this.owner=owner;
        this.nameDir=nameDir;
        this.countIm=countIm;
        this.countPage=Math.ceil(this.countIm/25);
        this.numPage=(numPage)?numPage:1;
        this.countImTP=this.countIm-((this.numPage-1)*25);
        this.nameGal=nameGal;
        if(this.countImTP>25)this.countImTP=25;
        this.fotoPanel=null;
        try {
            this.fotoPanel = $('fotoPanel');
            this.fotoPanel.update();
        }catch(ex){
            this.fotoPanel=new Element('div',{id:'fotoPanel'});
            this.owner.insert(this.fotoPanel);
        }
        this.fotoFrame=new Element('div').addClassName('fotoFrame centrPanelRP');
        this.fotoFrame.insert('&nbsp;&nbsp; '+nameGal+' >>');
        this.blockIm=new Element('div');
        this.fotoFrame.insert(this.blockIm);
        this.fotoPanel.insert(this.fotoFrame);
        this.imRed=new Element('div',{style:'position:absolute; left:0; top:0; z-index:3; display:none; text-align:center; background:black; filter:alpha(opacity=50); opacity: 0.5;'});
        this.fotoPanel.insert(this.imRed);
        this.owner.style.height=this.pS.windowHeight-20+"px";
        this.pS2=getPageSize(this.owner);
        this.panelSlideIm=new Element('div',{style:'position:absolute; left:0; top:0; z-index:5; display:none; text-align:center;'}).addClassName('panelSlideIm');
        this.imRed2=new Element('div',{style:'position:absolute; left:0; top:0; z-index:4; display:none; text-align:center;'});
        this.fotoPanel.insert(this.imRed2);
        this.fotoPanel.insert(this.panelSlideIm);

        this.panelSlideIm.style.top=this.pS2.windowHeight-60+"px";
        this.panelSlideIm.height=60+"px";

        this.panTool=null;
        this.curIm=null;
        this.curImage=null;
        this.currentImage=null;
        this.largeImage=null;

        this.uvelichIm=new Element('img',{width:'150px',height:'100px'}).addClassName('imageBorder');
        this.bodyUIm=new Element('div',{style:'position:absolute;left:0px;top:0px;z-index:10;display:block;'}).insert(this.uvelichIm).hide();
        this.fotoPanel.insert(this.bodyUIm);
        Element.observe(this.uvelichIm,'mouseout',function(){thisEl.bodyUIm.hide();});
        this.curMiniIm=null;
        Element.observe(this.uvelichIm,'click',function(){thisEl.bodyUIm.hide();thisEl.showFullImage(thisEl.curMiniIm);});

        this.imdf=0;
        this.pastIm=null;
        this.ieB=Prototype.Browser.IE;
        this.panelL=new Element('div',{style:'position:absolute; left:0; top:0; z-index:6; display:none; text-align:center;'});
        this.fotoPanel.insert(this.panelL);

        this.lP=new LoadingPan();
        this.panelL.insert(this.lP._buildP());
    },
    _getIeB:function(){
        return "?"+Math.random();
    },
    _getFlNI:function(ind){
        var thisEl=this;
        return function(){thisEl.loadNext(ind)};
    },
    _getFFCl:function(ind,root){
        var thisEl=this;
        return function(){
            thisEl.curMiniIm=ind;
            var el=elen(this); thisEl.uvelichIm.src=this.src; thisEl.bodyUIm.style.top=el.clientTop-((root)?10:6)+'px'; thisEl.bodyUIm.style.left=el.clientLeft-((root)?15:7)+'px'; thisEl.bodyUIm.style.cursor='pointer'; thisEl.bodyUIm.show();
        };
    },
    loadNext:function(ind){
        var thisEl=this;
        if(this.currentImage!=null) this.currentImage.show();
        else{
            this.imL=new Element("nobr").addClassName('imageBorder2').insert(new Element('img',{src:'images/0001m.gif'}).addClassName('imageBorder'));
            this.blockIm.insert(new Element("nobr").addClassName('imageBorder2').insert( this.imL));
        }
        setTimeout(function(){thisEl.loadNext2(ind)},200);
    },
    loadNext2:function(ind){
        //        $('ch').insert(this.imdf+++" ");
        var thisEl=this;
        var indstr=this.getNameIm(ind);
        this.currentImage=new Element('img').addClassName('imageBorder');
        Insertion.Before(this.imL,new Element("nobr").addClassName('imageBorder2').insert(this.currentImage));
        function f(im){Element.observe(im,'mouseover',thisEl._getFFCl(ind,true));}
        f(this.currentImage);
        if(ind<this.countImTP+(this.numPage-1)*25)
            this.currentImage.onload=this._getFlNI(ind+1);
        else{
            this.currentImage.onload=function(){Element.remove(thisEl.imL);thisEl.currentImage.show();};
            if(this.countPage>1){
            var gfd=function(i){
                return function(){
                    if(thisEl.numPage!=(i+1)){
                        thisEl.numPage=i+1;
                        var curAlb=new ImLoading(thisEl.owner,thisEl.nameDir,thisEl.countIm,thisEl.nameGal,i+1);
                        curAlb.loadNext((thisEl.numPage-1)*25+1);
                    }
                }
            }
            var pk=new Element('div',{align:'center'});
            this.fotoFrame.insert(pk);
            for(var i=0;i<this.countPage;i++){
                var asds= new Element('a',{href:'#',title:'Страница '+(i+1)}).insert(' '+(i+1)+' ');
                if((i+1)==this.numPage) asds.style.fontSize='20px';
                pk.insert(asds)
                Event.observe(asds,'click',gfd(i));
            }
        }
        }
        this.currentImage.src=this.nameDir+'small/'+indstr+'.jpg'+((this.ieB)?this._getIeB():'');
        this.currentImage.hide();
    },
    showFullImage:function(numIm){
        var thisEl=this;
        this.panelL.style.left = (Math.ceil(this.pS2.windowWidth/2)-30)+"px";
        this.panelL.style.top= (this.pS2.windowHeight-80)+"px";
        this.lP.start();
        this.panelL.show();
        this.fullIm=new Element('img').addClassName('imageBorderF');
        this.fullIm.onload=function(){thisEl.showFullIm();thisEl.showPanelSlide(numIm);thisEl.showPanelInstr();};
        this.fullIm.src=this.nameDir+"full/"+this.getNameIm(numIm)+".jpg"+((this.ieB)?this._getIeB():'');
        this.fullIm.hide();
        this.curIm = numIm;

    },
    showFullIm:function(){
        this.lP.stop();
        this.panelL.hide();
        this.imRed2.update(this.fullIm);
        this.imRed.style.top = "10px";
        this.imRed2.style.top = "10px";
        this.imRed.style.width=this.pS2.windowWidth+"px";
        this.imRed.style.height=this.pS2.windowHeight+"px";
        this.imRed.show();
        this.imRed2.style.width=this.pS2.windowWidth+"px";
        this.imRed2.style.height=this.pS2.windowHeight+"px";
        this.imRed2.show();
    },
    _clickPSF:function(ind){
        var thisEl=this;
        return function(){thisEl.showFullImage(ind);return null;}
    },
    getImPanelSlide:function(numIm){
        var thisEl=this;
        var h=getPageSize(this.fullIm).windowWidth;
        var t=Math.floor(h/96);
        if(t>this.countIm) t=this.countIm;
        if(t%2==0) t--;
        var ind=numIm-Math.floor(t/2);
        if(ind<1) ind=1;
        if(ind>this.countIm-t) ind=this.countIm-t+1;
        var divEEl=new Element('div',{style:"width:"+96*t+"px;"});
        for(var i=ind;i<ind+t;i++){
            var im=new Element('img',{src:this.nameDir+'small/'+this.getNameIm(i)+'.jpg',height:60+"px",width:90+"px"}).addClassName('imageBorder');
            if (i!=numIm) im.addClassName('opas');
            var s=new Element("nobr").addClassName('imageBorder2').insert(im);
            divEEl.insert(s);
            Element.observe(im,'mouseover',this._getFFCl(i));
        }
        return new Element('table',{cellpadding:'0',cellspacing:"0",border:"0",width:"100%",align:"center"}).insert(new Element('tr').insert(new Element('td',{align:"center"}).insert(divEEl)));
    },
    getNameIm:function(numIm){var nameIm='000'+numIm;if(numIm>9) nameIm='00'+numIm;if(numIm>99) nameIm='0'+numIm;if(numIm>999) nameIm=numIm; return nameIm;},
    showPanelSlide:function(numIm){
        this.uvelichIm.width=108;
        this.uvelichIm.height=72;
        this.fullIm.show();
        var h=new Number(this.fullIm.height);
        var eq=h/this.pS2.windowHeight;
        var t1=Math.ceil(this.fullIm.width/eq);
        var t2=Math.ceil(this.fullIm.height/eq);
        //        alert("t1:"+t1+"; t2:"+t2+"; wH"+this.pS2.windowHeight+"; width: "+this.fullIm.width+"; eq:"+eq+"; math:"+Math.ceil(h/eq)+"; height: "+h+"; eq:"+eq+"; math:"+Math.ceil(this.fullIm.height/eq));
        this.fullIm.width=t1;
        this.fullIm.height=t2;
        this.panelSlideIm.update(this.getImPanelSlide(numIm));
        this.panelSlideIm.style.width=this.pS2.windowWidth+"px";
        this.panelSlideIm.show();
    },
    showPanelInstr:function(){
        var thisEl=this;
        if(this.panTool==null){
            var b=this.fotoPanel;
            //создаем панель
            var butShAll=new Element('img',{alt:'',src:'images/showAll.gif',border:0,width:'17px',height:'17px',style:'cursor:pointer;'});
            var butPrev=new Element('img',{alt:'',src:'images/Previus.gif',border:0,width:'14px',height:'14px',style:'cursor:pointer;'});
            var butNext=new Element('img',{alt:'',src:'images/Next.gif',border:0,width:'14px',height:'14px',style:'cursor:pointer;'});
            var butClose=new Element('img',{alt:'',src:'images/Close.gif',border:0,width:'17px',height:'17px',style:'cursor:pointer;'});
            var panel=new Element('table',{cellpadding:0,cellspacing:0,border:0,style:"position: absolute; left: 0px; top: 0px; z-index: 4; text-align: center; width:340px;"});
            panel.addClassName('opas');
            Element.observe(panel,'mouseover',function(){panel.removeClassName('opas')});
            Element.observe(panel,'mouseout',function(){panel.addClassName('opas')});
            panel.style.left = (Math.ceil(this.pS2.windowWidth/2)-170)+"px";
            panel.style.top= (this.pS2.windowHeight-110)+"px";
            var numPage = new  Element('td').insert('');
            panel.insert(new Element('tr').
                    insert(new Element('td').
                    insert(new Element('table',{width:"35px",cellpadding:0,cellspacing:0,border:0,style:"background-color:#000; opacity: 0.6;filter:alpha(opacity=60); height:20px;"}).
                    insert(new Element('tr').insert(new  Element('td').insert(butShAll))))).
                    insert(new Element('td',{width:"4px;"}).insert('&nbsp;')).
                    insert(new Element('td').
                    insert(new Element('table',{width:"260px",cellpadding:0,cellspacing:0,border:0,style:"background-color:#000; opacity: 0.6;filter:alpha(opacity=60); height:20px;"}).
                    insert(new Element('tr').insert(new  Element('td').insert(butPrev)).insert(numPage).insert(new  Element('td').insert(butNext))))).
                    insert(new Element('td',{width:"4px;"}).insert('&nbsp;')).
                    insert(new Element('td').insert(new Element('table',{width:"35px",cellpadding:0,cellspacing:0,border:0,style:"background-color:#000; opacity: 0.6;filter:alpha(opacity=60); height:20px;"}).
                    insert(new Element('tr').insert(new  Element('td').insert(butClose)))))
                    );
            this.panTool=new Object();
            this.panTool.numPage=numPage;
            this.panTool.butShAll=butShAll;
            this.panTool.panel=panel;
            Element.observe(butClose,'click',function(){thisEl.hideUpPanel();});
            Element.observe(butShAll,'click',function(){thisEl.hideUpPanel();});
            Element.observe(butPrev,'click',function(){if(thisEl.curIm!=1)thisEl._clickPSF(thisEl.curIm-1)();});
            Element.observe(butNext,'click',function(){ if(thisEl.curIm!=thisEl.countIm) thisEl._clickPSF(thisEl.curIm+1)();});
            b.insert(panel);
        }
        this.panTool.numPage.update("Страница "+this.curIm+"/"+this.countIm);
        this.panTool.panel.show();
        this.panTool.butShAll.height=17;

    },
    hideUpPanel:function(){
        this.uvelichIm.width=150;
        this.uvelichIm.height=100;
        this.panelSlideIm.hide();
        this.imRed.hide();
        this.imRed2.hide();
        this.panTool.panel.hide();
        this.curIm=null;
    }

}

function elen(el){
    var d=Element.Methods.cumulativeOffset(el);
    return {
        clientW: el.clientWidth,
        clientH: el.clientHeight,
        clientTop: d.top,
        clientLeft: d.left
    };
}
function getPageSize(parent){
    parent = parent || document.body;
    var windowWidth, windowHeight;
    var pageHeight, pageWidth;
    if (parent != document.body) {
        windowWidth = parent.getWidth();
        windowHeight = parent.getHeight();
        pageWidth = parent.scrollWidth;
        pageHeight = parent.scrollHeight;
    }
    else {
        var xScroll, yScroll;
        if (window.innerHeight && window.scrollMaxY) {
            xScroll = document.body.scrollWidth;
            yScroll = window.innerHeight + window.scrollMaxY;
        } else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
            xScroll = document.body.scrollWidth;
            yScroll = document.body.scrollHeight;
        } else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
            xScroll = document.body.offsetWidth;
            yScroll = document.body.offsetHeight;
        }
        if (self.innerHeight) {  // all except Explorer
            windowWidth = self.innerWidth;
            windowHeight = self.innerHeight;
        } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
            windowWidth = document.documentElement.clientWidth;
            windowHeight = document.documentElement.clientHeight;
        } else if (document.body) { // other Explorers
            windowWidth = document.body.clientWidth;
            windowHeight = document.body.clientHeight;
        }
        // for small pages with total height less then height of the viewport
        if(yScroll < windowHeight){
            pageHeight = windowHeight;
        } else {
            pageHeight = yScroll;
        }
        // for small pages with total width less then width of the viewport
        if(xScroll < windowWidth){
            pageWidth = windowWidth;
        } else {
            pageWidth = xScroll;
        }
    }
    return {pageWidth: pageWidth ,pageHeight: pageHeight , windowWidth: windowWidth, windowHeight: windowHeight};
}

function ff(ind){
    return function d(){startObj.imL.loadNext(ind);};
}

var Navigation=Class.create();
Navigation.prototype = {
    initialize: function(el,arrAlb,pathImg){
        this.owner=el;
        this.arrAlb=arrAlb;
        this.pathImg=pathImg;
        this.pS2=elen(el);
        this.paintMenu();
    },
    paintMenu:function(){
        this.curAlb=new ImLoading(this.owner,this.pathImg+this.arrAlb[0].folder,this.arrAlb[0].count,this.arrAlb[0].name);
        this.curAlb.loadNext(1);
        var menu=new Element('div',{style:'position:absolute; left:'+this.pS2.clientLeft+'px; top:'+this.pS2.clientTop+'px; z-index:1;'});
        this.owner.insert(menu)
        var thisEl=this;
        var fch=function(el){
            return function(){
                thisEl.curAlb=new ImLoading(thisEl.owner,thisEl.pathImg+el.folder,el.count,el.name);
                thisEl.curAlb.loadNext(1);
            }
        }
        for(var i=0;i<this.arrAlb.length;i++){
            var li=new Element('span',{style:'padding:10px; cursor:pointer;'}).insert(this.arrAlb[i].name)
            menu.insert(li);
            Event.observe(li,'click',fch(this.arrAlb[i]));
        }
    }
}