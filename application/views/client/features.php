<?php
$ci = & get_instance();
?>
<article class="clearfix">
    <div class="page-header my-heading"><h1>Features</h1></div>
    <div class="span12 clearfix inner-wrapper">
        <?php
        if ($features != FALSE)
        {
            foreach ($features->result() as $f)
            {
                $img = $this->config->item('image_path') . '/' . $f->image;

                if (file_exists($img) & !empty($f->image) & $f->image_status == 0)
                {
                    
                } else
                {
                    $img = $ci->getVimeoImage($f->content, 'large');
                }
                ?>

                <?php
                $vimeo_id = $f->content;
                ?>
                <div class="videoblock" data-type="VIDEO">
                    <div class="videowrapper" data-vimeo="<?php echo $vimeo_id; ?>"></div>
                    <div class="keyframe">
                        <img src="<?php echo $img; ?>" alt="" class="">
                    </div>
                    <div class="caption show">
                        <div class="valign-center">
                            <div class="caption-title">
                                <?php echo $f->name; ?>
                            </div>
                            <!--<div class="caption-subtitle">  </div>-->
                            <div class="play-button">
                                <i class="icon-play">
                                    <span class="screen-reader-text">Play</span>
                                </i>
                            </div>
                        </div>
                    </div>
                </div>
                <section class="my-cap feature-caption clearfix" data-type="TEXT">
                    <?php echo $f->short_content; ?>
                </section>
                <?php
            }
        }
        ?>
    </div></article>