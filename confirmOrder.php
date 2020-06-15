<?php 
require_once('controller.php');
if(!isset($_SESSION['shopping_cart'])) {
	header("Location: /shop_old/index.php?action=signin ");
}
if(empty($_SESSION['userAuth'])) {
	header("Location: /shop_old/index.php?action=signin ");
}

//get the value of payment option selected
if(isset($_GET['payment'])) {
	$options = array('1'=>'Cash Deposit', '2'=>'EFT', '3'=>'Debit Card', '4'=>'Credit Card');
	foreach ($options as $key => $option) {
		if($key == $_GET['payment']) {
			$payOption = $option;
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Invoice#<?php echo $_SESSION['userAuth']['custOrder'] ?></title>
	<link rel="stylesheet" type="text/css" href="../boostrap3/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../boostrap3/css/bootstrap-theme.min.css">
	<link rel="stylesheet" type="text/css" href="../boostrap3/font-awesome/css/font-awesome.min.css">
	<script type="text/javascript" src="../boostrap3/jquery/jquery-1.12.4.min.js"></script>
	<script type="text/javascript" src="../boostrap3/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		function doPrintInvoice() {
			return window.print();
		}
		setTimeout(function() {
			doPrintInvoice();
			window.close();
		}, 1000);
	</script>
</head>
<body>
	<h2 class="page-header"><center>Order Summary</center></h2>
	<div class="row">
		<div class="col-md-2">
			<ul class="list-unstyled text-left">
				<li><strong>Bill To:</strong></li>
				<ul style="list-style:none">
					<li><?php echo $_SESSION['userAuth']['firstname']." ".$_SESSION['userAuth']['lastname']; ?></li>
					<li><?php echo str_replace(",", "<br>", $_SESSION['userAuth']['address']); ?></li>
				</ul>
			</ul>
		</div>
		<div class="col-md-3">
			<ul class="list-unstyled text-left">
				<li>&nbsp;</li>
				<ul style="list-style:none">
					<li>Phone: <?php echo (!empty($_SESSION['userAuth']['telephone'])) ? print $_SESSION['userAuth']['telephone'] : print ''; ?></li>
					<li>Email: <?php echo $_SESSION['userAuth']['email']; ?></li>
				</ul>
			</ul>
		</div>
		<div class="col-md-2">
			<ul class="list-unstyled text-left">
				<li><strong>Payment:</strong></li>
				<ul style="list-style:none">
					<li><?php echo $payOption; ?></li>
				</ul>
			</ul>
		</div>
	</div>

	<table class="table table-responsive ">
		<thead>
			<tr>
				<td><center>Invoice ID # <?php echo $_SESSION['userAuth']['custOrder']; ?></center></td>
				<td colspan="2"><center>Receipient: <?php echo $_SESSION['userAuth']['firstname']." ".$_SESSION['userAuth']['lastname']; ?></center></td>
				<td colspan="2">Date: <?php echo date('d/m/Y'); ?> &nbsp; Time: <?php echo date('H:ia'); ?></td>
			</tr>
			<th>Description</th><th>Qty</th><th>Price</th><th>Total Price</th>
		</thead>
		<tbody>
			<?php
			require_once('controller.php');
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
					<td align="left" class="info">R <?php echo number_format($values['item_quantity'] * $values['item_price'], 2); ?></td>

				</tr>
			<?php
				$total = $total + ($values['item_quantity'] * $values['item_price']);
			  	endforeach;
			?>
				<tr>
					<td colspan="3" align="right"><strong class="lead">Total Payable</strong></td>
					<td align="left" class="success lead">R <?php echo number_format($total, 2);?></td>
				</tr>
			<?php
			}
			?>
		</tbody>
	</table>
	<div class="lead" style="margin-top: 100px;"><center>Thank You for shopping with us.</center></div>
</body>
</html>