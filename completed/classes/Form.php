<?php
class Form {




	public function post2ArraySerialize($post = null, $expected = null) {
	
		if ($post) {
		
			$out = array();
			
			foreach($post as $row) {
				
				$key = $row['name'];
				$value = $row['value'];
				
				if (!empty($expected)) {
					
					$expected = is_array($expected) ? $expected : array($expected);
					
					if (in_array($key, $expected)) {
						$out[$key] = strip_tags($value);
					}
					
				} else {
					$out[$key] = strip_tags($value);
				}
				
			}
			
			return $out;
			
		}
		
	}




}