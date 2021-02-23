<?php

require ROOT.'/view/header.php';

?>

<section>
	
	<div class="container">
		<div class="title-info-container">
			<div class="title-name-container title-info-field">
				<h2 class="title-primary-name"><?=$title['primary_name']; ?></h2>
				<div class="title-alter-names">
					<?php foreach ($title['alter_names'] as $name): ?>
						<div class="alter-name"><?=$name ;?></div>
					<?php endforeach ?>
				</div>
			</div>
			<div class="title-genres-container title-info-field">
				Жанр: 
				<?php foreach ($title['genres'] as $genre): ?>
					<span><?=$genre['title'] ;?> /</span>
				<?php endforeach ?>
			</div>
			<div class="title-year title-info-field">Рік: 
				<span><?=$title['year'];?></span>
			</div>

			<div class="title-original title-info-field">Першоджерело: 
				<span><?=$title['original'];?></span>
			</div>
			<div class="title-rating title-info-field">Віковий рейтинг: 
				<span><?=$title['rating'];?></span>
			</div>

		</div>

		<div class="title-preview-container">
			<div class="title-preview-img-container">
				<img src="../sources/previews/<?=$title['preview_path'];?>">
			</div>
			<?php if (isset($user)): ?>
				<div class="title-status-container <? if($status) echo "watched"; ?>">
					Переглянуто
				</div>
			<?php endif ?>
			<?php if ($user['type'] == 1): ?>
				<a href="/edit/<?=$title['id']?>" class="title-edit-link">
					Перейти до редагування
				</a>
			<?php endif ?>
		</div>
	</div>

	<div class="container">
		<div class="title-description">
			<?=$title['description']; ?>
		</div>
	</div>

	<div class="container">
		<select class="title-voice-actor-select js-example-basic-single" name="voice-actor">
		  <?php foreach ($title['series'] as $key => $value): ?>
		  	<option value="<?=$key?>"><?=$key?></option>
		  <?php endforeach ?>
		</select>
	</div>

	<div class="container">
		<?php foreach ($title['series'] as $actor => $series): ?>
			<div class="title-series-cells-container" voice-actor="<?=$actor; ?>">
				<?php foreach ($series as $item): ?>
					
					<div class="title-series-cell" sibnet-id="<?=$item['sibnet_id']; ?>"><span><?=$item['text']; ?></span></div>

				<?php endforeach ?>
			</div>
		<?php endforeach ?>
	</div>

	<div class="container title-video-container">
		
	</div>

	<div class="container">
		<?php if (isset($user)): ?>
			<div class="title-comment-send-container">
				<textarea placeholder="Коментарій" name="comment"></textarea>
				<div class="title-cooment-send-btn">Відправити</div>
			</div>
		<?php else: ?>
			<div class="title-noautorization-comment">
				Щоб залишати коментарі треба авторизуватися
			</div>	
		<?php endif ?>
	</div>

	<div class="container">
		<div class="title-сommets-container">
			<?php foreach ($comments as $comment): ?>
				<div class="title-comment-user">
					<div>
						<div class="title-comment-user-img">
							<img src="../sources/avatars/<?=$comment['image_path']?>">
						</div>
						<div class="title-comment-user-name">
							<?=$comment['name']?>
						</div>
					</div>
					<div class="title-comment-user-text">
						<?=$comment['text']?>
					</div>
				</div>
			<?php endforeach ?>
		</div>
	</div>

</section>

<script type="text/javascript">

	let id = '<?=$title['id']?>';

	$(".title-voice-actor-select").select2({
		minimumResultsForSearch: -1
	});
	
	function setVoice(actor)
	{
		$(`.title-series-cells-container.active`).removeClass('active')
		$(`.title-series-cells-container[voice-actor=${actor}]`).addClass('active');
	}

	$('.title-voice-actor-select').on('select2:select', function (e) {
	    let data = e.params.data;-
	    console.log(data);
	    let actor = data.id;

	    setVoice(actor);
	});

	if ($('.title-voice-actor-select').select2("data").length > 0)
	{
		let set = $('.title-voice-actor-select').select2("data")[0].id;

		setVoice(set);
	}

	$(".title-series-cell").click(function(e){

		let id = $(this).attr("sibnet-id").trim();

		$(".title-video-container").html(
			`
			<iframe src="https://video.sibnet.ru/shell.php?videoid=${id}" frameborder="0" scrolling="no" allowfullscreen></iframe>
			`
		)
	})

	$(".title-cooment-send-btn").click(function(e){

		let text = $(".title-comment-send-container textarea").val().trim();

		if (!text) 
		{
			alert('Введіть тест коментарію');
		}
		else
		{
			$.post('/add_comment', { id, text },function(data){
				console.log(data);
				let ans = JSON.parse(data);

				if (ans.state) 
					window.location.reload(true);
			})
		}


	})

	$(document).on('click','.title-status-container', function(e){
		
		is_watched = $(this).hasClass('watched');

		let elem = this;

		console.log( !is_watched);

		$.post('/title_status', { id, status: !is_watched }, function(data){
			console.log(data);
			let ans = JSON.parse(data)

			if (ans.status == 2)
			{
				$(elem).toggleClass('watched');
			}
			else
			{
				console.log(ans);
			}

		});
	})



</script>


<?php

require ROOT.'/view/footer.php';

?>