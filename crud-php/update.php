<!-- used for updating a record. It uses an HTML form which will be filled out with data based on the given “id” parameter. -->

<?php
    require_once('header.php');
?>
<!-- This will answer the question: how to update a record with PDO? -->
<div class="page-header">
    <h1>Update Product</h1>
</div>

<!-- We have to get the record ID and store it in the $id variable. We access the $_GET['id'] variable to do it. -->
<!-- What we are trying to do here is to get the related data based on the given record ID. This is the way to auto-fill the HTML form with existing data from the database. -->
<?php
    // get passed parameter value, in this case, the record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
    
    //include database connection
    include 'config/database.php';
    
    // read current record's data
    try {
        // prepare select query
        $query = "SELECT id, name, description, price FROM products WHERE id = ? LIMIT 0,1";
        $stmt = $con->prepare( $query );
        
        // this is the first question mark
        $stmt->bindParam(1, $id);
        
        // execute our query
        $stmt->execute();
        
        // store retrieved row to a variable
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // values to fill up our form
        $name = $row['name'];
        $description = $row['description'];
        $price = $row['price'];
    }
    
    // show error
    catch(PDOException $exception){
        die('ERROR: ' . $exception->getMessage());
    }
?>
<!-- This form will show the data retrieved using the previous section's code. -->
<!-- We read a single record from the database, based on given ID parameter. -->
<!-- PHP post to update record will be here -->
<?php
    // check if form was submitted
    if($_POST){
        
        try{
        
            // write update query
            // in this case, it seemed like we have so many fields to pass and 
            // it is better to label them and not use question marks
            $query = "UPDATE products 
                        SET name=:name, description=:description, price=:price 
                        WHERE id = :id";
    
            // prepare query for excecution
            $stmt = $con->prepare($query);
    
            // posted values
            $name=htmlspecialchars(strip_tags($_POST['name']));
            $description=htmlspecialchars(strip_tags($_POST['description']));
            $price=htmlspecialchars(strip_tags($_POST['price']));
    
            // bind the parameters
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':id', $id);
            
            // Execute the query
            if($stmt->execute()){
                echo "<div class='alert alert-success'>Record was updated.</div>";
            }else{
                echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
            }
            
        }
        
        // show errors
        catch(PDOException $exception){
            die('ERROR: ' . $exception->getMessage());
        }
    }
?>
 
<!--we have our html form here where new record information can be updated-->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}");?>" method="post">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>Name</td>
            <td><input type='text' name='name' value="<?php echo htmlspecialchars($name, ENT_QUOTES);  ?>" class='form-control' /></td>
        </tr>
        <tr>
            <td>Description</td>
            <td><textarea name='description' class='form-control'><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></textarea></td>
        </tr>
        <tr>
            <td>Price</td>
            <td><input type='text' name='price' value="<?php echo htmlspecialchars($price, ENT_QUOTES);  ?>" class='form-control' /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' value='Save Changes' class='btn btn-primary' />
                <a href='index.php' class='btn btn-danger'>Back to read products</a>
            </td>
        </tr>
    </table>
</form>
<?php
    require_once('footer.php');
?>