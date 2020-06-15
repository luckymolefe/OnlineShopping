<?php 
require_once('controller.php');
if(!isset($_SESSION['shopping_cart'])) {
	header("Location: /shop_old/?action=signin ");
}
if(empty($_SESSION['userAuth']['email'])) {
	header("Location: /shop_old/?action=signin ");
}

// setcookie("email", $data, time() + (10 * 356 * 24 * 60 * 60));
/*if(!isset($_COOKIE['email'])) {
  setcookie("email", "", time() - (10 * 356 * 24 * 60 * 60));
}*/

?>
<!DOCTYPE html>
<html>
<head>
	<title>Customer Checkout</title>
	<link rel="stylesheet" type="text/css" href="../boostrap3/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../boostrap3/css/bootstrap-theme.min.css">
	<link rel="stylesheet" type="text/css" href="../boostrap3/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="styles.css">
	<script type="text/javascript" src="../boostrap3/jquery/jquery-1.12.4.min.js"></script>
	<script type="text/javascript" src="../boostrap3/js/bootstrap.min.js"></script>
</head>
<body onload="createOrder()"> <!-- on page load start creating new order Number if Not Exists -->
<div id="layer"></div>
	<div class="container">
		<ol class="breadcrumb">
			<li><a href="/shop_old/"><span class="fa fa-home"></span> Home</a></li>
			<li><a href="cart.php"><span class="fa fa-cart-arrow-down"></span> Update Cart</a></li>
			<li class="active">Checkout</li>
		</ol>
		<h2 class="page-header"><center>Order Summary</center></h2>
		<div id="alert-message"></div>
		<p class="pull-right">
			<a href="javascript:void(0)" class="btn btn-sm btn-default" onclick="openPrinting();">Print Invoice <span class="glyphicon glyphicon-print"></span></a>
		</p>
		<div class="row">
			<div class="col-md-4">
				<h4>Payment Type:</h4>
				<div class="form-group">
					<select class="form-control" id="paymentType" name="paymentType">
						<option value="1">Cash Deposit</option>
						<option value="2">EFT</option>
						<option value="3">Debit Card</option>
						<option value="4">Credit Card</option>
					</select>
				</div>
				<h4>Shipping Address</h4>
				<div class="form-group">
					<?php if(!empty($_SESSION['userAuth']['address'])) { $notify=""; ?>
					<div class="thumbnail">
						<label class="radio-inline">
							<input type="radio" name="shippingAddress" id="curAddress" value="" checked> Current Address
						</label>
						<div class="well curAddress"><?php echo str_replace(",", "<br/>", $_SESSION['userAuth']['address']); ?></div>
						<div>
							Telephone:
							<span id="telNum"><?php (!empty($_SESSION['userAuth']['telephone'])) ? print $_SESSION['userAuth']['telephone'] : print '<i class="text-danger">Please update your telephone number</i>' ?></span>
						</div>
					</div>
				<?php } else { $notify='<i class="text-danger notify-message">&laquo;please click here, to add your shipping address.</i>'; } ?>
					<div><label class="radio-inline"><input type="radio" name="shippingAddress" id="showNewAddr" value=""> Update Address <?php echo $notify; ?></label></div>
				</div>
				<div class="form-group" id="editAddr">
					<label class="form-label">New Telephone number:</label>
					<input type="hidden" id="hiddenEmail" name="email" value="<?php echo $_SESSION['userAuth']['email']; ?>">
					<div><?php if(empty($_SESSION['userAuth']['telephone'])) { echo $msg="<i class='text-danger'>Please update your telephone number</i>"; } ?>

						<?php $first = substr($_SESSION['userAuth']['telephone'], 0,3); $middle = substr($_SESSION['userAuth']['telephone'], 3,3); $last = substr($_SESSION['userAuth']['telephone'], 6,4); 
							  $newtelephone = $first.'-'.$middle.'-'.$last//split string ?>

						<input type="text" name="telephone" id="telephone" class="form-control" value="<?php (!empty($_SESSION['userAuth']['telephone'])) ? print $newtelephone : ''; ?>" placeholder="Telephone number (082-123-4567)"><br>
						<label class="form-label">New Shipping address:</label>
						<?php if(empty($_SESSION['userAuth']['address'])) { echo "<div><i class='text-danger'>Please update your address</i></div>"; } ?>
						<textarea class="form-control" name="newAddress" id="newAddr" rows="3" placeholder="Enter New Shipping Address"></textarea>
					</div>
					<div><button type="button" class="btn btn-block btn-primary" onclick="updateShippingAddress();"><span class="fa fa-refresh"></span> Update Address</button></div>
				</div>
			</div>
			<div class="col-md-8">
				<table class="table table-responsive ">
					<thead>
						<tr>
							<td><center>Invoice ID # <span id="orderNumber"><?php echo $_SESSION['userAuth']['custOrder']; ?><span></center></td>
							<td colspan="2"><center>Receipient: <?php echo $_SESSION['userAuth']['firstname']." ".$_SESSION['userAuth']['lastname']; ?></center></td>
							<td colspan="2">Date: <?php echo date('d/m/Y'); ?> &nbsp; Time: <?php echo date('H:ia'); ?></td>
						</tr>
						<th>Product Name</th><th>Qty</th><th>Price</th><th class="text-right">Total Price</th>
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
								<td class="info text-right">R <?php echo number_format($values['item_quantity'] * $values['item_price'], 2); ?></td>

							</tr>
						<?php
							$total = $total + ($values['item_quantity'] * $values['item_price']);
						  	endforeach;
						?>
							<tr>
								<td colspan="3" align="right"><strong class="lead">Total Payable</strong></td>
								<td  class="success text-right lead">R <?php echo number_format($total, 2);?></td>
							</tr>
							<tr>
								<td colspan="4">
									<button type="button" id="CancelOrder" onclick="cancelOrder();" class="btn btn-primary">Cancel Order</button>
									<button type="button" id="checkout" onclick="doPayment()" class="btn btn-success" <?php (empty($_SESSION['userAuth']['address'])) ? print 'disabled' : print 'enabled'; ?> >Checkout</button>
								</td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<!-- <div class="row">
			<div class="col-md-12">
				<img src="payoptions/maestrocard.jpg">
				<img src="payoptions/mastercard.jpg">
			</div>
		</div> -->
	</div>
	<!-- <footer class="container-fluid bg-4">
		<div class="row">
			<div class="col-md-12">
				<center>
				<img src="payoptions/maestrocard.jpg" style="width: 150px">&nbsp;
				<img src="payoptions/mastercard.jpg" style="width: 120px">
				</center>
			</div>
		</div> 
	</footer> -->
	<script type="text/javascript" src="ajaxRequests.js"></script>
</body>
</html>