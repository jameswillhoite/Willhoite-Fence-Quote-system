<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 11/23/18
	 * Time: 8:25 PM
	 */

	class ProjectConfig
	{
		const LOGIN_EXPIRE = 60; // 60 Minutes

		const BASE_URL = "http://ps11.pstcc.edu/~c2375a19/Project";

		const DB_HOST = 'localhost';

		const DB_USER = 'quote';

		const DB_PASS = '9kKp9pglbRqszX7N';

		public function getExpire() {
			$d = new DateTime();
			$di = new DateInterval('PT' . self::LOGIN_EXPIRE.'M');
			$d->add($di);
			return $d;
		}

		public static function getBaseUrl() {
			$baseURL = str_replace(basename(__FILE__), '', $_SERVER['SCRIPT_URI']) . '/../..';
			return $baseURL;
		}

	}