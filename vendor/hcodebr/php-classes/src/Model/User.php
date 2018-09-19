<?php

namespace Hcode\Model;
use \Hcode\DB\Sql;
use \Hcode\Model;
require_once("Model.php");

class User extends Model {

	const SESSION = "User";

public static function login($login, $password){

	$sql = new Sql();
	$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
		":LOGIN"=>$login
		));

	if (count($results)===0)
	{
		throw new \Exception("Usuario inexistente ou senha invalida");
	}

	$data = $results[0];

	if(password_verify($password, $data["despassword"]) === true){
	// a senha no banco está encriptada, isso testa se o hash "encriptação" da senha no banco é igual com a senha que foi passada na tela do login.(ele tira a decodifica e testa)
		$user = new User();

		$user->setData($data);

		$_SESSION[User::SESSION] = $user->getValues();

		return $user;



	}else{
		throw new \Exception("Usuario inexistente ou senha invalida");

	}

}

public static function verifyLogin($inademin = true){

	if(
		!isset($_SESSION[User::SESSION])
		||
		!$_SESSION[User::SESSION]
		||
		!(int)$_SESSION[User::SESSION]["iduser"] > 0
		||
		(bool)$_SESSION[User::SESSION]["indamin"] !== $inademin
		)
	{
		header("Location: /admin/login");
		exit;
	}


}

public static function logout (){

		$_SESSION[User::SESSION] = NULL;
}
}


?>