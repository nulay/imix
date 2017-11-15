<?php
class ObjectDAO {
	public function getListArray($result){
		$arr=array();
		while ($obj = db_fetch_array($result)) {
			$arr[]=$obj;
		}
		return $arr;
	}
	public function getListObject($result){
		$arr=array();
		while ($obj = db_fetch_object($result)) {
			$arr[]=$obj;
		}
		return $arr;
	}
}
?>