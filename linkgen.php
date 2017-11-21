<!DOCTYPE html>
<html>
<head>
	<title>Borgernes hus booking oversigt generator</title>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#LinkGenerator').on("submit", function(event) {
				event.preventDefault();
			    $form = $(this); //wrap this in jQuery
			    var action = $form.attr('action');

			    var skabelon_val = $('#Skabelon').val();
  				var skabelon_name = $('#Skabelon').attr('name')

			    var targetbox_val = $('#targetmailbox').val().join(',')
  				var targetbox_name = $('#targetmailbox').attr('name')

  				var link = action + "?" + skabelon_name+"="+skabelon_val +"&" + targetbox_name + "=" + targetbox_val;
  				$('#targetlink').html($('<a>').attr('href',link).text(link).attr('target','_blank'))

			});
		})
		
	</script>
</head>
<body>
<div>
	<form id="LinkGenerator" action="http://localhost/bhus_concierge/">
		<label>Skabelon</label>
		<select id="Skabelon" name="skabelon">
			<option value="">Vælg</option>
			<option value="bhus_oversigt">borgernes hus oversigt</option>
		</select>
		<label>Vælg Lokaler</label>
		<select id="targetmailbox" name="targetmailbox" multiple>
		  <option value="lok1.1@odense.dk">Lokale 1.1</option>
		  <option value="lok1.2@odense.dk">Lokale 1.2</option>
		  <option value="lok1.1@odense.dk">Lokale 1.3</option>
		  <option value="lok1.1@odense.dk">Lokale 1.4</option>
		</select>
		<button type="submit">Link mig !</button>
	</form>
	<div>
		<h1 id="targetlink">The Link!</h1>
	</div>
</div>
</body>
</html>