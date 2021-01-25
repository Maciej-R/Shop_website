<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>BD G22</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/price-range.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
	<link href="css/responsive.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->       
    <link rel="shortcut icon" href="images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
</head><!--/head-->

<sc>
	<header id="header"><!--header-->
		
		<div class="header-middle"><!--header-middle-->
			<div class="container">
				<div class="row">
					<div class="col-md-4 clearfix">
						<div class="logo pull-left">
							<a href="index.html" style="color:green; font-size:1.5em;">BD G22</a>
						</div>
					</div>
					<div class="col-md-8 clearfix">
						<div class="shop-menu clearfix pull-right">
							<ul class="nav navbar-nav">
								<li><a href=""><i class="fa fa-user"></i> Account</a></li>
								<li><a href="checkout.html"><i class="fa fa-crosshairs"></i> Checkout</a></li>
								<li><a href="cart.html"><i class="fa fa-shopping-cart"></i> Cart</a></li>
								<li><a href="login.html"><i class="fa fa-lock"></i> Login</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div><!--/header-middle-->
	
		<div class="header-bottom"><!--header-bottom-->
			<div class="container">
				<div class="row">
					<div class="col-sm-9">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
						<div class="mainmenu pull-left">
							<ul class="nav navbar-nav collapse navbar-collapse">
								<li><a href="index.html">Home</a></li>
								<li class="dropdown"><a href="#" class="active">Shop<i class="fa fa-angle-down"></i></a>
                                    <ul role="menu" class="sub-menu">
                                        <li><a href="shop.html" class="active">Products</a></li>
										<li><a href="product-details.html">Product Details</a></li> 
										<li><a href="checkout.html">Checkout</a></li> 
										<li><a href="cart.html">Cart</a></li> 
										<li><a href="login.html">Login</a></li> 
                                    </ul>
                                </li>
							</ul>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="search_box pull-right">
							<input type="text" placeholder="Search"/>
						</div>
					</div>
				</div>
				</div>
			</div>
	</header>
	
	<section id="advertisement">
		<div class="container">
			<img src="images/shop/advertisement.jpg" alt="" />
		</div>
	</section>
	
	<section>
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<div class="left-sidebar">
						<h2>Category</h2>
						<div class="panel-group category-products" id="accordian"><!--category-productsr-->
							<?php
								//include "DBConnection.php";
								include "CategoriesProxy.php";
								include "helpers.php";
                                session_start();

								try {
                                    $dbconn = Connection::getPDO();
                                    $proxy = CategoriesProxy::get();
                                    $success = false;
                                    $data = null;
                                    if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['cat'])) {
                                        $stmt = $dbconn->prepare("select id, parent_id, name from shop.categories");
                                        $success = $stmt->execute();
                                        $data = $stmt->fetchAll();
                                    }else {
                                        $stmt = $dbconn -> prepare('select "name" from shop.categories where id = :id');
                                        $stmt -> execute([':id' => $_GET['cat']]);
                                        $tmp = $stmt -> fetch(PDO::FETCH_ASSOC)['name'];
                                        $data = [['name' => $tmp, 'id' => intval($_GET['cat']), 'parent_id' => null]];
                                        //$tmp = $proxy -> get_subcategories($_GET['cat']);
                                        //$data = array();
                                        //foreach ($tmp as $item) {
                                        //    array_push($data, ['name' => $item, 'parent_id' => null]);
                                        //}
                                        $success = true;
                                    }
                                    if ($success) {
                                        foreach ($data as $row) {
                                            // Display only top categories if none was specified
                                            if ($row['parent_id'] != null) continue;
                                            echo '
                                                <div class="panel panel-default">
							                    	<div class="panel-heading">
							                    		<h4 class="panel-title">
							                    			<a data-toggle="collapse" data-parent="#accordian">
							                    				<span class="badge pull-right"><i class="fa fa-plus"></i></span>
							                    				    <a href="' . get_path_and_query("cat", $row['id']) . '">' . $row['name'] . '</a>
							                    			</a>
							                    		</h4>
							                    	</div>';
                                            $subs = $proxy -> get_subcategories($row['id']);
                                            if ($subs != null) {
                                                echo '
                                                     <div id="' . $row['name'] . '" class="panel-collapse collapse">
							                    		<div class="panel-body">
							                    			<ul>';
                                                foreach($subs as $rec) {
                                                    echo '<li><a href="/shop.php?cat=' . $rec['id'] . '">' . $rec['name'] . ' </a></li>';
                                                }
                                                echo        '</ul>
							                    		</div>
							                    	</div>';
                                            }
                                            echo '</div>';
                                        }

                                    }else throw new RuntimeException();
                                }catch (Exception $e){
								    echo "<p>Failed to load categories</p>";
                                }

							?>

						</div><!--/category-productsr-->
					
						<div class="brands_products"><!--brands_products-->
							<h2>Brands</h2>
							<div class="brands-name">
								<ul class="nav nav-pills nav-stacked">
                                    <?php
                                    $success = false;
                                    $data = null;
                                    if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['brd'])) {
                                        $stmt = $dbconn->prepare("select id, name from shop.brands");
                                        $success = $stmt->execute();
                                        $data = $stmt->fetchAll();
                                    }else {
                                        $stmt = $dbconn -> prepare('select "name" from shop.brands where id = :id');
                                        $stmt -> execute([':id' => $_GET['brd']]);
                                        $tmp = $stmt -> fetch(PDO::FETCH_ASSOC)['name'];
                                        $data = [['id' => intval($_GET['brd']), 'name' => $tmp]];
                                        $success = true;
                                    }
                                    if ($success) {
                                        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                        // $t = preg_match("\/.*\?[a-z]+.*=.*", $_SERVER['REQUEST_URI']);
                                        foreach ($data as $row) {
                                            if (!isset($_GET['brd'])) {
                                                if (count(array_keys($_GET)) == 0) $x = "?brd=";
                                                else $x = "&brd=";
                                                echo '<li><a href="' . $link . $x . $row['id'] . '"> <span class="pull-right"></span>' . $row['name'] . '</a></li>';
                                            }
                                            // Brands parameter was present so reconstruct other used in query and add new brand id
                                            else {
                                                $query = "?";
                                                foreach (array_keys($_GET) as $key) {
                                                    if ($key == 'brd') continue;
                                                    if ($query != "?") $query .= "&";
                                                    $query .= $key . "=" . $_GET[$key];
                                                }
                                                $query .= "&brd=" . $row['id'];
                                                preg_match("/\/.*(?=\?)?/", $_SERVER['REQUEST_URI'], $matches, PREG_OFFSET_CAPTURE); // Match path without query
                                                echo '<li><a href="' . $matches[0][0] . $query . '"> <span class="pull-right"></span>' . $row['name'] . '</a></li>';
                                            }
                                        }
                                    }
                                    ?>
								</ul>
							</div>
						</div><!--/brands_products-->
						
						<div class="price-range"><!--price-range-->
							<h2>Price Range</h2>
							<div class="well">
								 <input type="text" class="span2" value="" data-slider-min="0" data-slider-max="600" data-slider-step="5" data-slider-value="[250,450]" id="sl2" ><br />
								 <b>$ 0</b> <b class="pull-right">$ 600</b>
							</div>
						</div><!--/price-range-->
						
					</div>
				</div>
				
				<div class="col-sm-9 padding-right">
					<div class="features_items"><!--features_items-->
						<h2 class="title text-center">Features Items</h2>
                        <?php
                        $dbconn = Connection::getPDO();
                        if (isset($_GET['cat'])) {

                            $tmp_cids = $proxy -> get_subcategories(intval($_GET['cat']));
                            $queue = array();
                            $cids = array();
                            while ($tmp_cids != null) {
                                foreach ($tmp_cids as $row) {
                                    array_push($cids, intval($row['id']));
                                    array_push($queue, intval($row['id']));
                                }
                                $tmp_cids = $proxy -> get_subcategories(array_pop($queue));
                            }
                            array_push($cids, intval($_GET['cat']));
                            //$cids = to_pg_array($cids);
                            //$cids[0] = '(';
                            //$cids[strlen($cids)-1] = ')';

                            if (isset($_GET['brd'])) {

                                $stmt = $dbconn -> prepare('select * from shop.products where bid = :bid and cid in (' . implode(',', $cids) . ')');
                                $stmt -> execute(['bid' => intval($_GET['brd'])]);
                                $data = $stmt -> fetchAll();

                            }else {

                                $stmt = $dbconn -> prepare('select * from shop.products where cid in (' . implode(',', $cids) . ')'); // Input is safe based on how $cids variable is created
                                $stmt -> execute(); // Using parameter substitution here causes errors
                                $data = $stmt -> fetchAll();

                            }

                        }elseif (isset($_GET['brd'])) {

                            $stmt = $dbconn -> prepare('select * from shop.products where bid = :bid');
                            $stmt -> execute(['bid' => intval($_GET['brd'])]);
                            $data = $stmt -> fetchAll();

                        }else {

                            $stmt = $dbconn -> prepare('select * from shop.products');
                            $stmt -> execute();
                            $data = $stmt -> fetchAll();

                        }
                        foreach ($data as $product) {
                            echo '
                            <div class="col-sm-4">
						    	<div class="product-image-wrapper">
						    		<div class="single-products">
						    			<div class="productinfo text-center">
						    				<img src="images/shop/product8.jpg" alt="" />
						    				<h2 class="price">' . $product['price'] . '</h2>
						    				<p>' . $product['name'] . '</p> 
						    				<p hidden class="id">' . $product['id'] . '</p>
						    				<a onclick="add_to_cart(this);" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
						    			</div>
						    			<img src="images/home/sale.png" class="new" alt="" />
						    		</div>
						    	</div>
						    </div>';
                        }
                        ?>

						<div class="col-sm-4">
							<div class="product-image-wrapper">
								<div class="single-products">
									<div class="productinfo text-center">
										<img src="images/home/product3.jpg" alt="" />
										<h2>$56</h2>
										<p>HTML placeholder</p>
										<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
									</div>
								</div>
							</div>
						</div>

						
						<ul class="pagination">
							<li class="active"><a href="">1</a></li>
							<li><a href="">2</a></li>
							<li><a href="">3</a></li>
							<li><a href="">&raquo;</a></li>
						</ul>
					</div><!--features_items-->
				</div>
			</div>
		</div>
	</section>

  
    <script src="js/jquery.js"></script>
	<script src="js/price-range.js"></script>
    <script src="js/jquery.scrollUp.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/main.js"></script>
    <script src="js/cart.js"></script>
</body>
</html>