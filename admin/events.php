<?php require_once('./inc/header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>View Events</h1>
	</div>
	<div class="content-header-right">
		<a href="event-add.php" class="btn btn-primary btn-sm">Add New</a>
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
								<th>SL</th>
								<th>Thumbnail</th>
								<th width="180">Title</th>
								<th width="280">Short Content</th>
								<th>event_category</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$statement = $pdo->prepare("SELECT

														t1.event_id,
														t1.event_title,
														t1.event_content,
														t1.event_content_short,
														t1.photo,
														t1.event_category_id,
														t1.event_link,

														t2.event_category_id,
														t2.event_category_name

							                           	FROM tbl_events t1
							                           	JOIN tbl_event_category t2
							                           	ON t1.event_category_id = t2.event_category_id

							                           	ORDER BY t1.event_id DESC
							                           	");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
							foreach ($result as $row) {
								$i++;
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td>
										<?php
										if($row['photo'] == '')
										{
											echo '<img src="../assets/uploads/no-photo1.jpg" alt="" style="width:100px;">';
										}
										else
										{
											echo '<img src="../assets/uploads/'.$row['photo'].'" alt="'.$row['event_title'].'" style="width:100px;">';
										}
										?>
									</td>
									<td><?php echo $row['event_title']; ?></td>
									<td><?php echo $row['event_content_short']; ?></td>
									<td>
										<?php echo $row['event_category_name']; ?>
									</td>
									<td>										
										<a href="event-edit.php?id=<?php echo $row['event_id']; ?>" class="btn btn-primary btn-xs">Edit</a>
										<a href="#" class="btn btn-danger btn-xs" data-href="event-delete.php?id=<?php echo $row['event_id']; ?>" data-toggle="modal" data-target="#confirm-delete">Delete</a>  
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


<?php require_once('./inc/footer.php'); ?>