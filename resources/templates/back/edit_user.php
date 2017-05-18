<?php 

// get the current data from database to display in each column in this page
if(isset($_GET['id'])){

    $query = query("SELECT * FROM  users WHERE user_id =" . escape_string($_GET['id']));
    confirm($query);

    while($row = fetch_array($query)){

        $username = escape_string($row['username']);
        $password = escape_string($row['password']);
        $email = escape_string($row['email']);
        $user_image = escape_string($row['user_image']);
        $user_image = display_image($row['user_image']);

    }

        
    update_user();

}

?>

<div class="col-md-12">

<div class="row">
<h1 class="page-header">
   Edit User
</h1>
</div>
               

<form action="" method="post" enctype="multipart/form-data">

  <div class="col-md-12">

  <div class="form-group">

      <label for="username">Username </label>
          <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
         
      <label for="password">Password</label>
          <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
         
      <label for="email">Email</label>
          <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">

      <!-- Product Image -->
      
        <label for="product-title">Product Image</label>
        <input type="file" name="file">
        <img width="200" src="../../resources/<?php echo $user_image; ?>" alt="">
      
      <hr>
      <div>
        <input type="submit" name="draft" class="btn btn-warning btn-lg" value="Draft">
        <input type="submit" name="update" class="btn btn-primary btn-lg" value="Update">
  </div>
    </div>
      

  </div><!--Main Content-->

</form>
