<?php

if ($row != FALSE)
{
    ?>
    <div class="page-header my-heading"><h1><?php echo $row->name; ?></h1></div>
    <?php
    if (strtolower($row->slug) == 'contact')
    {
        ?>   
        <div class="span12 clearfix inner-wrapper accordion">
            <?php
            if (isset($inner_rows) && $inner_rows != FALSE)
            {
                foreach ($inner_rows->result() as $key => $in_row)
                {
                    ?>
                    <section style="margin-bottom:15px;" class="contact textblock span4 accordion js-altered"  data-type="TEXT">
                        <h1 class="contact-title accordion-header"><?php echo $in_row->name; ?></h1>
                        <div class="accordion-block">
                            <?php echo $in_row->content; ?>
                        </div>
                    </section>
                    <?php
                }
            }
            ?>
            <div class="clearfix"></div>
            <br>
            <h1>Map To Planet</h1>
            <?php echo $row->content; ?>
        </div>
        <?php
    } else
    {
        ?>
        <div class="span12 clearfix inner-wrapper">
            <?php
            $img = $this->config->item('image_path') . '/' . $row->image;
            if (file_exists($img) && !empty($row->image) && $row->image_status == 0)
            {
                ?>
                <div class="text-center">
                    <img src="<?php echo $img; ?>" alt="" style="width:auto; display:inline-block;   margin-bottom: 15px;" class="img-responsive thumbnail">
                </div>
                <?php
            }
            echo $row->content;
            ?>
        </div>

        <?php
    }
    ?>
    <div class="clearfix"></div>
    <?php
} else
{
    
    $video_width = '540';
    if (isset($videos) && $videos != FALSE)
    {
        $ci = & get_instance();
        $total = $videos->num_rows();
        $videos = $videos->result();
        $vid = $videos[0];

        $class = "span12";
        $img_size = "large";
        $img = $this->config->item('image_path') . '/' . $vid->image;

        $director = $this->groups_m->get('*', array('id' => $vid->parent_id), 1);
        ?>
        <?php
        if (file_exists($img) & !empty($vid->image) & $vid->image_status == 0)
        {
            
        } else
        {
            //$img = $ci->getVimeoImage($vid->content, $img_size);
        }
        ?>

        <article class="clearfix">
            <div class="videoblock <?php echo $class; ?>" data-type="VIDEO">
                <div class="videowrapper" data-vimeo="<?php echo $vid->content; ?>"></div>
                <div class="keyframe"><img  class="placeholder" src="<?php echo $img; ?>" alt="" /></div>
                <div class="caption">
                    <div class="valign-center">
                        <div class="caption-title"><?php echo $vid->name; ?></div>
                        <div class="caption-subtitle"></div>
                        <div class="play-button"><i class="icon-play"></i></div>
                    </div>
                </div>
            </div>
            <div class="txtblock">
                <h1>DIRECTOR: <?php echo $director->name; ?></h1>
                <?php echo $vid->short_content; ?>
            </div>

        </article>
        <?php
    }
    ?>




    <div class="wrapper_forflatUI">
        <div class="left_column">
            <?php
            for ($i = 1; $i < 4; $i++)
            {
                if (isset($videos[$i]))
                {
                    $vid = $videos[$i];
                    $img = $this->config->item('image_path') . '/' . $vid->image;
                    if (file_exists($img) & !empty($vid->image) & $vid->image_status == 0)
                    {
                        
                    } else
                    {
                        //$img = $ci->getVimeoImage($vid->content, $img_size);
                    }
                    if ($i == 3)
                        $height = '235';
                    else
                        $height = '353';
                    ?>
                    <!-- Modal -->
                    <div class="modal fade" id="myModal-<?php echo $vid->slug; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel"><?php echo $vid->name; ?></h4>
                                </div>
                                <div class="modal-body">
                                    <div class='text-center'>
                                        <iframe src="https://player.vimeo.com/video/<?php echo $vid->content; ?>?autoplay=0&color=ffffff&title=0&byline=0&portrait=0" width="<?php echo $video_width; ?>" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                    </div>
                                    <p style='margin-top: 15px;'>
                                        <?php echo $vid->short_content; ?>
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- /Modal-->
                    <article class="leftadjusted">
                        <div class="clearfix" data-toggle="modal" data-target="#myModal-<?php echo $vid->slug; ?>">
                            <div  class="my-videoblock span6">
                                <div style="height:<?php echo $height; ?>px; background-image: url('<?php echo $img; ?>'); " class="my-vid-block keyframe">
                                </div>
                                <div class="caption">
                                    <div class="valign-center">
                                        <div class="caption-title"><?php echo $vid->name; ?></div>
                                        <div class="play-button"><i class="icon-play"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?php
                }
            }
            ?>
        </div>

        <div class="left_column secondly">
            <?php
            $height = '314';

            if (isset($videos[4]))
            {
                $vid = $videos[4];
                $img = $this->config->item('image_path') . '/' . $vid->image;
                if (file_exists($img) & !empty($vid->image) & $vid->image_status == 0)
                {
                    
                } else
                {
                    //$img = $ci->getVimeoImage($vid->content, $img_size);
                }
                ?>
                <!-- Modal -->
                <div class="modal fade" id="myModal-<?php echo $vid->slug; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel"><?php echo $vid->name; ?></h4>
                            </div>
                            <div class="modal-body">
                                <div class='text-center'>
                                    <iframe src="https://player.vimeo.com/video/<?php echo $vid->content; ?>?autoplay=0&color=ffffff&title=0&byline=0&portrait=0" width="<?php echo $video_width; ?>" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                </div>
                                <p style='margin-top: 15px;'>
                                    <?php echo $vid->short_content; ?>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- /Modal-->
                <article class="leftadjusted">
                    <div class="clearfix" data-toggle="modal" data-target="#myModal-<?php echo $vid->slug; ?>">
                        <div class="my-videoblock span6">
                            <div style="height:<?php echo $height; ?>px; background-image: url('<?php echo $img; ?>'); " class="my-vid-block keyframe">

                            </div>
                            <div class="caption">
                                <div class="valign-center">
                                    <div class="caption-title"><?php echo $vid->name; ?></div>
                                    <div class="play-button"><i class="icon-play"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
                <?php
            }

            if (isset($news_row) && $news_row != FALSE)
            {
                ?>
                <article class="leftadjusted">
                    <div class="videoblock span6 newssection">
                        <div class="videowrapper"></div>
                        <div class="keyframe">
                            <img height="<?php echo $height; ?>" class="placeholder" src="assets/img/blank.jpg" alt="" />
                        </div>
                        <div class="caption"  style="display:block; color:black">
                            <div class="valign-center">
                                <div class="caption-title"><?php echo $news_row->name; ?></div>
                                <div class="caption-subtitle"><?php echo substr(strip_tags($news_row->content), 0, 100); ?> ...</div>
                                <a href="news" class="news_btn">Learn More</a> </div>
                        </div>
                    </div>
                </article>
                <?php
            }
            if (isset($videos[5]))
            {
                $vid = $videos[5];
                $img = $this->config->item('image_path') . '/' . $vid->image;
                if (file_exists($img) & !empty($vid->image) & $vid->image_status == 0)
                {
                    
                } else
                {
                    //$img = $ci->getVimeoImage($vid->content, $img_size);
                }
                ?>
                <!-- Modal -->
                <div class="modal fade" id="myModal-<?php echo $vid->slug; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel"><?php echo $vid->name; ?></h4>
                            </div>
                            <div class="modal-body">
                                <div class='text-center'>
                                    <iframe src="https://player.vimeo.com/video/<?php echo $vid->content; ?>?autoplay=0&color=ffffff&title=0&byline=0&portrait=0" width="<?php echo $video_width; ?>" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                </div>
                                <p style='margin-top: 15px;'>
                                    <?php echo $vid->short_content; ?>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- /Modal-->
                <article class="leftadjusted">
                    <div class="clearfix" data-toggle="modal" data-target="#myModal-<?php echo $vid->slug; ?>">
                        <div class="my-videoblock span6">
                            <div style="height:<?php echo $height; ?>px; background-image: url('<?php echo $img; ?>'); " class="my-vid-block keyframe">

                            </div>
                            <div class="caption">
                                <div class="valign-center">
                                    <div class="caption-title"><?php echo $vid->name; ?></div>
                                    <div class="play-button"><i class="icon-play"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
                <?php
            }
            ?>
        </div>
        <div class="left_column thirdly">
            <?php
            for ($i = 6; $i < 9; $i++)
            {
                if (isset($videos[$i]))
                {
                    $vid = $videos[$i];
                    $img = $this->config->item('image_path') . '/' . $vid->image;
                    if (file_exists($img) & !empty($vid->image) & $vid->image_status == 0)
                    {
                        
                    } else
                    {
                        //  $img = $ci->getVimeoImage($vid->content, $img_size);
                    }
                    if ($i == 7)
                        $height = '235';
                    else
                        $height = '353';
                    ?>
                    <!-- Modal -->
                    <div class="modal fade" id="myModal-<?php echo $vid->slug; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel"><?php echo $vid->name; ?></h4>
                                </div>
                                <div class="modal-body">
                                    <div class='text-center'>
                                        <iframe src="https://player.vimeo.com/video/<?php echo $vid->content; ?>?autoplay=0&color=ffffff&title=0&byline=0&portrait=0" width="<?php echo $video_width; ?>" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                    </div>
                                    <p style='margin-top: 15px;'>
                                        <?php echo $vid->short_content; ?>
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- /Modal-->
                    <article class="leftadjusted">
                        <div class="clearfix" >
                            <div data-toggle="modal" data-target="#myModal-<?php echo $vid->slug; ?>" class="my-videoblock span6">
                                <div style="height:<?php echo $height; ?>px; background-image: url('<?php echo $img; ?>'); " class="my-vid-block keyframe">

                                </div>
                                <div class="caption">
                                    <div class="valign-center">
                                        <div class="caption-title"><?php echo $vid->name; ?></div>
                                        <div class="play-button"><i class="icon-play"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?php
                }
            }
            ?>
        </div>
        <div style="clear:both;"></div>
    </div>
    <ul id="myList">
        <?php
        if (isset($videos) && $videos != FALSE)
        {
            if ($total >= 9)
            {
                $i = 0;
                $j = 0;
                for ($k = 9; $k < $total; $k++)
                {
                    $vid = $videos[$k];
                    ?>
                    <!-- Modal -->
                    <div class="modal fade" id="myModal-<?php echo $vid->slug; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel"><?php echo $vid->name; ?></h4>
                                </div>
                                <div class="modal-body">
                                    <div class='text-center'>
                                        <iframe src="https://player.vimeo.com/video/<?php echo $vid->content; ?>?autoplay=0&color=ffffff&title=0&byline=0&portrait=0" width="<?php echo $video_width; ?>" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
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
                            // $img = $ci->getVimeoImage($vid->content, $img_size);
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
                                //$img = $ci->getVimeoImage($vid->content, $img_size);
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
                    echo '</article>';
                }
            }
            ?>
    </ul>
    <div style="clear:both;"></div>
    <div id="loadMore" class="news_btn">Load more</div>
    <div id="showLess" class="news_btn">Show less</div>

<?php } ?>
