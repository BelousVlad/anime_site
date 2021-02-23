
	<footer>
	</footer>
	
</div>

</body>

<script type="text/javascript">
	$(document).ready(function() {
		$(".form-input input").focusin(function(e){
			$(this).parent().addClass("active")
		});
		$(".form-input input").focusout(function(e){
			$(this).parent().removeClass("active")
			$(this).parent().parent().removeClass("error")
		});

		$(".form-input span").click(function(e){
			$(this).parent().children("input").focus();
		})
		
	});
</script>

</html>