<?php 
	session_start();
?>

<!DOCTYPE html>
<html lang="em-US">
<link rel="icon"
      type="image/png"
      href="images/logo.png">
	
	<head>
		<title>GameGo Account Information</title>

		<!-- Metadata-->
		<?php include('includes/meta.html'); ?>

		<!-- CSS -->
		<?php include('includes/css.html'); ?>

		<!-- PHP Libraries -->
		<?php include('includes/phpLibs.html'); ?>

	</head>

	<body>
		
		<header>
			<!-- Navigation -->
			<?php include('includes/navigation.php'); ?>
		</header>




		<main class="container">

			<div class="row mt-5">
				<div class="col-md-12 text-center">
					<h2>Hi, <?php getSessionVariable("firstName") ?>!</h2>
				</div>
			</div>

			<div class="row justify-content-center mt-4">
				<?php printSessionSuccessMessage(); ?>
				<?php unset($_SESSION["successMessage"]); ?>
				<?php printSessionErrorMessage(); ?>
				<?php unset($_SESSION["errorMessage"]); ?>
			</div>

			<div class="card my-5 box-shadow">
				<div class="card-header">
					<h4 class="text-center">Account Info</h4>
				</div>

				<?php

					// Open Database Connection
					include("includes/openDBConn.php");

					// Pull account information from database and set it to session variables
					$sql = "SELECT `firstName`, `lastName`, `email`, `phone` FROM `Users` WHERE `username` = '" . returnSessionVariable("username") . "'";
					$result = $conn->query($sql);

					foreach ($result->fetch_assoc() as $key => $value) {
						setSessionVariable($key, $value);
					}

				?>
				
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="firstName">First Name</label>
								<input type="text" name="firstName" id="firstName" class="form-control" value="<?php getSessionVariable("firstName") ?>" required disabled>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
								<label for="lastName">Last Name</label>
								<input type="text" name="lastName" id="lastName" class="form-control" value="<?php getSessionVariable("lastName") ?>" required disabled>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="email">Email</label>
								<input type="email" name="email" id="email" class="form-control" value="<?php getSessionVariable("email") ?>" required disabled>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
								<label for="phone">Phone</label>
								<input type="tel" name="phone" id="phone" class="form-control" value="<?php getSessionVariable("phone") ?>" required disabled>
							</div>
						</div>
					</div>
					<button class="btn btn-info btn-block" data-toggle="modal" data-target="#accountModal"><i class="fas fa-pencil-alt mr-2"></i>Edit Account Info</button>
				</div>
			</div>

			<div class="card my-5 box-shadow">
				<div class="card-header">
					<h4 class="text-center">Login Info</h4>
				</div>
				
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="username">Username</label>
								<input type="text" name="username" id="username" class="form-control" value="<?php getSessionVariable("username") ?>" required disabled>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="password">Password</label>
								<input type="password" name="password" id="password" class="form-control" value="Roll Tide" required disabled>
							</div>
						</div>
					</div>
					<button class="btn btn-info btn-block" data-toggle="modal" data-target="#loginModal"><i class="fas fa-pencil-alt mr-2"></i>Edit Login Info</button>
				</div>
			</div>

			<div class="card my-5 box-shadow">

				<div class="card-header">
					<h4 class="text-center">Billing Info</h4>
				</div>
				
				<div class="card-body">
					<div class="row">
						<table class="table table-striped mx-3 text-center">
							<thead>
								<tr>
									<th>Card #</th>
									<th>Type</th>
									<th>Expiration</th>
									<th>Name</th>
									<th>Address</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php
									// Open Database Connection
									include("includes/openDBConn.php");

									// Select all but username from Billing
									$sql = "SELECT `cardNumber`, `cardType`, `cardExpirationMonth`, `cardExpirationYear`, `cardCVV`, ";
									$sql = $sql . "`billingFirstName`, `billingLastName`, `billingStreet`, `billingCity`, `billingState`, `billingZip` FROM `Billing` ";
									$sql = $sql . "WHERE username='" . returnSessionVariable("username") . "'";
									$result = $conn->query($sql);

									// Populate table rows and fill session variables
									for($i = 0; $i < $result->num_rows; $i++){
										$row = $result->fetch_assoc();
										echo("<tr>");	
										echo("<td>" . substr($row['cardNumber'], 12) . "</td>"); // Card
										echo("<td>" . $row['cardType'] . "</td>");	// Card Type
										echo("<td>" . $row['cardExpirationMonth'] . "/" . $row['cardExpirationYear'] . "</td>"); // Card Expiration
										echo("<td>" . $row['billingFirstName'] . " " . $row['billingLastName'] . "</td>"); // Name
										echo("<td>" . $row['billingStreet'] . ", " .  $row['billingCity'] . ", " . $row['billingState'] . " " . $row['billingZip'] . "</td>");	// Address

										// Add Action Menu
										printActionMenu("billing", $i);
										echo("</tr>");

										$_SESSION['billing'][$i]['cardNumber'] = $row['cardNumber'];
										$_SESSION['billing'][$i]['cardType'] = $row['cardType'];
										$_SESSION['billing'][$i]['cardExpirationMonth'] = $row['cardExpirationMonth'];
										$_SESSION['billing'][$i]['cardExpirationYear'] = $row['cardExpirationYear'];
										$_SESSION['billing'][$i]['cardCVV'] = $row['cardCVV'];
										$_SESSION['billing'][$i]['billingFirstName'] = $row['billingFirstName'];
										$_SESSION['billing'][$i]['billingLastName'] = $row['billingLastName'];
										$_SESSION['billing'][$i]['billingStreet'] = $row['billingStreet'];
										$_SESSION['billing'][$i]['billingCity'] = $row['billingCity'];
										$_SESSION['billing'][$i]['billingState'] = $row['billingState'];
										$_SESSION['billing'][$i]['billingZip'] = $row['billingZip'];

										include("billingEditModal.php");
										include("billingDeleteModal.php");
									}

									// Close Database Connection
									include("includes/closeDBConn.php");
								?>
							</tbody>
							<tfoot>
								<tr>
									<td class="text-center text-muted" colspan="6"><?php echo "Retrieved " . $result->num_rows . " Cards."; ?></td>
								</tr>
							</tfoot>
						</table>
					</div>
					<button class="btn btn-info btn-block" data-toggle="modal" data-target="#billingAddModal"><i class="fas fa-plus mr-2"></i>Add Billing Info</button>
				</div>
			</div>

			<div class="card my-5 box-shadow">
				<div class="card-header text-center">
					<h4>Shipping Info</h4>
				</div>

				<div class="card-body">
					<div class="row">
						<table class="table table-striped mx-3 text-center">
							<thead>
								<tr>
									<th>Name</th>
									<th>Address</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php
									// Open Database Connection
									include("includes/openDBConn.php");

									// Select all but username from Shipping
									$sql = "SELECT `ID`, `shippingFirstName`, `shippingLastName`, `shippingStreet`, `shippingCity`, `shippingState`, `shippingZip` FROM `Shipping` ";
									$sql = $sql . "WHERE username='" . returnSessionVariable("username") . "'";
									$result = $conn->query($sql);

									// Populate table rows
									for($i = 0; $i < $result->num_rows; $i++){
										$row = $result->fetch_assoc();
										echo("<tr>");	
										echo("<td>" . $row['shippingFirstName'] . " " . $row['shippingLastName'] . "</td>"); // Name
										echo("<td>" . $row['shippingStreet'] . ", " .  $row['shippingCity'] . ", " . $row['shippingState'] . " " . $row['shippingZip'] . "</td>");	// Address

										// Add Action Menu
										printActionMenu("shipping", $row['ID']);
										echo("</tr>");

										$_SESSION['shipping'][$row['ID']]['shippingFirstName'] = $row['shippingFirstName'];
										$_SESSION['shipping'][$row['ID']]['shippingLastName'] = $row['shippingLastName'];
										$_SESSION['shipping'][$row['ID']]['shippingStreet'] = $row['shippingStreet'];
										$_SESSION['shipping'][$row['ID']]['shippingCity'] = $row['shippingCity'];
										$_SESSION['shipping'][$row['ID']]['shippingState'] = $row['shippingState'];
										$_SESSION['shipping'][$row['ID']]['shippingZip'] = $row['shippingZip'];

										include("shippingEditModal.php");
										include("shippingDeleteModal.php");
									}

									// Close Database Connection
									include("includes/closeDBConn.php");
								?>
							</tbody>
							<tfoot>
								<tr>
									<td class="text-center text-muted" colspan="5"><?php echo "Retrieved " . $result->num_rows . " shipping addresses."; ?></td>
								</tr>
							</tfoot>
						</table>
					</div>
					<button class="btn btn-info btn-block" data-toggle="modal" data-target="#shippingAddModal"><i class="fas fa-plus mr-2"></i>Add Shipping Info</button>
				</div>
				
				<!-- Include Account Modal -->
				<?php include("accountModal.php"); ?>

				<!-- Include Login Modal -->
				<?php include("loginModal.php"); ?>

				<!-- Include Billing Add Modal -->
				<?php include("billingAddModal.php"); ?>

				<!-- Include Shipping Add Modal -->
				<?php include("shippingAddModal.php"); ?>				

			</div>
		</main>




		<footer>
			<?php include("includes/footer.html"); ?>
		</footer>

		<!-- Bootstrap JavaScript -->
		<?php include('includes/bootstrap_javascript.html') ?>

		<!-- JS Libraries -->
		<?php include('includes/jsLibs.html'); ?>

	</body>
</html>