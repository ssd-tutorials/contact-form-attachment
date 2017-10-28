<?php
@require_once('../inc/config.php');

try {

	if (isset($_POST['fields'])) {
		
		$expected = array(
			'type', 'fullName', 'telephone', 'email', 'enquiry'
		);
		
		$required = array(
			'type', 'fullName', 'telephone', 'email', 'enquiry'
		);
		
		$objForm = new Form();
		$objValid = new Validation();
		
		$objValid->_expected = $expected;
		$objValid->_required = $required;
		$objValid->_special = array('email' => 'email');
		
		$array = $objForm->post2ArraySerialize($_POST['fields'], $expected);
		
		$attachments = null;
		if (!empty($_POST['files'])) {
			$attachments = $_POST['files'];
		}
		
		if ($objValid->isValid($array)) {
		
			$objEmail = new Email();
			
			$objEmail->setFrom($array['email'], $array['fullName']);
			$objEmail->addReplyTo($array['email'], $array['fullName']);
			
			
			$objEmail->setSubject('Enquiry from our website');
			$objEmail->setBody($objEmail->parse(1, $array));
			
			$filesToRemove = array();
			
			if (!empty($attachments)) {
				
				foreach($attachments as $item) {
				
					rename(
						UPLOAD_PATH.DS.$item['newName'].'.'.FILE_EXT,
						UPLOAD_PATH.DS.$item['newName']
					);
					
					$objEmail->addAttachment(UPLOAD_PATH, $item['newName'], $item['oldName']);
					
					$filesToRemove[] = UPLOAD_PATH.DS.$item['newName'];
				
				}
				
			}
			
			
			$errors = array();
			
			$arrayRecip = array(
				array(
					'email' => 'sulinski.sebastian@gmail.com',
					'name' => 'Sebastian'
				),
				array(
					'email' => 'info@ssdtutorials.com',
					'name' => 'SSDTutorials'
				)
			);
			
			foreach($arrayRecip as $row) {
				$objEmail->setTo($row['email'], $row['name']);
				if (!$objEmail->send($array['type'])) {
					$errors[] = $row['email'];
				}
			}
			
			
			
			if (empty($errors)) {
				
				Helper::removeFiles($filesToRemove);
				
				$message  = '<h1>Thank you</h1>';
				$message .= '<p>Your message has been sent successfully.</p>';
				
				echo json_encode(array('error' => false, 'message' => $message));
				
			} else {
				throw new Exception($objEmail->_error);
			}
			
		
		} else {
			// some amendments have been applied here
			throw new Exception('Validation failed');
		}
		
	} else {
		throw new Exception('Missing post');
	}

} catch (Exception $e) {

	// some amendments have been applied here
	if (!empty($objValid->_errors)) {
		echo json_encode(array(
			'error' => true,
			'validation' => $objValid->_errors
		));
	} else {
		$message = $e->getMessage();
		echo json_encode(array(
			'error' => true,
			'message' => $message
		));
	}

}