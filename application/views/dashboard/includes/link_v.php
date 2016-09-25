<?php
$content  =  $type = NULL;
if(isset($row))
{
  $content = $row->content;
  $type = $row->type;
}
?>
<div style="display: <?php echo ($type == 'Link') ? "block;" : "none;"; ?>" class="mc" id="link">
    <div class="my-box">
        <h3 >Link</h3>
        <div class="form-group clearfix">
            <textarea class="form-control" name="link"><?php echo $content; ?></textarea>
        </div>
    </div>

    
</div>