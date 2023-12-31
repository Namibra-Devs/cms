<?php require_once('./inc/header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

    if(empty($_POST['event_category_name'])) {
        $valid = 0;
        $error_message .= "event_category Name can not be empty<br>";
    } else {
		// Duplicate event_category checking
    	// current event_category name that is in the database
    	$statement = $pdo->prepare("SELECT * FROM tbl_event_category WHERE event_category_id=?");
		$statement->execute(array($_REQUEST['id']));
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			$current_event_category_name = $row['event_category_name'];
		}

		$statement = $pdo->prepare("SELECT * FROM tbl_event_category WHERE event_category_name=? and event_category_name!=?");
    	$statement->execute(array($_POST['event_category_name'],$current_event_category_name));
    	$total = $statement->rowCount();							
    	if($total) {
    		$valid = 0;
        	$error_message .= 'event_category name already exists<br>';
    	}
    }

    if($valid == 1) {

    	if($_POST['event_category_slug'] == '') {
    		// generate slug
    		$temp_string = strtolower($_POST['event_category_name']);
    		$event_category_slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);;
    	} else {
    		$temp_string = strtolower($_POST['event_category_slug']);
    		$event_category_slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);
    	}

    	// if slug already exists, then rename it
		$statement = $pdo->prepare("SELECT * FROM tbl_event_category WHERE event_category_slug=? AND event_category_name!=?");
		$statement->execute(array($event_category_slug,$current_event_category_name));
		$total = $statement->rowCount();
		if($total) {
			$event_category_slug = $event_category_slug.'-1';
		}
    	
		// updating into the database
		$statement = $pdo->prepare("UPDATE tbl_event_category SET event_category_name=?, event_category_slug=?, meta_title=?, meta_keyword=?, meta_description=? WHERE event_category_id=?");
		$statement->execute(array($_POST['event_category_name'],$event_category_slug,$_POST['meta_title'],$_POST['meta_keyword'],$_POST['meta_description'],$_REQUEST['id']));

    	$success_message = 'event_category is updated successfully.';
    }
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_event_category WHERE event_category_id=?");
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
		<h1>Edit event_category</h1>
	</div>
	<div class="content-header-right">
		<a href="event-category.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>


<?php							
foreach ($result as $row) {
	$event_category_name = $row['event_category_name'];
	$event_category_slug = $row['event_category_slug'];
	$meta_title = $row['meta_title'];
	$meta_keyword = $row['meta_keyword'];
	$meta_description = $row['meta_description'];
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
                    <label for="" class="col-sm-2 control-label">event_category Name <span>*</span></label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="event_category_name" value="<?php echo $event_category_name; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">event_category Slug</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="event_category_slug" value="<?php echo $event_category_slug; ?>">
                    </div>
                </div>
                <h3 class="seo-info">SEO Information</h3>
                <div class="form-group">
					<label for="" class="col-sm-2 control-label">Meta Title </label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="meta_title" value="<?php echo $meta_title; ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 control-label">Meta Keywords </label>
					<div class="col-sm-9">
						<textarea class="form-control" name="meta_keyword" style="height:100px;"><?php echo $meta_keyword; ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 control-label">Meta Description </label>
					<div class="col-sm-9">
						<textarea class="form-control" name="meta_description" style="height:100px;"><?php echo $meta_description; ?></textarea>
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