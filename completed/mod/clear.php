<?php
@require_once('../inc/config.php');

$files = Helper::getFilesByExtension(UPLOAD_PATH, FILE_EXT);

if (!empty($files)) {
	
	foreach($files as $file) {
	
		$fileArray = explode(DS, $file);
		$fileName = array_pop($fileArray);
		$fileNameArray = explode('-', $fileName);
		$fileNameDate = array_shift($fileNameArray);
		
		$dateTime = date('Y-m-d H:i:s', strtotime(
			substr($fileNameDate, 0, 4).'-'
			.substr($fileNameDate, 4, 2).'-'
			.substr($fileNameDate, 6, 2).' '
			.substr($fileNameDate, 8, 2).':'
			.substr($fileNameDate, 10, 2).':'
			.substr($fileNameDate, 12, 2)
		));
		
		$dateTimeOver = strtotime($dateTime . ' + 1 hour');
		
		if (time() > $dateTimeOver) {
			unlink($file);
		}
	
	}
	
}