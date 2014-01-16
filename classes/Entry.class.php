<?php

class Entry implements ArrayAccess{

	protected $diary_directory;
	protected $entries_directory;
	protected $photos_directory;

	protected $filename;
	protected $filepath;

	public $entry_data_array = null;






	/**
	 * If filename is given, parse the entry, or it generates an new entry
	 */

	public function __construct($diary_directory = null, $filename = null) {
		$this->diary_directory = $diary_directory;
		$this->filename = $filename;

		if($this->diary_directory && $this->filename){
			$this->entries_directory = $this->diary_directory.'entries/';
			$this->photos_directory = $this->diary_directory.'photos/';
			$this->filepath = $this->entries_directory.$this->filename;
		}

		if($this->filename) $this->load();

	}


	/**
	 * Parse the entry xml file.
	 */

	public function load(){
		/*
		 * create a new CFPropertyList instance which loads the sample.plist on construct.
		 * since we know it's an XML file, we can skip format-determination
		 */
		$plist = new CFPropertyList\CFPropertyList($this->filepath, CFPropertyList\CFPropertyList::FORMAT_XML);
		$this->entry_data_array = $plist->toArray();
		$this->set_media();
	}


	/**
	 * Add a photos to the entry
	 */

	private function set_media(){
		$this->entry_data_array['Media URL'] = (file_exists($this->photos_directory.$this->entry_data_array['UUID'].'.jpg')) ? $this->photos_directory.$this->entry_data_array['UUID'].'.jpg' : false;
	}


	/**
	 * Generate an UUID.
	 */

	static function gen_uuid(){
		return substr(shell_exec('uuidgen | sed s/-//g'), 1);
	}


	/** Implementing ArrayAccess **/
	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->entry_data_array[] = $value;
		} else {
			$this->entry_data_array[$offset] = $value;
		}
	}
	public function offsetExists($offset) {
		return isset($this->entry_data_array[$offset]);
	}
	public function offsetUnset($offset) {
		unset($this->entry_data_array[$offset]);
	}
	public function offsetGet($offset) {
		return isset($this->entry_data_array[$offset]) ? $this->entry_data_array[$offset] : null;
	}

}