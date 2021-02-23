<?php

require ROOT.'/view/header.php';


?>
<section>

	<div class="container">
		<input type="search" class="search" placeholder="Пошук за назвою" />
	</div>
			
	<div class="container">

		<div class="sub-container main">
			<div class="serial-blocks-container">

				<?php foreach ($titles as $title): ?>
				
				<a href="/<?=$title['id']; ?>">
					<div class="serial-block">
						<div class="serial-block-img">
							<img src="sources/previews/<?=$title['preview_path']; ?>">
						</div>

						<div class="serial-block-title"><?=$title['primary_name']; ?></div>

					</div>
				</a>

				<?php endforeach ?>

			</div>
		</div>
		<aside class="sub-container">
			<div class="filters-container">
				<div class="filter-field">
					<select class="filters-genre-select js-example-basic-multiple" name="genres" multiple="multiple">
					  <?php foreach ($genres as $genre): ?>
					  	<option value="<?=$genre['id']; ?>"><?=$genre['title']; ?></option>
					  <?php endforeach ?>
					</select>
				</div>
				<div>Рік:</div>
				<div class="filter-field filter-year-container">
					<input id="input-year-start" type="number" name="year-start">
					<span> _ </span>
					<input id="input-year-end" type="number" name="year-end">
				</div>

				<div>Сортування: </div>
				<div class="filter-field">
					<select class="filters-sort-select js-example-basic-single" name="sort">
					  	<option value="1">По даті завантаження</option>
					  	<option value="2">По даті виходу</option>
					  	<option value="3">За алфавітом</option>
					</select>
				</div>

				<div class="filter-field">
					<div class="filter-btn button">
						<span>Знайти</span>
					</div>
				</div>

			</div>
		</aside>
		
	</div>
	
</section>


<script type="text/javascript">


	function setTitles(titles)
	{
		let html = titles.reduce((res, title) =>  // TODO path
			res + `
				<a href="/${title.id}">
					<div class="serial-block">
						<div class="serial-block-img">
							<img src="sources/previews/${title.preview_path}">
						</div>

						<div class="serial-block-title">${title.primary_name}</div>

					</div>
				</a>
			`
		, "");

		//console.log(html);

		$(".serial-blocks-container").html(html);
	}

	$('.js-example-basic-multiple').select2({
		placeholder: "Жанри"
	});
	$('.js-example-basic-single').select2({
		minimumResultsForSearch: -1
	});


	$(".filter-btn").click(function(e){

		let title = $(".search").val().trim();
		let genres = $(".filters-genre-select").select2("data");
		let year_start = $("input#input-year-start").val().trim();
		let year_end = $("input#input-year-end").val().trim();
		let sort = $(".filters-sort-select").select2("data")[0].id;

		//console.log(sort);

		genres = genres.reduce( (res, item) => {
			res.push(item.id);
			return res;
		}, []);

		//console.log(genres);
	
		$.post("/filter_list", { filters: {

			title,
			genres,
			year : { year_start, year_end }

		}}, function(data){

			//console.log(data);
			
			let arr = JSON.parse(data);

			if (sort == 2)
			{
				arr = arr.sort((o1,o2) => {
						return (Number(o2.year) - Number(o1.year));
					}
				);
			}
			else if (sort == 3)
			{
				arr = arr.sort((o1,o2) => {
						return o1.primary_name.localeCompare(o2.primary_name);
					}
				);
			}

			setTitles(arr);

		})
	})
</script>

<?php

require ROOT.'/view/footer.php';

?>