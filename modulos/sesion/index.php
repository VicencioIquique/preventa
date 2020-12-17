<!--
<script type="text/javascript">
$(document).ready(function() {
	
	$("#login").click(function() {
	
		var action = $("#horizontalForm").attr('action');
		var form_data = {
			username: $("#username").val(),
			password: $("#password").val(),
			is_ajax: 1
		};
		
		$.ajax({
			type: "POST",
			url: action,
			data: form_data,
			success: function(response)
			{
				if(response == 'success')
					$("#horizontalForm").slideUp('slow', function() {
						$("#message").html("<p class='success'>You have logged in successfully!</p>");
					});
				else
					$("#message").html("<p class='error'>Invalid username and/or password.</p>");	
			}
		});
		
		return false;
	});
	
});-->
</script>
<div id="content">
<form id="horizontalForm"  name="horizontalForm" action="index.php?opc=valida" method="POST">
			<fieldset>
				<legend>
				 	Iniciar Sesion
				</legend>
                <fieldset style=" width:210px;">
                    <label for="username">
                        Usuario
                        <input id="largo" name="username"  id="username" type="text" />
                    </label>
                    <label for="password">
                        Pass
                        <input id="largo"   name="password" id="password"  type="password"  />
                    </label>
                                    
                    </label>
                    <input class="submit" type="submit" value="Ingresar" id="login" name="login" />
                    
               </fieldset>
                 
			</fieldset>
			
</form>
  <div id="message"></div>
</div>
