<?php
class ElementSearch {
	public static $EL_COMBOBOX=1;
    public static $EL_CHECKBOX=2;
    public static $EL_INP_FROM=3;
    public static $EL_INP_TO=4;
    public static $EL_DBLINP=5;

    public static function getElementSearch($type){
        if($type==ElementSearch::$EL_COMBOBOX) return new ElementSearchComboBox();
        if($type==ElementSearch::$EL_CHECKBOX) return new ElementSearchCheckBox();
        if($type==ElementSearch::$EL_INP_FROM) return new ElementSearchInputFrom();
        if($type==ElementSearch::$EL_INP_TO) return new ElementSearchInputTo();
        if($type==ElementSearch::$EL_DBLINP) return new ElementSearchInputDBL();
        return null;
    }
	
	public static function escapePrHier($dP){
		$dP->type=(int) $dP->type;
		$dP->id=(int) $dP->id;
		$dP->value1=db_escape_string($dP->value1);
		if($dP->type!=ElementSearch::$EL_DBLINP)
		   $dP->value2=db_escape_string($dP->value2);
		return $dP;		
	}
}

interface ElementSearchIface{
	//метод возвращающий html для заказанного элемента SearthGoods
    public function getElementsHTML($sg);
    //метод возвращает часть SQL для поиска Integer
    public function getElementsSQLSearch($num,$dP);       
}

class ElementSearchComboBox implements ElementSearchIface{
	public function getElementsHTML($sg){
		$strB="<nobr>".(($sg->uper)?$sg->uper:"")."<select class='searchEl' name='ref_".ElementSearch::$EL_COMBOBOX."_".$sg->id."'><option value=''>&nbsp;</option>";
        if($sg->id_linklist){
            //подгружаем список
            $gds=new GeneralDataDAO();
            $lGDs=$gds->getLinkList($sg->id_linklist);            		
            foreach ($lGDs as $gd){	
                $strB.="<option value='".$gd->value."'".(($sg->value1 && $sg->value1==$gd->value)?(" selected='selected' "):"").">".$gd->value."</option>";
            }            
        }
        $strB.="</select>".(($sg->downer)?$sg->downer:"")."</nobr>";		
		return $strB;
	}
	public function getElementsHTML2($sg){			
		return "<nobr>Пока не работает</nobr>";
	}
	public function getElementsSQLSearch($num, $dP){
		$dP=ElementSearch::escapePrHier($dP);        
		return "gp".$num.".id_PrHierar=".$dP->id." AND gp".$num.".value LIKE '%".$dP->value1."%'";
	}
}

class ElementSearchCheckBox implements ElementSearchIface{
	public function getElementsHTML($sg){		
        return "<nobr>".(($sg->uper)?$sg->uper:"")."<input class='searchEl' type='checkbox'  name='ref_".ElementSearch::$EL_CHECKBOX."_".$sg->id."'".(($sg->value1 && $sg->value1=="on")?" checked='checked' ":"")."/>".(($sg->downer)?$sg->downer:"")."</nobr>";
	}
	public function getElementsSQLSearch($num, $dP){
		$dP=ElementSearch::escapePrHier($dP);
        return "gp".$num.".id_PrHierar=".$dP->id." AND gp".$num.".value=".(($dP->value1=='on')?"'Да'":"'Нет'");
	}	
}
class ElementSearchInputFrom implements ElementSearchIface{//от
	public function getElementsHTML($sg){
		return "<nobr>".(($sg->uper)?$sg->uper:"")."<input class='searchEl' type='text' size='4'  name='ref_".$sg->type."_".$sg->id."'".(($sg->value1 && $sg->value1!="")?("value='".$sg->value1."'"):"")."'/>".(($sg->downer)?$sg->downer:"")."</nobr>";     
	}
	public function getElementsSQLSearch($num, $dP){
		$dP=ElementSearch::escapePrHier($dP);        
		return "gp".$num.".id_PrHierar=".$dP->id." AND gp".$num.".value>=".$dP->value1;     
	}
}
class ElementSearchInputTo implements ElementSearchIface{//до
	public function getElementsHTML($sg){
		return "<nobr>".(($sg->uper)?$sg->uper:"")."<input class='searchEl' type='text' size='4'  name='ref_".$sg->type."_".$sg->id."'".(($sg->value1 && $sg->value1!="")?("value='".$sg->value1."'"):"")."'/>".(($sg->downer)?$sg->downer:"")."</nobr>";
	}
	public function getElementsSQLSearch($num, $dP){
		$dP=ElementSearch::escapePrHier($dP);        
		return "gp".$num.".id_PrHierar=".$dP->id." AND gp".$num.".value<=".$dP->value1; 
	}	
}
class ElementSearchInputDBL implements ElementSearchIface{
	public function getElementsHTML($sg){
		return "<nobr>".(($sg->uper)?$sg->uper:"")."<input class='searchEl' type='text' size='4' name='ref_".ElementSearch::$EL_DBLINP."_".$sg->id."'".(($sg->value1 && $sg->value1!="")?("value='".$sg->value1."'"):"")."'/>".(($sg->inners)?$sg->inners:"")."<input class='searchEl' type='text' size='4' name='dref_".ElementSearch::$EL_DBLINP."_".$sg->id."'".(($sg->value2 && $sg->value2!="")?("value='".$sg->value2."'"):"")."'/>".(($sg->downer)?$sg->downer:"")."</nobr>";		
	}
	public function getElementsSQLSearch($num, $dP){
		$dP=ElementSearch::escapePrHier($dP);        
        if($dP->value1!="" && $dP->value2!="")		
		    return "gp".$num.".id_PrHierar=".$dP->id." AND gp".$num.".value BETWEEN ".$dP->value1." AND ".$dP->value2; 
		if($dP->value1!="")	
            return "gp".$num.".id_PrHierar=".$dP->id." AND gp".$num.".value>=".$dP->value1;	
        if($dP->value2!="")	
            return "gp".$num.".id_PrHierar=".$dP->id." AND gp".$num.".value<=".$dP->value2;				
	}	
}
?>