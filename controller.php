<?php
//controllerHandler for shopping cart
session_start();
require_once('model/products.php');
require_once('model/customers.php');
require_once('model/orders.php');
//adding item to cart
if(isset($_POST['add_to_cart']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest') {
	sleep(1);
	$item = array();
	if(isset($_SESSION['shopping_cart'])) { //if set append to existing items
		$item_array_id = array_column($_SESSION['shopping_cart'], "item_id");

		if (!in_array($_REQUEST['pid'], $item_array_id)) {
			$count = count($_SESSION['shopping_cart']); //count items in a shopping cart
			$item_array = array(
			'item_id'		=> $_REQUEST['pid'],
			'item_name'		=> $_POST['hidden_name'],
			'item_price'	=> $_POST['hidden_price'],
			'item_quantity'	=> $_POST['quantity']
			);
			$_SESSION['shopping_cart'][$count] = $item_array;
			$item['appendItem'] = $_SESSION['shopping_cart'][$count];
			// $item['message'] = "added";
			echo json_encode($item);
		}
		else {
			$item['message'] = "exists"; //"<span class='fa fa-info-circle'></span> Item already added!.";
			echo json_encode($item);
			// exit();
			// echo "<script>alert('Item already added!.')</script>";
			// echo "<script>window.location='index.php'</script>";
		}
	}
	else { //else create a new item to cart
		$item_array = array(
			'item_id'		=> $_REQUEST['pid'],
			'item_name'		=> $_POST['hidden_name'],
			'item_price'	=> $_POST['hidden_price'],
			'item_quantity'	=> $_POST['quantity']
		);
		$_SESSION['shopping_cart'][0] = $item_array;
		$item['newItem'] = $_SESSION['shopping_cart'][0];
		echo json_encode($item);
	}
	exit();
}
//remove item from cart
if(isset($_GET['action'])) {
	// sleep(1);
	if($_GET['action'] == "delete") {
		$message = array();
		foreach($_SESSION['shopping_cart'] as $k => $v) {
			if($v['item_id'] == $_GET['pid']) {
				unset($_SESSION['shopping_cart'][$k]);
				$message['message'] = "<p><span class='fa fa-check'></span> Item removed.</p>";
				echo json_encode($message);
				// echo "<script>alert('Item removed.')</script>";
				// echo "<script>window.location = 'index.php'</script>";
			}
		}
	}
	exit();
}

if(isset($_GET['cart']) && $_GET['cart'] == true && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest') {
	if(!empty($_SESSION['shopping_cart'])) {
	  	$total = 0;
	  	$itemsCount = array();
	  	foreach ($_SESSION['shopping_cart'] as $key => $values) :
	  		$itemsCount[] = $values['item_quantity'];
	  		$total = $total + ($values['item_quantity'] * $values['item_price']);
	  	endforeach;
	  	$items['itemNum'] = count($itemsCount);
	  	$items['itemCost'] = number_format($total, 2);
	  	echo json_encode($items);
  	}
	else {
		// echo '<tr><td colspan="5"><center><div class="alert alert-info"><span class="fa fa-info-circle"></span> Your shopping cart is empty</div></center></td></tr>';
		$items['message'] = "<span class='fa fa-info-circle'></span> Your shopping cart is empty";
		echo json_encode($items);
	}
	// require_once('cart.php');
	exit();
} /*end IF*/


//search query
// $_GET['search'] = "telev";
if (isset($_GET['search']) && ($_GET['search']) !== '') {
	// require_once('model.php');
	$query = htmlentities(strip_tags(trim($_GET['search'])));
	$string = stripslashes(strip_tags($query));
	$start = microtime(true); //start for execution time
	$results = $initObj->doSearch($string);
	$end = microtime(true); //end for execution time
	$mst = ($end - $start) * 1000; //calculate seconds out of miliseconds

	$length = (!empty($results)) ? $length = count($results) : $length = 0; //counting an array to get the number of results returned/found
	echo "<p><span class='text-muted'>Searched for:</span> <i class='text-danger'>".$string."</i></p>";
	echo "<p class='text-muted'>Found ".$length." products (".number_format($mst, 2).") seconds</p>";
	if($length > 0) {
		foreach($results as $row) :
		?>
		<div class="row">
			<div class="col-sm-3">
				<a href="#"><div class="thumbnail"><img src="images/<?php echo $row['path']; ?>" class="img-responsive"/></div></a>
			</div>
			<div class="col-sm-4">
				<h3 class="text-info"><?php echo $row['product_name']; ?></h3>
				<h4 class="text-success"><?php echo "R".number_format($row['price'], 2); ?></h4>
				<div class="form-group">
					<input type="number" class="form-control" id="quantity" min="1" max="10"  value="1" name="quantity">
				</div>
				<input type="hidden" name="pid" id="pid" value="<?php echo $row['product_id']; ?>">
				<input type="hidden" name="hidden_name" id="hidden_name" value="<?php echo $row['product_name']; ?>">
				<input type="hidden" name="hidden_price" id="hidden_price" value="<?php echo $row['price']; ?>">
				<div class="form-group">
					<button type="button" name="add_to_cart" class="btn btn-md btn-primary addToCart">Add to cart <span class="fa fa-cart-plus fa-1x"></span></button>
				</div>
			</div>
			<div class="col-sm-6">
				&nbsp;
			</div>
		</div>
		<?php
		endforeach;
		?>
			<div class="col-md-4 col-md-offset-4">
				<div id="processCart" class="alert alert-info"></div>
			</div>
		<?php
	}
	else {
		?>
		<div class="col-sm-12">
		<center>
			<div class="alert alert-danger" role="alert">
			    <strong><span class="fa fa-info-circle"></span> Sorry, No Match Found</strong>
			</div>
		</center>
		</div>
		<?php
	}
	
}

if (isset($_GET['auth']) && $_GET['auth'] == true) {
	 //For testing purpose
	if(!empty($_SESSION['userAuth']['email'])) {
	?>
		<div id="user-profile">
			<p>
				<center>
					<span class="fa fa-user-circle-o fa-4x"></span><br>
					Logged in as: <span id='username'><?php echo $_SESSION['userAuth']['email']; ?></span>
				</center>
			</p>
			<p><button type="button" id="settings" class="btn btn-block btn-sm btn-info" onclick="viewProfile();">View profile <span class="fa fa-user"></span></button></p>
			<p><button type="button" id="logout" class="btn btn-block btn-sm btn-primary" onclick="logout();">Logout <span class="fa fa-sign-out"></span></button></p>
		</div>
	<?php
	}
	else {
	?>
		<form action='' method='POST' enctype='application/forms-url-encoded'>
			<div id="login-form">
				<div class="form-group"><input type="email" class="form-control" id="loginEmail" name="email" placeholder="Email"></div>
				<div class="form-group"><input type="password" class="form-control" id="loginPassword" name="password" placeholder="Password"></div>
				<div class="form-group">
					<button type="button" class="btn btn-block btn-sm btn-success" onclick="return loginUser(this);"><span class="fa fa-lock"></span> Login</button>
				</div>
				<div class="form-group">
					<button type="button" class="btn btn-block btn-sm btn-primary" id="register"><span class="fa fa-user-plus"></span> Register</button>
				</div>
			</div>
			<div id="register-form">
				<div class="form-group"><input type="text" class="form-control" id="registerFirstname" name="firstname" placeholder="Firstname"></div>
				<div class="form-group"><input type="text" class="form-control" id="registerLastname" name="lastname" placeholder="Lastname"></div>
				<div class="form-group"><input type="email" class="form-control" id="registerEmail" name="email" placeholder="Email"></div>
				<div class="form-group"><input type="password" class="form-control" id="registerPassword" name="password" placeholder="Password"></div>
				<div class="form-group">
					<button type="button" class="btn btn-block btn-sm btn-primary" onclick="registerUser(this);"><span class="fa fa-user-plus"></span> Register</button>
				</div>
				<div class="form-group">
					<center>Already registered?<br><a href="javascript:void(0)" id="login">Login here</a></center>
				</div>
			</div>
		</form>
	<?php
	}
}
//User login
if(isset($_POST['action']) && $_POST['action'] == "login") {
	sleep(1);
	// $email = $_POST['email'];
	// $password = $_POST['password'];
	$email = htmlentities(stripslashes(strip_tags(trim($_POST['email']))));
	$password = htmlentities(stripslashes(strip_tags(trim($_POST['password']))));
	
	$message = "";
	if(!empty($email) && !empty($password)) {
		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			if(method_exists($initCustomer, 'userLogin')) {
				$row = $initCustomer->userLogin($email, $password);
				if(count($row) > 0) {
					$_SESSION['userAuth']['firstname'] = $row['firstname'];
					$_SESSION['userAuth']['lastname'] = $row['lastname'];
					$_SESSION['userAuth']['telephone'] = $row['telephone'];
					$_SESSION['userAuth']['email'] = $row['email'];
					$_SESSION['userAuth']['address'] = $row['customer_address'];
					$message = "true";
				} 
				else {
					$message = "Incorrect login credentials.";
				}
			}
		}
		else {
			$message = "Invalid email address.";
		}
	}
	else {
		$message = "Please provide login details.";
	}
	echo $message;
	exit();
}
//User logout
if(isset($_POST['action']) && $_POST['action'] == "logout") {
	sleep(1);
	unset($_SESSION['userAuth']['custOrder']); //reset the session for order number
	foreach($_SESSION['userAuth'] as $k => $v) {
		unset($_SESSION['userAuth'][$k]);
	}
	if(empty($_SESSION['userAuth']['email'])) { //if session value empty
		echo "true";
	} else {
		echo "false";
	}
	exit();
}
//User register
if(isset($_POST['action']) && $_POST['action'] == "register") {
	sleep(1);
	$firstname = htmlentities(stripslashes(strip_tags(trim($_POST['firstname']))));
	$lastname = htmlentities(stripslashes(strip_tags(trim($_POST['lastname']))));
	$emailAdd = htmlentities(stripslashes(strip_tags(trim($_POST['email']))));
	$password = htmlentities(stripslashes(strip_tags(trim($_POST['password']))));
	$message = "";
	if(!empty($firstname) && !empty($lastname) && !empty($emailAdd) && !empty($password)) {
		if(filter_var($emailAdd, FILTER_VALIDATE_EMAIL)) {
			if($initCustomer->isUserExist($emailAdd)) {
				$message = "Sorry, email already registered!";
			} else {
				if(method_exists($initCustomer, 'userRegister')) {
					$results= $initCustomer->userRegister($firstname, $lastname, $emailAdd, $password); //call method to register record into DB
					if($results) {
						$_SESSION['userAuth']['email'] = $emailAdd;
						$message = "true";
					}
				} else {
					$message = "Internal Server error. Failed to register details";
				}
			}
		}
		else {
			$message = "Invalid email, please provide correct email.";
		}
	}
	else {
		$message = "Please provide all your details";
	}
	echo $message;
	exit();
}

if(isset($_GET['profile']) && $_GET['profile'] == "true") {
	$email = $_GET['username'];
	$row = $initCustomer->getProfile(null, $email);
	include_once('profile.php');
	exit();
}

if(isset($_POST['action']) && $_POST['action'] == "update") {
	sleep(2);
	$firstname = htmlentities(stripslashes(strip_tags(trim($_POST['firstname']))));
	$lastname = htmlentities(stripslashes(strip_tags(trim($_POST['lastname']))));
	$telephone = htmlentities(stripslashes(strip_tags(trim($_POST['telephone']))));
	$email = htmlentities(stripslashes(strip_tags(trim($_POST['email']))));
	$password = htmlentities(stripslashes(strip_tags(trim($_POST['password']))));
	$address = htmlentities(stripslashes(strip_tags(trim($_POST['address']))));

	$message = "";
	if(!empty($firstname) && !empty($lastname) && !empty($telephone) && !empty($email) && !empty($password) && !empty($address)) {
		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			if(preg_match('/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/', $telephone)) {
				$telephone = str_replace('-', '', $telephone); //remove dashes from phone numbers
				if(method_exists($initCustomer, 'updateUser')) {
					$results = $initCustomer->updateUser($firstname, $lastname, $telephone, $email, $password, $address);
					if($results) {
						$message = "true";
					}
					else {
						$message = "Faile to update your details, please try again.";
					}
				}
				else {
					$message = "Internal Server error. Failed to register details";
				}
			}
			else {
				$message = "Invalid phone numbers";
			}
		}
		else {
			$message = "Invalid email, please provide correct email.";
		}
	}
	else {
		$message = "Please provide all your details.";
	}
	echo $message;
	exit();
}
//update home address
if(isset($_POST['action']) && $_POST['action'] == "addNewAddress") {
	sleep(2);
	$newAddress = htmlentities(stripslashes(strip_tags(trim($_POST['newAddress']))));
	$email = htmlentities(strip_tags(trim($_POST['email'])));
	$telephone = htmlentities(strip_tags(trim($_POST['telephone'])));
	$data = array();
	if(preg_match('/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/', $telephone)) {
		$telephone = str_replace('-', '', $telephone);
		if(method_exists($initCustomer, 'updateAddress')) {
			if($initCustomer->updateAddress($newAddress, $email, $telephone)) { #$initCustomer->updateAddress($newAddress, $email)
				unset($_SESSION['userAuth']['address']); //erase data from session first
				$_SESSION['userAuth']['address'] = $newAddress; //then set new data to the session address
				$data[] = "success"; //position 0
				$data[] = $newAddress; //position 1
				$data[] = $telephone; //position 2
				echo json_encode($data);
			}
			else {
				$data[] = "Failed to update your address."; //position 0
				echo json_encode($data);
			}
		}
	}
	else {
		$data[] = "Invalid phone number!"; //position 0
		echo json_encode($data);
	}
	exit();
}

if(isset($_POST['processOrder']) && $_POST['processOrder'] == true) {
	$customer_email = trim($_POST['email']);
	
	$response = ""; //set array to empty
	
	if(isset($_SESSION['userAuth']['custOrder'])) {
		if($initOrder->checkOrder($customer_email, $_SESSION['userAuth']['custOrder']) == "exists") {
			$response = $_SESSION['userAuth']['custOrder']; //use the existing order Number
		} else {
			$response = "NULL";
		}
	}
	else {
		//first check if order exists, else create new order
		if(method_exists($initOrder, 'setProductOrder')) {
			require_once('model/keygen.php');
			$keygen = new Generate();
			if(is_a($keygen, 'Generate')) {
				if(method_exists($keygen, 'genRndString')) {
					$product_id = $keygen->genProductOrder();
				}
			}
			if($response = $initOrder->setProductOrder($customer_email, $product_id)) { //$product_id is the id of the product from Db record
				$_SESSION['userAuth']['custOrder'] = $response; //set order number in session
				// setcookie("custOrder", $response['orderNum'], time() + (10 * 356 * 24 * 60 * 60));  //create a cookie to track order
			} else {
				$response = "NULL";
			}
		}
	}
	echo $response;
	exit();
}

if(isset($_GET['getOrders'])) { //retieve list orders available for customer
	$customer_email = trim($_GET['email']);
	if(method_exists($initOrder, 'getOrder')) {
		echo $initOrder->getOrder($customer_email);
	}
	exit();
}

if(isset($_POST['cancelOrder']) && $_POST['cancelOrder'] == true) {
	sleep(1);
	if(method_exists($initOrder, 'cancelOrder')) {
		if($initOrder->cancelOrder($_SESSION['userAuth']['email'], $_SESSION['userAuth']['custOrder'])) { //pass email and order Number
			unset($_SESSION['shopping_cart']); //destroy every item in cart
			unset($_SESSION['userAuth']['custOrder']);
			$data['message'] = "success";
			echo json_encode($data);
		}
	}
	exit();
}

if(isset($_GET['finalize']) && $_GET['finalize'] == 'true') { //final step of payment
	sleep(1);
	unset($_SESSION['userAuth']['custOrder']);
	unset($_SESSION['shopping_cart']); //destroy every item in cart
	$response[] = "success";
	$response[] = "<span class='fa fa-check'></span> Your order was successfully processed!";
	echo json_encode($response);
	exit();
}

?>

<script type='text/javascript' src='ajaxRequests.js'></script>



