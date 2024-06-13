<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>View Media Photo</h1> 
	</div>
	<div class="content-header-right">
		<a href="media-photo-add.php" class="btn btn-primary btn-sm" target="_blank">Add Media Photo</a>
	</div>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body table-responsive">
					<table id="example1" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th width="50">SL</th>
								<th width="140">Photo</th>
								<th>Title</th>
                                <th>Media Link</th>
								<th width="140">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$statement = $pdo->prepare("SELECT
														*
							                           	FROM tbl_media
							                           	order by id desc
							                           	");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
							foreach ($result as $row) {
								$i++;
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td style="width:150px;"><img src="<?php echo BASE_URL.$row['file_path'].$row['photo']; ?>" alt="<?php echo $row['title']; ?>" style="width:140px;"></td>
									<td><?php echo $row['title']; ?></td>
                                    <td class="code-container">
										<span id='c<?php echo $row['id']; ?>' class="code"><span style="color:#59c053;"><?=BASE_URL.$row['file_path'].$row['photo'];?></span></span>
										<button style="margin-left:5px;float:;" onclick="copyToClipboard(this)" type="button" class="btn btn-info btn-xs">Copy</button>
                                    </td>
									<td>
										<a href="media-photo-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-xs" target="_blank"><i class="fa fa-pencil"></i></a>
										<a href="#" class="btn btn-danger btn-xs" data-href="media-photo-delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a>  
									</td>
								</tr>
								<?php
							}
							?>							
						</tbody>
					</table>
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


<?php require_once('footer.php'); ?>
<script>
function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).parents('.code-container').find('.code span').text()).select();
    document.execCommand("copy");
    $temp.remove();
}
</script>
