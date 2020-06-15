	<?php 
		require_once('model/orders.php');
		$orders = $initOrder->getOrders($row['email']);
		if($orders != false) {
	?>
		<div class="orders-popup"> <!-- show this popup if orders available -->
	<?php
			foreach($orders as $order) : 
				echo "Order Number: #".$order['orderNumber']."<br>";
			endforeach;
	?>
			<span class="closeMsg">&times;</span>
		</div>
	<?php
		}/* else {
			echo "<center><span class='fa fa-cubes'></span> No available orders.</center>";
		}*/
	?>

<div id="profile-edit" class="col-md-6 col-md-offset-3">
	<div><center><span class="fa fa-user-circle-o fa-5x"></span></center></div>
	<form action="" method="GET" enctype="application/forms-url-encoded">
		<div class="form-group">
			<label class="form-label">Firstame:</label>
			<input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $row['firstname']; ?>" placeholder="Firstname" autofocus>
		</div>
		<div class="form-group">
			<label class="form-label">Lastame:</label>
			<input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $row['lastname']; ?>" placeholder="Lastname">
		</div>
		<div class="form-group">
			<label class="form-label">Telephone:</label>
			<?php (empty($row['telephone'])) ? print '<div class="text-danger">Please update your phone numbers!</div>' : print ''; ?>
			<?php if(!empty($row['telephone'])) { ?>
			<?php $first = substr($row['telephone'], 0,3); $middle = substr($row['telephone'], 3,3); $last = substr($row['telephone'], 6,4); 
			$newtelephone = $first.'-'.$middle.'-'.$last//split string ?>
			<?php } else { $newtelephone = ""; } ?>
			<input type="text" class="form-control" name="telephone" id="telephone" value="<?php echo $newtelephone; ?>" placeholder="Telephone (082-123-4567)">
		</div>
		<div class="form-group">
			<label class="form-label">Email address:</label>
			<input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>" placeholder="Email">
		</div>
		<div class="form-group">
			<label class="form-label">Password:</label>
			<input type="password" class="form-control" id="password" name="password" value="<?php echo $row['password']; ?>" placeholder="Password">
		</div>
		<div class="form-group">
			<label class="form-label">Physical Address:</label>
			<?php (empty($row['customer_address'])) ? print '<div class="text-danger">Please update your physical address!</div>' : print ''; ?>
			<textarea class="form-control" name="address" id="address" placeholder="Home address"><?php echo $row['customer_address']; ?></textarea>
		</div>
		<div class="form-group">
			<button type="button" class="btn btn-block btn-lg btn-primary" onclick="UpdateProfile();"><span class="fa fa-save"></span> Save</button>
			<button type="button" class="btn btn-block btn-lg btn-default" onclick="continueShopping();" <?php (empty($row['customer_address'])) ? print 'disabled' : print 'enabled'; ?> ><span class="glyphicon glyphicon-floppy-remove"></span> Cancel</button>
		</div>
	</form>
</div>