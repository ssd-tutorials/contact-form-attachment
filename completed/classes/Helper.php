<?php
class Helper {




	public static function isEmpty($value = null) {
		return empty($value) && !is_numeric($value) ? true : false;
	}
	
	
	
	
	
	
	
	
	public static function printArray($array = null) {
		if (!empty($array)) {
			ob_start();
			echo '<pre>';
			print_r($array);
			echo '</pre>';
			return ob_get_clean();
		} else {
			return 'Array is empty';
		}
	}
	
	
	
	
	
	
	
	
	public static function getExtension($file = null) {
		if (!empty($file)) {
			$file = explode(".", $file);
			if (count($file) > 1) {
				return array_pop($file);
			}
		}
	}
	
	
	
	
	
	
	
	
	
	public static function getFilesByExtension($directory = null, $extensions = null) {
		$extensions = is_array($extensions) ? $extensions : array($extensions);
		$extensions = "*.".implode(", *.", $extensions);
		$files = glob($directory.DS."{$extensions}", GLOB_BRACE);
		return $files;
	}
	
	
	
	
	
	
	
	
	public static function bytesToSize($bytes = 0, $precision = 2) {
	
		$kilobyte = 1024;
		$megabyte = $kilobyte * 1024;
		$gigabyte = $megabyte * 1024;
		$terabyte = $gigabyte * 1024;
				
		if ($bytes >= 0 && $bytes < $kilobyte) {
			return $bytes . 'b';
		} else if ($bytes >= $kilobyte && $bytes < $megabyte) {
			return round($bytes / $kilobyte, $precision) . 'kb';
		} else if ($bytes >= $megabyte && $bytes < $gigabyte) {
			return round($bytes / $megabyte, $precision) . 'mb';
		} else if ($bytes >= $gigabyte && $bytes < $terabyte) {
			return round($bytes / $gigabyte, $precision) . 'gb';
		} else if ($bytes >= $terabyte) {
			return round($bytes / $terabyte, $precision) . 'tb';
		} else {
			return $bytes . 'b';
		}
		
	}
	
	
	
	
	
	
	
	
	
	
	public static function mergeArrays($array = null) {
		if (!empty($array)) {
			$out = array();
			foreach($array as $key => $value) {
				if (!empty($value)) {
					$value = is_array($value) ? $value : array($array);
					if (!empty($out)) {
						$out = array_merge($out, $value);
					} else {
						$out = $value;
					}
				}
			}
			return $out;
		}
	}
	
	
	
	
	
	
	
	
	
	public static function getMimeType($file = null) {
		if (!empty($file) && is_file($file)) {
			if (class_exists('finfo', false)) {
				$objFinfo = new finfo(FILEINFO_MIME);
				$type = $objFinfo->file($file);
				return substr($type, 0, strpos($type, ';'));
			} else if (function_exists('mime_content_type')) {
				$mimetype = mime_content_type($file);
				return $mimetype;
			}
			return false;
		}
		return false;
	}
	
	
	
	
	
	
	
	
	
	public static function removeFiles($filesToRemove = null) {
		if (!empty($filesToRemove)) {
			$filesToRemove = is_array($filesToRemove) ? $filesToRemove : array($filesToRemove);
			foreach($filesToRemove as $file) {
				if (is_file($file)) {
					unlink($file);
				}
			}
		}
	}
	
	
	
	
	
	




}












