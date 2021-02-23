<?php
	require ROOT.'/view/header.php';
?>

<section>
	<div class="container">

		<div class="login-container">
			
			<div class="login-field">
				<div class="form-input">
					<span>Пошта</span>
					<input type="email" name="email">
					<div class="form-input-status-line"></div>
				</div>
			</div>

			<div class="login-field">
				<div class="form-input">
					<span>Пароль</span>
					<input type="password" name="pass">
					<div class="form-input-status-line"></div>
				</div>
			</div>

			<div class="login-error-container">Неверная почта или пароль</div>

			<div class="button log-btn"><span>Вход</span></div>

		</div>

	</div>
</section>	

<script type="text/javascript">
	$(".log-btn").click(function(e){
		let email = $("input[name=email]").val().trim();
		let pass = $("input[name=pass]").val().trim();

		$.post('/login_input', {email, pass}, function(data){
			let ans;
				try{
					ans = JSON.parse(data)
					if (ans.status == 2)
					{
						window.location.href = "/";
					}
					else
					{
						$(".login-error-container").addClass("active");
					}

				}
				catch
				{
					$(".login-error-container").addClass("active");
				}
				finally
				{
					console.log(data);
					console.log(ans);
				}
		})

	});
</script>

<?php
	require ROOT.'/view/footer.php';
?>