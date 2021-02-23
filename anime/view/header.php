<?php require 'head.php';
$user = $_SESSION['user'];
?>

<body>

<div class="wrapper">
<header class="container">
	<?php if (!empty($user)): ?>
		<div class="profile-header-container">
			<div class="profile-header-img-name-container">
				<div class="profile-header-img">
					<img src="<?='../sources/avatars/'.$user['image_path']?>">
				</div>
				<div class="profile-header-name">
					<?=$user['name']; ?>
				</div>
			</div>
			<div class="profile-header-logout-container">
				<a href="/logout" class="profile-header-logout">
					<div class="profile-logout-icon-container">
						<i class="fas fa-sign-out-alt"></i>
					</div>
				</a>
			</div>
		</div>
	<?php else: ?>
		<div class="profile-header-container vertical-cont">
			<a href="/login" class="profile-header-login">
				<span>Вхід</span>
			</a>
			<a href="/register" class="profile-header-register">
				<span>Реєстрація</span>
			</a>
		</div>
	<?php endif ?>
</header>
<nav class="container nav-container">
	<a href="/">Головна</a>
	<a href="/profile">Профіль</a>
	<?php if ($user['type'] == 1): ?>
		<a href="/edit/create_new_title">Додати Серіал</a>
	<?php endif ?>

</nav>

