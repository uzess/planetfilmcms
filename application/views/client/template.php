<!DOCTYPE html>
<!--[if lt IE 7]><html class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html class="lt-ie9 lt-ie8 is-ie7"><![endif]-->
<!--[if IE 8]><html class="lt-ie9 is-ie8"><![endif]-->
<!--[if gt IE 8]><!-->
<html class="">
    <!--<![endif]-->
    <head>
        <title><?php echo $setting->meta_title; ?></title>
        <meta name="keywords" content="<?php echo $setting->meta_keyword; ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
        <!-- WEBTOOLS -->
        <base href="<?php echo base_url(); ?>">
        <link href="favicon.png" type="image/x-icon" rel="icon" />
        <link href="favicon.png" type="image/x-icon" rel="shortcut icon" />
        <script type="text/javascript" src="assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="assets/js/vendor/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/js/jquery.ui.js"></script>
        <script type="text/javascript" src="assets/js/jquery.plugins.min.js"></script>
        <script type="text/javascript" src="assets/js/scripts-1.0.js"></script>
        <script type="text/javascript" src="assets/js/jquery.collapsible.js"></script>
        <script type="text/javascript" src="assets/js/jquery.actual.min.js"></script>
        <script type="text/javascript" src="assets/js/masorny.js"></script>
        <script type="text/javascript" src="assets/js/main.js"></script>

        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="assets/css/LESS.css" />
        <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css" />
    </head>
    <body  class="HOMEPAGE theme-light <?php
    if ($page == "services")
    {
        echo "bg-service";
    }
    ?>">
        <div class="PARKED"></div>
        <div id="bodymask">
            <div id="body"> 
                <div class="xs-logo">
                    <img src="<?php echo $this->config->item('logo_path') . '/' . $setting->logo; ?>" alt="">
                </div>
                <!-- SECTION.MAIN -->
                <section class="main"  role="main">
                    <div id="PAGES" class="AJAX-TARGET ACTIVE-CONTENT" style="">
                        <?php $this->load->view('client/' . $page); ?>
                        <div class="footer_copyright">
                            <div style="text-align: center;">
                                &copy; <?php echo $setting->footer; ?> <br class="visible-xs">Powered by <a href="http://www.ktmfreelancer.com" target="_blank">Ktmfreelancer</a>
                            </div>  
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div id="POSTS" class="AJAX-TARGET " style=" display:none">
                        <div class="clearfix"></div>
                    </div>
                    <div id="DIRECTORS" class="AJAX-TARGET " style=" display:none">
                        <div class="clearfix"></div>
                    </div>
                </section>
                <!--/ SECTION.MAIN --> 

                <!-- ASIDE.MAIN --> 
                <aside class="main">
                    <div class="fixed-wrapper">
                        <nav class="reel" role="navigation">
                            <ul id="reel-navigation-thumbs">
                            </ul>
                        </nav>
                    </div>
                </aside> 
                <!--/ ASIDE.MAIN --> 

                <style>
<?php
$logo_path = $this->config->item('logo_path') . '/' . $setting->logo;
?>
                    .brand-icon{
                        background: transparent url(<?php echo $logo_path; ?>) no-repeat;
                        background-position: -7px 0px;
                        transition: all 0.2s;
                        transition-timing-function: ease-in-out;
                    }
                    
                    .brand-icon:hover{
                        background-position: -7px -260px;
                    }
                </style>
                <!-- NAV.MAIN -->
                <nav class="main">
                    <div class="fixed-wrapper">
                        <ul>
                            <nav class="mainmenu menu-mobile" role="">
                                <header class="branding">
                                    <a href="index.php" title="">

                                        <i class="brand-icon"></i>
                                    </a>
                                </header>
                                <li class="visible-xs">
                                    <ul style="margin-bottom:25px;" >
                                        <?php
                                        if ($director_list != FALSE)
                                        {
                                            foreach ($director_list->result() as $d)
                                            {
                                                ?>
                                                <li style="padding-left: 15px;" class="menu-item">
                                                    <a style="margin-bottom: 8px; font-size: 11px;" href="directors/<?php echo $d->slug; ?>"
                                                       ><?php echo $d->name; ?>
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                        }
                                        
                                        $menu_name = $menu_names->result();
                                        ?>
                                    </ul>
                                </li>
                                <li class="mainmenu-directors">
                                    <a id="director-link" href="" class="<?php echo (isset($nav_selected) && $nav_selected == 'director') ? 'navselected' : ''; ?>"><?php echo $menu_name[0]->name; ?></a>
                                </li>
                                <li class="menu-item"><a class="<?php echo (isset($nav_selected) && $nav_selected == 'feature') ? 'navselected' : ''; ?>" href="features"><?php echo $menu_name[1]->name; ?></a></li>
                                <li class="menu-item"><a class="<?php echo (isset($nav_selected) && $nav_selected == 'services') ? 'navselected' : ''; ?>" href="services"><?php echo $menu_name[2]->name; ?></a></li>
                                <?php
                                if ($menu != FALSE)
                                {
                                    foreach ($menu->result() as $me)
                                    {
                                        ?>
                                        <li class="menu-item"><a class="<?php
                                            if (isset($row) && $row != FALSE)
                                            {
                                                echo ($me->slug == $row->slug) ? 'navselected' : '';
                                            }
                                            ?>" href="<?php echo $me->slug; ?>"><?php echo $me->name; ?></a></li>
                                            <?php
                                        }
                                    }
                                    ?>

                            </nav>
                        </ul>
                        <nav class="social">
                            <ul>
                                <li><a href="<?php echo $setting->fb_link; ?>" target="_blank"><i class="icon icon-facebook"><span class="screen-reader-text">Facebook</span></i></a></li>
                                <li><a href="<?php echo $setting->twitter_link; ?>" target="_blank"><i class="icon icon-twitter"><span class="screen-reader-text">Twitter</span></i></a></li>
                            </ul>
                        </nav>
                    </div>
                </nav>
                <!--/ NAV.MAIN --> 
            </div>
            <!--/ #body --> 
        </div>
        <!--/ #bodymask -->
        <div id="contentFog" role="presentation"><!-- aria-hidden="true" --> 
        </div>
        <div id="drawer">
            <div id="drawer-preview-container"></div>
            <div class="close"><i class="close-button"></i></div>
            <ul style="margin-top:259px;">
                <?php
                if ($director_list != FALSE)
                {
                    foreach ($director_list->result() as $d)
                    {
                        ?>
                        <li class="menu-item"><a href="directors/<?php echo $d->slug; ?>"><?php echo $d->name; ?></a></li>
                            <?php
                        }
                    }
                    ?>
            </ul>
        </div>
    </body>
</html>
<!-- Localized -->