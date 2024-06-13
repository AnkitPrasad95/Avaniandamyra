<?php require_once('header.php');

use JasonGrimes\Paginator;

$showRecordPerPage = 10;
if (isset($_GET['page']) && !empty($_GET['page'])) {
	$currentPage = $_GET['page'];
} else {
	$currentPage = 1;
}
$startFrom = ($currentPage - 1) * $showRecordPerPage;

$sql = "SELECT id, `name`, slug FROM `tbl_product_category` order by id asc";
$catList = $pdo->query($sql)->fetchAll(PDO::FETCH_OBJ);

if(isset($_GET['category']) && isset($_GET['product'])) {
	//if(!empty($_GET['category']) && empty($_GET['product'])) {
		$cat = $_GET['category'];
		$sql = "SELECT id, name, slug, parent_category FROM tbl_product_category where slug = ?";
		$statement = $pdo->prepare($sql);
		$statement->execute(array($_GET['category']));
		$catDetails = $statement->fetch(PDO::FETCH_OBJ);
		if(!empty($catDetails)) {
			if($catDetails->parent_category > 0) {
				$cat_ids = $catDetails->parent_category.','.$catDetails->id;
			} else {
				$cat_ids = $catDetails->id;
			}
			$cat_ids;
		}
		
		$product = $_GET['product'];
		$query = "SELECT  id, name, categories, file_path, thumbnail_image, created_at FROM tbl_product_list ";
		if(!empty($_GET['category']) && empty($_GET['product'])) {
			$query .= "where categories LIKE '$cat_ids%'";
		} 
		if(!empty($_GET['product']) && empty($_GET['category'])) {
			$query .= "where name LIKE '$product%'";
		}

		if(!empty($_GET['category']) && !empty($_GET['product'])) {
			$query .= "where (categories LIKE '$cat_ids%' AND name LIKE '%$product%')";
		}
		$statement = $pdo->prepare($query);
		$statement->execute();
		$listProductCount = $statement->rowCount();
		$totalProduct = $listProductCount;
		$urlPattern = '?page=(:num)';
		$paginator = new Paginator($totalProduct, $showRecordPerPage, $currentPage, $urlPattern);
		$query .= "order by created_at desc limit $startFrom, $showRecordPerPage";
		$statement = $pdo->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);

		// echo "<pre>";
		// print_r($result); 
		// echo "</pre>"; die;
	//}
} else {
	$statement = $pdo->prepare("SELECT id FROM tbl_product_list");
	$statement->execute();
	$listProductCount = $statement->rowCount();
	$totalProduct = $listProductCount;
	$urlPattern = '?page=(:num)';
	$paginator = new Paginator($totalProduct, $showRecordPerPage, $currentPage, $urlPattern);
	$statement = $pdo->prepare("SELECT id, name, categories, file_path, thumbnail_image, created_at FROM tbl_product_list order by created_at desc limit $startFrom, $showRecordPerPage");
	$statement->execute();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
}
?>
<section class="content-header">
	<div class="content-header-left">
		<h1>View Product List (<?=$totalProduct;?>)</h1>
	</div>
	<div class="content-header-right">
		<a href="productList-add.php" class="btn btn-primary btn-sm">Add Product</a>
	</div>
</section>

<style>
	.custom-product-deta .heading-title {
		border: 1px solid #000;
		background: #ffff;
		display: inline-block;
		width: 100%;
		font-weight: 600;
		font-size: 13px;
	}

	.custom-product-deta .heading-title>div {
		padding: 10px;
	}

	.custom-product-deta .products-Details {
		position: relative;
		top: -5px;
	}

	.custom-product-deta .products-Details .data>div {
		height: 100%;
		padding: 0px 10px;
	}

	.custom-product-deta .products-Details .data>div:after {
		height: 350px;
		width: 1px;
		background-color: #f4f4f4;
		right: -1px;
		bottom: 0;
		position: absolute;
		top: -50px;
		content: '';
	}

	.custom-product-deta .products-Details .data {
		display: flex !important;
		justify-content: space-between !important;
		align-items: center !important;
		border-bottom: 1px solid #f4f4f4;
		position: relative;
		overflow: hidden;
	}

	.custom-product-deta .products-Details {
		background: #fff;
	}

	.custom-product-deta .products-Details .data:nth-child(even) {
		background-color: #f4f4f4;
	}

	.custom-product-deta .products-Details .data img {
		width: 30px;
		padding: 5px 0px;
		display: inline-block;
		margin-right: 15px;
	}

	.custom-product-deta .products-Details .data span {
		display: inline-block;
	}

	.custom-product-deta .products-Details .data span.img {
		width: 45px;
	}

	.border-right {
		border-right: 1px solid #f4f4f4;
	}

	.border-left {
		border-left: 1px solid #f4f4f4;
	}

	.border-bottom {
		border-bottom: 1px solid #f4f4f4;
	}

	.border-color-dark {
		border-color: #000 !important;
	}

	.pagination-content {
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 2rem 0;
	}

	nav {
		display: block;
	}

	.pagination {
		align-items: center;
		display: flex;
	}

	.pb-3 {
		padding-bottom: 1rem !important;
	}

	.pt-3 {
		padding-top: 1rem !important;
	}

	.mb-0 {
		margin-bottom: 0 !important;
	}

	.rotate-180 {
		transform: rotate(180deg);
	}

	img {
		max-width: 100%;
	}

	img,
	svg {
		vertical-align: middle;
	}

	*,
	::after,
	::before {
		box-sizing: border-box;
	}

	.pagination .page-link.btn.btn-primary {
		display: flex;
		width: auto !important;
		height: 36px;
		align-items: center;
		justify-content: center;
		border-radius: 0 !important;
		font-size: 1.5rem;
		font-weight: bold;
		border: 0px !important;
	}

	ul.pagination li a.page-link:hover {
		color: #fff;
		background-color: #483c32;
		border-color: #483c32;
	}

	.btn-primary:hover,
	.btn-primary:focus,
	.btn-primary:active,
	.btn-primary.active,
	.btn-primary.hover,
	.btn-primary:not(:disabled):not(.disabled):active:focus,
	a.btn-primary:hover,
	a.btn-primary:focus,
	a.btn-primary:active,
	a.btn-primary.active,
	a.btn-primary.hover,
	a.btn-primary:not(:disabled):not(.disabled):active:focus {
		background: #483C32;
		color: #fff !important;
		border: 1px solid #483C32;
		-webkit-box-shadow: none;
		-moz-box-shadow: none;
		box-shadow: none;
	}

	.page-link:hover {
		z-index: 2;
		color: #0a58ca;
		background-color: #e9ecef;
		border-color: #dee2e6;
	}

	.btn-primary:hover {
		color: #fff;
		background-color: #0b5ed7;
		border-color: #0a58ca;
	}

	.select2-container--default .select2-selection--single,
	.select2-selection .select2-selection--single {
		padding: 9px 12px !important;
		height: 40px !important;
	}

	ul.pagination li a.page-link:hover {
		color: #23527c !important;
		background-color: #eee !important;
		border-color: #ddd !important;
	}

	ul.pagination>.active>a:hover {
		background-color: #337ab7 !important;
		border-color: #337ab7 !important;
	}

	.pagination .page-link.btn.btn-primary {
		background: #337ab7 !important;
	}
	.prd-not-found.text-center {
		color: #dd4b39;
		font-size: 20px;
		/* margin-top: 50px; */
	}
</style>

<section class="content">
	<div class="row">
		<form action="" method="get">
			<div class="col-lg-2">
				<label>Categories</label>
				<select class="form-control select2" name="category">
					<option value="">Select category</option>
					<?php if(!empty($catList)) { 
						foreach($catList as $cat){
					?> 
					<option <?php if(isset($_GET['category']) && $_GET['category'] == $cat->slug) { echo "selected"; } ?> value="<?=$cat->slug;?>"><?=$cat->name;?></option>
					<?php } } ?>
				</select>
			</div>
			<div class="col-lg-2">
				<div class="form-group">
					<label for="first_name">Product Name</label>
					<input type="text" class="form-control" id="product_name" value="<?php if(isset($_GET['product'])) { echo $_GET['product']; } ?>" name="product">
				</div>
			</div>
			<div class="col-lg-8">
				<div class="pull-left" style="margin-top: 27px;">
					<button type="submit" class="btn btn-success">Submit</button>
				</div>
			</div>
		</form>


		<div class="col-lg-12">
			<div class="custom-product-deta" id="example1">
				<div class="heading-title">
					<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 border-right border-color-dark">SL</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 border-right border-color-dark">Thumbnail</div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 border-right border-color-dark">Name</div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 border-right border-color-dark">Categories</div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 border-right border-color-dark">Date</div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 ">Action</div>
				</div>

				<div class="products-Details">
					<?php if (!empty($result)) { ?>
						<div class="d-flex">
							<?php
							$i = 1;
							foreach ($result as $row) {
								$categories = $row['categories'];
								if (!empty($categories)) {
									$sql = "SELECT `name` FROM `tbl_product_category` where id IN($categories)";
									$name = $pdo->query($sql)->fetchAll(PDO::FETCH_COLUMN);
									$categories = implode(',', $name);
								} else {
									$categories = '';
								}
							?>
								<div class="data">
									<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center"><?php echo $i++; ?></div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
										<span class="img">
											<?php if (!empty($row['thumbnail_image'])) { ?>
												<img src="<?php echo BASE_URL . $row['file_path'] . $row['thumbnail_image']; ?>" class="img-responsive">
											<?php } else { ?>
												<img src="<?php echo BASE_URL . 'assets/uploads/placeholder.jpg'; ?>" alt="<?php echo $row['name']; ?>" class="img-responsive">
											<?php } ?>
										</span>

									</div>
									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"><?php echo $row['name']; ?></div>
									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"><?php echo $categories; ?></div>
									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"><?php echo date('d-m-Y H:i A', strtotime($row['created_at'])); ?></div>
									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
										<button value="<?php echo $row['id']; ?>" class="btn btn-info btn-xs myForm" title="view"><i class="fa fa-eye"></i></button>
										<a href="productList-add.php?copy_id=<?php echo $row['id']; ?>" title="copy" class="btn btn-success btn-xs"><i class="fa fa-copy"></i></a>
										<a href="productList-edit.php?id=<?php echo $row['id']; ?>" title="Edit" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
										<a href="product-images.php?id=<?php echo $row['id']; ?>" title="Add Product Images" class="btn btn-info btn-xs"><i class="fa fa-upload"></i></a>
										<a href="productList-delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-xs" title="delete"  onclick="return confirm('Are you sure you want to delete this product?');"><i class="fa fa-trash"></i></a>
									</div>
								</div>
							<?php } ?>
						</div>
						<div class="pagination-content pull-right">
							<nav aria-label="navigation">

								<ul class="pagination mb-0 pt-3 pb-3">

									<?php if ($paginator->getPrevUrl()) : ?>
										<li><a class="page-link btn btn-primary btn-animation px-3" href="<?php echo $paginator->getPrevUrl(); if(isset($_GET['category']) && isset($_GET['product'])) { echo '&category='.$_GET['category'].'&product='.$_GET['product']; } ?>">
												<img class=" icon-14 " src="<?= BASE_URL; ?>assets/images/b-arrow-white.svg"></a></li>
									<?php endif; ?>
									<?php foreach ($paginator->getPages() as $page) : ?>
										<?php if ($page['url']) : ?>
											<li <?php echo $page['isCurrent'] ? 'class="active page-item"' : ''; ?> class="page-item">
												<a class="page-link " href="<?php echo $page['url']; if(isset($_GET['category']) && isset($_GET['product'])) { echo '&category='.$_GET['category'].'&product='.$_GET['product']; } ?>">
													<?php echo $page['num']; ?></a>
											</li>
										<?php else : ?>
											<li class="disabled"><span><?php echo $page['num']; ?></span></li>
										<?php endif; ?>
									<?php endforeach; ?>

									<?php if ($paginator->getNextUrl()) : ?>
										<li><a class="page-link btn btn-primary btn-animation px-3" href="<?php echo $paginator->getNextUrl(); if(isset($_GET['category']) && isset($_GET['product'])) { echo '&category='.$_GET['category'].'&product='.$_GET['product']; } ?>">
												<img class="icon-14 rotate-180" src="<?= BASE_URL; ?>assets/images/b-arrow-white.svg"></a></li>
									<?php endif; ?>

								</ul>



							</nav>
						</div>
					<?php } else { ?> 
						<div class="prd-not-found text-center"><p style="padding:100px 0; font-size:40px;"> Product not found </p></div>
					<?php } ?>
				</div>
			</div>
		</div>


	</div>


</section>


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
			</div>
			<div class="modal-body">
				<p>Are you sure want to delete this item?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<a class="btn btn-danger btn-ok">Delete</a>
			</div>
		</div>
	</div>
</div>

<div class="modal fade product_view" id="view-data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Product Data</h4>
			</div>
			<div class="modal-body" id="recievedData">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

			</div>
		</div>
	</div>
</div>


<?php require_once('footer.php'); ?>
<script>
	$("#example1").on("click", ".myForm", function() {
		var id = $(this).val();
		//alert(id);
		$('#view-data').modal('show')
		$.ajax({
			url: 'ajax/getAjaxData.php',
			type: 'post',
			data: {
				product_id: id
			},
			success: function(html) {
				$('#recievedData').html(html);

			}
		});
	});
</script>