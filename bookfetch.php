

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookNook</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
    </style>
</head>
<body>
    <?php
        // XAMPP's default account
        $host = "localhost";
        $user = "root";
        $pass = "";
        $dbName = "booknook_db";

        /*
            1. Connect to the DBMS
            2. Select the DB
            3. Run SQL commands
            4. Process the results
            5. Close the connection
        */

        // 1 + 2: Create connection and select DB
        $conn = new mysqli($host, $user, $pass, $dbName);

        // if a connection results in an error, end it
        if($conn->connect_errno > 0){
            die("Unable to connect to DBMS and/or DB.<br/>");
        }


        if(isset($_GET["book_id"])){
            $id = $_GET["book_id"];
          
            // 3. RUN SQL commands
            // to start off, we'll try selecting a book of a specific iD
            $sql = "SELECT * FROM books where book_id = '$id';";

            if( !$result = $conn->query($sql)){
                die("Unable to execute the SQL command.<br/>");
            }

            // 4. Process the results

            echo "
            <table>
                <tr>
                    <th>Book ID</th>
                    <th>Title</th>
                    <th>Year</th>
                    <th>Genre ID</th>
                    <th>ISBN</th>
                    <th>Price</th>
                    <th>Blurb</th>
                </tr>";
            
                // store each row in $row
            while($row = $result->fetch_assoc() ){
                $book_id = $row["book_id"];
                $title = $row["title"];
                $year = $row["year"];
                $genre_id = $row["genre_id"];
                $isbn = $row["isbn"];
                $price = $row["price"];
                $blurb = $row["blurb"];

                echo "
                <tr>
                    <td>$book_id</td>
                    <td>$title</td>
                    <td>$year</td>
                    <td>$genre_id</td>
                    <td>$isbn</td>
                    <td>$price</td>
                    <td>$blurb</td>
                </tr>";
            }
            echo "</table>";
        }else{
            ?>
            <form action ="bookfetch.php" method = "GET">
                <select id="book_id" name="book_id" tabindex="1" autofocus="autofocus">
                    <option value=""></option>
                    <?php
                        $formsql = "SELECT book_id, title FROM books ORDER BY title;";

                        if( !$result = $conn->query($formsql)){
                            die("Unable to execute the SQL command.<br/>");
                        }

                        // 4. Process the results
                        
                            // store each row in $row
                        while($row = $result->fetch_assoc() ){
                            $book_id = $row["book_id"];
                            $title = $row["title"];

                            echo "
                            <option value=\"$book_id\">$title</option>";

                        }
                    ?>
                </select>
                <input type = "submit" value="Submit" tabindex="2"/>
            </form>
            <?php
        }


        $result->free();
        $conn->close();

        ?>

    
    <h1>Welcome to BookNook</h1>
    <!-- HTML content here -->
</body>
</html>