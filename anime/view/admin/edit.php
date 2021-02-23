<?php

require ROOT.'/view/header.php';


//var_dump($title);
?>

<div class="container title-edit-container">
	<div class="title-info-container">
		<div class="title-name-container title-info-field">
			<input type="text" class="title-primary-name" value="<?=$title['primary_name']; ?>">
			
			<div class="alter-names-add-btn">+</div><h3 class="title-alter-names-title">Альтернативні назви  :</h3>

			<div class="title-alter-names">
				<?php foreach ($title['alter_names'] as $name): ?>
					<div class="edit-alter-name-filed">
						<span class="edit-alter-name-filed-remove">X</span>
						<input type="text" class="alter-name" value="<?=$name ;?>">
					</div>
				<?php endforeach ?>
			</div>

			<div class="edit-names-save-btn edit-btn button">
				<span>
					Зберегти
				</span>
			</div>

		</div>
		<div class="title-addition-info-container">
				
			<div class="title-genres-container title-info-field">
				Жанр: 

				<select class="filters-genre-select js-example-basic-multiple" name="genres" multiple="multiple">
				  <?php foreach ($genres as $genre): ?>

				  	<?php
				  		$is_select = false;
				  		foreach ($title['genres'] as $title_genre) {
				  			if ($title_genre['id'] == $genre['id']) {
				  				$is_select = true;
				  				break;
				  			}
				  		}
				  	?>
				  	<option <? if($is_select) echo 'selected'; ?> value="<?=$genre['id']; ?>"><?=$genre['title']; ?></option>
				  <?php endforeach ?>
				</select>
			</div>
			<div class="title-year title-info-field">Рік: 
				<input type="text" value="<?=$title['year'];?>">
			</div>

			<div class="title-original title-info-field">Першоджерело: 
				<input type="text" value="<?=$title['original'];?>">
			</div>
			<div class="title-rating title-info-field">Віковий рейтинг: 
				<input type="text" value="<?=$title['rating'];?>">
			</div>

			<textarea class="title-description"><?=$title['description']; ?></textarea>

			<div class="edit-addition-info-save-btn edit-btn button">
				<span>
					Зберегти
				</span>
			</div>
		</div>

	</div>

	<div class="title-preview-container">
		<input type="file" name="image" accept="image/x-png,image/gif,image/jpeg" hidden>
		<div class="title-preview-img-container">
			<img src="../sources/previews/<?=$title['preview_path'];?>">
		</div>
		<div class="edit-title-delete-btn">
			Видалити
		</div>

	</div>

</div>

<div class="container edit-adding-series-super-container">
	<div class="edit-adding-series-container">
		<div class="title-edit-container edit-select-voice-actor-container">
			<select class="edit-series-voice-actor-select js-example-basic-single" name="sort">
			  	<?php foreach ($actors as $actor): ?>
				  	<option value="<?=$actor['id']?>"><?=$actor['name']?></option>
			  	<?php endforeach ?>
			</select>

			<div class="edit-add-seria-btn button"><span>Додати серію</span></div>

		</div>

		<div class="title-edit-container series-container">
			<div class="edit-seria-box">
				<div class="edit-seria-delete-btn">x</div>
				<input type="text" name="cell_text" placeholder="Текст">
				<input type="number" name="sibnet_id" placeholder="sibnet id">
			</div>
		</div>

		<div class="edit-series-save-btn edit-btn button">
			<span>
				Зберегти
			</span>
		</div>
	</div>
</div>



<script type="text/javascript">
	let title_id = `<?=$title['id']?>`;
	let title = JSON.parse(`<?=json_encode($title);?>`);
	let actors = JSON.parse(`<?=json_encode($actors);?>`);



	$('.js-example-basic-multiple').select2();
	$('.js-example-basic-single').select2({
		minimumResultsForSearch: -1

	});


	$('.alter-names-add-btn').click(function(e){
		$('.title-alter-names').append(`
			<div class="edit-alter-name-filed">
				<span class="edit-alter-name-filed-remove">X</span>
				<input type="text" class="alter-name" value="">
			</div>`);
	})

	$(document).on('click','.edit-alter-name-filed-remove',function(e){
		$(this).parent().remove();
	});

	$('.edit-names-save-btn').click(function(e){
		let alter_names = [];

		$('input.alter-name').each(function(i){
			let str = $(this).val().trim();
			str && alter_names.push(str);
		})

		let primary_name = $('.title-primary-name').val().trim();

		$.post('/edit/save_names', { alter_names, primary_name, title_id }, function(data){
			console.log(data);
			let ans = JSON.parse(data);

			if (ans.primary_state && ans.alter_state)
			{
				alert('ok');
			}
		})

	})

	let formData = new FormData();

	$('.title-preview-img-container').click(function(e){
		$('input[name=image]').click();
	})

	$('input[name=image]').on('change', function(e){ 
		let file = $(this).prop('files')[0];
		formData.delete('image');
		formData.append('image',file);
		formData.append('title_id', title_id );

		$.ajax({
		        url: '/edit/save_img',
		        type: 'POST',
		        data: formData,
		        success: function (data) {
		            console.log(data)
		            //let ans = JSON.parse(data);
		            
		        },
		        cache: false,
		        contentType: false,
		        processData: false
		    });

	});

	$('.edit-addition-info-save-btn').click(function(e){
		let genres = $('.filters-genre-select').select2('data');
		genres = genres.reduce( (res, item) => {
			res.push(item.id);
			return res;
		}, []);

		let year = $('.title-year input').val().trim();
		let original = $('.title-original input').val().trim();
		let rating = $('.title-rating input').val().trim();
		let desc = $('textarea.title-description').val().trim();

		//console.log(123);

		$.post('/edit/save_addition_info', {
			genres, title_id , year, original, rating, desc
		}, function(data){
			console.log(data);
			let ans = JSON.parse(data);

			if (ans.genres_state && ans.addition_info)
			{
				alert('ok');
			}
		});
	})

	function set_series(series)
	{

		console.log(series);

		$('.series-container').empty();

		if (series == undefined) return;

		let text = series.reduce( (res ,item) =>  res + 
				`<div class="edit-seria-box">
					<div class="edit-seria-delete-btn">x</div>
					<input type="text" name="cell_text" placeholder="Текст" value="${item.text}">
					<input type="number" name="sibnet_id" placeholder="sibnet id" value="${item.sibnet_id}">
				</div>
				`
				,"");
				

			$(".series-container").html(text);
	}

	$('.edit-add-seria-btn').click(function(e){
		$(".series-container").append(`
			<div class="edit-seria-box">
				<div class="edit-seria-delete-btn">x</div>
				<input type="text" name="cell_text" placeholder="Текст">
				<input type="number" name="sibnet_id" placeholder="sibnet id">
			</div>`);

	});

	$(document).on('click','.edit-seria-delete-btn',function(e){
		$(this).parent().remove()
	});

	(function(){
		let id = $('.edit-series-voice-actor-select').select2('data')[0].id;
		let name = actors[id].name
		set_series(title.series[name]);
	})()

	$('.edit-series-voice-actor-select').on('select2:select',function(e){
		let id = e.params.data.id;
		let name = actors[id].name
		set_series(title.series[name]);
	});

	$('.edit-series-save-btn').click(function(e){
		let id = $('.edit-series-voice-actor-select').select2('data')[0].id;

		let series = [];

		$('.edit-seria-box').each(function(i){
			let text = $(this).find('input[name=cell_text]').val().trim();
			let sibnet_id = $(this).find('input[name=sibnet_id]').val().trim();
			series.push({text, sibnet_id});
		});

		console.log(series);

		$.post('/edit/save_series', {actor_id: id, title_id, series }, function(data){
			console.log(data);
		});

	})

	$('.edit-title-delete-btn').click(function(e){
		$.post('/delete_title', { title_id }, function(data){
			console.log(data);
			let ans = JSON.parse(data);
			if (ans.state);
				window.location.href = '/';
		});
	});
	
</script>

<?php

require ROOT.'/view/footer.php';

?>