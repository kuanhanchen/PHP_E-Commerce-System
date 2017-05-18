<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Delete Box</h4>
      </div>
      <div class="modal-body">
        <h3 class="text-center">Are you sure you want to delete this post?</h3>
      </div>
      <div class="modal-footer">

        <form method="POST">

          <!-- get value=id from view_all_posts.php -->
          <input type="hidden" name="post_id" class="modal_delete">
          
          <input type="button" name="cancel" value="Cancel" class="btn btn-default" data-dismiss="modal">
            
          <input type="submit" name="delete" value="Delete" class="form-group btn btn-danger">

        </form>

        <?php 

          if(isset($_POST['delete'])){

            // find image_name by id, and unlink (truly delete) image in uploads folder
            $query_find_image = query("SELECT slide_image FROM slides WHERE slide_id = " . escape_string($_GET['id']) . " LIMIT 1 ");
            confirm($query_find_image); 
              $row = fetch_array($query_find_image);
            $target_path = UPLOAD_DIRECTORY . DS . $row['slide_image'];
            unlink($target_path);


            // delete image in phpmyadmin
            $query = query("DELETE FROM slides WHERE slide_id = " . escape_string($_GET['id']) . " ");
            confirm($query);


            set_message("Slide Deleted");

            redirect("../../../public/admin/index.php?slides");
          }

        ?>
      </div>
    </div>

  </div>
</div>