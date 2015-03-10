<?php !defined('IN_ART') && exit('Access Denied');

class model_poll extends wskm_model_abstract
{
	public $pageCount=15;
	function getPolls(){
		$totalcount=$this->getPollsCount();
		$startpage=multiPage_start($currpage,$this->pageCount,$totalcount);
		$list=array();
		$query=$this->db->query('SELECT pollid,hits,title,dateline FROM '.TABLE_PREFIX."polls ORDER BY pollid DESC LIMIT {$startpage},{$this->pageCount} ");
		while ($tempi=$this->db->fetch($query)){
			$tempi['mvcurl']=ART_URL.'poll.php?showid='.$tempi['pollid'];
			$list[]=$tempi;
		}
		return array('list'=>$list,'count'=>$totalcount,'page'=>$currpage);
	}
	
	function getPollsCount(){
		return $this->db->fetch_column('SELECT COUNT(*) FROM '.TABLE_PREFIX."polls ");
	}
	
	function getPoll($pollid){
		$poll=$this->db->fetch_first('SELECT title,ismore,hits,expire FROM '.TABLE_PREFIX."polls WHERE pollid='{$pollid}' " );

		$polloption=$noteid=array();
		$details='';
		if ($poll !== false) {
			$sumpoll=$this->db->fetch_column('SELECT SUM(total) FROM '.TABLE_PREFIX."pollsoptions WHERE pollid='{$pollid}' ");
			$query=$this->db->query('SELECT optionid,total,name,detail FROM '.TABLE_PREFIX."pollsoptions WHERE pollid='{$pollid}'");
			while ($tempi=$this->db->fetch($query)){
				$details.="\r".$tempi['detail'];
				$tempi['ratio']=@sprintf('%01.2f',$tempi['total']*100/$sumpoll) ;
				$tempi['width']=(int)$tempi['ratio']?$tempi['ratio'].'%':'2px';
				$polloption[]=$tempi;
			}

			if(count($polloption)<1)return false;
		}else{
			return false;
		}

		if ($details) {
			$noteid=explode("\r",$details);
		}

		$user=WSKM::user();
		$vaildkey=is_object($user)?($user->getUid()?$user->getUid():USER_IP):USER_IP;
		$poll['inputtype']=$poll['ismore']?'checkbox':'radio';
		
		//if (IS_HTML) {
		//	$poll['isput']=false;
		//}else{
			$poll['isput']=in_array($vaildkey,$noteid);
		//}
		$poll['isexpire']=$poll['expire']?(WSKM_TIME >= $poll['expire']):false;

		//return array('poll'=>$poll,'options'=>$polloption);
		$poll['options']=$polloption;
		return $poll;
	}

	function putPoll($pollid,$optionids){
		$poll=$this->db->fetch_first('SELECT ismore,expire FROM '.TABLE_PREFIX."polls WHERE pollid='{$pollid}' " );
		if ($poll == false) {
			return 'request_error';
		}
		if ($poll['expire'] && WSKM_TIME >= $poll['expire']) {
			return 'poll_err_expire';
		}
		if (!$poll['ismore'] && count($optionids)>1) {
			return 'poll_err_more';
		}

		$polloptions=$optionlist=array();
		$user=WSKM::user();
		$vaildkey=is_object($user)?($user->getUid()?$user->getUid():USER_IP):USER_IP;
		$query=$this->db->query('SELECT optionid,detail FROM '.TABLE_PREFIX."pollsoptions WHERE pollid='{$pollid}'  ");
		while ($tempi=$this->db->fetch($query)){
			if (strExists("\r".$tempi['detail']."\r","\r".$vaildkey."\r")) {
				return 'poll_err_alreadyput';
			}
			$polloptions[]=$tempi['optionid'];
		}

		foreach ($optionids as $optionid){
			if (!in_array($optionid,$polloptions)) {
				return 'request_error';
			}
			$optionlist[]=$optionid;
		}

		$optioncolumn=implode("','",$optionlist);
		if($this->db->exec('UPDATE '.TABLE_PREFIX."pollsoptions SET  total=total+1,detail=CONCAT(detail,'{$vaildkey}\r') WHERE optionid IN ('{$optioncolumn}') ") === false){
			return 'poll_bad';
		}

		if ($this->db->exec('UPDATE '.TABLE_PREFIX."polls SET hits=hits+1 WHERE pollid='{$pollid}'") === false) {
			return 'poll_bad';
		}
		return 'poll_ok';

	}

	function getPollFor($id){
		$wherestr=" pollid = '{$id}' ";
		$poll=$this->db->fetch_first('SELECT * FROM '.TABLE_PREFIX."polls WHERE {$wherestr} LIMIT 1 " );
		if ($poll !== false) {
			$query=$this->db->query('SELECT optionid,total,name FROM '.TABLE_PREFIX."pollsoptions WHERE pollid='{$poll['pollid']}'");
			while ($tempi=$this->db->fetch($query)){
				$tempi['ratio']=@sprintf('%01.2f',$tempi['total']*100/$sumpoll) ;
				$tempi['width']=(int)$tempi['ratio']?$tempi['ratio'].'%':'2px';
				$polloption[]=$tempi;
			}

			if(count($polloption)<1)return false;
		}else{
			return false;
		}
		$poll['inputtype']=$poll['ismore']?'checkbox':'radio';
		$poll['options']=$polloption;
		return $poll;
	}
}




?>