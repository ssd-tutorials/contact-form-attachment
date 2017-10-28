<?php
class Validation {

	private $_errors = array();
	
	public $_validation = array(
		'class' => 'Please select the class',
		'fullName' => 'Please provide your full name',
		'telephone' => 'Please provide your telephone number',
		'email' => 'Please provide your valid email address',
		'enquiry' => 'Please provide your enquiry',
				
		'file_not_found' => 'File could not be found',
		'file_size' => 'The uploaded file exceeds the maximum file size',
		'file_extension' => 'Invalid file extension'
	);
	
	public $_validationUpload = array(
		0 => 'There is no error, the file uploaded with success',
		1 => 'The uploaded file exceeds the maximum file size',
		2 => 'The uploaded file exceeds the maximum file size',
		3 => 'The uploaded file was only partially uploaded',
		4 => 'No file was uploaded',
		6 => 'Missing a temporary folder',
		7 => 'Failed to write file to disk',
		8 => 'A PHP extension stopped the file upload'
	);
	
	public $_expected = array();
	public $_required = array();
	public $_special = array();
	public $_out = array();
	
	
	
	
	public function add2Errors($key = null, $index = null) {
		if (!empty($key)) {
			$index = !empty($index) ? $index : $key;
			if (!array_key_exists($key, $this->_errors)) {
				$this->_errors[$key] = '<span class="warning">'.$this->_validation[$index].'</span>';
			}
		}
	}
	
	
	
	
	
	
	
	
	
	private function special($key = null, $value = null) {
		if (!empty($key) && !Helper::isEmpty($value)) {
			switch($key) {
				case 'email':
				return $this->validEmail($value);
				break;
			}
		}
		return false;
	}
	
	
	
	
	
	
	
	
	private function validEmail($email = null) {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}
	
	
	
	
	
	
	
	
	public function isValid($array = null) {
		if (!empty($array) && is_array($array)) {
			if (!empty($this->_expected)) {
				foreach($this->_expected as $key) {
					if (array_key_exists($key, $array) && !Helper::isEmpty($array[$key])) {
						$this->_out[$key] = $array[$key];
					} else {
						$this->add2Errors($key);
					}
				}
			}
			if (!empty($this->_required)) {
				foreach($this->_required as $key) {
					if (!array_key_exists($key, $this->_out)) {
						$this->add2Errors($key);
					}
				}
			}
			if (!empty($this->_special)) {
				foreach($this->_special as $key => $value) {
					if (array_key_exists($key, $this->_out) && !$this->special($value, $this->_out[$key])) {
						$this->add2Errors($key);
					}
				}
			}
			return !empty($this->_errors) ? false : true;
		}
		return false;
	}
	
	
	
	



}












