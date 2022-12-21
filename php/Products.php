<?php
    // Connection variable
    $conn = @mysqli_connect("localhost","root","","acme");

    // Misc. variables
    $nameErr = $finishErr = $usageErr = $costErr = $iconErr = "";
    $name = $finish = $usage = $cost = $icon = "";
    $success = "";
    $isValid = "";
    $showID = "";

    // Checks if connection is valid
    if (mysqli_connect_errno()) {
        echo "<p>Failed to connect to MySQL and the database: " . mysqli_connect_error() . "</p>";
    } else {
        // If connection works

        // Check if the request method was via the "Add product" button
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $valid = "yes";
            // If the product name area was empty...
            if (empty($_POST["prodName"])) {
                $valid = "no";
                $nameErr = "Product name is required";
            } 
            else { 
                $name = checkInput($_POST["prodName"]);
            }
            // If the product name area was empty...
            if (empty($_POST["prodFinish"])) {
                $valid = "no";
                $finishErr = "Product finish is required";
            } 
            else { 
                $finish = checkInput($_POST["prodFinish"]);
            }
            // If the product usage area was empty...
            if (empty($_POST["prodUsage"])) {
                $valid = "no";
                $usageErr = "Product usage is required";
            } 
            else { 
                $usage = checkInput($_POST["prodUsage"]);
            }
            // If the product cost area was empty...
            if (empty($_POST["prodCost"])) {
                $valid = "no";
                $costErr = "Product cost is required";
            } 
            else { 
                $cost = checkInput($_POST["prodCost"]);
                if (!is_numeric($cost)) {
                    $valid = "no";
                    $costErr = "Product cost must be numeric (don't include the $)";
                }
            }

            // Image validation
            $icon = checkInput($_POST["prodImage"]);
		    $target_dir = "images/";
		    $target_file = $target_dir . @basename($_FILES["prodImage"]["name"]);
		    $uploadOk = 1;
		    $imageFileType = @strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		    // Check if image file is a actual image or fake image
		    if(isset($_POST["submit"])) {
		      $check = @getimagesize($_FILES["prodImage"]["tmp_name"]);
		      if($check !== false) {
		    	$iconErr = "File is an image - " . $check["mime"] . ".";
		    	$uploadOk = 1;
		      } else {
		    	$iconErr = "File is not an image.";
		    	$uploadOk = 0;
		      }
		    }

		    // Check if file already exists
		    if (@file_exists($target_file)) {
		      $iconErr = "File already exists.";
		      $uploadOk = 0;
		    }

		    // Allow certain file formats
		    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
		      $iconErr = "Only JPG, JPEG & PNG files are allowed.";
		      $uploadOk = 0;
		    }
		    // Check if $uploadOk is set to 0 by an error
		    if ($uploadOk == 1) {
		      if (@move_uploaded_file($_FILES["prodImage"]["tmp_name"], $target_file)) {
		    	$iconErr = "The file ". @htmlspecialchars( @basename( $_FILES["prodImage"]["name"])). " has been uploaded.";
		      } else {
		    	$iconErr = "There was an error uploading file.";
                $valid = "no";
		      }
		    } else {
                $valid = "no";
            }

            if ($valid == "yes") {
                
                //insert data
                $query = "INSERT INTO products (prodName, prodFinish, prodUsage, prodCost, prodImage)
                    VALUES ('".$name."', '".$finish."', '".$usage."', '	".$cost."', '".$target_file."')";
                if (!mysqli_query($conn,$query)) {
                    $success = "<p>Error inserting product data: " . mysqli_error($conn) . "</p>";
                }
                else {
                    $success = "<p>Product data inserted successfully</p>";
                    $pid = mysqli_insert_id($conn);
                    @setcookie("pid", $pid);
                    $_POST = array();
                    include("processProdDetails.php");
                    @header('Location: processProdDetails.php');
                    exit();
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
    productFormvalidation.php
    25/11/2022
    Amos Chamberlain
    Allows the user to input product information to go into the database
-->
<!DOCTYPE HTML>
<hmtl lang="en">
    <head>
        <title>Add new Product | Acme Door Levers</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/style.css"/>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&family=Shippori+Antique&display=swap" rel="stylesheet">
    </head>
    <body>
        <header>
            <h1 class="heading">Acme Door Levers - Part 1</h1>
            <h2 class="subheading">Add New Product</h2>
        </header>

        <article>
            <span class="warning instruct">* - required</span>
            <form name="productForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
	        	<label for="prodName">Product name: </label>
	        	<input type="text" id="prodName" name="prodName" value="<?php echo $name;?>">
	        	<span class="error">* <?php echo $nameErr;?></span><br>

	        	<label for="prodFinish">Product finish: </label>
	        	<input type="text" id="prodFinish" name="prodFinish" value="<?php echo $finish;?>">
	        	<span class="error">* <?php echo $finishErr;?></span><br>

	        	<label for="prodUsage">Product usage: </label>
	        	<input type="text" id="prodUsage" name="prodUsage" value="<?php echo $usage;?>">
	        	<span class="error">* <?php echo $usageErr;?></span><br>

	        	<label for="prodCost">Product cost: $</label>
	        	<input type="text" id="prodCost" name="prodCost" value="<?php echo $cost;?>">
	        	<span class="error">* <?php echo $costErr;?></span><br>
                
                <label for="prodImage">Upload Product Image:</label>
		        <input type="file" name="prodImage" id="prodImage">
		        <span class="error">* <?php echo $iconErr;?></span><br>

	        	<input type="submit" value="Submit" name="submit">
	        	<input type="reset" value="Reset"><br>
	    </form>
        </article>
    </body>
</html>