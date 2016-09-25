<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Ktm | Admin</title>
        <meta name="keyword" content="">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <base href="<?php echo base_url(); ?>">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/css/admin.css">
        <link rel="stylesheet" href="assets/css/admin-media.css">

        <script src="assets/js/vendor/jquery-1.10.1.min.js"></script>
        <script src="assets/js/vendor/bootstrap.min.js"></script>
        <script src="assets/js/admin.js"></script>

    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <div class="container">
            <div class="row">
                <div class="col-md-4 login">
                    <h1>Cms Login !</h1>
                    <form action="dashboard/login/login_attempt" method="POST">
                        <label for="username">User Name:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input type="text" id="username" name="username" class="form-control" placeholder="Username" value="<?php echo set_value('username'); ?>" >
                        </div>

                        <label for="password">Password:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-unlock-alt"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Password">
                        </div>

                        <a class="forgot-password" href="">Forgot Password?</a>

                        <div class="row">
                            <div class="col-md-3">
                                <input type="submit" name="btn-submit" class="btn my-btn" value="Login">
                            </div>
                        </div>
                    </form>
                    <div class="text-right">
                        Powered By <b><a calss="" href="http://www.ktmfreelancer.com" target="_blank">ktmfreelancer</a></b>
                    </div>
                    <div>
                        <?php
                        if (isset($msg)) {
                            ?>

                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong>Warning!</strong> <? echo $msg; ?>
                            </div>
                            <?
                        }
                        if (validation_errors()) {
                            ?>

                            <div class="alert alert-warning alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong>Warning!</strong> <? echo validation_errors(); ?>
                            </div>
                        <?php } ?>
                    </div>
                </div><!-- end of login div -->
            </div>
        </div><!-- /container -->
    </body>

</html>

