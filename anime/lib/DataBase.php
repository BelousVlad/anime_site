<?
class DataBase{



	public static $connection;

	public static function getConnection()
	{
		if (is_null($connection)) {
			$connection = mysqli_connect("localhost","root","root","anime");
		}
		return $connection;
	}

	public static function closeConnection(){
		if (!(is_null($connection))) {
			$connection->close();
		}
	}

	public function getTitles()
	{

		$conn = self::getConnection();

		$result = $conn->query("SELECT * FROM titles");

		$titles = array();

		while ($row = $result->fetch_assoc()) {
			array_push($titles, $row);
		}

		foreach ($titles as &$title) {
			$id = $title['id'];

			$alter_names = array();
			$genres = array();

			$result = $conn->query("SELECT name FROM alter_titles WHERE title_id = $id");

			while ($row = $result->fetch_assoc()) {
				array_push($alter_names, $row['name']);
			}

			$result = $conn->query("SELECT g.id, g.title FROM titles as t, title_genre as tg, genre as g WHERE title_id = $id AND t.id = tg.title_id AND tg.genre_id = g.id ");

			while ($row = $result->fetch_assoc()) {
				array_push($genres, $row);
			}

			$title['alter_names'] = $alter_names;
			$title['genres'] = $genres;
		}

		return $titles;

	}

	public function getGenres()
	{
		$conn = self::getConnection();

		$result = $conn->query("SELECT * FROM genre");

		$arr = array();

		while ($row = $result->fetch_assoc()) {
			array_push($arr, $row);
		}

		return $arr;
	}

	public function getComments($id)
	{
		$conn = self::getConnection();

		$stm = $conn->prepare('SELECT * FROM comments as c, users as u WHERE c.title_id = ? AND u.id = c.user_id');

		$stm->bind_param('i',$id);

		$res = $stm->execute();

		if ($res) {
			$arr = array();
			$result = $stm->get_result();

			while ($row = $result->fetch_assoc()) {
				array_push($arr, $row);
			}

			return $arr;
		}

		return false;
	}


	public function getUserById($id)
	{
		$conn = self::getConnection();

		$stm = $conn->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');

		$stm->bind_param('i',$id);

		$res = $stm->execute();

		if ($res) {
			return $stm->get_result()->fetch_assoc();
		}
		return false;
	}

	public function getWatchedList($id)
	{
		$conn = self::getConnection();

		$stm = $conn->prepare('SELECT t.* FROM user_watched as uw, titles as t WHERE uw.user_id = ? AND uw.title_id = t.id');

		$stm->bind_param('i', $id);

		$res = $stm->execute();

		$result = array();


		if ($res) {

			$list = $stm->get_result();
			while($row = $list->fetch_assoc())
			{
				$result[$row['id']] = $row;
			}
			return $result;
		}
		return false;
	}

	public function updatePorfile($id,$name,$sex,$years)
	{
		$conn = self::getConnection();

		$stm = $conn->prepare('UPDATE users SET name = ?, sex = ?, years = ? WHERE id = ?');

		$stm->bind_param('siii', $name,$sex,$years, $id);

		return $stm->execute();
	}

	public function updatePorfileImg($id, $image_path)
	{
		$conn = self::getConnection();

		$stm = $conn->prepare('UPDATE users SET image_path = ? WHERE id = ?');

		$stm->bind_param('si', $image_path, $id);

		return $stm->execute();
	}

	public function getTitle($id)
	{
		$conn = self::getConnection();

		$result = $conn->query("SELECT t.id, t.primary_name, t.preview_path, t.year, t.original, t.rating, t.description FROM titles as t WHERE t.id = $id ");

		$title = $result->fetch_assoc();

		$id = $title['id'];

		$alter_names = array();
		$genres = array();
		$series = array();

		$result = $conn->query("SELECT name FROM alter_titles WHERE title_id = $id");

		while ($row = $result->fetch_assoc()) {
			array_push($alter_names, $row['name']);
		}

		$result = $conn->query("SELECT g.id, g.title FROM titles as t, title_genre as tg, genre as g WHERE title_id = $id AND t.id = tg.title_id AND tg.genre_id = g.id ");

		while ($row = $result->fetch_assoc()) {
			array_push($genres, $row);
		}

		$result = $conn->query("SELECT s.id, s.cell_text as `text`, s.sibnet_id, v.id as 'voice_actor_id', v.name as 'voice_actor_name' FROM titles as t, series as s , voice_actors as v WHERE t.id = $id AND t.id = s.title_id AND s.voice_actor_id = v.id");

		while ($row = $result->fetch_assoc()) {

			$actor = $row['voice_actor_name'];

			if (!is_array($series[$actor])) {
				$series[$actor] = array();
			}

			array_push($series[$actor], array(
				'text' => $row['text'],
				'sibnet_id' => $row['sibnet_id'],
				'voice_actor_id' => $row['voice_actor_id']
			));

			//array_push($series, $row);
		}

		$title['alter_names'] = $alter_names;
		$title['genres'] = $genres;
		$title['series'] = $series;
	
		return $title;
	}

	public function getVoiceActors()
	{
		$conn = self::getConnection();

		$result = $conn->query("SELECT * FROM voice_actors");

		$arr = array();

		while ($row = $result->fetch_assoc()) {
			$arr[$row['id']] = $row;
		}

		return $arr;
	}

	public function setStatus($user_id, $title_id, $status)
	{

		//var_dump($status);
		$conn = self::getConnection();
		if ($status) {
			$stm = $conn->prepare("INSERT INTO user_watched(user_id, title_id) VALUES(?,?)");
			$stm->bind_param('ii', $user_id, $title_id);

			return $stm->execute();
		}
		else
		{
			$stm = $conn->prepare("DELETE FROM user_watched WHERE user_id = ? AND title_id = ?");
			$stm->bind_param('ii', $user_id, $title_id);

			$res = $stm->execute();
			//var_dump($res);
			return $res;
		}
	}

	public function getStatus($user_id, $title_id)
	{
		$conn = self::getConnection();
		$stm = $conn->prepare("SELECT * FROM user_watched WHERE user_id = ? AND title_id = ? LIMIT 1");
		$stm->bind_param('ii', $user_id, $title_id);

		$res = $stm->execute();
		$result = $stm->get_result()->fetch_assoc();
		//var_dump($result);
		//var_dump(empty($result));

		return !empty($result);
	}

	public function register($name, $email, $pass)
	{
		$conn = self::getConnection();

		$stm = $conn->prepare("INSERT INTO users(name, email, password) VALUES (?,?,?) ");

		$stm->bind_param("sss", $name, $email, password_hash($pass, PASSWORD_DEFAULT));

		$result = $stm->execute();

		if ($result) {
			return 2;
		}
		return 0;
	}

	public function login($email, $pass)
	{
		$conn = self::getConnection();

		$stm = $conn->prepare("SELECT * FROM users WHERE email = ? ");

		$stm->bind_param("s", $email);

		$result = $stm->execute();

		if ($result) {
			$user = $stm->get_result()->fetch_assoc();
			if (password_verify($pass, $user['password'])) {
				return $user;
			}
		}
		return 0;
	}

	public function setAlterNames($id, $names)
	{
		$conn = self::getConnection();

		$res = $conn->query("DELETE FROM alter_titles WHERE title_id = '$id'");

		//echo $conn->error;

		if($res)
		{
			foreach ($names as $name) {
				$stm = $conn->prepare('INSERT INTO alter_titles (title_id, name) VALUES( ?, ?)');	
				$stm->bind_param('is', $id, $name);
				$res = $stm->execute();
			}
		}

		return $res;

	}

	public function setGenres($id, $genres)
	{
		$conn = self::getConnection();

		$res = $conn->query("DELETE FROM title_genre WHERE title_id = '$id'");

		//echo $conn->error;

		if($res)
		{
			foreach ($genres as $genre) {
				$stm = $conn->prepare('INSERT INTO title_genre (title_id, genre_id) VALUES( ?, ?)');
				$stm->bind_param('ii', $id, $genre);
				$res = $stm->execute();
			}
		}

		return $res;

	}

	public function updatePrimaryName($id, $name)
	{
		$conn = self::getConnection();
		
		$stm = $conn->prepare('UPDATE titles SET primary_name = ? WHERE id = ?');

		$stm->bind_param('si', $name, $id);

		$res = $stm->execute();
			
		return $res;

	}

	public function updateAdditionInfo($id,$year,$original,$rating,$desc)
	{
		$conn = self::getConnection();
		
		$stm = $conn->prepare('UPDATE titles SET year = ?, original = ?, rating = ?, description = ? WHERE id = ?');

		$stm->bind_param('isssi', $year,$original,$rating,$desc, $id);

		$res = $stm->execute();
			
		return $res;
	}
	public function updateTitleImg($id, $image)
	{
		$conn = self::getConnection();
		
		$stm = $conn->prepare('UPDATE titles SET preview_path = ? WHERE id = ?');

		$stm->bind_param('si', $image, $id);

		$res = $stm->execute();
			
		return $res;
	}

	public function setSeries($id, $voice_id , $series)
	{
		$conn = self::getConnection();

		$res = $conn->query("DELETE FROM series WHERE title_id = '$id' AND voice_actor_id = '$voice_id'");

		if($res && $series)
		{
			foreach ($series as $seria) {
				$stm = $conn->prepare('INSERT INTO series (title_id, voice_actor_id, cell_text, sibnet_id) VALUES( ?, ?,?,?)');
				$stm->bind_param('iisi', $id, $voice_id, $seria['text'], $seria['sibnet_id']);
				$res = $stm->execute();
			}
		}

		return $res;

	}

	public function createNewTitle()
	{
		$conn = self::getConnection();

		$res = $conn->query('INSERT INTO titles() VALUES()');

		if ($res) {
			return $conn->insert_id;
		}

		return false;

	}
	
	public function addComment($title_id, $user_id, $text)
	{
		$conn = self::getConnection();

		$stm = $conn->prepare("INSERT INTO comments (title_id, user_id, `text`) VALUES(?,?,?)");
		$stm->bind_param('iis', $title_id, $user_id, $text);

		$res = $stm->execute();

		return $res;

	}
	public function deleteTitle($title_id)
	{
		$conn = self::getConnection();

		$res = $conn->query("DELETE FROM series WHERE title_id = $title_id");

		echo $conn->error;

		if (!$res) {
			return false;
		}

		$res = $conn->query("DELETE FROM comments WHERE title_id = $title_id");

		if (!$res) {
			return false;
		}

		$res = $conn->query("DELETE FROM user_watched WHERE title_id = $title_id");

		if (!$res) {
			return false;
		}

		$res = $conn->query("DELETE FROM title_genre WHERE title_id = $title_id");

		if (!$res) {
			return false;
		}

		$res = $conn->query("DELETE FROM alter_titles WHERE title_id = $title_id");

		if (!$res) {
			return false;
		}

		$stm = $conn->prepare("DELETE FROM titles WHERE id = ?");

		$stm->bind_param('i', $title_id);

		$res = $stm->execute();

		echo $stm->error;

		return $res;

	}
/*
	public function login($login, $password)
	{
		$conn = self::getConnection();

		$stm = $conn->prepare("SELECT * FROM admins WHERE login = ? AND password = ? LIMIT 1");

		$stm->bind_param("ss", $login, $password);

		$result = $stm->execute();

		if($result)
		{
			$result = $stm->get_result();

			return $result->fetch_assoc();
		}
		return NULL;

	}
*/

}

?>