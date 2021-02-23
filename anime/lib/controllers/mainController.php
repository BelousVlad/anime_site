<?

class mainController{
	public function actionView()
	{
		$titles = $GLOBALS['db']->getTitles();
		$genres = $GLOBALS['db']->getGenres();

		require ROOT."/view/main.php";
	}

	public function actionTitle_status()
	{
		$ans = array();
		//var_dump(isset($_SESSION['user']));
		//var_dump($status = $_POST['status']);
		if (isset($_SESSION['user']) ) {
			$id = $_POST['id'] ?? false;
			$status = $_POST['status'] ?? false;

			if ($id && $status) {

				$status = $status === 'true' ? true: false;

				if ($GLOBALS['db']->setStatus($_SESSION['user']['id'], $id, $status)) {
					$ans['status'] = 2;
					echo json_encode($ans);
				}
			}
			else
			{
				$ans['status'] = 1;
				echo json_encode($ans);
			}
		}
	}

	public function actionFilteredList()
	{
		if(!empty($_POST['filters'])){

			//var_dump($_POST);
			//echo '\nEND\n';
			$titles = $GLOBALS['db']->getTitles();

			//var_dump($titles);

			$filtered = array();

			$filter_title = $_POST['filters']['title'];
			$filter_genre = $_POST['filters']['genres'];
			$filter_year_start = $_POST['filters']['year']['year_start'];
			$filter_year_end = $_POST['filters']['year']['year_end'];

			foreach ($titles as &$title) {
				$is_match = true;

				//TITLES filter
				
				if (!empty($filter_title)) {

					$is_match = strpos( $title['primary_name'], $filter_title );

					//var_dump(0 !== false);

					if ($is_match === false) {
						foreach ($title['alter_names'] as $name) {
							$is_match = strpos( $name, $filter_title );
							//var_dump($is_match);
							//var_dump($name);
							//var_dump($filter_title);
							if ($is_match === false)
								break;

						}
					}
				}
				
				//GENRE filter
				if ($is_match !== false && !empty($filter_genre)) {

					$is_match = !empty($title['genres']);

					if ($is_match)
						foreach ($filter_genre as $genre) {

							$is = false;

							foreach ($title['genres'] as $item) {
								if ($item['id'] == $genre) {
									$is = true;
									break;
								}
							}

							$is_match = $is;
							//echo "$genre - $is_match";

							if (!$is_match)
								break;


						}

				}

				//YEAR filter
				
				if ($is_match !== false && !empty($_POST['filters']['year'])) {
					if (!empty($filter_year_start) && !empty($filter_year_end)) {
						$is_match = $title['year'] >= $filter_year_start && $title['year'] <= $filter_year_end;
					}
					else if (empty($filter_year_start) && !empty($filter_year_end)) {
						$is_match = $title['year'] <= $filter_year_end;
					}
					else if (!empty($filter_year_start) && empty($filter_year_end)) {
						$is_match = $title['year'] >= $filter_year_start;
					}
					
				}
				

				if (empty($title['preview_path'])) {
					$title['preview_path'] = "not_found.png";
				}

				if ($is_match !== false) {
					array_push($filtered, $title);
				}

				$is_match = true;
			}
			echo json_encode($filtered);
		}
	}

	public function actionTitle($id)
	{
		$title = $GLOBALS['db']->getTitle($id);
		$comments = $GLOBALS['db']->getComments($id);


		if (isset($_SESSION['user'])) {
			$status = $GLOBALS['db']->getStatus($_SESSION['user']['id'], $id);
		}

		require ROOT."/view/title/title_page.php";
	}

	public function actionEdit($id)
	{
		if (isset($_SESSION['user']) && $_SESSION['user']['type'] == 1) {
			$title = $GLOBALS['db']->getTitle($id);
			$user = $_SESSION['user'];
			$genres = $GLOBALS['db']->getGenres();
			$actors = $GLOBALS['db']->getVoiceActors();

			if ($title) {
				
				require ROOT.'/view/admin/edit.php';

			}
			else
				header('Location: /');
		}
		else
			header('Location: /');
	}
	
	public function actionEdit_save_addition_info()
	{
		$user = $_SESSION['user'];

		$ans = array();

		if (isset($user) && $user['type'] == 1) {
			
			$id = $_POST['title_id'] ?? false;
			$genres = $_POST['genres'] ?? false;

			if ($genres && $id) {
				$ans['genres_state'] = $GLOBALS['db']->setGenres($id,$genres);
			}

			$year = $_POST['year'] ?? false;
			$original = $_POST['original'] ?? false;
			$rating = $_POST['rating'] ?? false;
			$desc = $_POST['desc'] ?? false;

			if ($year && $original && $rating && $desc) {
				$ans['addition_info'] = $GLOBALS['db']->updateAdditionInfo($id,$year,$original,$rating,$desc);
			}
		}

		echo json_encode($ans);
	}
	public function actionEdit_save_names()
	{
		$user = $_SESSION['user'];

		$ans = array();

		if (isset($user) && $user['type'] == 1) {
			
			$alter_names = $_POST['alter_names'] ?? false;
			$id = $_POST['title_id'] ?? false;

			if ($alter_names && $id) {
				$ans['alter_state'] = $GLOBALS['db']->setAlterNames($id,$alter_names);
			}

			$title = $_POST['primary_name'] ?? false;

			if ($title) {
				$ans['primary_state'] = $GLOBALS['db']->updatePrimaryName($id,$title);
			}
		}

		echo json_encode($ans);
	}
	public function actionEdit_save_img()
	{
		$user = $_SESSION['user'];

		$ans = array();

		if (isset($user) && $user['type'] == 1) {
			
			$id = $_POST['title_id'] ?? false;

			if ($id && $_FILES && $_FILES['image']['error'] == UPLOAD_ERR_OK)
			{
		    	$name = "$id.".pathinfo($_FILES['image']['name'])['extension'];

				$res = $GLOBALS['db']->updateTitleImg($id, $name);

				if ($res) {
					move_uploaded_file($_FILES['image']['tmp_name'], 'sources/previews/'.$name);
				}
			}
		}

		echo json_encode($ans);
	}

	public function actionEdit_save_series()
	{
		$user = $_SESSION['user'];

		$ans = array();

		if (isset($user) && $user['type'] == 1) {
			
			$id = $_POST['title_id'] ?? false;
			$actor_id = $_POST['actor_id'] ?? false;
			$series = $_POST['series'] ?? false;

			//var_dump($actor_id);

			$ans['state'] = $GLOBALS['db']->setSeries($id, $actor_id , $series);

		}

		echo json_encode($ans);
	}
	
	public function actionCreate_new_title()
	{
		$user = $_SESSION['user'];

		if (isset($user) && $user['type'] == 1) {
			
			
			$id = $GLOBALS['db']->createNewTitle();
			if ($id) {
				header("location: /edit/$id");
			}

		}
	}
	public function actionAdd_comment()
	{
		$user = $_SESSION['user'];

		$ans = array();

		if (isset($user)) {
			
			$title_id = $_POST['id'];
			$text = $_POST['text'];
			$user_id = $user['id'];

			$text = str_replace('>', '?', $text);

			$ans['state'] = $GLOBALS['db']->addComment($title_id, $user_id, $text);

		}

		echo json_encode($ans);
	}

	public function actionDelete_title()
	{
		$user = $_SESSION['user'];

		$ans = array();
		//echo 1;

		if (isset($user)) {
			
			$title_id = $_POST['title_id'];

			$ans['state'] = $GLOBALS['db']->deleteTitle($title_id);
			//var_dump($ans);
		
		}

		echo json_encode($ans);
	}

	


}

?>