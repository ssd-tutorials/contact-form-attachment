<?php
@require_once('../inc/config.php');

try {

	$objValid = new Validation();
	
	$allowedExt = array('jpg', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx');
	
	if ($_FILES) {
		
		if ($_FILES['ssdUploadFile']['error'] == 0) {
			
			$extension = Helper::getExtension($_FILES['ssdUploadFile']['name']);
			
			if (!in_array($extension, $allowedExt)) {
				throw new Exception($objValid->_validation['file_extension']);
			}
			
			if ($_FILES['ssdUploadFile']['size'] > MAX_SIZE) {
				throw new Exception($objValid->_validation['file_size'].' of '.Helper::bytesToSize(MAX_SIZE));
			}
			
			
			$fileName = date('YmdHis').'-'.mt_rand().'.'.$extension;
			
			move_uploaded_file(
				$_FILES['ssdUploadFile']['tmp_name'], 
				UPLOAD_PATH.DS.$fileName.'.'.FILE_EXT
			);
			
			echo json_encode(array(
				'error' => false,
				'name' => $fileName,
				'nameOriginal' => $_FILES['ssdUploadFile']['name'],
				'size' => $_FILES['ssdUploadFile']['size'],
				'sizeReadable' => Helper::bytesToSize($_FILES['ssdUploadFile']['size']),
				'ext' => $extension
			));
			
			
		} else {
			throw new Exception($objValid->_validationUpload[$_FILES['ssdUploadFile']['error']]);
		}
		
	} else {
		throw new Exception($objValid->_validation['file_not_found']);
	}

} catch (Exception $e) {

	echo json_encode(array('error' => true, 'message' => $e->getMessage()));

}





