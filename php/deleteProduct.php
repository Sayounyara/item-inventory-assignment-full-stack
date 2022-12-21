<?php
    // connect
    $conn = @mysqli_connect("localhost","root","","acme");

    // define variables and set to empty values
    $nameErr = "";
    $name = "";
    $success = "";
    $valid = "";
    $showID = "";
    $output = "";
    
    // Check connection 
    if (mysqli_connect_errno()){ 
    	echo "<p>Failed to connect to MySQL and the db: " . mysqli_connect_error() . "</p>";
    } else {

    	if ($_SERVER["REQUEST_METHOD"] == "POST") {
    		$valid = "yes";
    		if (empty($_POST["prodID"])) {
    			$valid = "no";
    			$nameErr = "Product ID is required";
    		} 
    		else { 
    			$name = checkInput($_POST["prodID"]);
    			if (!is_numeric($name)) {
    				$valid = "no";
    				$costErr = "ID must be an integer";
    			}
    		}
    		if ($valid == "yes") {
    			$check = "SELECT * FROM products WHERE id='".$name."'";
    			$results = mysqli_query($conn,$check);
    			if ($results) {  
    				$numRecords = mysqli_num_rows($results);
    				if ($numRecords == 0) { 
    					$valid = "no";
    					$output = "<p>Error: Product ID could not be found in the table.</p>";
    				}
    				else {
    					//insert data
    					$query = "DELETE FROM products WHERE id='".$name."'";
    					if (!mysqli_query($conn,$query)) {
    						$output = "<p>Error deleting product data: " . mysqli_error($conn) . "</p>";
    					}
    					else {
    						$output = "<p>Product deleted successfully</p>";
    						setcookie("deletedid", $name);
    						$_POST = array();
    						include("processDeleteDetails.php");
    						header('Location: processDeleteDetails.php');
    						exit();
    					}
    				}
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
    deleteProduct.php
    26/11/2022
    Amos Chamberlain
    Allows the user to delete a product using id
-->
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Delete a Product | Acme Door Levers</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/style.css"/>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&family=Shippori+Antique&display=swap" rel="stylesheet">

    <title>Delete a Product</title>
    </head>
    <body>
        <header>
            <h1 class="heading">Acme Door Levers - Part 4</h1>
            <h2 class="subheading">Delete Product</h2>
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
    	    <h2 class="instruct">Enter an ID for the product you would like to delete.</h2>
            <span class="warning">* - required</span>

    	    <form name="productForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    	    	<label for="prodID">Product ID: </label>
    	    	<input type="text" id="prodID" name="prodID" value="<?php echo $name;?>">
    	    	<span class="error">* <?php echo $nameErr;?></span><br>

    	    	<input type="submit" value="Submit">
    	    	<input type="reset" value="Reset"><br>
    	    	<span class="error"><?php echo $output;?></span><br>
    	    </form>
        </article>
    </body>
</html>
