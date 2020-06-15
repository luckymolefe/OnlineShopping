<?php
// require_once('controller.php'); //include controller script
session_start();

?>
<!DOCTYPE html>
<html>
<head>
	<title>Shopping Cart</title>
	<link rel="stylesheet" type="text/css" href="../boostrap3/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../boostrap3/css/bootstrap-theme.min.css">
	<link rel="stylesheet" type="text/css" href="../boostrap3/font-awesome/css/font-awesome.min.css">
	<script type="text/javascript" src="../boostrap3/jquery/jquery-1.12.4.min.js"></script>
	<script type="text/javascript" src="../boostrap3/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="styles.css">

</head>
<body>
<div id="layer"></div>

	<div class="container">
		<?php
		//show if updating cart items on existing Order Number
			if(isset($_SESSION['userAuth']['custOrder'])) {
			  echo "<div class='orders-popup'>Your available order: #".$_SESSION['userAuth']['custOrder']."<span class='closeMsg'>&times;</span></div>";
			} else {
				echo "<div class='orders-popup text-center'>No orders available.<span class='closeMsg'>&times;</span></div>";
			}
		?>

		<h1 class="page-header"><center>Shopping Cart <span class="fa fa-cart-arrow-down"></span> items </center></h1>
		<div class="row">
			<div class="col-md-12">
			<!-- <p><a href="javascript:void(0)" class="btn btn-sm btn-default" onclick="openPrinting();">Print Invoice <span class="glyphicon glyphicon-print"></span></a></p> -->
				<table class="table table-responsive table-bordered table-hover">
					<thead>
						<th>Item Name</th><th>Quantity</th><th>Price</th><th>Total Price</th><th></th>
					</thead>
					<tbody>
						<?php

						if(!empty($_SESSION['shopping_cart'])) {
						  	$total = 0;
						  	$itemsCount = array(); //set an empty array
						  	foreach ($_SESSION['shopping_cart'] as $key => $values) :
						  		$itemsCount[] = $values['item_quantity']; //count items in cart
						?>	
							<tr>
								<td><?php echo $values['item_name']; ?></td>
								<td><?php echo $values['item_quantity']; ?></td>
								<td>R <?php echo $values['item_price']; ?></td>
								<td align="right" class="info">R <?php echo number_format($values['item_quantity'] * $values['item_price'], 2); ?></td>
								<td>
									<a href="javascript:void(0)" data-url="action=delete&pid=<?php echo $values['item_id']; ?>" class="btn btn-md btn-danger removeItem" onclick="removeItem(this.getAttribute('data-url'), this.parentNode.children[1].innerHTML='<span class=\'fa fa-spinner fa-pulse fa-blue\'></span>'); ">
									<span class="fa fa-trash fa-1x"></span></a>
									<span id="process" class="process"></span>
								</td>
							</tr>
						<?php
							$total = $total + ($values['item_quantity'] * $values['item_price']);
						  	endforeach;
						?>
							<tr>
								<td colspan="3" align="right"><strong class="lead">SubTotal</strong></td>
								<td align="right" class="success">R <?php echo number_format($total, 2);?></td>
							</tr>
							<tr>
								<td colspan="3">
									<span class="fa fa-shopping-cart cart-item"></span><span class="badge item-badge"><?php echo count($itemsCount); ?></span> <!-- display cart items count -->
									<button type="button" class="btn btn-lg btn-info" onclick="continueShopping()">
										Continue Shopping <span class="fa fa-chevron-right"></span>
									</button>
									<!-- &nbsp; -->
									<!-- <span class="fa fa-shopping-cart"></span><span class="badge"><?php echo count($itemsCount); ?></span> -->
								</td>
								<td colspan="2">
									<button type="button" class="btn btn-lg btn-success pull-left" onclick="doCheckout();">Proceed to Checkout</button>
								</td>
							</tr>
						<?php
						  }
						  else {
						  	echo '<tr><td colspan="5"><center><div class="alert alert-info"><span class="fa fa-info-circle"></span> Your shopping cart is empty</div></center></td></tr>';
						  	header("Location: /shop_old/");
						  }

						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<script type="text/javascript" src="ajaxRequests.js"></script>
</body>
</html>