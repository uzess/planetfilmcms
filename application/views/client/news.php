<div class="page-header my-heading">
    <h1>News</h1>
</div>

<div class="span12 clearfix inner-wrapper">
    <?php
    if (isset($news) && $news != FALSE)
    {
        foreach ($news->result() as $key => $in)
        {
            ?>
            <div class="news-wrapper" id="expand-<?php echo $key; ?>">
                <div class="news-title">
                    <?php echo $in->name; ?> 
                    <span data-target="#expand-<?php echo $key; ?>"  class="my-expand pull-right">
                        <i class="fa fa-plus-circle"></i>
                    </span>
                </div>
                <div class="content"><?php echo strip_tags($in->content); ?></div>
            </div>
            <?php
        }
    }
    ?>
</div>
