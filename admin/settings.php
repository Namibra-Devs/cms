<?php require_once('./inc/header.php'); ?>

<?php
if (isset($_POST['form1'])) {
    $valid = 1;

    $path = $_FILES['photo_logo']['name'];
    $path_tmp = $_FILES['photo_logo']['tmp_name'];

    if ($path == '') {
        $valid = 0;
        $error_message .= 'You must have to select a photo<br>';
    } else {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $file_name = basename($path, '.' . $ext);
        if ($ext != 'jpg' && $ext != 'png' && $ext != 'jpeg' && $ext != 'gif') {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file<br>';
        }
    }

    if ($valid == 1) {
        // removing the existing photo
        $statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $logo = $row['logo'];
            unlink('../assets/uploads/' . $logo);
        }

        // updating the data
        $final_name = 'logo' . '.' . $ext;
        move_uploaded_file($path_tmp, '../assets/uploads/' . $final_name);

        // updating the database
        $statement = $pdo->prepare("UPDATE tbl_settings SET logo=? WHERE id=1");
        $statement->execute(array($final_name));

        $success_message = 'Logo is updated successfully.';
    }
}

if (isset($_POST['form2'])) {
    $valid = 1;

    $path = $_FILES['photo_favicon']['name'];
    $path_tmp = $_FILES['photo_favicon']['tmp_name'];

    if ($path == '') {
        $valid = 0;
        $error_message .= 'You must have to select a photo<br>';
    } else {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $file_name = basename($path, '.' . $ext);
        if ($ext != 'jpg' && $ext != 'png' && $ext != 'jpeg' && $ext != 'gif') {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file<br>';
        }
    }

    if ($valid == 1) {
        // removing the existing photo
        $statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $favicon = $row['favicon'];
            unlink('../assets/uploads/' . $favicon);
        }

        // updating the data
        $final_name = 'favicon' . '.' . $ext;
        move_uploaded_file($path_tmp, '../assets/uploads/' . $final_name);

        // updating the database
        $statement = $pdo->prepare("UPDATE tbl_settings SET favicon=? WHERE id=1");
        $statement->execute(array($final_name));

        $success_message = 'Favicon is updated successfully.';
    }
}

if (isset($_POST['form3'])) {

    // updating the database
    $statement = $pdo->prepare("UPDATE tbl_settings SET footer_about=?, footer_copyright=?, contact_address=?, contact_email=?, contact_phone=?, contact_fax=?, contact_map_iframe=? WHERE id=1");
    $statement->execute(array($_POST['footer_about'], $_POST['footer_copyright'], $_POST['contact_address'], $_POST['contact_email'], $_POST['contact_phone'], $_POST['contact_fax'], $_POST['contact_map_iframe']));

    $success_message = 'General content settings is updated successfully.';
}

if (isset($_POST['form_email'])) {
    $statement = $pdo->prepare("UPDATE tbl_setting_email SET 
                                send_email_from=?,
                                receive_email_to=?,
                                smtp_host=?,
                                smtp_port=?,
                                smtp_username=?,
                                smtp_password=?

                            WHERE id=1");
    $statement->execute(array(
        $_POST['send_email_from'],
        $_POST['receive_email_to'],
        $_POST['smtp_host'],
        $_POST['smtp_port'],
        $_POST['smtp_username'],
        $_POST['smtp_password']
    ));

    $success_message = 'Email settings is updated successfully.';
}


if (isset($_POST['form6'])) {
    // updating the database
    $statement = $pdo->prepare("UPDATE tbl_settings SET meta_title_home=?, meta_keyword_home=?, meta_description_home=? WHERE id=1");
    $statement->execute(array($_POST['meta_title_home'], $_POST['meta_keyword_home'], $_POST['meta_description_home']));

    $success_message = 'Home Meta settings is updated successfully.';
}

if (isset($_POST['form_other'])) {
    // updating the database
    $statement = $pdo->prepare("UPDATE tbl_settings SET mod_rewrite=?,preloader=?,active_editor=?,website_name=? WHERE id=1");
    $statement->execute(array($_POST['mod_rewrite'], $_POST['preloader'], $_POST['active_editor'], $_POST['website_name']));

    $success_message = 'Other settings is updated successfully.';
}
if (isset($_POST['form_newsletter'])) {

    $valid = 1;

    $path = $_FILES['newsletter_photo']['name'];
    $path_tmp = $_FILES['newsletter_photo']['tmp_name'];

    if ($path != '') {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $file_name = basename($path, '.' . $ext);
        if ($ext != 'jpg' && $ext != 'png' && $ext != 'jpeg' && $ext != 'gif') {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file<br>';
        }
    }

    if ($valid == 1) {

        if ($path != '') {
            // removing the existing photo
            $statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $newsletter_photo = $row['newsletter_photo'];
                unlink('../assets/uploads/' . $newsletter_photo);
            }

            // updating the data
            $final_name = 'newsletter' . '.' . $ext;
            move_uploaded_file($path_tmp, '../assets/uploads/' . $final_name);

            // updating the database
            $statement = $pdo->prepare("UPDATE tbl_settings SET newsletter_title=?,newsletter_text=?,newsletter_photo=?,newsletter_status=? WHERE id=1");
            $statement->execute(array($_POST['newsletter_title'], $_POST['newsletter_text'], $final_name, $_POST['newsletter_status']));
        } else {
            // updating the database
            $statement = $pdo->prepare("UPDATE tbl_settings SET newsletter_title=?,newsletter_text=?,newsletter_status=? WHERE id=1");
            $statement->execute(array($_POST['newsletter_title'], $_POST['newsletter_text'], $_POST['newsletter_status']));
        }

        $success_message = 'Newsletter Data is updated successfully.';
    }
}


// update about section
if (isset($_POST['form_home_about'])) {
    $valid = 1;

    $path = $_FILES['home_about_img']['name'];
    $path_tmp = $_FILES['home_about_img']['tmp_name'];

    if ($path != '') {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $file_name = basename($path, '.' . $ext);
        if ($ext != 'jpg' && $ext != 'png' && $ext != 'jpeg' && $ext != 'gif') {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file<br>';
        }
    }

    if ($valid == 1) {

        if ($path != '') {
            // removing the existing photo
            $statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $home_about_img = $row['home_about_img'];
                unlink('../assets/uploads/' . $home_about_img);
            }

            // updating the data
            $final_name = 'home_about_img' . '.' . $ext;
            move_uploaded_file($path_tmp, '../assets/uploads/' . $final_name);

            // updating the database
            $statement = $pdo->prepare("UPDATE tbl_settings SET home_about_title=?, home_about_img=?, home_about_content=? WHERE id=1");
            $statement->execute(array($_POST['home_about_title'], $final_name, $_POST['home_about_content']));
        }else {
            $statement = $pdo->prepare("UPDATE tbl_settings SET home_about_title=?, home_about_content=? WHERE id=1");
            $statement->execute(array($_POST['home_about_title'], $_POST['home_about_content']));
        }
            $success_message = 'About Section is updated successfully.';
        
    }
}

// check whether service section is to be updated
if (isset($_POST['form_home_service'])) {
    $statement = $pdo->prepare("UPDATE tbl_settings SET home_title_service=?, home_subtitle_service=?, home_status_service=? WHERE id=1");
    $statement->execute(array($_POST['home_title_service'], $_POST['home_subtitle_service'], $_POST['home_status_service']));

    $success_message = 'Service Section is updated successfully.';
}

if (isset($_POST['form_home_team_member'])) {
    $statement = $pdo->prepare("UPDATE tbl_settings SET home_title_team_member=?, home_subtitle_team_member=?, home_status_team_member=? WHERE id=1");
    $statement->execute(array($_POST['home_title_team_member'], $_POST['home_subtitle_team_member'], $_POST['home_status_team_member']));

    $success_message = 'Team Member Section is updated successfully.';
}

if (isset($_POST['form_home_counter'])) {

    $valid = 1;

    $path = $_FILES['counter_photo']['name'];
    $path_tmp = $_FILES['counter_photo']['tmp_name'];

    if ($path != '') {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $file_name = basename($path, '.' . $ext);
        if ($ext != 'jpg' && $ext != 'png' && $ext != 'jpeg' && $ext != 'gif') {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file<br>';
        }
    }

    if ($valid == 1) {

        if ($path != '') {
            // removing the existing photo
            $statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $counter_photo = $row['counter_photo'];
                unlink('../assets/uploads/' . $counter_photo);
            }

            // updating the data
            $final_name = 'counter' . '.' . $ext;
            move_uploaded_file($path_tmp, '../assets/uploads/' . $final_name);

            // updating the database
            $statement = $pdo->prepare("UPDATE tbl_settings SET counter_1_title=?, counter_1_value=?, counter_2_title=?, counter_2_value=?, counter_3_title=?, counter_3_value=? counter_photo=?, counter_status=? WHERE id=1");
            $statement->execute(array($_POST['counter_1_title'], $_POST['counter_1_value'], $_POST['counter_2_title'], $_POST['counter_2_value'], $_POST['counter_3_title'], $_POST['counter_3_value'], $final_name, $_POST['counter_status']));
        } else {
            // updating the database
            $statement = $pdo->prepare("UPDATE tbl_settings SET counter_1_title=?, counter_1_value=?, counter_2_title=?, counter_2_value=?, counter_3_title=?, counter_3_value=?, counter_status=? WHERE id=1");
            $statement->execute(array($_POST['counter_1_title'], $_POST['counter_1_value'], $_POST['counter_2_title'], $_POST['counter_2_value'], $_POST['counter_3_title'], $_POST['counter_3_value'], $_POST['counter_status']));
        }

        $success_message = 'Counter Data is updated successfully.';
    }
}

if (isset($_POST['form_color'])) {
    $statement = $pdo->prepare("UPDATE tbl_settings SET color=? WHERE id=1");
    $statement->execute(array($_POST['color']));

    $success_message = 'Color settings is updated successfully.';
}
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Settings</h1>
    </div>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $logo                        = $row['logo'];
    $favicon                     = $row['favicon'];
    $footer_about                = $row['footer_about'];
    $footer_copyright            = $row['footer_copyright'];
    $contact_address             = $row['contact_address'];
    $contact_email               = $row['contact_email'];
    $contact_phone               = $row['contact_phone'];
    $contact_fax                 = $row['contact_fax'];
    $contact_map_iframe          = $row['contact_map_iframe'];
    $meta_title_home             = $row['meta_title_home'];
    $meta_keyword_home           = $row['meta_keyword_home'];
    $meta_description_home       = $row['meta_description_home'];
    $home_about_title            = $row['home_about_title'];
	$home_about_img              = $row['home_about_img'];
	$home_about_content          = $row['home_about_content'];
    $home_title_service          = $row['home_title_service'];
    $home_subtitle_service       = $row['home_subtitle_service'];
    $home_status_service         = $row['home_status_service'];
    $home_title_team_member      = $row['home_title_team_member'];
    $home_subtitle_team_member   = $row['home_subtitle_team_member'];
    $home_status_team_member     = $row['home_status_team_member'];
    $mod_rewrite                 = $row['mod_rewrite'];

    $counter_1_title             = $row['counter_1_title'];
    $counter_1_value             = $row['counter_1_value'];
    $counter_2_title             = $row['counter_2_title'];
    $counter_2_value             = $row['counter_2_value'];
    $counter_3_title             = $row['counter_3_title'];
    $counter_3_value             = $row['counter_3_value'];
    $color                       = $row['color'];
    $preloader                   = $row['preloader'];
    $active_editor               = $row['active_editor'];
    $website_name                = $row['website_name'];
}

$statement = $pdo->prepare("SELECT * FROM tbl_setting_email WHERE id=1");
$statement->execute();
$result = $statement->fetchAll();
foreach ($result as $row) {
    $send_email_from  = $row['send_email_from'];
    $receive_email_to = $row['receive_email_to'];
    $smtp_host        = $row['smtp_host'];
    $smtp_port        = $row['smtp_port'];
    $smtp_username    = $row['smtp_username'];
    $smtp_password    = $row['smtp_password'];
}
?>


<section class="content" style="min-height:auto;margin-bottom: -30px;">
    <div class="row">
        <div class="col-md-12">
            <?php if ($error_message) : ?>
                <div class="callout callout-danger">

                    <p>
                        <?php echo $error_message; ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php if ($success_message) : ?>
                <div class="callout callout-success">

                    <p><?php echo $success_message; ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="content">

    <div class="row">
        <div class="col-md-12">

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab">Logo</a></li>
                    <li><a href="#tab_2" data-toggle="tab">Favicon</a></li>
                    <li><a href="#tab_3" data-toggle="tab">General Content</a></li>
                    <li><a href="#tab_4" data-toggle="tab">Email Settings</a></li>
                    <li><a href="#tab_6" data-toggle="tab">Home Page</a></li>
                    <li><a href="#tab_8" data-toggle="tab">Other</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">


                        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                            <div class="box box-info">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Existing Photo</label>
                                        <div class="col-sm-6" style="padding-top:6px;">
                                            <img src="../assets/uploads/<?php echo $logo; ?>" class="existing-photo" style="height:80px;">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">New Photo</label>
                                        <div class="col-sm-6" style="padding-top:6px;">
                                            <input type="file" name="photo_logo">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label"></label>
                                        <div class="col-sm-6">
                                            <button type="submit" class="btn btn-success pull-left" name="form1">Update Logo</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>




                    </div>
                    <div class="tab-pane" id="tab_2">

                        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                            <div class="box box-info">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Existing Photo</label>
                                        <div class="col-sm-6" style="padding-top:6px;">
                                            <img src="../assets/uploads/<?php echo $favicon; ?>" class="existing-photo" style="height:40px;">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">New Photo</label>
                                        <div class="col-sm-6" style="padding-top:6px;">
                                            <input type="file" name="photo_favicon">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label"></label>
                                        <div class="col-sm-6">
                                            <button type="submit" class="btn btn-success pull-left" name="form2">Update Favicon</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>


                    </div>
                    <div class="tab-pane" id="tab_3">

                        <form class="form-horizontal" action="" method="post">
                            <div class="box box-info">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Footer - About Us </label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control editor" name="footer_about"><?php echo $footer_about; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Footer - Copyright </label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" name="footer_copyright" value="<?php echo $footer_copyright; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Contact Address </label>
                                        <div class="col-sm-6">
                                            <textarea class="form-control" name="contact_address" style="height:140px;"><?php echo $contact_address; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Contact Email </label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="contact_email" value="<?php echo $contact_email; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Contact Phone Number </label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="contact_phone" value="<?php echo $contact_phone; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Contact Fax Number </label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="contact_fax" value="<?php echo $contact_fax; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Contact Map iFrame </label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" name="contact_map_iframe" style="height:200px;"><?php echo $contact_map_iframe; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label"></label>
                                        <div class="col-sm-6">
                                            <button type="submit" class="btn btn-success pull-left" name="form3">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>


                    </div>

                    <div class="tab-pane" id="tab_4">

                        <form class="form-horizontal" action="" method="post">
                            <div class="box box-info">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Send Email From</label>
                                        <div class="col-sm-5">
                                            <input class="form-control" type="text" name="send_email_from" value="<?php echo $send_email_from; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Receive Email To</label>
                                        <div class="col-sm-5">
                                            <input class="form-control" type="text" name="receive_email_to" value="<?php echo $receive_email_to; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">SMTP Host</label>
                                        <div class="col-sm-5">
                                            <input class="form-control" type="text" name="smtp_host" value="<?php echo $smtp_host; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">SMTP Port</label>
                                        <div class="col-sm-5">
                                            <input class="form-control" type="text" name="smtp_port" value="<?php echo $smtp_port; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">SMTP Username</label>
                                        <div class="col-sm-5">
                                            <input class="form-control" type="text" name="smtp_username" value="<?php echo $smtp_username; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">SMTP Password</label>
                                        <div class="col-sm-5">
                                            <input class="form-control" type="text" name="smtp_password" value="<?php echo $smtp_password; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label"></label>
                                        <div class="col-sm-6">
                                            <button type="submit" class="btn btn-success pull-left" name="form_email">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>


                    </div>


                    <div class="tab-pane" id="tab_6">

                        <!-- Hero section -->

                        <h3>Meta Section</h3>
                        <form class="form-horizontal" action="" method="post">
                            <div class="box box-info">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Meta Title </label>
                                        <div class="col-sm-9">
                                            <input type="text" name="meta_title_home" class="form-control" value="<?php echo $meta_title_home ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Meta Keyword </label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" name="meta_keyword_home" style="height:100px;"><?php echo $meta_keyword_home ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Meta Description </label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" name="meta_description_home" style="height:200px;"><?php echo $meta_description_home ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label"></label>
                                        <div class="col-sm-6">
                                            <button type="submit" class="btn btn-success pull-left" name="form6">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                <!-- ABOUT SECTION -->
                        <h3>About Section</h3>
                        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                            <div class="box box-info">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Title </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="home_about_title" class="form-control" value="<?php echo $home_about_title; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Content </label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control editor" name="home_about_content"><?php echo $home_about_content ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Existing Photo</label>
                                        <div class="col-sm-6" style="padding-top:6px;">
                                            <img src="../assets/uploads/<?php echo $home_about_img; ?>" class="existing-photo" style="height:80px;">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">New Photo</label>
                                        <div class="col-sm-6" style="padding-top:6px;">
                                            <input type="file" name="home_about_img">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label"></label>
                                        <div class="col-sm-6">
                                            <button type="submit" class="btn btn-success pull-left" name="form_home_about">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>


                        <h3>Counter Section</h3>
                        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                            <div class="box box-info">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Item 1 - Title </label>
                                        <div class="col-sm-3">
                                            <input type="text" name="counter_1_title" class="form-control" value="<?php echo $counter_1_title; ?>">
                                        </div>
                                        <label for="" class="col-sm-2 control-label">Item 1 - Value </label>
                                        <div class="col-sm-2">
                                            <input type="text" name="counter_1_value" class="form-control" value="<?php echo $counter_1_value; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Item 2 - Title </label>
                                        <div class="col-sm-3">
                                            <input type="text" name="counter_2_title" class="form-control" value="<?php echo $counter_2_title; ?>">
                                        </div>
                                        <label for="" class="col-sm-2 control-label">Item 2 - Value </label>
                                        <div class="col-sm-2">
                                            <input type="text" name="counter_2_value" class="form-control" value="<?php echo $counter_2_value; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Item 3 - Title </label>
                                        <div class="col-sm-3">
                                            <input type="text" name="counter_3_title" class="form-control" value="<?php echo $counter_3_title; ?>">
                                        </div>
                                        <label for="" class="col-sm-2 control-label">Item 3 - Value </label>
                                        <div class="col-sm-2">
                                            <input type="text" name="counter_3_value" class="form-control" value="<?php echo $counter_3_value; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label"></label>
                                        <div class="col-sm-6">
                                            <button type="submit" class="btn btn-success pull-left" name="form_home_counter">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>



                    <div class="tab-pane" id="tab_8">
                        <form class="form-horizontal" action="" method="post">
                            <div class="box box-info">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Mod Rewrite </label>
                                        <div class="col-sm-2">
                                            <select name="mod_rewrite" class="form-control" style="width:100%;">
                                                <option value="Off" <?php if ($mod_rewrite == 'Off') {
                                                                        echo 'selected';
                                                                    } ?>>Off</option>
                                                <option value="On" <?php if ($mod_rewrite == 'On') {
                                                                        echo 'selected';
                                                                    } ?>>On</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Preloader </label>
                                        <div class="col-sm-2">
                                            <select name="preloader" class="form-control" style="width:100%;">
                                                <option value="On" <?php if ($preloader == 'On') {
                                                                        echo 'selected';
                                                                    } ?>>On</option>
                                                <option value="Off" <?php if ($preloader == 'Off') {
                                                                        echo 'selected';
                                                                    } ?>>Off</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Active Editor </label>
                                        <div class="col-sm-2">
                                            <select name="active_editor" class="form-control" style="width:100%;">
                                                <option value="Summernote" <?php if ($active_editor == 'Summernote') {
                                                                                echo 'selected';
                                                                            } ?>>Summernote</option>
                                                <option value="Ckeditor" <?php if ($active_editor == 'Ckeditor') {
                                                                                echo 'selected';
                                                                            } ?>>Ckeditor</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Website Name </label>
                                        <div class="col-sm-4">
                                            <input type="text" name="website_name" class="form-control" value="<?php echo $website_name; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label"></label>
                                        <div class="col-sm-6">
                                            <button type="submit" class="btn btn-success pull-left" name="form_other">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once('./inc/footer.php'); ?>