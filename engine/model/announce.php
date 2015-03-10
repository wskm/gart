<?php !defined('IN_ART') && exit('Access Denied');

class model_announce extends wskm_model_abstract
{
	function getNews($count=10){
		$count=(int)$count;
		$query=$this->db->query("SELECT * FROM ".TABLE_PREFIX."announce ORDER BY displaysort DESC LIMIT {$count} ");
		$article=array();
		while ($tempi=$this->db->fetch($query)) {
			$tempi['mvcurl']=mvcUrl('',array('announce','show',array('id'=>$tempi['id'])),ART_URL,WSKM::getConfig('urlMode'));
			$article[]=$tempi;
		}

		return $article;
	}
	
	function getInfo($id){		
		return $this->db->fetch_first("SELECT * FROM ".TABLE_PREFIX."announce WHERE id='$id' ");
	}
}




?>