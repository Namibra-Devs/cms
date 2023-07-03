<?php require_once('./inc/header.php'); ?>

<section class="content-header">
  <h1>Dashboard</h1>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_user");
$statement->execute();
$total_user = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_category");
$statement->execute();
$total_category = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_blogs");
$statement->execute();
$total_blogs = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_photo");
$statement->execute();
$total_photo = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_team_member");
$statement->execute();
$total_team_member = $statement->rowCount();
?>

<section class="content">

  <div class="row">
    <div class="col-lg-3">
      <div class="panel panel-info">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-6">
              <i class="fa fa-rss fa-5x"></i>
            </div>
            <div class="col-xs-6 text-right">
              <p class="announcement-heading"><?php echo $total_user; ?></p>
              <p class="announcement-text"><strong>Users</strong></p>
            </div>
          </div>
        </div>
        <a href="#">
          <div class="panel-footer announcement-bottom">
            <div class="row">
              <div class="col-xs-6">
                View
              </div>
              <div class="col-xs-6 text-right">
                <i class="fa fa-arrow-circle-right"></i>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>


    <div class="col-lg-3">
      <div class="panel panel-warning">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-6">
              <i class="fa fa-tags fa-5x"></i>
            </div>
            <div class="col-xs-6 text-right">
              <p class="announcement-heading"><?php echo $total_category; ?></p>
              <p class="announcement-text"><strong>Categories</strong></p>
            </div>
          </div>
        </div>
        <a href="category.php">
          <div class="panel-footer announcement-bottom">
            <div class="row">
              <div class="col-xs-6">
                View
              </div>
              <div class="col-xs-6 text-right">
                <i class="fa fa-arrow-circle-right"></i>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>


    <div class="col-lg-3">
      <div class="panel panel-success">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-6">
              <i class="fa fa-rss fa-5x"></i>
            </div>
            <div class="col-xs-6 text-right">
              <p class="announcement-heading"><?php echo $total_blogs; ?></p>
              <p class="announcement-text"><strong>Blogs</strong></p>
            </div>
          </div>
        </div>
        <a href="blogs.php">
          <div class="panel-footer announcement-bottom">
            <div class="row">
              <div class="col-xs-6">
                View
              </div>
              <div class="col-xs-6 text-right">
                <i class="fa fa-arrow-circle-right"></i>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="col-lg-3">
      <div class="panel panel-warning">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-6">
              <i class="fa fa-picture-o fa-5x"></i>
            </div>
            <div class="col-xs-6 text-right">
              <p class="announcement-heading"><?php echo $total_photo; ?></p>
              <p class="announcement-text"><strong>Photos</strong></p>
            </div>
          </div>
        </div>
        <a href="photo.php">
          <div class="panel-footer announcement-bottom">
            <div class="row">
              <div class="col-xs-6">
                View
              </div>
              <div class="col-xs-6 text-right">
                <i class="fa fa-arrow-circle-right"></i>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="col-lg-3">
      <div class="panel panel-success">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-6">
              <i class="fa fa-users fa-5x"></i>
            </div>
            <div class="col-xs-6 text-right">
              <p class="announcement-heading"><?php echo $total_team_member; ?></p>
              <p class="announcement-text"><strong>Team members</strong></p>
            </div>
          </div>
        </div>
        <a href="team-member.php">
          <div class="panel-footer announcement-bottom">
            <div class="row">
              <div class="col-xs-6">
                View
              </div>
              <div class="col-xs-6 text-right">
                <i class="fa fa-arrow-circle-right"></i>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>


</section>

<?php require_once('./inc/footer.php'); ?>