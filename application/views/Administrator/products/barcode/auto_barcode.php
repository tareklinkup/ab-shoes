<!DOCTYPE html>
<html>

<head>
	<title>Barcode Generator</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style type="text/css">
		.content {
			width: 120px;
			float: left;
			padding: 2px;
		}

		.name {
			height: auto;
			width: 120px;
			font-size: 11px;
		}

		.img {
			height: 60px;
			width: 120px;
		}

		.pid {
			height: 15px;
			width: 120px;
		}

		.price {
			height: 10px;
			width: 120px;
		}

		.date {
			height: 90px;
			width: 20px;
			float: right;
			writing-mode: tb-rl;
		}

		.mytext {
			height: 25px !important;
			padding: 2px;
		}
	</style>
	<style type="text/css" media="print">
		@page {
			size: 192px 144px;
			background: white;
			display: block;
			margin: 0 auto;
			box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
		}

		.borderNonePirint {
			margin: 4px !important;
			border: 0 !important;
			page-break-inside: avoid;
			page-break-after: always;
			/*border:1px solid #ccc !important;*/
		}
	</style>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url('barcode/style.css'); ?>" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="shortcut icon" href="<?php echo base_url('barcode/favicon.ico'); ?>" />
	<script src="<?php //echo base_url('barcode/jquery-1.7.2.min.js'); 
					?>"></script>
	<script src="<?php //echo base_url('barcode/barcode.js'); 
					?>"></script>
	<script type="text/javascript">
		function printpage() {
			// document.getElementById('printButton').style.visibility="hidden";
			document.getElementById("printButton").style.cssText = "display:none;height:0px;margin-top:0px"
			document.getElementById('printButton2').style.display = "none";
			window.print();
			document.getElementById('printButton').style.display = "block";
			location.reload();
		}
	</script>

</head>

<body class="">
	<div class="container-fluid" style="margin:0px;padding:0px;">
		<div class="row" id="printButton">
			<div class="col-md-12">
				<form class="form-horizontal" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
					<section class="" style="background:#f4f4f4;height:200px;">
						<div class="">
							<div class="col-sm-12 text-center">
								<h3 class="text-info">Barcode Generator</h3>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-sm-2" for="text">Product ID</label>
							<div class="col-sm-2">
								<input type="text" name="pID" class="form-control mytext" placeholder="Product ID ..." value="<?php echo $product->Product_Code; ?>" />
							</div>

							<label class="control-label col-sm-2" for="text">Product Name</label>
							<div class="col-sm-2">
								<input type="text" name="pname" class="form-control mytext" placeholder="Product name ..." value="<?php echo $product->Product_Name; ?>" />
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-sm-2" for="Price">Price </label>
							<div class="col-sm-2">
								<input type="text" name="Price" class="form-control mytext" placeholder="Product price ..." value="<?php echo $product->Product_SellingPrice; ?>" />
							</div>

							<label class="control-label col-sm-2" for="Price">Article </label>

							<div class="col-sm-2">
								<input type="text" name="article" class="form-control mytext" placeholder="Article ..." value="<?php echo $product->color_name . ' ' . $product->name; ?>" />
								<input type="hidden" name="brand_name" value="<?php echo $product->brand_name; ?>">
							</div>

							<div class="col-sm-2">
								<input type="submit" name="submit" value="Generate" class="btn btn-primary" />
								<input name="print" type="button" value="Print" id="printButton2" onClick="printpage()" class="btn btn-success" style="width:100px;" />
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-sm-2" for="qty">Quantity</label>
							<div class="col-sm-2">
								<input type="text" name="qty" class="form-control mytext" placeholder="Product quantity ...">
							</div>

							<label style="display: none;" class="control-label col-sm-2" for="date">Date</label>
							<div class="col-sm-2" style="display: none;">
								<input type="date" name="date" class="form-control mytext" />
							</div>
						</div>
					</section>
				</form>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<section class="output" style="margin:0px;padding:0px;">
					<?php

					if (isset($_REQUEST['submit'])) {
						$PID = $_POST['pID'];
						$Price = $_POST['Price'];
						$article = $_POST['article'];
						$qty = $_POST['qty'];
						$date = $_POST['date'];
						$pname = $_POST['pname'];
						$brand_name = $_POST['brand_name'];
						$Price = $_POST['Price'];

						for ($i = 0; $i < $qty; $i++) {

							if (isset($kode)) : echo $kode;
							endif;
					?>
							<!-- <div id="imageOutput" style="padding:5px;width:172px;float:left;background:#fff;border:1px #ccc solid;" align="center">	
							  <div class="article"><?php echo $article; ?></div>
							  <div class="content">
								<div class="name"><?php echo $pname; ?></div>
								<div class="img">
									<img src='<?php echo site_url(); ?>GenerateBarcode/<?php echo $PID; ?>' style="height: 1.3cm; width: 2.5cm;"/>
								</div>
								<div class="price"><?php echo $this->session->userdata('Currency_Name') . ' ' . $Price; ?></div>
							  </div>
							<div class="date"><?php echo $date; ?></div>
						</div> -->


							<div class="borderNonePirint" style="float:left;margin:0px;padding:5px; height:135px; width:200px;overflow:hidden;box-sizing:border-box;">
								<div style="width: 190px; height:115px;text-align: center;margin:0;padding:20px 0px 0px 0px;">
									<p style="padding-bottom:0px;font-size: 22px;text-align: center;margin:0px;font-weight: bold;letter-spacing: .5px;margin-bottom: -4px;"><?php echo substr($pname, 0, 20); ?></p>
									<p style="margin:-20px 0 0 0;width:20px;font-size: 11px;float:left;writing-mode: tb-rl;font-weight:700;height:100px"><?php echo $article; ?></p>
									<p style="margin:0;padding-right:5px;font-size: 10px;float:right;writing-mode: tb-rl;font-weight: 700;"><?php echo $brand_name; ?></p>
									<img src='<?php echo site_url(); ?>GenerateBarcode/<?php echo $PID; ?>' style="height: 65px; width: 150px;" /><br>
									<p style="font-weight: bold;margin: 0 auto;width:100px"><?php echo $this->session->userdata('Currency_Name') . ' ' . $Price; ?></p>
								</div>
							</div>
					<?php
						}
					}
					?>

				</section>
			</div>
		</div>

	</div>
</body>

</html>