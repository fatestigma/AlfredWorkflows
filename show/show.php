<?php
/**
 *
 * @author JinXi <fate.stigma@gmail.com>
 * 
 */
namespace CFPropertyList;

require_once('workflows.php');
require_once(__DIR__.'/source/CFPropertyList/CFPropertyList.php');
require_once(__DIR__.'/source/pinyin.php');

class WatchShow {
	private $_query = null;
	private $_workflow = null;
	private $_data = null;
	private $_path = null;
	
	public static function factory($q) {
		return new WatchShow($q);
	}
	
	public function __construct($q) {
		$this->_workflow = new \Workflows();
		$this->_query = $q;
		$this->_path = $this->_workflow->data() . '/tvlist.plist';
		if (!file_exists($this->_path)) {
			if (file_exists(__DIR__.'/tvlist.plist')){
				$plist = new CFPropertyList(__DIR__.'/tvlist.plist');
				unlink(__DIR__.'/tvlist.plist');
			}
			else {
				$plist = new CFPropertyList();
				$plist->add( $dict = new CFDictionary() );
				$plist->add( $dict2 = new CFDictionary() );
				$dict2->add( 'blank', new CFNumber(0) );
			}
			$plist->saveBinary($this->_path);
		}
		$this->_data = new CFPropertyList($this->_path);
	}

	//watch a watched tv show
	public function watched() {
		$content = $this->_data->getValue(true)->get(0)->getValue();
		$key = $this->_query;
		$ep = -1;
		//find it and update its episodes
		if (substr($key, 0, 4)=="init") {
			$array = split(" ", $key, 3);
			$ep = (int)$array[1];
			$key = $array[2];
		}
		if (array_key_exists($key, $content)) {
			$value = $content[$key];
			$this->saveHistory($key, $value);
			if ($ep == -1) {
				$ep = $value->getValue()+1;
			}
			$value->setValue($ep);		
		} else {
			$dict = $this->_data->getValue(true)->get(0);
			if ($ep == -1)
				$ep = 1;
			$dict->add($key, new CFNumber($ep));
		}
		$this->_data->save($this->_path, CFPropertyList::FORMAT_BINARY );
		$str = $key . ' Episode ' . $ep . ' is watched!';
		return $str;
	}
	
	public function finished() {
		$dict = $this->_data->getValue(true)->get(0);
		$content = $dict->getValue();
		$key = $this->_query;
		
		if (array_key_exists($key, $content)) {
			$this->saveHistory($key, $content[$key]);
			$dict->del($key);
			$str = 'This season of ' . $this->_query . ' is finished.';
			$this->_data->save($this->_path, CFPropertyList::FORMAT_BINARY );
		} else {
			$str = $this->_query . ' is not in your plist or already deleted!';
		}
		return $str;
	}

	public function undo() {
		$dict = $this->_data->getValue(true)->get(0);
		$content = $dict->getValue();
		foreach ($this->_data->getValue(true)->get(1)->getValue() as $key => $episode) {
			$ep = $episode->getValue();
			if (array_key_exists($key, $content)) {
				$value = $content[$key];
				$value->setValue($ep);		
			} else {	//not exist then add it in your plist
				$dict->add($key, new CFNumber($ep));
			}
			$this->_data->save($this->_path, CFPropertyList::FORMAT_BINARY );
			return 'Undo ' . $key . ' with epidode ' . $ep . ' successfully!';
		}
	}
	
	//show in alfred show all if displayAll
	public function listInAlfred($displayAll=false) {
		$response = $this->_data->getValue(true)->get(0);
		$query = $this->_query;
		$int = 1;
		$prefix = "";
		
		if ($query=="undo"){	// undo last change
			$this->_workflow->result($int.'.'.time(), "undo", "undo", "undo last watch", 'icon.png');
			$int++;
		}
		
		if (substr($query, 0, 4)=="init") {
			$array = split(" ", $query,3);
			$eps = $array[1];
			$query = $array[2];
			$prefix = "init ".$eps." ";
		}
		
		foreach ($response->getValue() as $key => $value) {
			if ($displayAll) {	// display all
				$ep = $value->getValue();
				$this->_workflow->result($int.'.'.time(), "$key", "$key", "$ep", 'icon.png');
				$int++;
				continue;
			}
			
			$name = $key;
			if ($this->check_str($key)!=1)
				$key = \Pinyin($key,1);
			if (stripos($key, $query) !== false || stripos($name, $query) !== false) {
				$ep = $value->getValue();
				$this->_workflow->result($int.'.'.time(),$prefix . $name, $name, "$ep", 'icon.png');
				$int++;
			}
			
		}
		
		$results = $this->_workflow->results();
		if (count($results) == 0)
			$this->_workflow->result('show', $this->_query, 'Cannot find this show in my property list', 'Press \'Enter\' key to add it in your plist file.', 'icon.png' );
		else
			$this->_workflow->result(0, $this->_query, $this->_query . ' is not in the list', 'Press \'Enter\' key to add it in your plist file.', 'icon.png' );

		return $this->_workflow->toxml();
	}
	
	private function check_str($str=''){
		if(trim($str)==''){
			return '';
		}
		$m=mb_strlen($str,'utf-8');
		$s=strlen($str);
		if($s==$m){
			return 1;
		}
		if($s%$m==0&&$s%3==0){
			return 2;
		}
		return 3;
	}
	
	private function saveHistory($varkey, $varvalue) {
		$content = $this->_data->getValue(true)->get(1);
		foreach ($content->getValue() as $key => $value) {
			$content->del($key);
		}
		$content->add($varkey, new CFNumber($varvalue->getValue()));
	}
	
}
?>
