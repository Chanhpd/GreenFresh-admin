<?php
include_once('./inc/header.php');
include_once('./DB/dbhelper.php');


$cart = [];
if (isset($_COOKIE['cart'])) {
	$json = $_COOKIE['cart'];
	$cart = json_decode($json, true);
}
if (isset($_SERVER['QUERY_STRING'])) {
	$activePage = basename($_SERVER['QUERY_STRING']);
}

?>
<!-- END nav -->

<div class="hero-wrap hero-bread" style="background-image: url('images/bg_1.jpg');">
	<div class="container">
		<div class="row no-gutters slider-text align-items-center justify-content-center">
			<div class="col-md-9 ftco-animate text-center">
				<p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span> <span>Products</span></p>
				<h1 class="mb-0 bread">Products</h1>
			</div>
		</div>
	</div>
</div>

<section class="ftco-section">

	<div class="container">

		<div class="row justify-content-center">
			
			<div class="col-md-10 mb-5 text-center">
				<ul class="product-category">
					<!-- <li><a href="shop.php" class="active">All</a></li>
					<li><a href="?category=1">Vegetables</a></li>
					<li><a href="?category=2">Fruits</a></li>
					<li><a href="?category=3">Juice</a></li>
					<li><a href="?category=4">Dried</a></li> -->

					<li><a class="<?= ($activePage == '') ? 'active' : ''; ?>" href="shop.php">All</a></li>
					<li><a class="<?= strlen(strstr($activePage, 'category=1')) > 0 ? 'active' : ''; ?>" href="?category=1">Vegetables</a></li>
					<li><a class="<?= strlen(strstr($activePage, 'category=2')) > 0 ? 'active' : ''; ?>" href="?category=2">Fruits</a></li>
					<li><a class="<?= strlen(strstr($activePage, 'category=3')) > 0 ? 'active' : ''; ?>" href="?category=3">Juice</a></li>
					<li><a class="<?= strlen(strstr($activePage, 'category=4')) > 0 ? 'active' : ''; ?>" href="?category=4">Dried</a></li>
					
				</ul>
			</div>

			<div class="row">
				<div class="col-md-6 col-md-offset-3 my-4">

					<input type="text" name="search_text" id="search_text" placeholder="&#xF002; Search" style="font-family:Arial, FontAwesome">
				</div>

			</div>
		</div>

		<div class="row" id="result">

			<?php

			$category = '';
			if (isset($_GET['category'])) {
				$category = $_GET['category'];
			}
			$sql = 'select count(id) as number from product';
			if (isset($_POST['category'])) {
				$sql = 'SELECT count(id) as number FROM product ';
			} elseif ($category == '') {
				$sql = 'SELECT count(id) as number FROM product ';
			} else {
				$sql = "SELECT  count(id) as number FROM product where id_cate='$category'";
			}

			$result = executeResult($sql);
			$number = 0;
			if ($result != null && count($result) > 0) {
				$number = $result[0]['number'];
			}
			$page = ceil($number / 12);

			$current_page = 1;

			if (isset($_GET['page'])) {
				$current_page = $_GET['page'];
			}
			$index = ($current_page - 1) * 12;

			if (isset($_POST['category'])) {
				$sql = 'SELECT * FROM product limit ' . $index . ', 12';
			} elseif ($category == '') {
				$sql = 'SELECT * FROM product limit ' . $index . ', 12';
			} else {
				$sql = "SELECT * FROM product where id_cate='$category' limit $index, 12";
			}


			$result = executeResult($sql);

			foreach ($result as $row) {

				if ($row['sale'] !== null && $row['sale'] != "0" ) {
					echo '<div class="col-md-6 col-lg-3 ftco-animate">
				<div class="product">
					<a href="product-single.php?id=' . $row['id'] . '" class="img-prod"><img class="img-fluid" src="' . $row['img'] . '" alt="Colorlib Template">
						<span class="status">' . $row['sale'] . '%</span>
						<div class="overlay"></div>
					</a>
					<div class="text py-3 pb-4 px-3 text-center">
						<h3><a href="#">' . $row['name'] . '</a></h3>
						<div class="d-flex">
							<div class="pricing">
								<p class="price"><span class="mr-2 price-dc">$' . number_format($row['price'], '2', '.', '.') . '</span><span class="price-sale">' . number_format($row['price'] * (100 - $row['sale']) * 0.01, '2', '.', '.') . '$</span></p>
							</div>
						</div>
						';
				} else {
					echo '<div class="col-md-6 col-lg-3 ftco-animate">
					<div class="product">
						<a href="product-single.php?id=' . $row['id'] . '" class="img-prod"><img class="img-fluid" src="' . $row['img'] . '" alt="Colorlib Template">
							<div class="overlay"></div>
						</a>
						<div class="text py-3 pb-4 px-3 text-center">
							<h3><a href="#">' . $row['name'] . '</a></h3>
							<div class="d-flex">
								<div class="pricing">
									<p class="price"><span>$' . number_format($row['price'], '2', '.', '.') . '</span></p>
								</div>
							</div>
							';
				}
				echo 	'<div class="bottom-area d-flex px-3">
								<div class="m-auto d-flex">
									<a  href="" class="add-to-cart d-flex justify-content-center align-items-center text-center">
										<span><i class="ion-ios-menu"></i></span>
									</a>
									<button onclick=addToCart(' . $row['id'] . ') class="btn btn-success buy-now d-flex justify-content-center align-items-center mx-1">
										<span><i class="ion-ios-cart"></i></span>
									</button>
									<button onclick=addToWishList(' . $row['id'] . ') class="btn btn-success heart d-flex justify-content-center align-items-center ">
										<span><i class="ion-ios-heart"></i></span>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>';
			}
			echo '</div>

			<div class="row mt-5">
				<div class="col text-center">
					<div class="block-27">
						<ul>';
			$pageNum = 1;

			for ($i = 1; $i <= $page; $i++) {
				if (isset($_GET['category'])) {
					$cat = $_GET['category'];
				} else $cat = '';
				if (isset($_GET['page'])) {
					$pageNum = (int)$_GET['page'];
				} else {
					$pageNum = 1;;
				}
				if ($i == $pageNum) {
					echo '<li class="active"><a href="?page=' . $i . '">' . $i . '</a></li>';
				} else {
					echo '<li><a href="?page=' . $i . '&category=' . $cat . '">' . $i . '</a></li>';
				}
			}
			//?id=1&cat=2
			?>
		</div>
		</ul>

</section>

<?php
include_once('./inc/footer.php');
?>


<!-- loader -->
<div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
		<circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
		<circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00" />
	</svg></div>


<script src="js/jquery.min.js"></script>
<script src="js/jquery-migrate-3.0.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/jquery.waypoints.min.js"></script>
<script src="js/jquery.stellar.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/jquery.magnific-popup.min.js"></script>
<script src="js/aos.js"></script>
<script src="js/jquery.animateNumber.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/scrollax.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
<script src="js/google-map.js"></script>
<script src="js/main.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="js/action-cookie.js"></script>

<script>
	$(document).ready(function() {
		$('#search_text').keyup(function() {
			var txt = $(this).val();
			$('#result').html('');
			$.ajax({
				url: "api/fetch-search.php",
				method: "post",
				data: {
					search: txt
				},
				dataType: "text",
				success: function(data) {
					$('#result').html(data);
				}
			})
		});
	});
</script>
</body>

</html>