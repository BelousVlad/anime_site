<?php 

class adminController{
	public function actionView()
	{
		session_start();

		if (!empty($_SESSION['user'])) {
			
			$user = $_SESSION['user'];
			$orders = $GLOBALS['db']->getOrders();

			$orders = $this->refactorOrdersDates($orders);

			require ROOT."/view/admin/list.php";

		}
		else
		{
			require ROOT."/view/admin/login.php";

		}
	}

	public function actionOrders()
	{
		session_start();

		if (!empty($_SESSION['user'])) {
			
			$orders = $GLOBALS['db']->getOrders();


			$orders = $this->refactorOrdersDates($orders);

			echo json_encode($orders);

		}
	}

	private function refactorOrdersDates($orders)
	{
		for ($i=0; $i < count($orders); $i++) {
			$item = $orders[$i];
			$str = date('Y-m-d\TH:i', strtotime($item["added_time"]));
			$orders[$i]["added_time"] = $str;
			if ($item["date_complete"]) {
				$str = date('Y-m-d\TH:i', strtotime($item["date_complete"]));
				$orders[$i]["date_complete"] = $str;
			}
			$orders[$i]["description"] = $orders[$i]["description"] ?? "";
		}
		return $orders;
	}

	public function actionRemove()
	{
		session_start();

		if (!empty($_SESSION['user']) && !empty($_POST['id'])) {

			$id = $_POST['id'];

			$res = $GLOBALS["db"]->removeOrder($id);

			echo "{ \"result\": \"$res\" }";
		}
	}

	public function actionSave()
	{
		session_start();

		if (!empty($_SESSION['user']) && !empty($_POST['id'])) {

			$id = $_POST['id'];
			$name =  $_POST['name'];
			$email = $_POST['email'];
			$address = $_POST['address'];
			$phone = $_POST['phone'];
			$price = $_POST['price'];
			$complete_time =  $_POST['complete_time'];
			$desc =  $_POST['description'];

			if (empty($complete_time)) {
				$complete_time = NULL;
			}

			$res = $GLOBALS["db"]->updateOrder($id, $name, $email, $address, $phone, $price, $complete_time, $desc);

			echo "{ \"result\": \"$res\" }";
		}
	}

	public function actionLogin()
	{
		session_start();

		if (empty($_SESSION['user'])) {
			$login = $_POST['login'];
			$pass = $_POST['password'];

			$res = $GLOBALS['db']->login($login, $pass);
			if ($res) {
				$_SESSION['user'] = $res;
				echo json_encode($res);
			}

		}

	}
}

?>