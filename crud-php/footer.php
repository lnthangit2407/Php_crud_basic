</div> <!-- end .container -->
      
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    
    <!-- Latest compiled and minified Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
    <script type='text/javascript'>
        // confirm record deletion
        function delete_user( id ){
            
            var answer = confirm('Are you sure?');
            if (answer){
                // if user clicked ok, 
                // pass the id to delete.php and execute the delete query
                window.location = 'delete.php?id=' + id;
            } 
        }
    </script>
</body>
</html>