<?php
	require ROOT.'/view/header.php';

//	var_dump($user);
?>

<section>
	<div class="container">
		<div class="profile-container">
			<div class="profile-img-container">
				<img src="<?='../sources/avatars/'.$user['image_path']?>">
			</div>
			<div class="profile-info-container profile-info-unchange">
				<p>Ім'я: <?=$profile['name']?></p>
				<p>Стать: <?= $profile['sex'] == 2 ? 'Не вказано' : ($profile['sex'] == 1 ? 'Чоловік' : "Жінка") ?></p>
				<p>Вік: <?=$profile['years'] ?? 'Не вказано' ?></p>
				<div class="profile-btns-container">
					<!--<div class="button profile-logout-btn"><span>Выход</span></div>-->
					<div class="button profile-save-btn"><span>Зберегти</span></div>
					<div class="button profile-change-btn"><span>Редагувати</span></div>
				</div>
			</div>
			<div class="profile-info-container profile-info-change">
				<input type="file" name="image" accept="image/x-png,image/gif,image/jpeg" hidden>
				<input type="text" name="name" value="<?=$profile['name']?>">
				<div>
					<label for="famale_radio">Жінка <input <?php if($profile['sex'] == 0) echo 'checked'; ?> id="famale_radio" type="radio" name="sex" value="0"></label>
					<label for="male_radio">Чоловік <input <?php if($profile['sex'] == 1) echo 'checked'; ?> id="male_radio" type="radio" name="sex" value="1"></label>
					<label for="unknow_radio">Не вказано <input <?php if($profile['sex'] == 2) echo 'checked'; ?> id="unknow_radio" type="radio" name="sex" value="2"></label>
				</div>
				<input min="1" type="number" name="years" value="<?=$profile['years']?>">
				<div class="profile-btns-container">
					<!--<div class="button profile-logout-btn"><span>Выход</span></div>-->
					<div class="button profile-save-btn"><span>Зберегти</span></div>
					<div class="button profile-change-btn"><span>Редагувати</span></div>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="profile-watched-list">
			<?php foreach ($watched_list as $item): ?>
				<a href="/<?=$item['id']?>" class="watched-list-item">
					<?=$item['primary_name']?>
				</a>
			<?php endforeach ?>
		</div>
	</div>
	
</section>

<script type="text/javascript">
	let formData = new FormData();

	$('.profile-change-btn').click(function(e){
		$('.profile-container').addClass('active');
	})
	$(document).on('click','.profile-container.active .profile-img-container',function(e){
		$('input[name=image]').click();
	})

	$('input[name=image]').on('change', function(e){ 
		let file = $(this).prop('files')[0];
		formData.delete('img');
		formData.append('img',file);
	});

	$('.profile-save-btn').click(function(e){
		let name = $('input[name=name]').val().trim();
		let sex = $("input[name=sex]:checked").val();
		let year = $("input[name=years]").val();

		let is = true;

		if (!name)
		{
			alert("Введить ім'я");
			is = false;
		}
		else if (!year)
		{
			alert('Введіть вік');
			is = false;
		}

		if (is)
		{
			formData.delete('name');
			formData.delete('sex');
			formData.delete('year');

			formData.append('name', name);
			formData.append('sex', sex);
			formData.append('year', year);

			$.ajax({
		        url: '/save_info',
		        type: 'POST',
		        data: formData,
		        success: function (data) {
		            console.log(data)
		            let ans = JSON.parse(data);
		            if (ans.state == 2)
		            {
		            	setTimeout(function(){
		            		window.location.reload(true);
		            	}, 100);
		            	
		            }
		        },
		        cache: false,
		        contentType: false,
		        processData: false
		    });

			/*
			$.post('user/save_info', formData, function(data){
				console.log(data);
			})
			*/
		}

	})




</script>

<?php
	require ROOT.'/view/footer.php';
?>