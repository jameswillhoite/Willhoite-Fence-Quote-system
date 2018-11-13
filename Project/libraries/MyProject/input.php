<?php
	/**
	 * Class Input
	 * Will get all variables from the $_POST and put into a array, which can be retrieved
	 * with the functions below
	 */

	class Input
	{
		/**
		 * Storage of all $_POST variables
		 * @var ArrayObject
		 */
		private $allPosts;
		/**
		 * Class Files
		 * @var Files
		 */
		public $files;
		public function __construct()
		{
			$tempInputArray = array();
			//If $_POST is set then get all variables and place in array
			if(isset($_POST)) {
				foreach ($_POST as $key=>$value) {
					$tempInputArray[$key] = $value;
				}
			}
			//Convert that array to an Assoc Object
			$this->allPosts = new ArrayObject($tempInputArray, 2);
			unset($tempInputArray);

			//Initialize the Files Class
			$this->files = new Files();
		}

		/**
		 * Get Variable as it lives in the array
		 * @param String     $key
		 * @param null      $default    default value if none present
		 *
		 * @return null|mixed
		 */
		public function getVar($key, $default = null) {
			if(isset($this->allPosts->{$key}))
				return $this->allPosts->{$key};
			else
				return $default;
		}

		/**
		 * Get Variable and cast to an Int
	     * @param String     $key       key to return
		 * @param null       $default   default value if key doesn't exist
		 *
		 * @return int|null
		 */
		public function getInt($key, $default = null) {
			if(isset($this->allPosts->{$key}))
				return (int)$this->allPosts->{$key};
			else
				return $default;
		}

		/**
		 * Get Variable and Cast to a String
		 * @param string     $key       Key to return
		 * @param string     $default   Default Value if key doesn't exist
		 *
		 * @return string
		 */
		public function getString($key, $default = '') {
			if(isset($this->allPosts->{$key}))
				return (String)$this->allPosts->{$key};
			else
				return $default;
		}
	}

	class Files {
		protected $files;
		public function __construct()
		{
			$temp = array();
			//If $_FILES exists then put these into an array
			if(isset($_FILES)) {
				foreach ($_FILES as $key => $value) {
					$temp[$key] = $value;
				}
			}
			//Convert array to Object
			$this->files = new ArrayObject($temp, 2);
			unset($temp);
		}

		/**
		 * Get the Value from the array if key exists
		 * @param string $name
		 *
		 * @return null
		 */
		public function get(string $name) {
			if(isset($this->files->{$name}))
				return $this->files->{$name};
			else
				return null;
		}

	}