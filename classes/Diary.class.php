<?php

class Diary{

	protected $path = null;
	public $entries = null;







	public function __construct($path = null, $load = true) {
		$this->path = $path;
		if($this->path && $load = true) $this->load();
	}

	/**
	 * Return an array of all the entries, sorted by date.
	 */

	public function getEntries(){
		if(! $this->entries) $this->load();
		return $this->entries;
	}


	/**
	 * Return a pointer on an new entry object
	 */

	public function createEntry(){
		$entry = new Entry($this->path);
		return $entry;
	}

	/**
	 * Load all the entries in memory.
	 */

	public function load(){

		if ($handle = opendir($this->path.'entries/')):
			while (false !== ($file = readdir($handle))):
				if($file != '.' && $file != '..'):
					
					// for each file, we create a new entry
					$this->entries[] = new Entry($this->path, $file);

				endif;
			endwhile;
			closedir($handle);

			usort($this->entries, array('Diary', 'compare_entry'));

		endif;

	}


	/**
	 * Function for comparing/sorting entries
	 */

	static function compare_entry($a, $b){
		if ($a['UUID'] == $b['UUID']) {
			return 0;
		}

		return ($a['Creation Date'] > $b['Creation Date']) ? +1 : -1;
	}

}