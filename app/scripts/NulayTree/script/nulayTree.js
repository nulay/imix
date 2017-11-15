/*  NulayTree JavaScript
 *  (c) 2009 Mikhail Kachanovskii
 *
 *  NulayTree is freely distributable under the terms of an MIT-style license.
 *  For details, see the NulayTree web site: http://www.nulay.narod.ru/
 *--------------------------------------------------------------------------*/

var ListenerChangeTree=Class.create();
ListenerChangeTree.prototype={
    initialize:function(tree){
        this.tree=tree;
    },
    insertFire:function(node,ind,key,nolist){
        if(node.parent){
            var nplc=node.parent.listChild;
            if(!nolist)node.addListeners(this);
            var ttr=this.tree.rendere;
            ttr.paintEl(node,ind);
            if(!key && ind!=0) this.updateNode(nplc[ind-1]);
            var nlc=node.listChild;
            for(var t=0;t<nlc.length;t++){
                this.insertFire(nlc[t],t,true,nolist);
            }
            ttr.repaint(node.parent);
        }
    },
    removeFire:function(node){
        var ttr=this.tree.rendere;
        ttr.remove(node.parent);
        var nplc=node.parent.listChild;
        for(var tt=0;tt<nplc.length;tt++){
            this.insertFire(nplc[tt],tt,false,true);
        }
        ttr.repaint(node.parent);
        ttr.selectedEl=null;
        this.tree.collapsedAll(node.parent);
        this.tree.rendere.expand(node.parent);
    },
    updateNode:function(node){
        this.tree.rendere.repaint(node);
        var nlc=node.listChild;
        for(var z=0;z<nlc.length;z++){
            this.updateNode(nlc[z]);
        }
    },
    removeAll:function(node){
        this.tree.setModel(null);
    }
}

var idTree=0;
function getIdTree(){
    if(!Object.isNumber(idTree)) idTree=(Date.getTime());
    return ''+idTree++;
}

var NulayTree=Class.create();
NulayTree.prototype={
    initialize:function(options){
        var thisEl=this;
        this.nT=getIdTree();
        this.treeElement=new Element('div',{id:'nulayTree_'+this.nT}).addClassName('treeRoot noTextSelect');
        this.rendere=new TreeRenderer(thisEl,options);
        this.listChTree=new ListenerChangeTree(this);
        this.listActionRedact=new Array();
        this.treeNode=null;
        this.checked=false;
        this.aAsChild=false;
        if(options){
            if(options.checked) this.checked=true;
            if(options.aAsChild) this.aAsChild=true;
        }
    },
    setModel:function(treeNode){
        this.treeNode=treeNode;
        this.treeElement.update();
        if(this.treeNode!=null)
            this.paintTree(this.treeNode);
    },
    paintTree:function(node){
        if(!node.parent)
            this.treeElement.insert(this.rendere.paint(node));
        else
            this.rendere.paintEl(node);
        node.addListeners(this.listChTree);
        for(var i=0;i<node.listChild.length;i++){this.paintTree(node.listChild[i]);}
    },
    setChecked:function(checked){
        this.rendere.options.checked=checked;
        this.checked=checked;
        this.setModel(this.treeNode);
    },
    removeImage:function(delIm){
        this.rendere.options.delIm=delIm;
        this.setModel(this.treeNode);
    },
    getSelectedElement:function(){
        return this.rendere.selectedEl;
    },
    getCheckedElement:function(){
        if(!this.checked || !this.treeNode) return false;
        var checkedNode=new Array();
        this.getChE(checkedNode, this.treeNode);
        return checkedNode;
    },
    getChE:function(cN,tN){
        for(var ti=0;ti<tN.listChild.length;ti++){
            this.getChE(cN, tN.listChild[ti]);
        }
        var ff=$('t_'+this.nT+'chB_'+tN.sn);
        if(ff && ff.checked) cN[cN.length]=tN;
    },
    setAsChild:function(aAsChild){
        this.aAsChild=aAsChild;
    },
    isLeaf:function(node){
        if(this.aAsChild){
            return node.leaf;
        }else{
            return node.isLeaf();
        }
    },
    expandAll:function(trN){//разв. ¬се
        if(!trN) trN=this.treeNode;
        if(!trN.isLeaf()){
            for(var i=0;i<trN.listChild.length;i++){
                this.expandAll(trN.listChild[i]);
            }
            this.rendere.expand(trN);
        }
    },
    collapsedAll:function(trN){//cв. ¬се
        if(!trN) trN=this.treeNode;
        if(!trN.isLeaf()){
            for(var i=0;i<trN.listChild.length;i++){
                this.collapsedAll(trN.listChild[i]);
            }
            this.rendere.collapsed(trN);
        }
    },
    expand:function(trN){
        this.rendere.expand(trN);
    },
    collapsed:function(trN){
        this.rendere.collapsed(trN);
    },
    addActionRedact:function(actionRedact){
        this.listActionRedact[this.listActionRedact.length]=actionRedact;
        this.rendere.listActionRedact=this.listActionRedact;
    }
}

var TreeNode=Class.create();
TreeNode.prototype={
    initialize:function(element,leaf,objecti) {
        this.element=element;
        this.objecti=objecti;
        this.listChild=new Array();
        this.listeners=new Array();
        this.parent=null;
        this.sn='0';
        this.leaf=leaf;
    },
    addListeners:function(listener){
        if(listener)
            this.listeners[this.listeners.length]=listener;
    },
    removeListeners:function(ind){
        var tl=this.listeners;
        if(ind<tl.length){
            tl[ind]=null;
            tl=tl.compact();
        }
    },
    getChildAt:function(childIndex){
        var tlc=this.listChild;
        if(childIndex<tlc.length)
            return tlc[childIndex];
        return null;
    },
    isLeaf:function(){
        return this.listChild.length==0;
    },
    insert:function(cN,ind){
        if(this.leaf) return false;
        if(cN){
            cN.parent = this;
            var tlc=this.listChild;
            if(!ind)ind=tlc.length;
            if(ind<=tlc.length){
                if(tlc.length==ind){
                    tlc[ind]=cN;
                }else{
                    var stlc=tlc.slice(0,ind); stlc[ind]=cN; stlc.concat(stlc.slice(ind+1));
                }
                this.setSN();
                var tl=this.listeners;
                for (var b=0;b<tl.length;b++){
                    tl[b].insertFire(cN,ind);
                }
                return true;
            }
        }
        return false
    },
    setSN:function(){
        var nlc=this.listChild;
        for(var j=0;j<nlc.length;j++){
            nlc[j].sn=this.sn+'.'+j;
            nlc[j].setSN();
        }
    },
    remove:function(ind){
        var tlc=this.listChild;
        if(!ind && ind!=0){
            if(this.parent==null){
                this.element=null;
                this.listChild=new Array();
                this.parent=null;
                this.sn='0';
                var tl=this.listeners;
                if(tl.length!=0){
                    for (var g=0;g<tl.length;g++){
                        tl[g].removeAll();
                    }
                }
                this.listeners=new Array();
                return true;
            }
            return this.parent.remove(this.parent.listChild.indexOf(this));
        }else{
            if(ind<tlc.length){
                var n=tlc[ind];
                tlc[ind]=null;
                this.listChild=tlc.compact();
                this.setSN();
                var tl=this.listeners;
                if(tl.length!=0){
                    for (var g=0;g<tl.length;g++){
                        tl[g].removeFire(n);
                    }
                }
                return true;
            }
        }
        return false;
    },
    getObjecti: function(){
        return this.objecti;
    }
}

var TreeViewer = Class.create();
TreeViewer.prototype = {
    initialize:function(nT, sn, options) {
        var f =(options && options.folderImage)?options.folderImage:'tree';
        this.checked=(options && options.checked)?new Element('input',{type:'checkbox',checked:false, id:'t_'+nT+'chB_'+sn,height:'18',width:'18'}).addClassName('checkedView'):null;
        this.image={m_line:f+'/m-line.png',nullIm:f+'/null.gif',tArrExp:f+'/tArrExp.gif',tArrCol:f+'/tArrCol.gif',imageFolder:f+'/folder.gif',imageLeaf:f+'/leaf.gif',i_line:f+'/i-line.gif',l_line:f+'/l-line.gif',t_line:f+'/t-line.gif'};
        this.imageNode=(options && options.delIm)?'':new Element('img',{id:'t_'+nT+'iN_'+sn,src:this.image.imageLeaf,height:'18',width:'18',border:"0", align:"absbottom"}).addClassName('imageNode');
        this.placeElem=new Element('span',{id:'t_'+nT+'pE_'+sn}).addClassName('placeEl');
        this.imExpand=new Element('img',{id:'t_'+nT+'iE_'+sn, src:this.image.tArrExp,height:'18',width:'18',border:"0", align:"absbottom"}).addClassName('expander');
        this.arrowEl=new Element('span',{id:'t_'+nT+'aL_'+sn}).insert(this.imageNode).insert(this.placeElem).addClassName('arrowTree');
        this.imageLine=new Element('span',{id:'t_'+nT+'iL_'+sn}).addClassName('imageLine');
        this.blockChild=new Element('span',{id:'t_'+nT+'bC_'+sn}).addClassName('nodeTree');

        this.nodeView=new Element('div',{id:'t_'+nT+'nV_'+sn}).addClassName('nView').
                insert(this.imageLine).
                insert(this.imExpand).
                insert(this.arrowEl).
                insert(this.blockChild);
    }
}

var TreeRenderer = Class.create();
TreeRenderer.prototype = {
    initialize:function(treeModel,options) {
        var thisEl=this;
        this.treeModel=treeModel;
        this.options = options;
        this.selectedEl=null;
        this.imageArr=new TreeViewer(null,null,this.options);
        this.funt={
            colEx:function(rend){
                var node=$A(arguments)[1];
                var tnt='t_'+this.treeModel.nT;
                var bC=$(tnt+'bC_'+node.sn);
                var iE=$(tnt+'iE_'+node.sn);
                if(bC.visible()){
                    bC.hide();
                    iE.src=this.imageArr.image.tArrCol;
                }else{
                    bC.show();
                    iE.src=this.imageArr.image.tArrExp;
                }
            },
            selecti:function(rend){
                var node=$A(arguments)[1];
                var sel=$A(arguments)[2];
                var aL=$('t_'+this.treeModel.nT+'aL_'+node.sn);
                if(sel){
                    aL.addClassName('selectedNode');
                }else{
                    aL.removeClassName('selectedNode');
                }
            },
            selected:function(rend){
                var node=$A(arguments)[1];
                if(this.selectedEl!=null){
                    var aLi=$('t_'+this.treeModel.nT+'aL_'+this.selectedEl.sn);
                    if(aLi) aLi.removeClassName('isSelected');
                }
                var aL=$('t_'+this.treeModel.nT+'aL_'+node.sn);
                if(aL.hasClassName('isSelected')){
                    aL.removeClassName('isSelected');
                    this.selectedEl=null;
                }else{
                    aL.addClassName('isSelected');
                    this.selectedEl=node;
                }
                if(this.listActionRedact!=null &&this.listActionRedact.length!=0){
                    for(var i=0;i<this.listActionRedact.length;i++){
                       this.listActionRedact[i](node.objecti);
                    }
                }
            }
        }
    },
    repaint:function(node){
        var iL=$('t_'+this.treeModel.nT+'iL_'+node.sn);
        iL.update();
        var tV=new TreeViewer(this.treeModel.nT,node.sn,this.options);
        tV.imageLine=iL;
        this.paintLine(node.parent,node,tV);
    },
    remove:function(node){
        $('t_'+this.treeModel.nT+'bC_'+node.sn).update('');
    },
    paint:function(node){
        var tV=new TreeViewer(this.treeModel.nT,node.sn,this.options);
        tV.placeElem.insert(node.element);
        this.paintLine(null,node,tV);
        return tV.nodeView;
    },
    paintEl:function(node,ind){
        var tV=new TreeViewer(this.treeModel.nT,node.sn,this.options);
        tV.placeElem.insert(node.element);
        if((ind==0 || ind) && node.parent.length>1){
            if(ind==0) Insertion.Top('t_'+this.treeModel.nT+'bC_'+node.parent.sn,tV.nodeView);
            Insertion.After($('t_'+this.treeModel.nT+'bC_'+node.parent.listChild[ind-1].sn),tV.nodeView);
        }else
            $('t_'+this.treeModel.nT+'bC_'+node.parent.sn).insert(tV.nodeView);
        this.paintLine(node.parent, node,tV);
    },
    paintLine:function(nodein, node, tV){
        var thisEl=this;
        //провер€ем €вл€етс€ ли этот узел root-овым
        if(node.parent){
            if(nodein){
                //провер€ем если этот элемент €вл€етс€ непосредственным ребенком
                if(nodein.listChild.include(node)){
                    //смотрим узел будет листом или папкой
                    if(node.isLeaf()){
                        /*смотрим узел €вл€етс€ последним в родительском узле
                  то рисуем L линию иначе T линию*/
                        if(node==node.parent.listChild.last())
                            tV.imageLine.insert(this.gImE(tV.image.l_line));
                        else
                            tV.imageLine.insert(this.gImE(tV.image.t_line));
                    }
                    this.paintInner(node,tV);
                }
                if(tV.checked){
                    Insertion.Top(tV.imageLine,this.gImE(tV.image.nullIm));
                }
                if(nodein.parent){
                    if(nodein==nodein.parent.listChild.last()){
                        Insertion.Top(tV.imageLine,this.gImE(tV.image.nullIm));
                    }else{
                        Insertion.Top(tV.imageLine,this.gImE(tV.image.i_line));
                    }
                } else{
                    if(nodein.listChild.length!=0)
                        Insertion.Top(tV.imageLine,this.gImE(tV.image.nullIm));
                }
                if(nodein.parent)
                    this.paintLine(nodein.parent,node,tV);
            }
        }else{
            this.paintInner(node,tV);
        }
    },
    paintInner:function(node,tV){
        var thisEl=this;
        //—вертывание, развертывание при двойном клике на элементе
        var aL=$('t_'+this.treeModel.nT+'aL_'+node.sn); aL=(aL)?aL:tV.arrowEl;
        var imN=$('t_'+this.treeModel.nT+'iN_'+node.sn); imN=(imN)?imN:tV.imageNode;
        var ex=$('t_'+this.treeModel.nT+'iE_'+node.sn); ex=(ex)?ex:tV.imExpand;
        this.funti=this.funt.colEx.bindAsEventListener(thisEl,node);
        if(this.treeModel.isLeaf(node))
            imN.src=tV.image.imageLeaf;
        else
            imN.src=tV.image.imageFolder;
        if(node.isLeaf()){
            aL.ondblclick=null;
            ex.ondblclick=null;
            if (ex.visible()) ex.hide();
        }else{
            aL.ondblclick=this.funti;
            //—мотрим нарисован ли експандер
            if (!ex.visible()){
                ex.show();
            }
            ex.onclick=this.funti;
            var bCh=$('t_'+this.treeModel.nT+'bC_'+node.sn);
            bCh=(bCh)?bCh:tV.blockChild;
            if(!bCh.visible()){ex.src=tV.image.tArrCol;}
        }
        aL.onclick=this.funt.selected.bindAsEventListener(thisEl,node);
        aL.onmouseover=this.funt.selecti.bindAsEventListener(thisEl,node,true);
        aL.onmouseout=this.funt.selecti.bindAsEventListener(thisEl,node,false);
        //если нужно рисовать флажок
        if(tV.checked){
            var chB=$('t_'+this.treeModel.nT+'chB_'+node.sn);
            //провер€ем ни нарисован ли он уже
            if(!chB)Insertion.After(ex,tV.checked);
        }
    },
    gImE:function(s){
        return new Element('img',{src:s,height:'18',width:'18',border:"0", align:"absbottom"});
    },
    expand:function(node){ //раскрыть
        var tnt='t_'+this.treeModel.nT
        var bC=$(tnt+'bC_'+node.sn);
        var iE=$(tnt+'iE_'+node.sn);
        if(bC && !bC.visible()){
            bC.show();
            iE.src=this.imageArr.image.tArrExp;
        }
    },
    collapsed:function(node){ //свернуть
        var tnt='t_'+this.treeModel.nT
        var bC=$(tnt+'bC_'+node.sn);
        var iE=$(tnt+'iE_'+node.sn);
        if(bC && bC.visible()){
            bC.hide();
            iE.src=this.imageArr.image.tArrCol;
        }
    }
}