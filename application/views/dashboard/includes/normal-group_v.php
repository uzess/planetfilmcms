<?php
$short_content = $content = $type = NULL;
if (isset($row))
{
  $short_content = $row->short_content;
  $content = $row->content;
  $type = $row->type;
}
?>

<div style="" class="mc" id="ng">
  <div class="my-box" style="display:none;">
    <h3 data-id="#shortContent" data-state="" class="my-toggle nmb">Short Content</h3>
    <div id="shortContent" class="form-group clearfix">
      <textarea class="form-control" name="short_content"><?php echo $short_content; ?></textarea>
    </div>
  </div>

  <div  class="my-box">
    <h3>Content</h3>
    <div class="form-group">
      <textarea id="content" name="content"><?php echo $content; ?></textarea>
    </div>
  </div>
</div>