<?php
@require_once('../inc/config.php');

try {

	if (!empty($_POST['name'])) {
		
		$name = $_POST['name'];
		$file = UPLOAD_PATH.DS.$name.'.'.FILE_EXT;
		
		if (is_file($file)) {
			unlink($file);
			echo json_encode(array('error' => false));
		} else {
			throw new Exception('File not found');
		}
		
	} else {
		throw new Exception('Empty name string');
	}

} catch (Exception $e) {
	echo json_encode(array('error' => true, 'message' => $e->getMessage()));
}