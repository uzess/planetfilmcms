<div class='dir-wrapper'>
    <div>
        <article class="clearfix">
            <div class="top_navigation_slider">
                <h1><?php echo $name; ?></h1>
                <div class="collapsible" id="nav-section1">BIO<span></span></div>
                <div class="collapse_nt">
                    <?php echo $content; ?>
                </div>
            </div>
        </article>
        <?php
        if ($videos->num_rows() == 0)
        {
            echo "<div style='  text-align: center;
  padding: 20% 0;
  font-size: 30px;
  color: #949494;'>No Videos</div>";
        }
        if ($videos != FALSE)
        {
            $i = 0;
            $j = 0;
            $count = 0;
            $ci = & get_instance();
            $total = $videos->num_rows();

            $vid_r = $videos->result();
            for ($k = 0; $k < 5; $k++)
            {
                if (isset($vid_r[$k]))
                {
                    $vid = $vid_r[$k];
                    if ($k == 0)
                    {
                        $class = "span12";
                        $img_size = "large";
                    } else
                    {
                        $class = "span6";
                        $img_size = "medium";
                    }
                    $img = $this->config->item('image_path') . '/' . $vid->image;
                    if (file_exists($img) & !empty($vid->image) & $vid->image_status == 0)
                    {
                        
                    } else
                    {
                        $img = $ci->getVimeoImage($vid->content, $img_size);
                    }
                    $block = 'videoblock';
                    if ($k > 0)
                    {
                        $block = 'my-videoblock';
                        ?>
                        <!-- Modal -->
                        <div class="modal fade" id="myModal-<?php echo $vid->slug; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel-<?php echo $vid->slug; ?>"><?php echo $vid->name; ?></h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class='text-center'>
                                            <iframe src="https://player.vimeo.com/video/<?php echo $vid->content; ?>?autoplay=0&color=ffffff&title=0&byline=0&portrait=0" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                        </div>
                                        <p style='margin-top: 15px;'>
                                            <?php echo $vid->short_content; ?>
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- /Modal-->
                    <?php } ?>
                    <article>
                        <div data-toggle="modal" data-target="#myModal-<?php echo $vid->slug; ?>" class="<?php
                        echo $block . ' ';
                        echo $class;
                        ?>" data-type="VIDEO">
                            <div class="videowrapper" data-vimeo="<?php echo $vid->content; ?>"></div>
                            <div class="keyframe">
                                <img class="placeholder" src="<?php echo $img; ?>" alt="" />
                            </div>
                            <div class="caption">
                                <div class="valign-center">
                                    <div class="caption-title"><?php echo $vid->name; ?></div>
                                    <!--<div class="caption-subtitle"><?php echo $vid->short_content; ?></div>-->
                                    <div class="play-button"><i class="icon-play"></i></div>
                                </div>
                            </div>
                        </div>
                        <?php
                        if ($k == 0)
                        {
                            ?>
                            <div class="txtblock">
                                <?php echo $vid->short_content; ?>     
                            </div>
                        <?php } ?>
                    </article>
                    <?php
                }
            }
        }
        ?>
    </div>
    <div class="clearfix"></div>

    <ul id="myList">
        <?php
        if ($videos != FALSE)
        {
            $i = $j = 0;
            if ($total >= 5)
            {
                for ($k = 5; $k < $total; $k++)
                {
                    if (isset($vid_r[$k]))
                    {
                        $vid = $vid_r[$k];
                        ?>
                        <!-- Modal -->
                        <div class="modal fade" id="myModal-<?php echo $vid->slug; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel-<?php echo $vid->slug; ?>"><?php echo $vid->name; ?></h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class='text-center'>
                                            <iframe src="https://player.vimeo.com/video/<?php echo $vid->content; ?>?autoplay=0&color=ffffff&title=0&byline=0&portrait=0" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                        </div>
                                        <p style='margin-top: 15px;'>
                                            <?php echo $vid->short_content; ?>
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- /Modal-->
                        <?php
                        if ($i % 5 == 0)
                        {
                            if ($i != 0)
                            {
                                echo '</article>';
                            }
                            $class = "span12";
                            $img_size = "large";
                            $img = $this->config->item('image_path') . '/' . $vid->image;

                            if (file_exists($img) & !empty($vid->image) & $vid->image_status == 0)
                            {
                                
                            } else
                            {
                                $img = $ci->getVimeoImage($vid->content, $img_size);
                            }
                            ?>


                            <article class="clearfix">
                                <div data-toggle="modal" data-target="#myModal-<?php echo $vid->slug; ?>" class="my-videoblock <?php echo $class; ?>">
                                    <div class="keyframe"><img class="placeholder" src="<?php echo $img; ?>" alt="" /></div>
                                    <div class="caption">
                                        <div class="valign-center">
                                            <div class="caption-title"><?php echo $vid->name; ?></div>
                                            <!--<div class="caption-subtitle"><?php echo $vid->short_content; ?></div>-->
                                            <div class="play-button"><i class="icon-play"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if ($i == 0)
                                    echo '</article>';
                            } else
                            {
                                $class = "span6";
                                $img_size = "medium";
                                if ($j % 2 == 0)
                                {
                                    if ($j != 0)
                                    {
                                        echo '</article>';
                                    }
                                    echo '<article class="clearfix">';
                                }

                                $img = $this->config->item('image_path') . '/' . $vid->image;

                                if (file_exists($img) & !empty($vid->image) & $vid->image_status == 0)
                                {
                                    
                                } else
                                {
                                    $img = $ci->getVimeoImage($vid->content, $img_size);
                                }
                                ?>
                                <div data-toggle="modal" data-target="#myModal-<?php echo $vid->slug; ?>" class="my-videoblock <?php echo $class; ?>" >
                                    <div class="keyframe"><img class="placeholder" src="<?php echo $img; ?>" alt="" /></div>
                                    <div class="caption">
                                        <div class="valign-center">
                                            <div class="caption-title"><?php echo $vid->name; ?></div>
                                            <!--<div class="caption-subtitle"><?php echo $vid->short_content; ?></div>-->
                                            <div class="play-button"><i class="icon-play"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $j++;
                            }
                            $i++;
                        }
                    }
                    echo '</article>';
                }
            }
            ?>
    </ul>
    <div style="clear:both;"></div>
    <?php
    if ($videos->num_rows() != 0)
    {
        ?>
        <div id="loadMore" class="news_btn">Load more</div>
        <div id="showLess" class="news_btn">Show less</div>
    <?php } ?>
</div>
