<?php
// connect
$conn = @mysqli_connect("localhost","root","","acme");

// define variables and set to empty values
$nameErr = $passErr = $output = "";
$userName = $userPassword = "";
$success = "";
$valid = "";
$showID = "";
	
// Check connection 
if (mysqli_connect_errno()){ 
	echo "<p>Failed to connect to MySQL and the db: " . mysqli_connect_error() . "</p>";
} else {

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$userName = checkInput($_POST['username']);
		$userPassword = checkInput($_POST['password']);
		$query = "SELECT * FROM users WHERE username='".$userName."'";
		$results = mysqli_query($conn,$query);
		if ($results) { 
			$numRecords=mysqli_num_rows($results);
			if ($numRecords != 0) { //found a match with the username 
				//need to verify user - check the password
				$row = mysqli_fetch_array($results);
				$hashedPassword = $row['userPassword'];
				$passwordsAreTheSame = password_verify($userPassword,$hashedPassword);
				if ($passwordsAreTheSame == true){ 
					$output = "<p>Passwords match!</p>"; 
				} else{ 
					$output = "<p>Invalid username and/or password.</p>"; 
				} 
			}
			else{ 
				$output = "<p>Invalid username and/or password.</p>";
			} 
		}
	}
}

function checkInput($inputData) {
	$inputData = trim($inputData);
	$inputData = stripslashes($inputData);
	$inputData = htmlspecialchars($inputData);
	return $inputData;
}

?>

<!--
    login.php
    26/11/2022
    Amos Chamberlain
    Allows the user to login to the website
-->
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login | Acme Door Levers</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/style.css"/>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&family=Shippori+Antique&display=swap" rel="stylesheet">
    </head>
    <body>
	    <header>
            <h1 class="heading">Acme Door Levers - Part 3</h1>
            <h2 class="subheading">Login Details</h2>
        </header>
    
	    <article>
	        <h2>Please enter your login details</h2>
	        <span class="warning">* - required</span>
        
	        <form name="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	        	<label for="username">Username: </label>
	        	<input type="text" id="username" name="username">
	        	<span class="error">* <?php echo $nameErr;?></span><br>
        
	        	<label for="password">Password: </label>
	        	<input type="password" id="password" name="password">
	        	<span class="error">* <?php echo $passErr;?></span><br>
        
	        	<input type="submit" value="Submit">
	        	<input type="reset" value="Reset"><br>
	        	<span class="error"><?php echo $output;?></span><br>
	        </form>
        </article>
    </body>
</html>
