<?php
class userController{
	public function actionRegisterView()
	{
		if (isset($_POST) && empty($_SESSION['user']))
			require ROOT."/view/user/register.php";
		else
			header("Location: /");
	}
	public function actionRegister_in()
	{
		$answer = array();

		if (isset($_POST) && empty($_SESSION['user'])) {
			
			$name = $_POST['name'] ?? false;
			$email = $_POST['email'] ?? false;
			$pass = $_POST['pass'] ?? false;

			if ($name && $email && $pass) {

				$name = str_replace(array("<",">"), "=)", $name);

				if ($this->isEmail($email) && strlen($pass) > 5)
				{
					$code = $GLOBALS['db']->register($name, $email, $pass);
					$answer["status"] = $code;
				}
			}
		}

		echo json_encode($answer);
	}

	public function actionLoginView()
	{

		if (isset($_POST) && empty($_SESSION['user']))
			require ROOT."/view/user/login.php";
		else
			header("Location: /");
	}
	public function actionLogout()
	{
		unset($_SESSION['user']);

		header("Location: /");
	}

	public function actionLogin_in()
	{
		$answer = array();

		if (isset($_POST) && empty($_SESSION['user'])) {
			$email = $_POST['email'] ?? false;
			$pass = $_POST['pass'] ?? false;

			if ($email && $pass) {
				$res = $GLOBALS['db']->login($email, $pass);

				if ($res) {

					$_SESSION['user'] = $res;

					$answer['status'] = 2;
				}
				else
					$answer['status'] = 0;
			}
			else
				$answer['status'] = 0;
		}
		else
			$answer['status'] = 2;

		echo json_encode($answer);
	}

	public function actionProfile($id)
	{

		if (!empty($id)) {
			$profile = $GLOBALS['db']->getUserById($id);
			$is_self = $id == $_SESSION['user']['id'];

		}
		else {
			if (isset($_SESSION['user'])) {
				$profile = $_SESSION['user'] ?? false;
				$is_self = true;
			}
			else
			{
				header('Location: / ');
			}
		}

		$watched_list = $GLOBALS['db']->getWatchedList($profile['id']);

		require ROOT."/view/user/profile.php";


	}

	public function actionSave_info()
	{
		//var_dump($_FILES);

		$ans = array();

		if(($id = $_SESSION['user']['id']) && ($user = $_SESSION['user']))
		{
			$name = $_POST['name'] ?? false;
			$sex = $_POST['sex'] ?? false;
			$years = $_POST['year'] ?? false;

			if ($name && isset($sex) && $years) {
				$res = $GLOBALS['db']->updatePorfile($id,$name,$sex,$years);
				if ($res) {
					$_SESSION['user']['years'] = $years;
					$_SESSION['user']['name'] = $name;
					$_SESSION['user']['sex'] = $sex;
				}
			}

			if ($_FILES && $_FILES['img']['error'] == UPLOAD_ERR_OK)
			{

		    	$name = "$id.".pathinfo($_FILES['img']['name'])['extension'];

				move_uploaded_file($_FILES['img']['tmp_name'], 'sources/avatars/'.$name);

				$res2 = $GLOBALS['db']->updatePorfileImg($id,$name);

				if ($res2) {
					$_SESSION['user']['image_path'] = $name;
				}
			}
			if ($res || $res2) {
				$ans['state'] = 2;
			}
			else
				$ans['status'] = 1;

		}
		else
			$ans['status'] = 1;

		echo json_encode($ans);
	}

	private function isEmail($email)
	{
		return preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/' , $email);
	} 
}

?>