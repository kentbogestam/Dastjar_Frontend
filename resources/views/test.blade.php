<!DOCTYPE html>
<html>
<head>
<!--<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css"/>-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<style>
	#delete-me-btn{
		background-color: #d25229;
    	color: #fff;
	    border-radius: 10px;
	}
</style>
</head>

<body>

	<div data-role="header" class="header" id="nav-header"  data-position="fixed">
		<div class="logo">
			<div class="inner-logo">
				<img src="images/logo.png">
				<span>Mayank</span>
			</div>
		</div>
		<a href="search-map-eatnow" class="ui-btn-right map-btn user-link" data-ajax="false"><img src="images/icons/map-icon.png" width="30px"></a>
	</div>
	<div role="main" data-role="main-content" class="content">
		<div class="inner-page-container">

			<div class="table-content">
				<h2>ORDER DETAILS</h2>
				<table data-role="table" id="table-custom-2" data-mode="" class="ui-body-d ui-shadow table-stripe ui-responsive">
						<tr>
							<td>product_name</td><td>100</td><td>sek 100</td>
						</tr>	
				<tr class="last-row">	
				<td> </td>
				<td>         </td>
				<td>  TOTAL:- sek 100</td>
				</tr>
				</tr>
				</table>
			</div>
		</div>
	</div>



	<form action="#" class="payment_form_btn" method="POST">
        <script
                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
				data-key="pk_test_5P1GedJTk0HsWb3AnjYBbz6G"
                data-amount=""
                data-name="Stripe"
                data-description="Dastjar"
                data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                data-locale="auto"
                data-zip-code="false">
        </script>
    </form>

	<div data-role="footer" class="footer" data-position="fixed">
		<div class="ui-grid-c inner-footer center">
		<div class="ui-block-a"><a href="eat-now" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container">
				<img src="images/icons/select-store_01.png">
			</div>
			<span>Restaurant</span>
		</a></div>
		<div class="ui-block-b"><a class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container">
				<img src="images/icons/select-store_03.png">
			</div>
			<span>Send</span>
		</a></div>
		<div class="ui-block-d"><a href="user-setting" class="ui-shadow ui-btn ui-corner-all icon-img ui-btn-inline" data-ajax="false">
			<div class="img-container"><img src="images/icons/select-store_07.png"></div>
		</a></div>
		</div>
	</div>

<script type="text/javascript">
	 $(".ordersec").click(function(){
	    $("#order-popup").toggleClass("hide-popup");
	 });
</script>


	</body>
</html>
