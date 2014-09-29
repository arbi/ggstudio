<?php
namespace ggs\api\Controller;

use ggs\api\Service\Database;

class Login
{
	const TBL_USER    = 'user';

	public function authenticate()
	{
		$parameters = func_get_args()[0];
		$username   = htmlspecialchars(trim($parameters['username']));
		$username   = preg_replace('/&|#|;|\(|\)|\/|\'/', '', $username);
		$condition  = " username ='" . $username . "'";
		$password   = htmlspecialchars(trim($parameters['password']));
		$password   = preg_replace('/&|#|;|\(|\)|\/|\'/', '', $password);

		$db = new Database();
		$result = $db->get(self::TBL_USER, null, $condition);
		if ($result) {
			if (password_verify($password, $result['password'])) {
				$_SESSION['user_id'] = $result['id'];
				$_SESSION['name']    = $result['firstname'];
				echo json_encode(
					[
						'result' => 'true',
						'name'   => $result['firstname']
					]
				);
			} else {
				echo json_encode('false');
			}
		} else {
			echo json_encode('false');
		}
	}

	public function getUserInfo()
	{
		$parameters = func_get_args()[0];

		$userId =  $parameters['userId'];
		$db = new Database();
		$result = $db->get(self::TBL_USER, $userId);
		if ($result) {
			return json_encode($result);

		} else {
			return false;
		}
	}

	public function logout()
	{
		$parameters = func_get_args()[0];
		if ($parameters['status']) {
			session_destroy();
			echo json_encode('true');
		}
	}
}