<?php
require_once('ObjectDAO3.php');

class AnnounceDAO extends ObjectDAO{

	public function AnnounceDAO() {}
	
	/*Получить список типов товаров*/
	public function getListAnnounceFromType($id_cat){		
		$sql='SELECT id, value FROM {na_announcements} WHERE id_parent=%d';
		$result=db_query($sql,$id_cat);	
		return $this->getListArray($result);
	}	
	
	public function saveAnnounce($announce){	
		if($goods->id==null){
			$res=db_query("INSERT INTO {na_announcements} (keyword, discription, price, tel1, tel2, note, uid, ta_id,checka,datacreate,timepublish,torg) VALUES('%s','%s',%d,'%s','%s','%s',%d,%d,%d,'%s','%s','%d')",$announce['keyword'], $announce['discription'], $announce['price'], $announce['tel1'], $announce['tel2'], $announce['note'], $announce['uid'], $announce['ta_id'], $announce['checka'],$announce['datacreate'],$announce['timepublish'],$announce['torgv']);	
          $result = db_last_insert_id("nc_listshops","id");			
		}else{
		$sql='UPDATE {na_announcements} SET keyword="%s", discription="%s", price="%d", tel1="%s", tel2="%s", note="%s",  uid="%d", ta_id="%d", checka="%d", datacreate="%s", timepublish="%s",torg="%d" WHERE id=%d';
		$result=db_query($sql,$announce['keyword'], $announce['discription'], $announce['price'], $announce['tel1'], $announce['tel2'], $announce['note'], $announce['uid'], $announce['ta_id'], $announce['checka'],$announce['datacreate'],$announce['timepublish'], $announce['torg']);
}		
		return $res;
	}
}
?>