<?php
require_once('ObjectDAO3.php');

class TypeAnnounceDAO extends ObjectDAO{

	public function TypeAnnounceDAO() {}
	
	/*Получить список типов товаров*/
	public function getListTypeAnnounce(){
		$sql='SELECT id, value FROM {na_typeannounce} WHERE id_parent=1 ORDER BY weight';		
		$result=db_query($sql);
		$arr=array();
		while ($obj = db_fetch_object($result)) {
			$sql='SELECT id, value FROM {na_typeannounce} WHERE id_parent=%d  ORDER BY weight';
			$result2=db_query($sql,$obj->id);
			$obj->listTypeAnnounce=$this->getListObject($result2);
			$arr[]=$obj;
		}
		return $arr;
	}	
	
	public function saveTypeAnnounce($typeannounce){			
		if($goods->id==null){
			$res=db_query("INSERT INTO {na_typeannounce} (id_parent, name_prop, value, label,weight) VALUES(%d,'%s','%s','%s',%d)",$typeannounce->id_parent, $typeannounce->name_prop,$typeannounce->value,$typeannounce->label,$typeannounce->weight);			
		}		
		return $res;
	}
	
}
?>