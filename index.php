<?php
/*session_start();
session_destroy();*/
?>
<!DOCTYPE html>
<html>
<head>
	<title>Online Shopping Cart</title>
	<link rel="stylesheet" type="text/css" href="../boostrap3/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../boostrap3/css/bootstrap-theme.min.css">
	<link rel="stylesheet" type="text/css" href="../boostrap3/font-awesome/css/font-awesome.min.css">
	<script type="text/javascript" src="../boostrap3/jquery/jquery-1.12.4.min.js"></script>
	<script type="text/javascript" src="../boostrap3/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

	<nav class="navbar navbar-light navbar-fixed-top bg-faded" style='background-color: #e3f2fd;'>
		<a class="navbar-brand" href="/shop_old/">Home</a>
		<ul class="nav navbar-nav">
			<!-- <li class="nav-item active">
				<a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
			</li> -->
			<li class="nav-item">
				<a class="nav-link" href="#">Orders <span class="fa fa-calendar-check-o"></span></a>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link" href="#" class="dropdown-toggle" data-toggle="dropdown">Products <span class="fa fa-cube"></span></a>
				<ul class="dropdown-menu">
				  <li><a href="#"><span class="fa fa-tv"></span> Electronics</a></li>
				  <li><a href="#"><span class="fa fa-desktop"></span> Computers</a></li>
				  <li><a href="#"><span class="fa fa-mobile-phone"></span> Mobile Phones</a></li>
				  <li class="divider"></li>
				  <li><a href="#"><span class="fa fa-android"></span> Apple Products</a></li>
				  <li class="divider"></li>
				  <li><a href="#"><span class="fa fa-apple"></span> Android Products</a></li>
				</ul>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="#">Settings <span class="fa fa-cog"></span></a>
			</li>
		</ul>
		<a class="nav-link pull-right" href="javascript:void(0)" id="userAuth" data-toggle="popover" data-trigger="click" data-container="body" data-placement="bottom" 
		data-content="">
		<span class="fa fa-user-circle fa-2x"></span></a>

		<form class="form-inline navbar-form pull-right">
			<span class="input-group">
				<input class="form-control" type="text" id="search" name="search" placeholder="Search...">
				<span  class="input-group-btn">
					<button class="btn btn-default submit" type="submit"><span class="fa fa-search"></span></button>
				</span>
			</span>
		</form>
		<a href="javascript:void(0)" class="nav-link pull-right fa-2x" id="controlCart" data-toggle="popover" data-trigger="toggle" data-container="body" data-placement="bottom" data-content=""> <!-- data-content value will be written dynamically -->
		<span class="fa fa-shopping-cart"></span><span id="shopCart" class="badge menu-badge"></span></a>
	</nav>

	<div class="container">
		<h3 class="page-header"><center>Online Shopping</center></h3>
		<div class="row">
			<div id="results">

			<?php
				$action = isset($_GET['action']) ? $_GET['action'] : "";
	            if($action == 'signin') {
	              echo "<div  id='target'><div class='alert alert-info alert-dismissable'>
	                     <button class='close' data-dismiss='alert' aria-hidden='true'>&times</button>
	                       <span class='fa fa-info-circle'></span> Please login or register first!.
	                    </div></div>";
	            }
	            if($action == 'processed') {
	              echo "<div  id='target'><div class='alert alert-success alert-dismissable'>
	                     <button class='close' data-dismiss='alert' aria-hidden='true'>&times</button>
	                       <span class='fa fa-check'></span> Your order has been finalized. Thank You for shopping with us. <span class='fa fa-smile-o'></span>
	                    </div></div>";
	            }
				require_once('model/products.php'); //include model script
				$results = $initObj->getProducts();
				if($results > 0) {
					foreach ($results as $row) :
			?>
				<div class="col-sm-3 text-center">
					<form action="index.php?action=add&pid=<?php echo $row['product_id']; ?>" method="POST" enctype="application/forms-url-encoded">
						<div class="well">
							<center><div class="thumbnail"><img src="images/<?php echo $row['path']; ?>" class="img-responsive"/></div></center>
							<h4 class="text-info"><?php echo $row['product_name']; ?></h4>
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
					</form>
				</div>
			<?php
					endforeach;
				}
			?>
			<span class="glyphicon glyphicon-chevron-left move-left"></span>
			<span class="glyphicon glyphicon-chevron-right move-right"></span>

				<div class="col-md-4 col-md-offset-4">
					<div id="processCart" class="alert alert-info"></div>
				</div>
			</div> <!-- results END -->
		</div>
		<hr>
		<center>Webstore &copy; <?php echo date('Y');?></center>
	</div><!-- container END -->
</body>
<script type="text/javascript" src="ajaxRequests.js"></script>

</html>