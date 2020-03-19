<!-- used for reading records from the database. It uses an HTML table to display the data retrieved from the MySQL database. -->

<?php
    require_once('header.php');
?>
<!-- We prepare this to read records from the database. It answers the question: how to read records with PDO? -->
<div class="page-header">
    <h1>Read Products</h1>
</div>

<?php
    // include database connection
    include 'config/database.php';

    // The following variables are used to calculate the correct numbers for the LIMIT clause of our SELECT query.
    // PAGINATION VARIABLES
    // page is the current page, if there's nothing set, default is page 1
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    
    // set records or rows of data per page
    $records_per_page = 5;
    
    // calculate for the query LIMIT clause
    $from_record_num = ($records_per_page * $page) - $records_per_page;
    
    // delete message prompt will be here
    $action = isset($_GET['action']) ? $_GET['action'] : "";
    
    // if it was redirected from delete.php
    if($action=='deleted'){
        echo "<div class='alert alert-success'>Record was deleted.</div>";
    }
    
    // select all data
    // $query = "SELECT id, name, description, price FROM products ORDER BY id DESC";
    // $stmt = $con->prepare($query);
    // $stmt->execute();
    // This will enable paginated requests to database. 

    // select data for current page
    $query = "SELECT id, name, description, price FROM products ORDER BY id DESC
    LIMIT :from_record_num, :records_per_page";

    $stmt = $con->prepare($query);
    $stmt->bindParam(":from_record_num", $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(":records_per_page", $records_per_page, PDO::PARAM_INT);
    $stmt->execute();
    // Counting the total number of records will help calculate the correct pagination numbers.
    // Below the closing table tag in index.php file, add the following code.
    
    // this is how to get number of rows returned
    $num = $stmt->rowCount();
    
    // link to create record form
    echo "<a href='create.php' class='btn btn-primary m-b-1em'>Create New Product</a>";
    
    //check if more than 0 record found
    if($num>0){
    
        // This is the HTML table that will hold and display data from the database.
        echo "<table class='table table-hover table-responsive table-bordered'>";//start table
 
            //creating our table heading
            echo "<tr>";
                echo "<th>ID</th>";
                echo "<th>Name</th>";
                echo "<th>Description</th>";
                echo "<th>Price</th>";
                echo "<th>Action</th>";
            echo "</tr>";
            
            // This part is where we will loop through the list of records from the database. This loop will create the rows of data on our HTML table
            // retrieve our table contents
            // fetch() is faster than fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                // extract row
                // this will make $row['firstname'] to
                // just $firstname only
                extract($row);
                
                // creating new table row per record
                echo "<tr>";
                    echo "<td>{$id}</td>";
                    echo "<td>{$name}</td>";
                    echo "<td>{$description}</td>";
                    echo "<td>&#36;{$price}</td>";
                    echo "<td>";
                        // read one record 
                        echo "<a href='detail.php?id={$id}' class='btn btn-info m-r-1em'>Read</a>";
                        
                        // we will use this links on next part of this post
                        echo "<a href='update.php?id={$id}' class='btn btn-primary m-r-1em'>Edit</a>";
            
                        // we will use this links on next part of this post
                        echo "<a href='#' onclick='delete_user({$id});'  class='btn btn-danger'>Delete</a>";
                    echo "</td>";
                echo "</tr>";
            }
    
        // end table
        echo "</table>";

        // PAGINATION
        // count total number of rows
        $query = "SELECT COUNT(*) as total_rows FROM products";
        $stmt = $con->prepare($query);
        
        // execute query
        $stmt->execute();
        
        // get total rows
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_rows = $row['total_rows'];

        // paginate records
        $page_url="index.php?";
        include_once "paging.php";
        // Why a $page_url variable is needed? Because we made paging.php re-usable. You can use it for other objects you want to paginate.
        // For example you're trying to paginate your read_categories.php, you will need to do:
        // $page_url="read_categories.php?";
        // You will have to follow the code pattern of section 10.1 to 10.3 when you use paging.php file.
        
    }
    
    // if no records found
    else{
        echo "<div class='alert alert-danger'>No records found.</div>";
    }
?>
<?php
    require_once('footer.php');
?>