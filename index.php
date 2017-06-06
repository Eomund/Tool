<?php include('includes/header.php'); ?>

<div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark"></div>
    <script>
      function onSignIn(googleUser) {
        // Useful data for your client-side scripts:
        var profile = googleUser.getBasicProfile();
        console.log("ID: " + profile.getId()); // Don't send this directly to your server!
        console.log('Full Name: ' + profile.getName());
        console.log('Given Name: ' + profile.getGivenName());
        console.log('Family Name: ' + profile.getFamilyName());
        console.log("Image URL: " + profile.getImageUrl());
        console.log("Email: " + profile.getEmail());
        
        // The ID token you need to pass to your backend:
        var id_token = googleUser.getAuthResponse().id_token;
        console.log("ID Token: " + id_token);
        
        var xhr = new XMLHttpRequest();
		xhr.open('POST', 'includes/tokensignin.php');
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.onload = function() {
			
			console.log(xhr.status);
			if(xhr.responseText ==  profile.getName()){
				console.log('Signed in as: ' + xhr.responseText);
				window.location.href = "/cards.php";
			}else{
				alert("Unauthorized");	
			}
		};
		xhr.send('idtoken=' + id_token);
  
      };
      
    </script>
    
    
    <?php include('includes/footer.php'); ?>
