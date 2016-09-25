<?php
$section = $this->config->item('section');
$user_role = $this->session->userdata('role');
$user = $this->session->userdata('username');
$user = ucfirst($user);
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Admin - <?php echo $setting->meta_title; ?></title>
        <meta name="keyword" content="<?php echo $setting->meta_keyword; ?>">
        <meta name="description" content="<?php echo $setting->meta_description; ?>">
        <meta name="viewport" content="width=device-width">
        <base href="<?php echo base_url(); ?>">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">

        <link rel="stylesheet" href="assets/css/demo_table.css">
        <link rel="stylesheet" href="assets/css/hover.css">
        <link rel="stylesheet" href="assets/css/admin.css">
        <link rel="stylesheet" href="assets/css/admin-media.css">

        <script src="assets/js/vendor/jquery-1.10.1.min.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script src="assets/js/vendor/bootstrap.min.js"></script>

        <script type="text/javascript" src="assets/tinymce/tinymce.min.js"></script>
        <script type="text/javascript" src="assets/js/vendor/jquery.dataTables.min.js"></script>
        <script src="assets/js/admin.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <div class="template-wrapper">
            <div class="container">
                <header>
                    <nav class="menu clearfix">
                        <div class="row">
                            <div class="col-xs-5 col-sm-6 col-md-3">
                                <h1 class="title"><?php echo $setting->site_name; ?></h1>
                            </div>
                            <div class="col-visible-md col-visible-lg col-md-4">
                            </div>
                            <div class="col-xs-7 col-sm-6 col-md-5">
                                <ul class="top-menu clearfix">
                                    <li><a href="dashboard/video/feature/home"><span class="fa fa-dashboard"></span>&nbsp;&nbsp;Home Page Video</a></li>                  
                                    <li><a href="dashboard/video/feature/feature"><span class="fa fa-star"></span>&nbsp;&nbsp;Featured Video</a></li>                  
                                    <!--<li><a href="dashboard/manage_content/"><span class="fa fa-dashboard"></span>&nbsp;&nbsp;Dashboard</a></li>-->                  
                                    <li><li><a href="dashboard/login/logout"><i class="fa fa-power-off"></i>&nbsp;&nbsp;Logout</a></li></li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </header>
                <div class="template-wrapper-2">
                    <div class="row">
                        <div class="col-md-1">
                            <div class="left-nav">
                                <div class="side-bar-profile">
                                    
                                    <img src="assets/uploads/profile/guy-5.jpg" class="img-circle">
                                    <div class="name"><?php echo $user ?></div>
                                    <div class="role"><small><?php echo $user_role ?></small></div>
                                </div>
                                <ul class="side-nav">
                                    <li class="<?php echo ($select == 'change_password')?'active':''; ?>">
                                        <a href="dashboard/user/change_password/">
                                            <span class="glyphicon glyphicon-user"></span>
                                            <p>Change <br> Password</p>
                                        </a>
                                    </li>


                                    <?php
                                    foreach ($section as $key => $val)
                                    {
                                        ?>
                                        <li class="<?php echo ($select == $key) ? 'active' : ''; ?>">
                                            <a href="dashboard/manage_content/view/<?php echo $key; ?>">
                                                <span class="<?php echo $val; ?>"></span>
                                                <p class="text-center">Manage <br><?php echo $key;  ?></p>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li class="<?php echo ($select == 'video') ? 'active' : ''; ?>">
                                        <a href="dashboard/video">
                                            <span class="glyphicon glyphicon-film"></span>
                                            <p>Video</p>
                                        </a>
                                    </li>
                                    <li class="<?php echo ($select == 'setting') ? 'active' : ''; ?>">
                                        <a href="dashboard/setting">
                                            <span class="glyphicon glyphicon-cog"></span>
                                            <p>Setting</p>
                                        </a>
                                    </li>
                                </ul>
                            </div><!-- /left-nav -->
                        </div>
                        <div class="col-md-11">
                            <?php
                            $error = $this->session->flashdata('error');
                            $success = $this->session->flashdata('success');
                            if (!empty($error))
                            {
                                ?>
                                <div class="alert alert-warning alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <strong>Warning! </strong> <?php echo $error; ?>
                                </div>
                                <?php
                            }
                            if (!empty($success))
                            {
                                ?>
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <strong><span class="fa fa-check-circle"></span></strong> <?php echo $success; ?>
                                </div>
                                <?php
                            }
                            $this->load->view($page);
                            ?>

                        </div>
                    </div>

                    <footer>
                        <div class="copyright text-center">Copyright &copy; <a href="http://www.ktmfreelancer.com" target="_blank">Ktmfreelancer Pvt Ltd.</a></div>
                    </footer>
                </div>
            </div> <!-- /container -->
        </div><!-- /template-wrapper -->
    </body>
</html>
