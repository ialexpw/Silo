<?php
	include('../resources/lib/phpSec/Net/SSH2.php');						
?>

<div id="ssh-post" method="post">
	<div class="radio">
		<label>
			<input type="radio" name="sshSelect" id="sshSelect1" value="reboot" checked>
			 Reboot
		</label>
	</div>
	<br />
	<div class="radio">
		<label>
			<input type="radio" name="sshSelect" id="sshSelect2" value="shutdown">
			 Shutdown
		</label>
	</div>
	<br />
	<div class="radio">
		<label>
			<input type="radio" name="sshSelect" id="sshSelect3" value="custom">
			 Custom
		</label>
	</div>

	<input type="text" class="form-control" id="custom" name="custom" placeholder="ps aux">

	<input type="text" class="form-control" id="username" name="username" placeholder="root">
	<input type="password" class="form-control" id="password" name="password" placeholder="">

	<div>
		<button id="submit-button">Submit</button>
	</div>
</div>

<div class="result"></div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
	$(document).ready(function () {
		$("#submit-button").click(function (e) {
			e.preventDefault();
			
			var username = $('#username').val();
			var password = $('#password').val();
			var post = $('#custom').val();
			
			var option = $('input[name="sshSelect"]:checked').val();
			
			$.ajax({ 
				type:"POST",
				url:"../resources/ssh_post.php",
				data: {username: username, password: password, command: post, option: option},
				success: function(data){
					 $(".result").html(data);
				}
			
			});
		});
	});
</script>