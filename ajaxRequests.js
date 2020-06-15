
$(document).ready(function() {
	
	$("#layer").hide();
	$('#processCart').hide();
	$('#editAddr').hide();
	//adding item to cart
	$('.addToCart').click(function() {
		var requestId = $(this).parent().parent().find('#pid').val().trim();
		var hidden_name = $(this).parent().parent().find('#hidden_name').val().trim();
		var hidden_price = $(this).parent().parent().find('#hidden_price').val().trim();
		var quantity = $(this).parent().parent().find('#quantity').val().trim();
		/*var confirmAction = confirm('Add item to cart?');
		if(confirmAction == false) {
			return false;
		}*/
		//animate object to animate to Cart
		$(this).parent().parent()
			.find("img")
			.clone()
			.addClass("zoom")
			.appendTo("nav"); //append image to navigation element
		setTimeout(function() {
			$(".zoom").remove();
		}, 1500);

		$('#processCart').removeClass('alert-danger').removeClass('alert-success').addClass('alert-info');

		var dataString = "add_to_cart=true&action=add&pid="+requestId+"&hidden_name="+hidden_name+"&hidden_price="+hidden_price+"&quantity="+quantity; //url string data parameters
		$.ajax({
        	type: "POST",
        	url: "controller.php",
        	data: dataString,
        	cache: false,
	         beforeSend: function() {
	         	$('#processCart').html('<center><span class="fa fa-spinner fa-pulse"></span> Processing...</center>').show();
	         },
	         success: function(responseText) {
	         	var jason = JSON.parse(responseText);
	         	if(jason['message'] == "exists") {
	         		$('#processCart').removeClass('alert-info').addClass('alert-danger').html("<center><span class='fa fa-info-circle'></span> Item already added!.</center>");
	         	} else {
		         	$('#processCart').removeClass('alert-info').addClass('alert-success').html('<center><span class="fa fa-check"></span> Item added to cart.</center>');
		         	$('#shopCart').html("");
		         	reloadItems();
		        }
	         },
	         error: function() {
	         	$("#layer").show();
	         	$('#processCart').hide();
	         	$('#layer').html("<center><span class='fa fa-warning'></span> Error 404: URL not found on server.</center>").delay(5000).fadeOut(500);
	         }
    	});
    	// $('#processCart').delay(5000).fadeOut(1500); //fadeout the message
	});

	//removing item from shopping cart
	/*$(".removeItem").each(function(e) {
		$(".removeItem").click(function(e) {
			e.preventDefault();
			
			var confirmAction = confirm('Remove this item?.');
			if(confirmAction == false) {
				return false;
			}
			$(this).parent().parent().children('td').addClass("danger");
			$(this).parent().children('span').html('<span class="fa fa-spinner fa-pulse fa-blue"></span>').show();
			var urlString = $(this).attr('data-url'); //get and pass url data  "action=test";
			$.ajax({
				type: "GET",
				url: "controller.php",
				data: urlString,
				cache: false,
				beforeSend: function() {
					// $(this).parent().children('span').html('<span class="fa fa-spinner fa-pulse fa-blue"></span>').show();
					// $(this).parent().parent().children('td').addClass("danger");
				},
				success: function(responseText) {
					var jason = JSON.parse(responseText);
					$("#layer").show();
					$("#layer").html('<center>'+jason['message']+'</center>').delay(1500).fadeOut(500);
					$('#shopCart').html("");
					// $("#shopCart").load("controller");
				},
				error: function() {
					$("#layer").show();
					$("#layer").html("<center><span class='fa fa-warning'></span> Error 404: URL not found on server, plase try again!.</center>").delay(5000).fadeOut(500);
				}
			});
		});
		return false;
	});*/

	//load the cart once, after page loads first time
	var loadCart = setInterval(function() {
		$.get("controller.php","cart=true", function(responseText) {
			var jason = JSON.parse(responseText); //receive response set in JSON Format

			if(jason['message']) {
				$('#shopCart').html("0"); //and show 0 zero for no items in cart
				$('#controlCart').attr('data-content', jason['message']); //show message if cart is empty
			} 
			else {
				$("#shopCart").html(jason['itemNum']); //then select single data from JSON array, display it
				// $("#itemTotalCost").html("R "+jason['itemCost']); //show total amount of items
				$('#controlCart').attr('data-content', "<div><span class=\'label label-success\'>Total R "+jason['itemCost']+"</span></div><br/><div><button type='button' class='btn btn-block btn-sm btn-primary' onclick='continueCheckOut();'>view cart <span class='fa fa-cart-arrow-down'></span></button></div>");
			}
		});
	}, 1000);
	setTimeout(function() {
		clearInterval(loadCart); //reset the timer after loading the cart
	}, 1000);

	$("[data-toggle='popover']").popover({html : true});
    $("[data-toggle='popover']").popover(); /*toggle*/
    $("[data-toggle='tooltip']").tooltip();

    $(".submit").click(function() {
	  var query = $("#search").val().trim(); //document.getElementById("search").value;
	    var dataString = 'search=' + query;
      if(query == '') {

      } else {
        $.ajax({
        	type: "GET",
        	url: "controller.php",
        	data: dataString,
        	cache: false,
         beforeSend: function(response) {
         	document.getElementById('results').innerHTML = '';
         	$("#results").html('<center><img src="../loader_gifs/magnify.gif" width="100px"/> Searching...</center>');
         },
         success: function(response) {
         	// $("#results").html(response);
         	setTimeout(function() {
				$("#results").html(response);
			}, 500);
         }
        });
      }
      return false;
    }); /*search event END*/

    //load popover data
    $.get("controller.php", "auth=true", function(responseText) {
		$('#userAuth').attr('data-content', responseText);
	});

	$('#register-form').addClass("hide-form"); //hide registration form
	//toggle visibility of forms
	$('#register').click(function() {
		// $('#login-form').slideUp('slow').next('#register-form').slideDown('slow');
		if( $('#register-form').hasClass('hide-form') ) {
			// $('#login-form').slideUp('slow').next('#register-form').slideDown('slow');
			$('#login-form').slideUp('slow').addClass("hide-form"); //hide this
			$('#register-form').slideDown('slow').removeClass("hide-form"); //then show this
		}
	});
	$('#login').click(function() {
		if( $('#login-form').hasClass('hide-form') ) {
			// $('#register-form').slideUp('slow').next('#login-form').slideDown('slow');
			$('#register-form').slideUp('slow').addClass("hide-form"); //hide this
			$('#login-form').slideDown('slow').removeClass("hide-form"); //then show this
		}
	});
	//control visibility of textarea to update shipping address
	$('#showNewAddr').click(function() {
		$('#editAddr').fadeIn('slow');
		$('.notify-message').delay(2000).fadeOut(1000);
		$('#telephone').focus();
		// if($('#editAddr').is(':checked') && $('#editAddr').is(':hidden')) {
			// $('#editAddr').show();
		// }
	}); //clear the field on hiding the textarea
	$('#curAddress').click(function() {
		$('#editAddr').fadeOut('slow');
		$('#newAddr').val("");
	});

	//fadeout the message popup
	$('.closeMsg').click(function() {
		$('.closeMsg').parent().delay(500).fadeOut(1000);
	});

    $('#processCart').delay(20000).fadeOut(1500);

}); //document.ready ENDS

function reloadAuth() {
	$.get("controller.php", "auth=true", function(responseText) {
		if($('#login-form').is(':visible')) {
			$('.popover-content').html(responseText);
			// $('#user-control').html(responseText); //write user-profile page data
		} else {
			$('.popover-content').html(responseText);
			// $('#user-control').html(responseText); //else write user-login form
		}
		// $('#register-form').addClass("hide-form");
	});
}

//call function to reload remaining items on Cart, on a badge and popover
function reloadItems() {
	$(function(){
		$.get("controller.php","cart=true", function(responseText) {
			var jason = JSON.parse(responseText);
			// $("#shopCart").html(jason['itemNum']);
			if(jason['message']) {
				$('#shopCart').html(0); //and show 0 zero for no items in cart
				$('#controlCart').attr('data-content', jason['message']); //show message if cart is empty
			} else {
				$("#shopCart").html(jason['itemNum']); //then select single data from JSON array, display it
				// $("#itemTotalCost").html("R "+jason['itemCost']); //show total amount of items
				$('#controlCart').attr('data-content', "<div><span class=\'label label-success\'>Total R "+jason['itemCost']+"</span></div><br/><div><button type='button' class='btn btn-sm btn-primary' onclick='continueCheckOut();'>view cart <span class='fa fa-cart-arrow-down'></span></button></div>");
			}
		});
	});
}

function loadCart() {
	window.open('cart.php','_self');
}

//remove the current chosen item from cart
function removeItem(data) {
	var confirmAction = confirm('Remove this item?.');
	if(confirmAction == false) {
		return false;
	}
	$(function() {
		var xhr;
		var urlString = "controller.php?"+data;
		xhr = new XMLHttpRequest();
		xhr.open("GET", urlString, true);
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.onreadystatechange = function() {
				if(xhr.readyState == 4 && xhr.status == 200) {
					//on complete or success
					var jason = JSON.parse(xhr.responseText);
					$('#layer').show();
					$('#layer').html('<center>'+jason['message']+'</center>'); //.delay(1500).fadeOut(500);
					$('#shopCart').html(""); //clear the table to prepare to load remaining items
					reloadItems(); //load remaining items on cart
					setTimeout(function(){
						$('#layer').delay(1000).fadeOut(500);
					}, 1000);
					loadCart(); //reload page
				}
				if(xhr.status == 404) {
					//on error
					$('#layer').show();
					$('#layer').html("<center><span class='fa fa-warning'></span> Error 404: URL not found on server, plase try again!.</center>");
					setTimeout(function() {
						$('#layer').delay(5000).fadeOut(500);
					}, 1000);
				}
			}
		xhr.send();
	});
	// alert(data+" : "+elementRef);
	// elementRef.parentNode.parentNode.createTextNode=message;
}

function continueCheckOut() {
	var confirmAction = confirm('Go to checkout?.');
	if(confirmAction == false) {
		return false;
	}
	window.open('cart.php','_self');
}
function continueShopping() {
	window.open('/shop_old/','_self');
}
function doCheckout() {
	if(confirm('Continue to payment?')) {
		if(confirm("Process cart items?")) {
			window.open('checkout.php','_self');
			/*process payment of Shopping Cart*/	
		}
	}
}
function doPayment() {
	if(confirm('Finalize order payment?')) {
		$('#layer').show();
		$('#layer').html('<center>Processing...</center>');
		$.get("controller.php", "finalize=true", function(responseText) {
			var jason = JSON.parse(responseText);
			if(jason[0] == "success") {
				$('#layer').html('<center>'+jason[1]+'<center>');
				$('#layer').fadeOut('slow');
				// alert("Thank You for shopping with us.");
			}
		});
		setTimeout(function() {
			// continueShopping();
			window.open('/shop_old/?action=processed','_self');
		}, 2000);
	}
}
function openPrinting() {
	if(confirm('Print the invoice?')) {
		var option = $('#paymentType').val();
		window.open('confirmOrder.php?payment='+option,'_blank'); //open page to print invoice
	}
}
function loginUser(thisBtn) {
	var email = $('#loginEmail').val().trim();
	var password = $('#loginPassword').val().trim();

	if(email != "" && password != "") {
		$('#login-form').removeClass("hide-form");
		$('#login-form').children('center').remove(); //remove appended elements before adding another
		// alert("Email:"+email+" and Password:"+password);
		var dataString = "action=login&email="+email+"&password="+password;
		$.ajax({
			type: "POST",
			url: "controller.php",
			data: dataString,
			beforeSend: function() {
				$('#login-form').prepend('<span id="loader"><center><span class="fa fa-spinner fa-pulse fa-2x"></span> Signing in...</center></span>');
				$(thisBtn).attr("disabled", true);
				$('#register[type=button]').attr("disabled",true); //hide(); On loggin disable button
			},
			success: function(responseText) {
				// $('#login-form').hasClass('fa-spinner').hide();
				if(responseText == "true") {
					reloadAuth();
					setTimeout(function() {
						viewProfile(); //after registration open the profile to update the address
					}, 1000);
				} else {
					$('#login-form').append('<center class="text-danger notify"><strong><span class="fa fa-warning"></span> '+responseText+'</strong></center>');
					$(thisBtn).attr("disabled", false); //on login failure enable the buttons
					$('#register[type=button]').attr("disabled",false);
				}
				$('#loader').remove();				
			},
			error: function() {
				$('#loader').remove();
				$('#login-form').append("<center class='text-danger notify'>Error 404: URL not found.</center>");
				$('#register[type=button]').attr("disabled",false); //On script or server error enable the button
			}
		});
		$('.notify').delay(10000).fadeOut(1500);
	}
	else {
		alert("Please provide login details");
	}
}

function registerUser(thisBtn) {
	var firstname = $('#registerFirstname').val().trim();
	var lastname = $('#registerLastname').val().trim();
	var email = $('#registerEmail').val().trim();
	var password = $('#registerPassword').val().trim();
	if(firstname != "" && lastname != "" && email != "" && password != "") {
		//remove appended elements before adding another
		$('#register-form').children('center').remove();
		var dataString = "action=register&firstname="+firstname+"&lastname="+lastname+"&email="+email+"&password="+password;
		$.ajax({
			type: "POST",
			url: "controller.php",
			data: dataString,
			beforeSend: function() {
				$('#register-form').prepend('<span id="loader"><center><span class="fa fa-spinner fa-pulse fa-2x"></span> Registering...</center></span>');
				$(thisBtn).attr('disabled', true);
			},
			success: function(response) {
				if(response=="true") {
					reloadAuth();
					setTimeout(function() {
						viewProfile(); //after registration open the profile to update the address
					}, 1000);
				}
				else {
					$('#register-form').append("<center class='text-danger'><strong><span class='fa fa-warning'></span>"+response+"</span></strong></center>");
					$('#loader').remove();
					$(thisBtn).attr('disabled', false);
				}
			},
			error: function() {
				$('#loader').remove();
				$('#register-form').append("<center class='text-danger'>Error 404: URL not found.</center>");
				$(thisBtn).attr('disabled', false);
			}
		});
	}
	else {
		alert("Please fill all fields are required.");
	}
}

function logout() {
	var confirmAction = confirm('Want to logout?.');
	if(confirmAction == false) {
		return false;
	}
	// var username = $('#username').text();
	var dataString = "action=logout&username="+$('#username').text();
	$('#user-profile').prepend('<span id="loader"><center><span class="fa fa-spinner fa-pulse fa-2x"></span> logging out...</center></span>');
	$.ajax({
		type: "POST",
		url: "controller.php",
		data: dataString,
		success: function(responsedata) {
			if(responsedata=="true") {
				reloadAuth();
				if($('#profile-edit').is(':visible')) {
					continueShopping(); //reaload the products page only if user opened profile for editing
				}
			} else {
				$('#user-profile').append('<center class="text-muted">Failed to logout the user.</center>');
			}
		},
		error: function() {
			$('#login-form').append("<center class='text-danger'>Error 404: URL not found.</center>");
		}
	});
}

function viewProfile() {
	var dataString = "profile=true&username="+$('#username').text();
	// var dataString = "profile=true";
	$.ajax({
		type: "GET",
		url: "controller.php",
		data: dataString,
		cache: false,
		beforeSend: function(response) {
		 	document.getElementById('results').innerHTML = '';
		 	$("#results").html('<center><img src="../loader_gifs/ripple.gif" width="100px"/> Loading...</center>');
		},
		success: function(response) {
		 	setTimeout(function() {
				$("#results").html(response);
				$('.page-header').html("<center>Customer Details</center>");
			}, 500);
		}
	});
}

function UpdateProfile() {
	var confirmAction = confirm('Save details?.');
	if(confirmAction == false) {
		return false;
	}
	if($('#firstname').val().trim() == "" && $('#lastname').val().trim() == "" && $('#telephone').val().trim() == "" && $('#email').val().trim() == "" && $('#password').val().trim() == "") {
		alert("Please provide all required details");
	}
	else if($('#address').val().trim() == "") {
		alert("Please update your Physical Address first!");
	}
	else {
		var dataString = "action=update&firstname="+$('#firstname').val().trim()+"&lastname="+$('#lastname').val().trim()+"&telephone="+
		$('#telephone').val().trim()+"&email="+$('#email').val().trim()+"&password="+$('#password').val().trim()+"&address="+$('#address').val().trim();
		$.ajax({
			type: "POST",
			url: "controller.php",
			data: dataString,
			cache: false,
		 beforeSend: function() {
		 	$('#profile-edit').prepend('<div id="loader"><center><span class="fa fa-spinner fa-pulse fa-3x"></span> Saving data...</center></div>');
		 	$('.btn-default[type=button]').attr("disabled", true);
		 	$('.btn-primary[type=button]').attr("disabled", true);
		 },
		 success: function(response) {
		 	if(response == "true") {
		 		setTimeout(function() {
			 		$('.page-header').html("<center>Customer Details</center>");
					$("#loader").addClass('alert alert-success').html('<span class="fa fa-check"></span> Your details were saved successfully!.');
					$('.btn-primary[type=button]').attr("disabled", false);
					$('.btn-default[type=button]').attr("disabled", false);
				}, 500);
		 	}
		 	else {
		 		$("#loader").addClass('alert alert-danger').html('<span class="fa fa-warning"></span> '+response);
		 		$('.btn-primary[type=button]').attr("disabled", false); //enable the save button only
		 	}
		 	
		 },
		 error: function(response) {
		 	$("#loader").addClass('alert alert-danger').html('<span class="fa fa-warning"></span> Error: 404 URL not found!.');
		 	$('.btn-primary[type=button]').attr("disabled", false);
		 }
		});
		$('#loader').delay(20000).fadeOut(1500);
	}
}

function updateShippingAddress() {
	if(confirm("Update Physical address?")) {
		//collect values from text inputs
		var newShippAddr = $('#newAddr').val().trim();
		var hiddenEmail = $('#hiddenEmail').val().trim();
		var telephone = $('#telephone').val().trim();
		var dataString = "action=addNewAddress&newAddress="+newShippAddr+"&email="+hiddenEmail+"&telephone="+telephone;

		if(newShippAddr != "" && telephone != "") {
			$.ajax({
				type: "POST", 
				url: "controller.php",
				data: dataString,
				beforeSend: function() {
					$('.btn-primary[type=button]').attr("disabled", true);
					$('.btn-primary[type=button]').children('span').addClass('fa-pulse');
				},
				success: function(responseText) {
					var jason = JSON.parse(responseText);
					if(jason[0] == "success") {
						$('#alert-message').html(jason['success']);
						$('#alert-message').html('<div class="alert alert-success" role="alert"><span class="fa fa-check"></span> Your address was successfully updated.</div>').delay(20000).fadeOut(1000);
						$('.btn-primary[type=button]').attr("disabled", false);
						
						$('#newAddr').val("");
						$('#editAddr').fadeOut('slow');
						// $('input[type=radio]:last-child').attr('checked', false);
						$('.curAddress').html('<center><span class="fa fa-spinner fa-pulse fa-3x"></span></center>');
						$('#telNum').html('<center><span class="fa fa-spinner fa-pulse fa-1x"></span></center>');
						setTimeout(function() {
							$('.curAddress').html(jason[1].replace(new RegExp(',', 'gi'), '<br/>'));
							$('#telNum').html(jason[2]+' <i class="text-success">updated!</i>');
							//window.open('checkout.php','_self'); //after update the address reload the page
						}, 1000);
						$('#curAddress').attr('checked', true);
					}
					else {
						$('#alert-message').html('<div class="alert alert-danger" role="alert"><span class="fa fa-times"></span> '+jason[0]+'</div>');
						$('.btn-primary[type=button]').attr("disabled", false);
						$('.btn-primary[type=button]').children('span').removeClass('fa-pulse');
					}
				},
				error: function() {
					$('#alert-message').html('<div class="alert alert-danger" role="alert"><span class="fa fa-warning"></span> Error 404: URL not found.</div>');
					$('.btn-primary[type=button]').attr("disabled", false);
					$('.btn-primary[type=button]').children('span').removeClass('fa-pulse');
				}
			});
		}
		else {
			alert("Please type address and telephone first.");
		}
	}
}

function createOrder() {
	var custEmail = $('#hiddenEmail').val().trim();
	dataString = "processOrder=true&email="+custEmail;
	$.ajax({
		type: "POST",
		url: "controller.php",
		data: dataString,
		success: function(responsedata) {
			// var jason = JSON.parse(responsedata);
			$('#orderNumber').text(responsedata); //set order number
		},
		error: function() {
			alert("Error: 404 URL not found!.");
		}
	});
}

function cancelOrder() {
	if(confirm("Sure want to cancel this Order?")) {
		var dataString = "cancelOrder=true";
		$.ajax({
			type: "POST",
			url: "controller.php",
			data: dataString,
			cache: false,
			beforeSend: function() {
				$('#layer').show();
				$('#layer').html('<center><img src="../loader_gifs/ripple.gif" width="100px"/> Cancelling...</center>');
			},
			success: function(rresponse_data) {
				var jason = JSON.parse(rresponse_data);
				if(jason.message == "success") {
					$('#layer').fadeOut('slow');
					continueShopping();
				}
			},
			error: function() {
				setTimeout(function() {
					$('#layer').html('<center><span class="fa fa-times"></span> Failed to cancel order...</center>');
				}, 1000);
			}
		});
	}
}
