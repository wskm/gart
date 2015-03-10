<?php !defined('IN_WSKM') && exit('Access Denied');
/*
*	WskmPHP Framework
*
*	Copyright (c) 2009 WSKM Inc.
*
*
*/

define('MAIL_SYS',0);
define('MAIL_PHPMAILER',1);

class wskm_email{
	private $Host='xxx';
	private $Charset='UTF-8';
	private $Language='zh';
	private $From='admin@admin.com';
	private $FromName='administrators';
	private $Username='';
	private $Password='';
	private $Port=25;
	private $Delimiter=0;
	
	private $phpemailerDir='';
	public $emaillist=array();

	private $mode='smtp';
	public $mailer=null;
	public $pop=null;


	public function __set($name,$value)
	{
		if (isset($this->$name)) {
			$this->$name=$value;

			if ($name != 'mode' && $name !='mailer' && $name != 'pop' && $name !='$phpemailerDir') {
				$this->mailer->$name=$value;
			}

			return ;
		}

		throw new wskm_exception('class wskm_email not val:'.$name);
	}

	public function __get($name)
	{
		if (isset($this->$name)) {
			return 	$this->$name;
		}

		throw new wskm_exception('class wskm_email not val:'.$name);
	}

	public function addAttachment($path, $name = '', $encoding = 'base64', $type = 'application/octet-stream')
	{
		if (file_exists($path)) {
			$this->mailer->AddAttachment($path,$name,$encoding,$type);
			return true;
		}
		return false;
	}

	public function addAddress($address,$name='')
	{
		if (isEmail($address)) {
			$this->emaillist[]=$address;
			$this->mailer->AddAddress($address,$name);
		}
	}

	public static function sendMail($subject,$body,$address,$htmlstyle=1){
		if (!$subject || !$body || !$address ) {
			throw  new wskm_exception('SendMail must provide the necessary parameters!');
		}

		$mail=new wskm_email();
		$mail->Host=WSKM::getConfig('emailHost');
		$mail->Charset=WSKM::getConfig('emailCharset');
		$mail->Language=WSKM::getConfig('emailLanguage');
		$mail->From=WSKM::getConfig('emailFrom');
		$mail->FromName=WSKM::getConfig('emailFromName');
		$mail->Username=WSKM::getConfig('emailUserName');
		$mail->Password=WSKM::getConfig('emailPassword');
		$mail->Port=WSKM::getConfig('emailPort');
		$mail->Delimiter=$delimiter=WSKM::getConfig('emailDelimiter');
		$mailtype=WSKM::getConfig('emailType');

		$data='';
		if ($mailtype == MAIL_PHPMAILER) {
			WSKM::import(WSKM_PLUGPATH.'phpmailer'.DS.'class.phpmailer.php');

			$mail->mailer=new PHPMailer();
			if ($mail->Language=='zh' || $mail->Language=='en') {
				$mail->mailer->SetLanguage($mail->Language);
			}
			else
			{
				$mail->mailer->SetLanguage('zh');
			}

			$mail->mailer->PluginDir=$mail->mailer->phpemailerDir=WSKM_PLUGPATH.'phpmailer'.DS;

			$mail->mailer->CharSet = $mail->Charset;
			$mail->mailer->Host =$mail->Host;
			$mail->mailer->Username = $mail->Username;
			$mail->mailer->Password = $mail->Password;
			$mail->mailer->From  = $mail->From;
			$mail->mailer->FromName = $mail->FromName;
			$mail->mailer->Port = $mail->Port;

			if (is_array($address)) {
				foreach ($address as $em){
					$mail->addAddress($em);
				}
			}else{
				$mail->addAddress($address);
			}

			if (count($mail->emaillist) > 0 ) {
				return $mail->send($subject,$body,$htmlstyle);
			}

			$data = 'EMAIL_EMPTY';
		}elseif ($mailtype==MAIL_SYS){
			$delimiter = $delimiter == 0 ? "\n" : ($delimiter == 2 ? "\r" : "\r\n");
			$subject = '=?'.$mail->Charset.'?B?'.base64_encode( str_replace(array("\r","\n"),'',$subject) ).'?=';
			$body = chunk_split(base64_encode(str_replace("\r\n.", " \r\n..", str_replace("\n", "\r\n", str_replace("\r", "\n", str_replace("\r\n", "\n", str_replace("\n\r", "\r", $body)))))));

			$mailfrom=$mail->From;
			if ($mail->FromName) {
				$mailfrom = '=?'.$mail->Charset.'?B?'.base64_encode($mail->FromName)."?= <{$mail->From}>";
			}

			$toaddresss=array();
			if (is_array($address)) {
				foreach ($address as $em){
					$toaddresss[] = preg_match('/^(.+?) \<(.+?)\>$/',$em, $to) ?  '=?'.$mail->Charset.'?B?'.base64_encode($to[1])."?= <$to[2]>" : $em;
				}
			}else{
				$toaddresss[] = preg_match('/^(.+?) \<(.+?)\>$/',$address, $to) ?  '=?'.$mail->Charset.'?B?'.base64_encode($to[1])."?= <$to[2]>" : $address;
			}
			$address = implode(',', $toaddresss);
			$htmlstyle = $htmlstyle ? 'text/html' : 'text/plain';

			$headers = "From: {$mailfrom}{$delimiter}X-Priority: 3{$delimiter}X-Mailer: Gart{$delimiter}MIME-Version: 1.0{$delimiter}Content-type: {$htmlstyle}; charset={$mail->Charset}{$delimiter}Content-Transfer-Encoding: base64{$delimiter}";

			if (function_exists('mail')) {
				$data=@mail($address, $subject, $body, $headers) ? '':'ERROR';
			}

		}

		return $data;
	}

	public function send($subject,$body,$htmlsytle=1)
	{
		$msg='';

		$time=date('Y-m-d H:i',time());

		$this->mailer->Subject=$subject;

		$htmlfile=$this->phpemailerDir.'html'.DS.'style_'.$htmlsytle.'.html';
		if (file_exists($htmlfile)) {
			$msg  = $this->mailer->getFile($htmlfile);
			$msg  = str_replace('<$info$>',$time,$msg);
			$msg  = str_replace('<$title$>',$subject,$msg);
			$msg  = str_replace('<$content$>',$body,$msg);
			$msg  = str_replace('<$name$>',$this->FromName,$msg);
		}
		if (empty($msg)) {
			$msg=$body;
		}
		$this->mailer->MsgHTML($msg);

		if ($this->mode=='smtp') {
			$this->mailer->IsSMTP();
			$this->sendSmtp();
		}
		elseif ($this->mode=='pop3'){
			$this->sendPop3();
		}

		if($this->mailer->Send()){
			return '';
		}
		else
		{
			return $this->mailer->ErrorInfo;
		}
	}

	public function clearAddress()
	{
		$this->mailer->ClearReplyTos();
		$this->mailer->ClearAllRecipients();
	}

	public function sendSmtp()
	{
		if (!empty($this->Password) && !empty($this->Username)) {
			$this->mailer->SMTPAuth = true;
		}
	}

	public function sendPop3()
	{
		$this->pop=new POP3();
		$this->pop->Authorise($this->Host, 110, $this->Port, $this->UserName , $this->Password, 1);

	}

}

?>