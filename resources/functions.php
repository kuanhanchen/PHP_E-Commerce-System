<?php

$upload_directory = "uploads";

// helper functions

function last_id(){

	global $connection;
	return mysqli_insert_id($connection);

}


function set_message($msg){

	if(!empty($msg)){

		$_SESSION['message'] = $msg;

	} else{

		$msg = "";

	}

}

function display_message(){

	if(isset($_SESSION['message'])){

		echo $_SESSION['message'];
		unset($_SESSION['message']);

	}

}



function redirect($location){

	header("Location: $location");

}

function query($sql){

	global $connection;
	return mysqli_query($connection, $sql);

}

function confirm($result){

	global $connection;

	if(!$result){

		die("QUERY FAILED" . mysqli_error($connection));

	}

}

function escape_string($string){

	global $connection;

	return mysqli_real_escape_string($connection, $string);

}

function fetch_array($result){

	return mysqli_fetch_array($result);

}


/*************************FRONT END FUNCTIONS*************************/


// get products shown in index.php
function get_products(){

	$query = query("SELECT * FROM products WHERE product_quantity >= 1 ");
	confirm($query);


// pagination

	// Get total of mumber of rows from the database
	$rows = mysqli_num_rows($query);

	if(isset($_GET['page'])){ //get page from URL if its there

	    $page = preg_replace('#[^0-9]#', '', $_GET['page']);
	    //filter everything but numbers


	} else{// If the page url variable is not present force it to be number 1

	    $page = 1;

	}


	$perPage = 6; // Items per page here 

	$lastPage = ceil($rows / $perPage); // Get the value of the last page


	// Be sure URL variable $page(page number) is no lower than page 1 and no higher than $lastpage

	if($page < 1){ // If it is less than 1

	    $page = 1; // force if to be 1

	}elseif($page > $lastPage){ // if it is greater than $lastpage

	    $page = $lastPage; // force it to be $lastpage's value

	}



	$middleNumbers = ''; // Initialize this variable

	// This creates the numbers to click in between the next and back buttons


	$sub1 = $page - 1;
	$sub2 = $page - 2;
	$add1 = $page + 1;
	$add2 = $page + 2;


	// if in page 1
	if($page == 1){

		// if there only 1 page, only show page 1
	    $middleNumbers .= '<li class="page-item active"><a>' .$page. '</a></li>';

	    // if there is 2 page and be in page 1 now, create page 2
	    if($page != $lastPage){	

	    	$middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$add1.'">' .$add1. '</a></li>';

	    }

	} elseif ($page == $lastPage) {	// if in the last page and not page 1, means there are more than one page, create previous page and current page
	    
	    $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$sub1.'">' .$sub1. '</a></li>';
	    $middleNumbers .= '<li class="page-item active"><a>' .$page. '</a></li>';

	}elseif ($page > 2 && $page < ($lastPage -1)) { // e.g. in page 3 and lastpage=5, create page 1, 2, 3, 4 and 5

	    $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$sub2.'">' .$sub2. '</a></li>';

	    $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$sub1.'">' .$sub1. '</a></li>';

	    $middleNumbers .= '<li class="page-item active"><a>' .$page. '</a></li>';

	    $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$add1.'">' .$add1. '</a></li>';

	    $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$add2.'">' .$add2. '</a></li>';

	} elseif($page > 1 && $page < $lastPage){ // e.g. in page 2 and lastpage=5, create page 1, 2 and 3

	     $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page= '.$sub1.'">' .$sub1. '</a></li>';

	     $middleNumbers .= '<li class="page-item active"><a>' .$page. '</a></li>';
	 
	     $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$add1.'">' .$add1. '</a></li>';

	}


	// This line sets the "LIMIT" range... the 2 values we place to choose a range of rows from database in our query
	// e.g. in page 2 and $perPage=6, get rows after 6th(1*6) rows and get 6 rows
	$limit = 'LIMIT ' . ($page-1) * $perPage . ',' . $perPage;


	// $query2 is what we will use to to display products with out $limit variable

	$query_display_current_items = query(" SELECT * FROM products WHERE product_quantity >= 1 $limit ");
	confirm($query_display_current_items);


	$outputPagination = ""; // Initialize the pagination output variable

	if($lastPage != 1){

	   echo "<p style='margin-left:20px;'>Page $page of $lastPage</p>";

	}

	// If we are not on page one we place the back link
	if($page != 1){

	    $prev  = $page - 1;

	    $outputPagination .='<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$prev.'">Back</a></li>';
	}

	 // Lets append all our links to this variable that we can use this output pagination

	$outputPagination .= $middleNumbers;


	// If we are not on the very last page we the place the next link

	if($page != $lastPage){

	    $next = $page + 1;

	    $outputPagination .='<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$next.'">Next</a></li>';

	}

// Done with pagination



	// Remember we use query 2 below :)

	while($row = fetch_array($query_display_current_items)) {


		$category = show_product_category_title($row['product_category_id']);

		$product_image = display_image($row['product_image']);

		$product = <<<DELIMETER

		<div class="col-sm-4 col-lg-4 col-md-4">
	        <div class="thumbnail">
	            <a href="item.php?id={$row['product_id']}"><img style="height:90px;" src="../resources/{$product_image}" alt=""></a>
	            <div class="caption">
	                <h4 class="pull-right">&#36;{$row['product_price']}</h4>
	                <h4><a href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
	                </h4>
	                <p>See more snippets like this online store item at <a target="_blank" href="http://www.bootsnipp.com">Bootsnipp - http://bootsnipp.com</a>.</p>
	                <a class="btn btn-primary" target="_blank" href="../resources/cart.php?add={$row['product_id']}">Add to Cart</a>
	            </div>
	        </div>
	    </div>

DELIMETER;

		echo $product;

	}

	 echo "<div class='text-center'><ul class='pagination'>{$outputPagination}</ul></div>";

}

function get_categories(){

	$query = query("SELECT * FROM categories");
	confirm($query);

	while($row = fetch_array($query)){

		$categories_links = <<<DELIMETER

		<a href='category.php?id={$row['cat_id']}' class='list-group-item'>{$row['cat_title']}</a>

DELIMETER;

		echo $categories_links;		

	}

}


function get_products_in_cat_page(){

	$query = query("SELECT * FROM products WHERE product_category_id = " . escape_string($_GET['id']) . " AND product_quantity >= 1");
	confirm($query);

	while($row = fetch_array($query)){

		$product_image = display_image($row['product_image']);

		$product = <<<DELIMETER

		<div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img style="height:90px;" src="../resources/{$product_image}" alt="">
                    <div class="caption">
                        <h3>{$row['product_title']}</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                        <p>
                            <a href="../resources/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> 
                            <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>

DELIMETER;

		echo $product;

	}

}


function get_products_in_shop_page(){

	$query = query("SELECT * FROM products WHERE product_quantity >= 1 ");
	confirm($query);

	while($row = fetch_array($query)){

		$product_image = display_image($row['product_image']);

		$product = <<<DELIMETER

		<div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img style="height:90px;" src="../resources/{$product_image}" alt="">
                    <div class="caption">
                        <h3>{$row['product_title']}</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                        <p>
                            <a href="../resources/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> 
                            <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>

DELIMETER;

		echo $product;

	}

}


function login_user(){

	if(isset($_POST['submit'])){

		$username = escape_string($_POST['username']);
		$password = escape_string($_POST['password']);

		$query = query("SELECT * FROM users WHERE username = '{$username}' AND password = '{$password}'");

		confirm($query);

		if(mysqli_num_rows($query) == 0){

			set_message('Your Password or Username are wrong!');
			redirect('login.php');

		} else{

			$_SESSION['username'] = $username;	//make only logined user can access admin
			set_message("Welcome to Admin {$username}");
			redirect('admin');

		}
		
	}

}


function send_message(){

	if(isset($_POST['submit'])){

		$to = "kuanhanchen0608@gmail.com";

		$from_name = $_POST['name'];
		$subject = $_POST['subject'];
		$email = $_POST['email'];
		$message = $_POST['message'];
		
		$headers = "From: {$from_name} {$email}";

		$result = mail($to, $subject, $message, $headers);

		if(!$result){

			set_message("Sorry we could not send your message");
			redirect("contact.php");

		} else {

			set_message("Your message has been sent");
			redirect("contact.php");

		}

	}

}

/*************************BACK END FUNCTIONS*************************/

function display_orders(){

	$query = query("SELECT * FROM orders");
	confirm($query);

	while($row = fetch_array($query)){

		$orders = <<<DELIMETER

		<tr>
			<td>{$row['order_id']}</td>
			<td>{$row['order_amount']}</td>
			<td>{$row['order_transaction']}</td>
			<td>{$row['order_currency']}</td>
			<td>{$row['order_status']}</td>
			<td><a class="btn btn-danger" href="index.php?delete_order_id={$row['order_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
		</tr>

DELIMETER;

	echo $orders;

	}

}

/*************************ADMIN PRODUCTS*************************/

function display_image($picture) {

	global $upload_directory;

	return $upload_directory  . DS . $picture;

}

// used in products.php
function get_products_in_admin(){

	$query = query("SELECT * FROM products");
	confirm($query);

	// image linking to edit_product.php
	while($row = fetch_array($query)){

		// show real category name by show_product_category_title() with given category_id
		$category = show_product_category_title($row['product_category_id']);

		$product_image = display_image($row['product_image']);

		$product = <<<DELIMETER

		<tr>
            <td>{$row['product_id']}</td>
            <td>{$row['product_title']}<br>
              <a href="index.php?edit_product&id={$row['product_id']}"><img width='100' src="../../resources/{$product_image}" alt=""></a>
            </td>
            <td>$category</td>
            <td>{$row['product_price']}</td>
            <td>{$row['product_quantity']}</td>
            <td><a class="btn btn-danger" href="index.php?delete_product_id={$row['product_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>

DELIMETER;

		echo $product;

	}

}

function show_product_category_title($product_category_id){

	$category_query = query("SELECT * FROM categories WHERE cat_id = '{$product_category_id}' ");
	confirm($category_query);

	while($category_row = fetch_array($category_query)) {

		return $category_row['cat_title'];

	}

}

/*************************ADD PRODUCTS IN ADMIN*************************/

function add_product(){

	if(isset($_POST['publish'])){

		$product_title          = escape_string($_POST['product_title']);
		$product_category_id    = escape_string($_POST['product_category_id']);
		$product_price          = escape_string($_POST['product_price']);
		$product_description    = escape_string($_POST['product_description']);
		$short_desc             = escape_string($_POST['short_desc']);
		$product_quantity       = escape_string($_POST['product_quantity']);
		$product_image          = escape_string($_FILES['file']['name']);
		$image_temp_location    = escape_string($_FILES['file']['tmp_name']);

		move_uploaded_file($image_temp_location  , UPLOAD_DIRECTORY . DS . $product_image);

		$query = query("INSERT INTO products(product_title, product_category_id, product_price, product_description, short_desc, product_quantity, product_image) VALUES('{$product_title}', '{$product_category_id}', '{$product_price}', '{$product_description}', '{$short_desc}', '{$product_quantity}', '{$product_image}')");
		$last_id = last_id();
		confirm($query);
		set_message("New Product with id {$last_id} was Added");
		redirect("index.php?products");

	}

}


// used in add_product and edit_product.php
function show_categories_add_product_page(){

	$query = query("SELECT * FROM categories");
	confirm($query);

	while($row = fetch_array($query)) {

		$categories_options = <<<DELIMETER

 		<option value="{$row['cat_id']}">{$row['cat_title']}</option>

DELIMETER;

		echo $categories_options;

    }

}

/*************************EDIT PRODUCTS IN ADMIN*************************/

function update_product(){

	if(isset($_POST['update'])){

		$product_title          = escape_string($_POST['product_title']);
		$product_category_id    = escape_string($_POST['product_category_id']);
		$product_price          = escape_string($_POST['product_price']);
		$product_description    = escape_string($_POST['product_description']);
		$short_desc             = escape_string($_POST['short_desc']);
		$product_quantity       = escape_string($_POST['product_quantity']);
		$product_image          = escape_string($_FILES['file']['name']);
		$image_temp_location    = escape_string($_FILES['file']['tmp_name']);


		// help get original image if don't update image
		if(empty($product_image)){

			$get_pic = query("SELECT product_image FROM products WHERE product_id =" . escape_string($_GET['id']) . "");
			confirm($get_pic);

			while($pic = fetch_array($get_pic)){

				$product_image = $pic['product_image'];

			}

		}


		move_uploaded_file($image_temp_location  , UPLOAD_DIRECTORY . DS . $product_image);

		$query = "UPDATE products SET ";
		$query .= "product_title 			= '{$product_title}', ";
		$query .= "product_category_id 		= '{$product_category_id}', ";
		$query .= "product_price 			= '{$product_price}', ";
		$query .= "product_description 		= '{$product_description}', ";
		$query .= "short_desc 				= '{$short_desc}', ";
		$query .= "product_quantity 		= '{$product_quantity}', ";
		$query .= "product_image 			= '{$product_image}' ";
		$query .= "WHERE product_id=" . escape_string($_GET['id']);

		$send_update_query = query($query);

		confirm($send_update_query);
		set_message("Product has been updated");
		redirect("index.php?products");

	}

}

/*************************CATEGORIES IN ADMIN*************************/

function show_categories_in_admin(){

	$query = "SELECT * FROM categories";
	$category_query = query($query);
	confirm($category_query);

	while($row = fetch_array($category_query)){

		$cat_id = $row['cat_id'];
		$cat_title = $row['cat_title'];
		
		$category = <<<DELIMETER

		<tr>
            <td>$cat_id</td>
            <td>$cat_title</td>
            <td><a class="btn btn-danger" href="index.php?delete_category_id={$row['cat_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>

DELIMETER;

		echo $category;

	}

}


function add_category(){

    if(isset($_POST['add_category']) && !empty($_POST['cat_title'])){
        
        $cat_title = escape_string($_POST['cat_title']);
        
        $query = query("INSERT INTO categories(cat_title) VALUES('{$cat_title}')");
        confirm($query);
        
        set_message("The Category Created");
        
    }elseif(isset($_POST['add_category'])){

    	set_message("The Category Title Can't Be Empty");
    
    }
}



/*************************USERS IN ADMIN*************************/

function display_users(){

	$query = "SELECT * FROM users";
	$user_query = query($query);
	confirm($user_query);

	while($row = fetch_array($user_query)){

		$user_id = $row['user_id'];
		$user_name = $row['username'];
		$email = $row['email'];
		$password = $row['password'];

		$user_image = display_image($row['user_image']);
		
		$user = <<<DELIMETER

		<tr>
            <td>$user_id</td>
            <td>$user_name<br>
            	<a href="index.php?edit_user&id={$row['user_id']}"><img width='100' src="../../resources/{$user_image}" alt=""></a>
            </td>
            <td>$email</td>
            <td><a class="btn btn-danger" href="index.php?delete_user_id={$row['user_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>

DELIMETER;

		echo $user;

	}

}


function add_user(){

	if(isset($_POST['add_user'])) {

		$username   = escape_string($_POST['username']);
		$email      = escape_string($_POST['email']);
		$password   = escape_string($_POST['password']);
		$user_image = escape_string($_FILES['file']['name']);
		$photo_temp = escape_string($_FILES['file']['tmp_name']);

		move_uploaded_file($photo_temp, UPLOAD_DIRECTORY . DS . $user_image);

		$query = query("INSERT INTO users(username, email, password, user_image) VALUES('{$username}','{$email}','{$password}', '{$user_image}')");
		confirm($query);

		set_message("USER CREATED");

		redirect("index.php?users");

	}

}

function update_user(){

	if(isset($_POST['update'])){

		$username = escape_string($_POST['username']);
		$password = escape_string($_POST['password']);
		$email = escape_string($_POST['email']);
		$user_image = escape_string($_FILES['file']['name']);
		$image_temp_location = escape_string($_FILES['file']['tmp_name']);

		// help get original image if don't update image
		if(empty($user_image)){

			$get_pic = query("SELECT user_image FROM users WHERE user_id =" . escape_string($_GET['id']) . "");
			confirm($get_pic);

			while($pic = fetch_array($get_pic)){

				$user_image = $pic['user_image'];

			}

		}

		move_uploaded_file($image_temp_location  , UPLOAD_DIRECTORY . DS . $user_image);

		$query = "UPDATE users SET ";
		$query .= "username 				= '{$username}', ";
		$query .= "password 				= '{$password}', ";
		$query .= "email 					= '{$email}', ";
		$query .= "user_image 				= '{$user_image}' ";
		$query .= "WHERE user_id=" . escape_string($_GET['id']);

		$send_update_query = query($query);

		confirm($send_update_query);
		set_message("User has been updated");
		redirect("index.php?users");

	}

}

/*************************REPORTS IN ADMIN*************************/

// used in products.php
function get_reports(){

	$query = query("SELECT * FROM reports");
	confirm($query);

	// image linking to edit_product.php
	while($row = fetch_array($query)){

		$report = <<<DELIMETER

		<tr>
			<td>{$row['report_id']}</td>
            <td>{$row['product_id']}</td>
            <td>{$row['order_id']}</td>
            <td>{$row['product_price']}</td>
            <td>{$row['product_title']}</td>
            <td>{$row['product_quantity']}</td>
            <td><a class="btn btn-danger" href="index.php?delete_report_id={$row['report_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>

DELIMETER;

		echo $report;

	}

}


/*************************SLIDES FUNCTIONS*************************/

function add_slides() {

	if(isset($_POST['add_slide'])) {

		$slide_title        = escape_string($_POST['slide_title']);
		$slide_image        = escape_string($_FILES['file']['name']);
		$slide_image_loc    = escape_string($_FILES['file']['tmp_name']);

		if(empty($slide_title) || empty($slide_image)) {

			echo "<h4 class='bg-danger'>This field cannot be empty</h4>";

		} else {

			move_uploaded_file($slide_image_loc, UPLOAD_DIRECTORY . DS . $slide_image);

			$query = query("INSERT INTO slides(slide_title, slide_image) VALUES('{$slide_title}', '{$slide_image}')");
			confirm($query);
			set_message("Slide Added");
			redirect("index.php?slides");

        }

    }

}


// show the newest stored image preview in slides.php
function get_current_slide_in_admin(){

	$query = query("SELECT * FROM slides ORDER BY slide_id DESC LIMIT 1");
	confirm($query);

	while($row = fetch_array($query)) {

		$slide_image = display_image($row['slide_image']);

		$slide_active_admin = <<<DELIMETER

    	<img class="img-responsive" src="../../resources/{$slide_image}" alt="">

DELIMETER;

		echo $slide_active_admin;

    }

}


// get the slide with the smallest slide_id to be active slide (first slide)
function get_active_slide() {

	$query = query("SELECT * FROM slides ORDER BY slide_id DESC LIMIT 1");
	confirm($query);

	while($row = fetch_array($query)) {

		$slide_image = display_image($row['slide_image']);

		$slide_active = <<<DELIMETER

		<div class="item active">
		    <img class="slide-image" src="../resources/{$slide_image}" alt="">
		</div>

DELIMETER;

		echo $slide_active;

    }

}


function get_slides() {

	$query = query("SELECT * FROM slides");
	confirm($query);

	while($row = fetch_array($query)) {

		$slide_image = display_image($row['slide_image']);

		$slides = <<<DELIMETER

		 <div class="item">
		    <img class="slide-image" src="../resources/{$slide_image}" alt="">
		</div>

DELIMETER;

		echo $slides;

	}

}


function get_slide_thumbnails(){

	$query = query("SELECT * FROM slides ORDER BY slide_id ASC ");
	confirm($query);

	while($row = fetch_array($query)) {

		$slide_image = display_image($row['slide_image']);

		$slide_thumb_admin = <<<DELIMETER


		<div class="col-xs-6 col-md-3 image_container">
		    
		    <a href="index.php?delete_slide_id={$row['slide_id']}">
		        
		        <img class="img-responsive slide_image" src="../../resources/{$slide_image}" alt="">

		    </a>

		    <div class="caption">

		    <p>{$row['slide_title']}</p>

		    </div>

		</div>

DELIMETER;

		echo $slide_thumb_admin;

    }

}

?>

