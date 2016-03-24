<?php 

namespace App\Validator;

use App\Core\Validator\Validator;

class SigninValidator extends Validator 
{
	public function __construct()
	{
		$data = $_POST;
		$rules = [
			'login' => 'required|mail',
			'password' => 'required|min:8|confirmed'
		];
		parent::__construct($data, $rules);
	}
}