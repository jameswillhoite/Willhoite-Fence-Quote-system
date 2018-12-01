<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 11/30/18
	 * Time: 10:24 PM
	 */

	class ProjectHelpers
	{
		/**
		 * Will format a phone number like (123) 456-7890
		 * @param string $phone
		 *
		 * @return null|string
		 */
		public function formattedPhone(string $phone) {
			$phone = preg_replace('/[^\d]/', '', trim($phone));
			$newPhone = null;
			//Phone is like 1234567 then add area code
			if(strlen($phone) == 7) {
				$newPhone = "(937) " . substr($phone, 0, 3) . '-' . substr($phone, 3);
			}
			elseif (strlen($phone) == 10) {
				$newPhone = "(" . substr($phone, 0, 3) . ') ' . substr($phone, 3, 3) . '-' . substr($phone, 6);
			}
			else {
				$newPhone = $phone;
			}

			return $newPhone;
		}
	}