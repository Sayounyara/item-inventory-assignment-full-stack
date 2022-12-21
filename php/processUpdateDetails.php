<?php
    // connect
    $conn = @mysqli_connect("localhost","root","","acme");
    // Check connection 
    if (mysqli_connect_errno()){ 
    	echo "<p>Failed to connect to MySQL and the db: " . mysqli_connect_error() . "</p>";
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Product Updated | Acme Door Levers</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/style.css"/>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&family=Shippori+Antique&display=swap" rel="stylesheet">
    </head>
    <body>
    	<header>
            <h1 class="heading">Door Lever Inventory - Part 4</h1>
            <h2 class="subheading">Update Details</h2>
        </header>
    
    	<nav>
    		<div class="navi">
    			<a href="products.php">Add Product</a>
    		</div>
    		<div class="navi">
    			<a href="updateProduct.php">Update Product</a>
    		</div>
    		<div class="navi">
    			<a href="deleteProduct.php">Delete Product</a>
    		</div>
    	</nav>
    
    	<article>
    	    <h2 class="instruct">Updated</h2>
    		<?php
    		if(!isset($_COOKIE["pid"])) {
    			echo "error: could not retrieve product ID, cookie was not set!";
    		} else {
    			echo "<p>Product cost successfully updated for ID " . $_COOKIE["updatedid"] . "!</p>";
    		}
        
    		$query = "SELECT * FROM products WHERE id='".$_COOKIE["updatedid"]."'";
    		$results = mysqli_query($conn,$query);
    		if (!$results) { 
    			echo "<p>Error retrieving product data: " . mysqli_error($conn) . "</p>"; 
    		} else { 
    			//fetch and display results
    			while ($row = mysqli_fetch_array($results)) {        
    				echo "<p>Product name: $row[prodName]</p>";     
    				echo "<p>Product finish: $row[prodFinish]</p>";     
    				echo "<p>Product usage: $row[prodUsage]</p>";
    				echo "<p>Product cost: $row[prodCost]</p>";
    			}
    		} 
    		?>
    
        
    	</article>
    </body>
</html>
