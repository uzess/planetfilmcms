<div style="  position: relative;
  margin-bottom: -25px;
  padding-bottom: 0;
  height: auto;
  padding-left: 0;" class="page-header my-heading bg-service bg-transparent">
    <h1>Production Services</h1>
</div>
<div class="service-wrapper">
    <div id="servicesMaso" style="min-height:500px" class="clearfix">
        <?php
        // var_dump($result->result());
        foreach ($result->result() as $row)
        {
            $img = $this->config->item('image_path') . '/' . $row->image;
            if (file_exists($img) && !empty($row->image))
            {
                ?>
                <div class="col-xs-12 col-sm-4 col-md-4 service-img-wrap">
                    <a href="<?php echo $row->slug; ?>">
                        <img src="<?php echo $img; ?>">
                    </a>
                    <div class="title">
                        <a href="<?php echo $row->slug; ?>"><?php echo $row->name; ?></a>  
                    </div>
                </div>
            <?php }
        }
        ?>
    </div>
</div>
<script>
    $(window).resize(function () {
        location.href = 'services';
    });
</script>