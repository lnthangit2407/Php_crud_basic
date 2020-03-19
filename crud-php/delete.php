<!--  used for deleting a record. It accepts an “id” parameter and deletes the record with it. Once it execute the delete query, it will redirect the user to the index.php page. -->
<?php
    require_once('header.php');
?>
<!-- This answers the question: how to delete a record with PDO? -->
<?php
    // include database connection
    include 'config/database.php';
    
    try {
        
        // get record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
    
        // delete query
        $query = "DELETE FROM products WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $id);
        
        if($stmt->execute()){
            // redirect to read records page and 
            // tell the user record was deleted
            header('Location: index.php?action=deleted');
        }else{
            die('Unable to delete record.');
        }
    }
    
    // show error
    catch(PDOException $exception){
        die('ERROR: ' . $exception->getMessage());
    }
?>
<?php
    require_once('footer.php');
?>