<?php //require_once("../../../resources/config.php"); ?>
<!-- kuanhanchen.com/ecom -->

<!-- localhost -->
<?php require_once("../../resources/config.php"); ?>

<?php include(TEMPLATES_BACK . "/header.php"); ?>
<?php 

// only allow logined user enable to access admin page
if(!isset($_SESSION['username'])){

    redirect("../../public");

}

?>


        <div id="page-wrapper">

            <div class="container-fluid">


            <?php 
        
            //localhost
            if($_SERVER['REQUEST_URI'] == "/ecom/public/admin/" || $_SERVER['REQUEST_URI'] == "/ecom/public/admin/index.php"){

                include(TEMPLATES_BACK . "/admin_content.php");

            }

            // get $_GET['orders'] from side_nav.php
            if(isset($_GET['orders'])){

                include(TEMPLATES_BACK . "/orders.php");
            }

            // get $_GET['categories'] from side_nav.php
            if(isset($_GET['categories'])){

                include(TEMPLATES_BACK . "/categories.php");
            }

            // get $_GET['products'] from side_nav.php
            if(isset($_GET['products'])){

                include(TEMPLATES_BACK . "/products.php");
            }

            // get $_GET['add_products'] from side_nav.php
            if(isset($_GET['add_product'])){

                include(TEMPLATES_BACK . "/add_product.php");
            }

            // get $_GET['edit_products'] from side_nav.php
            if(isset($_GET['edit_product'])){

                include(TEMPLATES_BACK . "/edit_product.php");
            }

            // get $_GET['users'] from side_nav.php
            if(isset($_GET['users'])){

                include(TEMPLATES_BACK . "/users.php");
            }

            // get $_GET['add_user'] from side_nav.php
            if(isset($_GET['add_user'])){

                include(TEMPLATES_BACK . "/add_user.php");
            }

            // get $_GET['edit_user'] from side_nav.php
            if(isset($_GET['edit_user'])){

                include(TEMPLATES_BACK . "/edit_user.php");
            }

            // get $_GET['reports'] from side_nav.php
            if(isset($_GET['reports'])){

                include(TEMPLATES_BACK . "/reports.php");
            }

            if(isset($_GET['slides'])){

                include(TEMPLATES_BACK . "/slides.php");
            }

            if(isset($_GET['delete_order_id'])){

                include(TEMPLATES_BACK . "/delete_order.php");
            }

            if(isset($_GET['delete_product_id'])){

                include(TEMPLATES_BACK . "/delete_product.php");
            }

            if(isset($_GET['delete_category_id'])){

                include(TEMPLATES_BACK . "/delete_category.php");
            }

            if(isset($_GET['delete_user_id'])){

                include(TEMPLATES_BACK . "/delete_user.php");
            }

            if(isset($_GET['delete_report_id'])){

                include(TEMPLATES_BACK . "/delete_report.php");
            }

            if(isset($_GET['delete_slide_id'])){

                include(TEMPLATES_BACK . "/delete_slide.php");
            }
           

            

            ?>
            

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

<?php include(TEMPLATES_BACK . "/footer.php"); ?>    