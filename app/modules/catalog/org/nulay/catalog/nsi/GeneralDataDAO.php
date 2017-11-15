<?php
require_once('ObjectDAO.php');
class GeneralDataDAO extends ObjectDAO{
	public function getLinkList($idRootEl){
		$sql='SELECT id, value FROM {nc_generaldata} WHERE id_parent=%d';
		$result=db_query($sql, $idRootEl);
		return $this->getListObject($result);
	}
}
?>