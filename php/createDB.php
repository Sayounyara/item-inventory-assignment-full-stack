<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Create Database</title>
	</head>
	<body>
		<?php
        $conn = mysqli_connect("localhost","root","","acme");
        // Checks if there was a sql connection error
        if (mysqli_connect_errno())
        {
            echo "Failed to connect to MySQL: ". mysqli_connect_error();
        }
        else
        {
            // If no error, create a query to drop acme database, create it, and create a table called products
            echo "Connected to MySQL";
            $query = "DROP DATABASE IF EXISTS acme;";
            $query.= "CREATE DATABASE acme;";
            $query.= "USE acme;";
            $query.= "CREATE TABLE products (
                id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                prodName VARCHAR(30) NOT NULL, 
                prodFinish VARCHAR(30) NOT NULL, 
                prodUsage VARCHAR(30) NOT NULL, 
                prodCost FLOAT(8,2) NOT NULL,
                prodImage VARCHAR(100) NOT NULL
            );";
            $query.= "CREATE TABLE users (
                userName VARCHAR(50) NOT NULL PRIMARY KEY,
                userPassword VARCHAR(255) NOT NULL
            );";
            $query.="INSERT INTO users (userName, userPassword)
                VALUES
                    ('amos_chamberlain', '".password_hash("Melodii", PASSWORD_BCRYPT)."'),
                    ('joe_bloggs', '".password_hash("Bassline", PASSWORD_BCRYPT)."');";
        }

        // If query has multiple lines of code, this, will be true
        if (mysqli_multi_query($conn,$query)) 
	    {
		    echo "<p>Database and products table created successfully</p>";
	        // execute each query given in sequence
		    do {mysqli_next_result($conn);} while (mysqli_more_results($conn));
	    }
	    else 
	    {
            // If an error occured, print this
	    	echo "<p>Error creating table: ". mysqli_error($conn). "</p>";
	    }
		?>
	</body>
</html>