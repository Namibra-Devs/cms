<?php require_once('./inc/header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

    if(empty($_POST['performer_category_name'])) {
        $valid = 0;
        $error_message .= "Performer Category Name can not be empty<br>";
    } else {
		// Duplicate Performer Category checking
    	// current Performer Category name that is in the database
    	$statement = $pdo->prepare("SELECT * FROM tbl_performer_category WHERE performer_category_id=?");
		$statement->execute(array($_REQUEST['id']));
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			$current_performer_category_name = $row['performer_category_name'];
		}

		$statement = $pdo->prepare("SELECT * FROM tbl_performer_category WHERE performer_category_name=? and performer_category_name!=?");
    	$statement->execute(array($_POST['performer_category_name'],$current_performer_category_name));
    	$total = $statement->rowCount();							
    	if($total) {
    		$valid = 0;
        	$error_message .= 'Performer Category name already exists<br>';
    	}
    }

    if($valid == 1) {

		// updating into the database
		$statement = $pdo->prepare("UPDATE tbl_performer_category SET performer_category_name=? WHERE performer_category_id=?");
		$statement->execute(array($_POST['performer_category_name'],$_REQUEST['id']));

    	$success_message = 'Performer Category is updated successfully.';
    }
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_performer_category WHERE performer_category_id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Edit Category</h1>
	</div>
	<div class="content-header-right">
		<a href="performer-category.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>

<?php							
foreach ($result as $row) {
	$performer_category_name = $row['performer_category_name'];
}
?>

<section class="content">

  <div class="row">
    <div class="col-md-12">

		<?php if($error_message): ?>
		<div class="callout callout-danger">
			<p>
				<?php echo $error_message; ?>
			</p>
		</div>
		<?php endif; ?>

		<?php if($success_message): ?>
		<div class="callout callout-success">
			<p><?php echo $success_message; ?></p>
		</div>
		<?php endif; ?>

        <form class="form-horizontal" action="" method="post">
	        <div class="box box-info">
	            <div class="box-body">
	                <div class="form-group">
	                    <label for="" class="col-sm-2 control-label">Category Name <span>*</span></label>
	                    <div class="col-sm-4">
	                        <input type="text" class="form-control" name="performer_category_name" value="<?php echo $performer_category_name; ?>" autocomplete="off">
	                    </div>
	                </div>
	                <div class="form-group">
	                	<label for="" class="col-sm-2 control-label"></label>
	                    <div class="col-sm-6">
	                      <button type="submit" class="btn btn-success pull-left" name="form1">Update</button>
	                    </div>
	                </div>
	            </div>
	        </div>
        </form>



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
                Are you sure want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('./inc/footer.php'); ?>