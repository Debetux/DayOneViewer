<?php

class Entry implements ArrayAccess{

	protected $diary_directory;
	protected $entries_directory;
	protected $photos_directory;

	protected $filename;
	protected $filepath;
	protected $propertyListData;

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
		else $this->initNewEntry();

	}


	private function initNewEntry(){

		$this->filename = Entry::gen_uuid();
		$this->filepath = $this->entries_directory.$this->filename;


		/*
		 * create a new CFPropertyList instance without loading any content
		 */
		$this->propertyListData = new CFPropertyList();

		/*
		 * Manuall Create the sample.xml.plist 
		 */
		// the Root element of the PList is a Dictionary
		$plist->add( $dict = new CFDictionary() );

		$dict->add( 'Activity', new CFString( 'Stationary' ) );
		$dict->add( 'Creation Date', new CFDate( gmmktime() ) );

		$dict->add( $creator = new CFDictionary() );
			$creator->add( 'Device Agent', new CFString( DEVICE_AGENT ) );
			$creator->add( 'Generation Date', new CFDate( gmmktime() ) );
			$creator->add( 'Host Name', new CFString( HOST_NAME ) );
			$creator->add( 'OS Agent', new CFString( OS_AGENT ) );
			$creator->add( 'Software Agent', new CFString( SOFTWARE_AGENT ) );

		// <key>Year Of Birth</key><integer>1965</integer>
		$dict->add( 'Year Of Birth', new CFNumber( 1965 ) );

		

		// <key>Pets Names</key><array/>
		$dict->add( 'Pets Names', new CFArray() );

		// <key>Picture</key><data>PEKBpYGlmYFCPA==</data>
		// to keep it simple we insert an already base64-encoded string
		$dict->add( 'Picture', new CFData( 'PEKBpYGlmYFCPA==', true ) );

		// <key>City Of Birth</key><string>Springfield</string>
		$dict->add( 'City Of Birth', new CFString( 'Springfield' ) );

		// <key>Name</key><string>John Doe</string>
		$dict->add( 'Name', new CFString( 'John Doe' ) );

		// <key>Kids Names</key><array><string>John</string><string>Kyra</string></array>
		$dict->add( 'Kids Names', $array = new CFArray() );
		$array->add( new CFString( 'John' ) );
		$array->add( new CFString( 'Kyra' ) );


		/*
		 * Save PList as XML
		 */
		$plist->saveXML( __DIR__.'/example-create-01.xml.plist' );
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