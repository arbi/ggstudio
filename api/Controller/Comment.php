<?php

namespace ggs\api\Controller;

use ggs\api\Service\Database;

class Comment
{

	const TBL_COMMENT = 'comment';
	const TBL_USER    = 'user';

	public function get()
	{
		$parameters = func_get_args()[0];
		$db = Database::getInstance();

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
		$db = Database::getInstance();

		$comment = htmlspecialchars(trim($parameters['comment']));
		 $comment = preg_replace('/\'/', '', $comment);
		$date 	 = date('Y-m-d H:i:s');
		$userId  = 0;
		$userId  = !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

		$data = [
			'comment' => $comment,
			'user_id' => $userId,
			'date'    => $date
		];
		$user = "Stranger";
		if ($userId) {
			$userInfo = $db->get(self::TBL_USER, $userId);
			$user = !empty($userInfo) ? 
				$userInfo['firstname'] . ' ' . $userInfo['lastname'] : 
				'Stranger';
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
		
	}

	public function delete()
	{
		
	}
}