<?php

require ROOT.'/view/header.php';
?>

<section>

	<div class="container">


	</div>

	
	<div class="container">
		<div class="register-container">
			<div class="register-field">
				<div class="form-input">
					<span>Имя</span>
					<input type="text" name="nick">
					<div class="form-input-status-line"></div>
				</div>
				
			</div>

			<div class="register-field">
				<div class="form-input">
					<span>Пошта</span>
					<input type="email" name="email">
					<div class="form-input-status-line"></div>
				</div>
			</div>
			<div class="register-field">
				<div class="form-input">
					<span>Пароль</span>
					<input type="password" name="pass">
					<div class="form-input-status-line"></div>

				</div>
				<div class="register-field-error-msg">Пароль должен иметь больше 5 символов</div>
			</div>
			<div class="register-field">
				<div class="form-input">
					<span>Пароль</span>
					<input type="password" name="pass_repeat">
					<div class="form-input-status-line"></div>
				</div>
			</div>

			<div class="reg-button button active">
				<span>Регистрация</span>
			</div>

			<div class="loader-container">
				<div class="lds-ellipsis">
					<div></div>
					<div></div>
					<div></div>
					<div></div>
				</div>
			</div>
			<div class="register-success-container">
				<p>Вы успешно зарегестрировались</p>
				<a href="/login">Перейти к авторизации</a>
			</div>

			<br>
			<br>
			<br>
			<br>

		</div>
	</div>


</section>

<script type="text/javascript">

	function validateEmail(email) {
	    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	    return re.test(String(email).toLowerCase());
	}
	function	setLoader()
	{
		$(".loader-container").addClass("active");
		$(".reg-button").removeClass("active");
		$(".register-success-container").removeClass("active");
	}
	function	setButton()
	{
		$(".loader-container").removeClass("active");
		$(".reg-button").addClass("active");
		$(".register-success-container").removeClass("active");
	}
	function	setSuccess()
	{
		$(".loader-container").removeClass("active");
		$(".reg-button").removeClass("active");
		$(".register-success-container").addClass("active");
	}

	function setError(elem)
	{
		$(`input[name=${elem}]`).parent().addClass("active");
		$(`input[name=${elem}]`).parent().parent().addClass("error");
	}

	$(".reg-button").click(function(e){

		$(".form-input").removeClass("error");

		let name = $("input[name=nick]").val().trim();
		let email = $("input[name=email]").val().trim();
		let pass = $("input[name=pass]").val().trim();
		let pass1 = $("input[name=pass_repeat]").val().trim();

		let isValid = true;

		if (name == "")
		{
			setError('nick')
			isValid = false;
		}
		if (!validateEmail(email))
		{
			setError('email')
			isValid = false;
		}
		if (pass.length < 6)
		{
			setError('pass')
			
			isValid = false;
		}
		if (pass != pass1)
		{
			setError('pass_repeat')
			isValid = false;
		}

		if (isValid)
		{
			setLoader();
			$.post("/register_input", {name, email, pass}, function(data){
				let ans;
				try{
					ans = JSON.parse(data)
					if (ans.status == 2)
					{
						setSuccess()
					}
					else
					{
						setButton();
					}

				}
				catch
				{
					setButton();
				}
				finally
				{
					console.log(data);
					console.log(ans);
				}
			})
		}

	})

</script>

<?php

require ROOT.'/view/footer.php';

?>