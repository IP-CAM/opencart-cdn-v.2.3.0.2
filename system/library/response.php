<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Response class
*/
class Response {
	private $headers = array();
	private $level = 0;
	private $output;

	/**
	 * Constructor
	 *
	 * @param	string	$header
	 *
 	*/
	public function addHeader($header) {
		$this->headers[] = $header;
	}
	
	/**
	 * 
	 *
	 * @param	string	$url
	 * @param	int		$status
	 *
 	*/
	public function redirect($url, $status = 302) {
		header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url), true, $status);
		exit();
	}
	
	/**
	 * 
	 *
	 * @param	int		$level
 	*/
	public function setCompression($level) {
		$this->level = $level;
	}
	
	/**
	 * 
	 *
	 * @return	array
 	*/
	public function getOutput() {
		return $this->output;
	}
	
	/**
	 * 
	 *
	 * @param	string	$output
 	*/	
	public function setOutput($output) {
		$this->output = $output;
	}
	
	/**
	 * 
	 *
	 * @param	string	$data
	 * @param	int		$level
	 * 
	 * @return	string
 	*/
	private function compress($data, $level = 0) {
		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)) {
			$encoding = 'gzip';
		}

		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false)) {
			$encoding = 'x-gzip';
		}

		if (!isset($encoding) || ($level < -1 || $level > 9)) {
			return $data;
		}

		if (!extension_loaded('zlib') || ini_get('zlib.output_compression')) {
			return $data;
		}

		if (headers_sent()) {
			return $data;
		}

		if (connection_status()) {
			return $data;
		}

		$this->addHeader('Content-Encoding: ' . $encoding);

		return gzencode($data, (int)$level);
	}
	
	/**
	 * 
 	*/
	public function output() {
		if ($this->output) {
			$output = $this->level ? $this->compress($this->output, $this->level) : $this->output;
			if (!headers_sent()) {
				foreach ($this->headers as $header) {
					header($header, true);
				}
			}
			echo $this->cdn_output($output);
		}
	}


function cdn_output($result) {
	$dbObject = new DB(DB_DRIVER,DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE,DB_PORT);
	$query = $dbObject->query("SELECT * FROM " . DB_PREFIX . "setting WHERE code = 'cdn'");
	if(!$query->rows){
		return $result;
	}
	foreach ($query->rows as $results) {
			if (!$results['serialized']) {
				$cdn_data[$results['key']] = $results['value'];
			} else {
				$cdn_data[$results['key']] = json_decode($results['value'], true);
			}
		}
	$cdn_data['cdn_domain'] = (isset($cdn_data['cdn_domain'])) ? $cdn_data['cdn_domain'] : '';
	$cdn_data['cdn_images'] = (isset($cdn_data['cdn_domain'])) ? $cdn_data['cdn_domain'] : '';
	$cdn_data['cdn_js'] = (isset($cdn_data['cdn_domain'])) ? $cdn_data['cdn_domain'] : '';
	$cdn_data['cdn_css'] = (isset($cdn_data['cdn_domain'])) ? $cdn_data['cdn_domain'] : '';
	$cdn_data['cdn_theme']='default';
	$template = (isset($cdn_data['cdn_theme'])) ? $cdn_data['cdn_theme'] : $cdn_data['cdn_theme'];	
	
		
	if ($cdn_data['cdn_status']) {
		$cdn_protocol = (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) ? 'https://' : 'http://';
		$cdn_domain = $cdn_protocol . $cdn_data['cdn_domain'];
		
		
		/*******edited to extract absolute path*******/
		
		$serverWithWWW = $_SERVER['SERVER_NAME'];
		$array = explode('www.',$serverWithWWW);
		
		if(isset($array[1])){
		    $serverWithoutWWW = $array[1];
		}else {
			$serverWithoutWWW = $array[0];
		}
		/*******edited to extract absolute path*******/
		
		if ($cdn_data['cdn_images']) {
			$result = str_replace(DIR_IMAGE, $cdn_domain . '/image/', $result);
			
			/**********edited for absolute path images******************/
			$result = str_replace('http://'.$serverWithoutWWW.'/image/', $cdn_domain . '/image/', $result);
			$result = str_replace('https://'.$serverWithoutWWW.'/image/', $cdn_domain . '/image/', $result);
			/**********edited for absolute path images******************/
			
			
			$result = str_replace('src="catalog/view/theme/' . $template . '/image/', 'src="' . $cdn_domain . '/catalog/view/theme/' . $template . '/image/', $result);
			$result = str_replace('src="catalog/view/theme/default/image/', 'src="' . $cdn_domain . '/catalog/view/theme/default/image/', $result);
		}
		if ($cdn_data['cdn_js']) {
			$result = str_replace('src="catalog/view/javascript/', 'src="' . $cdn_domain . '/catalog/view/javascript/', $result);
		}
		if ($cdn_data['cdn_css']) {
			$result = str_replace('href="catalog/view/theme/' . $template . '/stylesheet/', 'href="' . $cdn_domain . '/catalog/view/theme/' . $template . '/stylesheet/', $result);
			$result = str_replace('href="catalog/view/theme/default/stylesheet/', 'href="' . $cdn_domain . '/catalog/view/theme/default/stylesheet/', $result);
		}
	}
	return $result;
}

}
