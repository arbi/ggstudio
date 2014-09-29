<?php

namespace ggs\api\Controller;

use ggs\api\Service\Database;

session_start();
require_once('./Service/Database.php');

class Comment
{

	const TBL_COMMENT = 'comment';
	const TBL_USER    = 'user';

	public function get()
	{
		$parameters = func_get_args()[0];
		$db = new Database();

		if (empty($parameters)) {
			$result = $db->getAll(self::TBL_COMMENT);
			echo json_encode($result);
		} else {
			echo json_encode('false');
		}
	}

	public function create()
	{
		$parameters = func_get_args()[0];
		$db = new Database();

		$comment = htmlentities(trim($parameters['comment']));
		$date 	 = date('Y-m-d H:i:s');
		$userId  = 0;
		$userId  = !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

		$data = [
			'comment' => $comment,
			'user_id' => $userId,
			'date'    => $date
		];
		$user = "Anonymous";
		if ($userId) {
			$userInfo = $db->get(self::TBL_USER, $userId);
			$user = !empty($userInfo) ? 
				$userInfo['firstname'] . ' ' . $userInfo['lastname'] : 
				'Anonymous';
		}
		$result  = $db->save($data, self::TBL_COMMENT);
		
		if ($result) {
			return json_encode(
				[
					'result'  => 'true',
					'user'	  => $user,
					'comment' => $comment,
					'date'    => $date
				]
			);
		}
		
	}

	public function update()
	{
		$parameters = func_get_args();
		$db = new Database();
		// $db->getConnection();


		$title   = 'arbi'; //htmlentities(trim($parameters['title']));
		$comment = 'yesss'; //htmlentities(trim($parameters['comment']));
		$date 	 = date('Y-m-d');
		$userId  = '234'; //$_SESSION['user_id'];

		$data = [
			'title'   => 'a',
			'comment' => 'yess',
			'user_id' => '234',
			'date'    => $date
		];
		$result  = $db->save($id, $data, self::TBL_COMMENT);	
	}

	public function delete()
	{
		$parameters = func_get_args();
		var_dump('delete', $parameters);exit;
	}
}