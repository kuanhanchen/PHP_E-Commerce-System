<?php //require_once("../../resources/config.php"); ?>
<!-- kuanhanchen.com/ecom -->

<!-- localhost -->
<?php require_once("../resources/config.php"); ?>

<?php include(TEMPLATES_FRONT . DS . "header.php") ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Categories here -->
            <?php include(TEMPLATES_FRONT . DS . "side_nav.php") ?>

            <div class="col-md-9">

                <div class="row carousel-holder">

                    <div class="col-md-12">
                        
                        <!-- Carousel here -->
                        <?php include(TEMPLATES_FRONT . DS . "slider.php") ?>

                    </div>

                </div>

            </div>

            <div class="row">
                    
                <?php 

                    get_products();

                ?>

            </div>

        </div>

    </div>
    <!-- /.container -->

<?php include(TEMPLATES_FRONT . DS . "footer.php") ?>    