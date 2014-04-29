<?php
/**
 *
 * @author JinXi <fate.stigma@gmail.com>
 * 
 */
namespace CFPropertyList;

require_once('workflows.php');
require_once(__DIR__.'/CFPropertyList/CFPropertyList.php');

class WatchShow {
	private $_query = null;
	private $_workflow = null;
	private $_data = null;
	
	public static function factory($q) {
		return new WatchShow($q);
	}
	
	public function __construct($q) {
		$this->_workflow = new \Workflows();

		$this->_query = $q;

		$content = file_get_contents('tvlist.plist');
		$this->_data = new CFPropertyList();
		$this->_data->parseBinary($content);
	}

	//watch a watched tv show
	public function watched() {
		$ep = 1;
		$found = false;
		//find it and update its episodes
		foreach ($this->_data->getValue(true) as $key => $value) {
			if ( $key == $this->_query) {
				$ep = $value->getValue() + 1;
				$value->setValue($ep);
				$found = true;
				break;
			}
		}
		//not exist then add it in your plist
		if ($found == false) {
			$dict = $this->_data->getValue(true);
			$dict->add($this->_query, new CFNumber(1));
		}
		
		$this->_data->save('tvlist.plist', CFPropertyList::FORMAT_BINARY );
		$str = $this->_query . ' Episode ' . $ep . ' is watched!';
		return $str;
	}

	//show in alfred
	public function listInAlfred() {
		$response = $this->_data;
		$query = $this->_query;
		$int = 1;
		foreach ($response->getValue(true) as $key => $value) {
			if (stripos($key, $query) !== false) {
				$ep = $value->getValue();
				$this->_workflow->result($int.'.'.time(), "$key", "$key", "$ep", 'icon.png');
				$int++;
			}
		}
		
		$results = $this->_workflow->results();
		if (count($results) == 0)
			$this->_workflow->result('show', $this->_query, 'Cannot find this show in my property list', 'Press \'Enter\' key to add it in your plist file.', 'icon.png' );

		return $this->_workflow->toxml();
	}
}
?>
