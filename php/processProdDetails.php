<?php
    // connect
    $conn = @mysqli_connect("localhost","root","","acme");
    // Check connection 
    if (mysqli_connect_errno()){ 
    	echo "<p>Failed to connect to MySQL and the db: " . mysqli_connect_error() . "</p>";
    }
?>

<!--
    processProdDetails.php
    25/11/2022
    Amos Chamberlain
    Shows user that their entered product was successfully added and shows the product ID using cookies
-->
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Product succesfully added | Acme Door Levers</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/style.css"/>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&family=Shippori+Antique&display=swap" rel="stylesheet">
    </head>
    <body>
        <header>
            <h1 class="heading">Acme Door Levers - Part 2</h1>
            <h2 class="subheading">Product Details</h2>
        </header>   

    	<article>
    	<p>Product Successfully added!</p>
    
    	<?php
	        if(!isset($_COOKIE["pid"])) {
	        	echo "error: could not retrieve product ID, cookie was not set!";
	        } else {
	        	echo "<p>Product ID for this record is: " . $_COOKIE["pid"] . "</p>";
	        }
	        $query = "SELECT * FROM products WHERE id='".$_COOKIE["pid"]."'";
	    	$results = mysqli_query($conn,$query);
	    	if (!$results) { 
	    		echo "<p>Error retrieving product data: " . mysqli_error($conn) . "</p>"; 
	    	} else { 
	    		//fetch and display results
	    		$iconAlt = "product image";
	    		while ($row = mysqli_fetch_array($results)) {
	    			echo "<img src=$row[prodImage] alt=$iconAlt>";
	    		}
	    	} 
	    ?>
    	</article>
    </body>
</html>
