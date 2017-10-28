<?php
class Email {

	private $_smtpHost = SMTP_HOST;
	private $_smtpAuthentication = SMTP_AUTHENTICATION;
	private $_smtpPort = SMTP_PORT;
	private $_smtpUsername = SMTP_USERNAME;
	private $_smtpPassword = SMTP_PASSWORD;
	private $_smtpEncryption = SMTP_ENCRYPTION;
	
	private $_attachments = array();
	
	private $_to = array();
	private $_from = array();
	private $_replyTo = array();
	
	private $_subject = null;
	private $_body = null;
	
	public $_error = null;
	
	
	
	
	
	public function send($class = 1) {
	
		try {
			
			if (!empty($class) && !empty($this->_to) && !empty($this->_from) && !empty($this->_subject)) {
				
				switch($class) {
					
					case 1:
					return $this->phpMailer();
					break;
					
					case 2:
					return $this->swiftMailer();
					break;
					
					case 3:
					return $this->zendMail();
					break;
					
					default:
					$this->_error = 'Case out of boundries';
					return false;
					
				}
					
			} else {
				throw new Exception('One of the mandatory items has not been defined');
			}
			
		} catch (Exception $e) {
			$this->_error = $e->getMessage();
			return false;
		}
	
	}
	
	
	
	
	
	
	
	
	
	
	
	public function parse($file = null, $data = null) {
		if (!empty($file) && is_file(EMAILS_PATH.DS.$file.'.php')) {
			ob_start();
			@include(EMAILS_PATH.DS.$file.'.php');
			return ob_get_clean();
		}
	}
	
	
	
	
	
	
	
	
	
	public function setSubject($subject = null) {
		if (!empty($subject)) {
			$this->_subject = $subject;
		}
	}
	
	
	
	
	
	
	
	
	public function setBody($body = null) {
		if (!empty($body)) {
			$this->_body = $body;
		}
	}
	
	
	
	
	
	
	
	
	public function setFrom($email = null, $name = null) {
		if (!empty($email)) {
			$name = !empty($name) ? $name : $email;
			$this->_from = array('email' => $email, 'name' => $name);
		}
	}
	
	
	
	
	
	
	
	
	public function setTo($email = null, $name = null) {
		$this->_to = array();
		if (!empty($email)) {
			$name = !empty($name) ? $name : $email;
			$this->_to = array('email' => $email, 'name' => $name);
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	public function addReplyTo($email = null, $name = null) {
		if (!empty($email)) {
			$name = !empty($name) ? $name : $email;
			$this->_replyTo[] = array('email' => $email, 'name' => $name);
		}
	}
	
	
	
	
	
	public function addAttachment($path = null, $file = null, $name = null) {
		if (!empty($path) && !empty($file) && is_file($path.DS.$file)) {
			$file = strip_tags($file);
			$name = !empty($name) ? strval(strip_tags($name)) : strval($file);
			$this->_attachments[] = array('file' => $path.DS.$file, 'name' => $name);
		}
		return false;
	}
	
	
	
	
	
	
	
	private function phpMailer() {
	
		@require_once('PHPMailer_5.2.1'.DS.'class.phpmailer.php');
		
		$objMail = new PHPMailer(true);
		
		$objMail->isSMTP();
		
		$objMail->Host = $this->_smtpHost;
		$objMail->SMTPAuth = $this->_smtpAuthentication;
		$objMail->Post = $this->_smtpPort;
		$objMail->Username = $this->_smtpUsername;
		$objMail->Password = $this->_smtpPassword;
		
		if (!empty($this->_smtpEncryption)) {
			$objMail->SMTPSecure = $this->_smtpEncryption;
		}
		
		$objMail->AddAddress($this->_to['email'], $this->_to['name']);
		$objMail->SetFrom($this->_from['email'], $this->_from['name']);
		
		if (!empty($this->_replyTo)) {
			foreach($this->_replyTo as $rt) {
				$objMail->AddReplyTo($rt['email'], $rt['name']);
			}
		}
		
		$objMail->Subject = $this->_subject;
		$objMail->AltBody = strip_tags($this->_body);
		$objMail->MsgHTML($this->_body);
		
		if (!empty($this->_attachments)) {
			foreach($this->_attachments as $attachment) {
				$objMail->AddAttachment($attachment['file'], $attachment['name']);
			}
		}
		
		return $objMail->Send();
	
	}
	
	
	
	
	
	
	
	private function swiftMailer() {
	
		require_once('Swift-4.2.1'.DS.'swift_init.php');
		
		$objTransport = Swift_SmtpTransport::newInstance();
		
		$objTransport->setHost($this->_smtpHost);
		$objTransport->setPort($this->_smtpPort);
		
		if (!empty($this->_smtpEncryption)) {
			$objTransport->setEncryption($this->_smtpEncryption);
		}
		
		$objTransport->setUsername($this->_smtpUsername);
		$objTransport->setPassword($this->_smtpPassword);
		
		$objMailer = Swift_Mailer::newInstance($objTransport);
		
		$objMessage = Swift_Message::newInstance($this->_subject);
		
		$objMessage->setBody($this->_body, 'text/html');
		$objMessage->addPart(strip_tags($this->_body), 'text/plain');
		
		$objMessage->setFrom(array($this->_from['email'] => $this->_from['name']));
		
		$objMessage->setTo(array($this->_to['email'] => $this->_to['name']));
		
		if (!empty($this->_replyTo)) {
			foreach($this->_replyTo as $rt) {
				$objMessage->setReplyTo(array(
					$rt['email'] => $rt['name']
				));
			}
		}
		
		if (!empty($this->_attachments)) {
			foreach($this->_attachments as $attachment) {
				$objMessage->attach(Swift_Attachment::fromPath($attachment['file'])->setFilename($attachment['name']));
			}
		}
		
		
		$result = $objMailer->send($objMessage);
		
		return !empty($result) ? true : false;
	
	}
	
	
	
	
	
	
	
	
	
	
	
	private function zendMail() {
	
		$objMessage = new Zend\Mail\Message();
		
		$objMessage->addTo($this->_to['email'], $this->_to['name']);
		$objMessage->setFrom($this->_from['email'], $this->_from['name']);
		
		if (!empty($this->_replyTo)) {
			foreach($this->_replyTo as $row) {
				$objMessage->addReplyTo(
					$row['email'], $row['name']
				);
			}
		}
		
		$objMessage->setSubject($this->_subject);
		
		$arrParts = array();
		$attachmentsParts = array();
		
		$partHtml = new Zend\Mime\Part($this->_body);
		$partHtml->type = 'text/html';
		$arrParts[] = $partHtml;
		
		if (!empty($this->_attachments)) {
			if (count($this->_attachments) > 1) {
				foreach($this->_attachments as $item) {
					
					$objPart = new Zend\Mime\Part(file_get_contents($item['file']));
					$mime = Helper::getMimeType($item['file']);
					
					$objPart->type = $mime;
					$objPart->disposition = Zend\Mime\Mime::DISPOSITION_ATTACHMENT;
					
					$objPart->encoding = Zend\Mime\Mime::ENCODING_BASE64;
					$objPart->filename = $item['name'];
					
					$attachmentsParts[] = $objPart;
					
				}
			} else {
				
				$file = $this->_attachments[0]['file'];
				$name = $this->_attachments[0]['name'];
				
				$objPart = new Zend\Mime\Part(file_get_contents($file));
				
				$mime = Helper::getMimeType($file);
				
				$objPart->type = $mime;
				$objPart->disposition = Zend\Mime\Mime::DISPOSITION_ATTACHMENT;
				$objPart->encoding = Zend\Mime\Mime::ENCODING_BASE64;
				$objPart->filename = $name;
				
				$attachmentsParts[] = $objPart;
				
			}
		}
		
		
		$arrParts = Helper::mergeArrays(array($arrParts, $attachmentsParts));
		
		$bodyParts = new Zend\Mime\Message();
		$bodyParts->setParts($arrParts);
		
		$objMessage->setBody($bodyParts);
		
		if ($objMessage->isValid()) {
			
			$objTransport = new Zend\Mail\Transport\Smtp();
			
			$options = array(
				'name' => 'localhost',
				'host' => SMTP_HOST,
				'port' => SMTP_PORT,
				'connection_class' => 'login',
				'connection_config' => array(
					'username' => SMTP_USERNAME,
					'password' => SMTP_PASSWORD
				)
			);
			
			if (!empty($this->_smtpEncryption)) {
				$options['connection_config']['ssl'] = $this->_smtpEncryption;
			}
			
			$objTransport->setOptions(new Zend\Mail\Transport\SmtpOptions($options));
			
			$objTransport->send($objMessage);
			return true;
			
		} else {
			throw new Exception('Validation failed');
		}
		
	
	}
	


















}













