<?php require_once('./inc/header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

	if(empty($_POST['event_title'])) {
		$valid = 0;
		$error_message .= 'event title can not be empty<br>';
	} else {
		// Duplicate event_category checking
    	// current event title that is in the database
    	$statement = $pdo->prepare("SELECT * FROM tbl_events WHERE event_id=?");
		$statement->execute(array($_REQUEST['id']));
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			$current_event_title = $row['event_title'];
		}

		$statement = $pdo->prepare("SELECT * FROM tbl_events WHERE event_title=? and event_title!=?");
    	$statement->execute(array($_POST['event_title'],$current_event_title));
    	$total = $statement->rowCount();							
    	if($total) {
    		$valid = 0;
        	$error_message .= 'event title already exists<br>';
    	}
	}

	if(empty($_POST['event_content'])) {
		$valid = 0;
		$error_message .= 'event content can not be empty<br>';
	}

	if(empty($_POST['event_content_short'])) {
		$valid = 0;
		$error_message .= 'event content (short) can not be empty<br>';
	}

	if(empty($_POST['event_date'])) {
		$valid = 0;
		$error_message .= 'event publish date can not be empty<br>';
	}

	if(empty($_POST['event_category_id'])) {
		$valid = 0;
		$error_message .= 'You must have to select a event_category<br>';
	}


	$path = $_FILES['photo']['name'];
    $path_tmp = $_FILES['photo']['tmp_name'];

    $previous_photo = $_POST['previous_photo'];

	if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' && $ext!='JPG' && $ext!='PNG' && $ext!='JPEG' && $ext!='GIF' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file<br>';
        }
    }

	if($valid == 1) {

		if($_POST['event_slug'] == '') {
    		// generate slug
    		$temp_string = strtolower($_POST['event_title']);
    		$event_slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);;
    	} else {
    		$temp_string = strtolower($_POST['event_slug']);
    		$event_slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);
    	}

    	// if slug already exists, then rename it
		$statement = $pdo->prepare("SELECT * FROM tbl_events WHERE event_slug=? AND event_title!=?");
		$statement->execute(array($event_slug,$current_event_title));
		$total = $statement->rowCount();
		if($total) {
			$event_slug = $event_slug.'-1';
		}

		// If previous image not found and user do not want to change the photo
	    if($previous_photo == '' && $path == '') {
	    	$statement = $pdo->prepare("UPDATE tbl_events SET event_title=?, event_slug=?, event_content=?, event_content_short=?, event_date=?, event_category_id=?, meta_title=?, meta_keyword=?, meta_description=? WHERE event_id=?");
	    	$statement->execute(array($_POST['event_title'],$event_slug,$_POST['event_content'],$_POST['event_content_short'],$_POST['event_date'],$_POST['event_category_id'],$_POST['meta_title'],$_POST['meta_keyword'],$_POST['meta_description'],$_REQUEST['id']));
	    }

		// If previous image found and user do not want to change the photo
	    if($previous_photo != '' && $path == '') {
	    	$statement = $pdo->prepare("UPDATE tbl_events SET event_title=?, event_slug=?, event_content=?, event_content_short=?, event_date=?, event_category_id=?, meta_title=?, meta_keyword=?, meta_description=? WHERE event_id=?");
	    	$statement->execute(array($_POST['event_title'],$event_slug,$_POST['event_content'],$_POST['event_content_short'],$_POST['event_date'],$_POST['event_category_id'],$_POST['meta_title'],$_POST['meta_keyword'],$_POST['meta_description'],$_REQUEST['id']));
	    }


	    // If previous image not found and user want to change the photo
	    if($previous_photo == '' && $path != '') {

	    	$final_name = 'event-'.$_REQUEST['id'].'.'.$ext;
            move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );

	    	$statement = $pdo->prepare("UPDATE tbl_events SET event_title=?, event_slug=?, event_content=?, event_content_short=?, event_date=?, photo=?, event_category_id=?, meta_title=?, meta_keyword=?, meta_description=? WHERE event_id=?");
	    	$statement->execute(array($_POST['event_title'],$event_slug,$_POST['event_content'],$_POST['event_content_short'],$_POST['event_date'],$final_name,$_POST['event_category_id'],$_POST['meta_title'],$_POST['meta_keyword'],$_POST['meta_description'],$_REQUEST['id']));
	    }

	    
	    // If previous image found and user want to change the photo
		if($previous_photo != '' && $path != '') {

	    	unlink('../assets/uploads/'.$previous_photo);

	    	$final_name = 'event-'.$_REQUEST['id'].'.'.$ext;
            move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );

	    	$statement = $pdo->prepare("UPDATE tbl_events SET event_title=?, event_slug=?, event_content=?, event_content_short=?, event_date=?, photo=?, event_category_id=?, meta_title=?, meta_keyword=?, meta_description=? WHERE event_id=?");
	    	$statement->execute(array($_POST['event_title'],$event_slug,$_POST['event_content'],$_POST['event_content_short'],$_POST['event_date'],$final_name,$_POST['event_category_id'],$_POST['meta_title'],$_POST['meta_keyword'],$_POST['meta_description'],$_REQUEST['id']));
	    }

	    $success_message = 'event is updated successfully!';
	}
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_events WHERE event_id=?");
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
		<h1>Edit event</h1>
	</div>
	<div class="content-header-right">
		<a href="events.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_events WHERE event_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	$event_title         = $row['event_title'];
	$event_slug          = $row['event_slug'];
	$event_content       = $row['event_content'];
	$event_content_short = $row['event_content_short'];
	$event_venue 		 = $row['event_venue'];
	$event_link          = $row['event_link'];
	$event_date          = $row['event_date'];
	$photo               = $row['photo'];
	$event_category_id   = $row['event_category_id'];
	$meta_title          = $row['meta_title'];
	$meta_keyword        = $row['meta_keyword'];
	$meta_description    = $row['meta_description'];
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

			<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
				<div class="box box-info">
					<div class="box-body">
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">event Title <span>*</span></label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="event_title" value="<?php echo $event_title; ?>">
							</div>
						</div>
						<div class="form-group">
		                    <label for="" class="col-sm-3 control-label">event Slug</label>
		                    <div class="col-sm-6">
		                        <input type="text" class="form-control" name="event_slug" value="<?php echo $event_slug; ?>">
		                    </div>
		                </div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">event Content <span>*</span></label>
							<div class="col-sm-8">
								<textarea class="form-control editor" name="event_content"><?php echo $event_content; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">event Content (Short) <span>*</span></label>
							<div class="col-sm-8">
								<textarea class="form-control" name="event_content_short" style="height:100px;"><?php echo $event_content_short; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">event Venue <span>*</span></label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="event_venue" value="<?php echo $event_venue; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">event Link <span>*</span></label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="event_link" value="<?php echo $event_link; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">event Date <span>*</span></label>
							<div class="col-sm-2">
								<input type="text" class="form-control" name="event_date" id="datepicker" value="<?php echo $event_date; ?>">(Format: dd-mm-yy)
							</div>
						</div>
						<div class="form-group">
				            <label for="" class="col-sm-3 control-label">Existing Featured Photo</label>
				            <div class="col-sm-6" style="padding-top:6px;">
				            	<?php
				            	if($photo == '') {
				            		echo 'No photo found';
				            	} else {
				            		echo '<img src="../assets/uploads/'.$photo.'" class="existing-photo" style="width:200px;">';	
				            	}
				            	?>
				                <input type="hidden" name="previous_photo" value="<?php echo $photo; ?>">
				            </div>
				        </div>
						<div class="form-group">
				            <label for="" class="col-sm-3 control-label">Change Featured Photo</label>
				            <div class="col-sm-6" style="padding-top:6px;">
				                <input type="file" name="photo">
				            </div>
				        </div>
						<div class="form-group">
				            <label for="" class="col-sm-3 control-label">Categories <span>*</span></label>
				            <div class="col-sm-3">
				            	<select class="form-control select2" name="event_category_id">
								<?php
				            	$i=0;
				            	$statement = $pdo->prepare("SELECT * FROM tbl_event_category ORDER BY event_category_name ASC");
				            	$statement->execute();
				            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				            	foreach ($result as $row) {
									?>
									<option value="<?php echo $row['event_category_id']; ?>" <?php if($row['event_category_id']==$event_category_id){echo 'selected';} ?>><?php echo $row['event_category_name']; ?></option>
	                                <?php
								}
								?>
								</select>
				            </div>
				        </div>
						<h3 class="seo-info">SEO Information</h3>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Meta Title </label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="meta_title" value="<?php echo $meta_title; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Meta Keywords </label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="meta_keyword" value="<?php echo $meta_keyword; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Meta Description </label>
							<div class="col-sm-8">
								<textarea class="form-control" name="meta_description" style="height:200px;"><?php echo $meta_description; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"></label>
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

<?php require_once('./inc/footer.php'); ?>