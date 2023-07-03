<?php require_once('./inc/header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

	if(empty($_POST['event_title'])) {
		$valid = 0;
		$error_message .= 'event title can not be empty<br>';
	} else {
		// Duplicate Checking
    	$statement = $pdo->prepare("SELECT * FROM tbl_events WHERE event_title=?");
    	$statement->execute(array($_POST['event_title']));
    	$total = $statement->rowCount();
    	if($total) {
    		$valid = 0;
        	$error_message .= "event title already exists<br>";
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

	if(empty($_POST['event_venue'])) {
		$valid = 0;
		$error_message .= 'event Venue can not be empty<br>';
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


    if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' && $ext!='JPG' && $ext!='PNG' && $ext!='JPEG' && $ext!='GIF' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file<br>';
        }
    }
	

	if($valid == 1) {

		// getting auto increment id for photo renaming
		$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_events'");
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row) {
			$ai_id=$row[10];
		}

		if($_POST['event_slug'] == '') {
    		// generate slug
    		$temp_string = strtolower($_POST['event_title']);
    		$event_slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);
    	} else {
    		$temp_string = strtolower($_POST['event_slug']);
    		$event_slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);
    	}

    	// if slug already exists, then rename it
		$statement = $pdo->prepare("SELECT * FROM tbl_events WHERE event_slug=?");
		$statement->execute(array($event_slug));
		$total = $statement->rowCount();
		if($total) {
			$event_slug = $event_slug.'-1';
		}

		if($path=='') {
			// When no photo will be selected
			$statement = $pdo->prepare("INSERT INTO tbl_events (event_title,event_slug,event_content,event_content_short,event_venue,event_date,event_link,photo,event_category_id,total_view,meta_title,meta_keyword,meta_description) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$statement->execute(array($_POST['event_title'],$event_slug,$_POST['event_content'],$_POST['event_content_short'],$_POST['event_venue'],$_POST['event_date'],$_POST['event_link'],'',$_POST['event_category_id'],0,$_POST['meta_title'],$_POST['meta_keyword'],$_POST['meta_description']));
		} else {
    		// uploading the photo into the main location and giving it a final name
    		$final_name = 'event-'.$ai_id.'.'.$ext;
            move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );

            $statement = $pdo->prepare("INSERT INTO tbl_events (event_title,event_slug,event_content,event_content_short,event_venue,event_date,event_link,photo,event_category_id,total_view,meta_title,meta_keyword,meta_description) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$statement->execute(array($_POST['event_title'],$event_slug,$_POST['event_content'],$_POST['event_content_short'],$_POST['event_venue'],$_POST['event_date'],$_POST['event_link'],$final_name,$_POST['event_category_id'],0,$_POST['meta_title'],$_POST['meta_keyword'],$_POST['meta_description']));
		}
	
		$success_message = 'event is added successfully!';
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add event</h1>
	</div>
	<div class="content-header-right">
		<a href="events.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>


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
								<input type="text" class="form-control" name="event_title" placeholder="Example: event Headline">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">event Slug </label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="event_slug" placeholder="Example: event-headline">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">event Content <span>*</span></label>
							<div class="col-sm-8">
								<textarea class="form-control editor" name="event_content"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">event Content (Short) <span>*</span></label>
							<div class="col-sm-8">
								<textarea class="form-control" name="event_content_short" style="height:100px;"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">event Venue <span>*</span></label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="event_venue" placeholder="Example: School Auditorium, Ghana">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">event Link <span>*</span></label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="event_link" placeholder="copy link from eventbrite and paste here">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">event Date Date <span>*</span></label>
							<div class="col-sm-2">
								<input type="text" class="form-control" name="event_date" id="datepicker" value="<?php echo date('d-m-Y'); ?>">(Format: dd-mm-yy)
							</div>
						</div>
						<div class="form-group">
				            <label for="" class="col-sm-3 control-label">Featured Photo</label>
				            <div class="col-sm-6" style="padding-top:6px;">
				                <input type="file" name="photo">
				            </div>
				        </div>
						<div class="form-group">
				            <label for="" class="col-sm-3 control-label">Select event_category <span>*</span></label>
				            <div class="col-sm-3">
				            	<select class="form-control select2" name="event_category_id">
				            		<option value="">Select a event_category</option>
				            		<?php
						            	$i=0;
						            	$statement = $pdo->prepare("SELECT * FROM tbl_event_category ORDER BY event_category_name ASC");
						            	$statement->execute();
						            	$result = $statement->fetchAll();
						            	foreach ($result as $row) {
						            		?>
											<option value="<?php echo $row['event_category_id']; ?>"><?php echo $row['event_category_name']; ?></option>
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
								<input type="text" class="form-control" name="meta_title">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Meta Keywords </label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="meta_keyword">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Meta Description </label>
							<div class="col-sm-8">
								<textarea class="form-control" name="meta_description" style="height:200px;"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>

</section>

<?php require_once('./inc/footer.php'); ?>