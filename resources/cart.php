<?php require_once("config.php"); ?>

<?php

if(isset($_GET['add'])){

    $query = query("SELECT * FROM products WHERE product_id=" . escape_string($_GET['add']) . "");

    confirm($query);

    while($row = fetch_array($query)){
        //  It checks the current available quantity of that particular product,and compares it with the request from the user,so if the quantity from the database,is not equal to the requested quantity,that only means that there is enough products left to sell.
        if($row['product_quantity'] != $_SESSION['product_' . $_GET['add']]){

            $_SESSION['product_' . $_GET['add']] += 1;
            redirect('../public/checkout.php');

        } else{

            set_message("We Only Have " . $row['product_quantity'] . " " . "{$row['product_title']}" . " Available");
            redirect('../public/checkout.php');

        }

    }

}

// if clicking delete btn, session value = number of item decrease 1
if(isset($_GET['remove'])){

    $_SESSION['product_' . $_GET['remove']]--;

    // if number of item = 0, delete session
    if($_SESSION['product_' . $_GET['add']] < 1){

        unset($_SESSION['item_total']);
        unset($_SESSION['item_quantity']);
        redirect('../public/checkout.php');

    } else{

        redirect("../public/checkout.php");

    }

}

// if clicking delete btn, session value = number of item = 0 and delete session of item in total content
if(isset($_GET['delete'])){

    $_SESSION['product_' . $_GET['delete']] = 0;

    unset($_SESSION['item_total']);
    unset($_SESSION['item_quantity']);
    redirect("../public/checkout.php");

}

function cart(){

    $total = 0;
    $item_quantity = 0;

    // default value for paypal form
    $item_name = 1;
    $item_number = 1;
    $amount = 1;
    $quantity = 1;

    foreach($_SESSION as $name => $value){

        if($value > 0){

            if(substr($name, 0, 8) == 'product_'){
                
                // we wanna get id, for example, 1 from product_1, so trim "product_" off
                $length = strlen($name);
                $id = substr($name, 8, $length);
                
                $query = query("SELECT * FROM products WHERE product_id = " . escape_string($id) . "");
                confirm($query);

                while($row = fetch_array($query)){

                    // get subtotal = price * number of item
                    $sub = $row['product_price'] * $value;
                    $item_quantity += $value;   // for total content

                    $product_image = display_image($row['product_image']);
                    
                    // show title, price, value=quantity, icons about adding, decreasing and deleting
                    $product = <<<DELIMER
                        <tr>
                            <td>{$row['product_title']}<br>
                                <img width='100' src='../resources/{$product_image}'>

                            </td>
                            <td>&#36;{$row['product_price']}</td>
                            <td>{$value}</td>
                            <td>&#36;{$sub}</td>
                            <td>
                                <a class="btn btn-success" href="../resources/cart.php?add={$row['product_id']}"><span class='glyphicon glyphicon-plus'></span></a>
                                <a class="btn btn-warning" href="../resources/cart.php?remove={$row['product_id']}"><span class='glyphicon glyphicon-minus'></span></a>
                                <a class="btn btn-danger" href="../resources/cart.php?delete={$row['product_id']}"><span class='glyphicon glyphicon-remove'></span></a>
                            </td>
                        </tr>
                        
                        <input type="hidden" name="item_name_{$item_name}" value="{$row['product_title']}">
                        <input type="hidden" name="item_number_{$item_number}" value="{$row['product_id']}">
                        <input type="hidden" name="amount_{$amount}" value="{$row['product_price']}">
                        <input type="hidden" name="quantity_{$quantity}" value="{$value}">

DELIMER;

                    echo $product;

                    $item_name += 1;
                    $item_number += 1;
                    $amount += 1;
                    $quantity += 1;
                    // if we have two items, we can get diff name with each item
                    // for item 1
                    // <input type="hidden" name="item_name_1" value="product 1">
                    // <input type="hidden" name="item_number_1" value="1">
                    // <input type="hidden" name="amount_1" value="24.99">
                    // <input type="hidden" name="quantity_1" value="2">

                    // for item 2
                    // <input type="hidden" name="item_name_2" value="product 2">
                    // <input type="hidden" name="item_number_2" value="2">
                    // <input type="hidden" name="amount_2" value="55.99">
                    // <input type="hidden" name="quantity_2" value="1">

                }

                // calculate total price, display in checkout.php
                $_SESSION['item_total'] = $total += $sub;

                // calculate total quantity, display in checkout.php
                $_SESSION['item_quantity'] = $item_quantity;

            }
        }
    }
}

function show_paypal(){

    // only when there is any product in checkout, show paypal buynow button
    if(isset($_SESSION['item_quantity']) && $_SESSION['item_quantity']>=1){
    
        $paypal_button = <<<DELIMETER

        <input type="image" name="upload"
        src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif"
        alt="PayPal - The safer, easier way to pay online">

DELIMETER;

        return $paypal_button;

    }
}

function process_transaction(){

    global $connection;

    // these get data are from paypal form
    // e.g. http://localhost/ecom/public/thank_you.php?tx=adfe12a&amt=123&cc=USD&st=Completed
    if(isset($_GET['tx'])){

        $amount = $_GET['amt'];
        $currency = $_GET['cc'];
        $transaction = $_GET['tx'];
        $status = $_GET['st'];
        

        $total = 0;
        $item_quantity = 0;

        foreach($_SESSION as $name => $value){

            if($value > 0){

                if(substr($name, 0, 8) == 'product_'){
                    
                    // we wanna get id, for example, 1 from product_1, so trim "product_" off
                    $length = strlen($name);
                    $id = substr($name, 8, $length);
                    

                    $send_order = query("INSERT INTO orders (order_amount, order_transaction, order_status, order_currency) VALUES('{$amount}', '{$transaction}', '{$status}', '{$currency}')");
                    $last_id = last_id();
                    confirm($send_order);


                    $query = query("SELECT * FROM products WHERE product_id = " . escape_string($id) . "");
                    confirm($query);

                    while($row = fetch_array($query)){

                        $product_title = $row['product_title'];
                        $product_price = $row['product_price'];
                        // get subtotal = price * number of item
                        $sub = $row['product_price'] * $value;
                        $item_quantity += $value;   // for total content

                        // insert data in report table
                        $insert_report = query("INSERT INTO reports (order_id, product_id, product_title, product_price, product_quantity) VALUES('{$last_id}', '{$id}', '{$product_title}', '{$product_price}', '{$value}')");
                        
                        confirm($insert_report);

                    }

                    $total += $sub;

                    //echo $item_quantity;

                }
            }
        }

        session_destroy();
        // destroy all sessions to make cart without previous orders
        // prevent inserting data when refreshing thank_you.php again and again

    }else{

        redirect("index.php");

    }
    
}

?>