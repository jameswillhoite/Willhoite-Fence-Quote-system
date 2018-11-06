<?php
	/**
	 * Class Input
	 * Will get all variables from the $_POST and put into a array, which can be retrieved
	 * with the functions below
	 */

	class Input
	{
		private $allPosts;
		public function __construct()
		{
			$tempInputArray = array();
			if(isset($_POST)) {
				foreach ($_POST as $key=>$value) {
					$tempInputArray[$key] = $value;
				}
			}
			$this->allPosts = new ArrayObject($tempInputArray, 2);
			unset($tempInputArray);
		}

		public function getVar($key, $default = null) {
			if(isset($this->allPosts->{$key}))
				return $this->allPosts->{$key};
			else
				return $default;
		}

		public function getInt($key, $default = null) {
			if(isset($this->allPosts->{$key}))
				return (int)$this->allPosts->{$key};
			else
				return $default;
		}

		public function getString($key, $default = '') {
			if(isset($this->allPosts->{$key}))
				return (String)$this->allPosts->{$key};
			else
				return $default;
		}
	}