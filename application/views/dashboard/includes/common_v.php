<?php
$name = $slug = $type = NULL;
if (isset($row))
{
    $name = $row->name;
    $slug = $row->slug;
    $type = $row->type;
}
?>
<div class="row">
    <div class="col-sm-6" style="display:none;">
        <div class="my-box">
            <h3>Content Type</h3>
            <div class="form-group">
                <label class="col-sm-2 control-label">Parent</label>
                <div class="col-sm-10">
                    <select name="parent_id" class="form-control">
                        <option value="0">Select Parent</option>
                        <?php echo $link_list; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="my-box">
            <h3>Basic Info</h3>
            <div class="form-group clearfix">
                <label class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10">
                    <input id="name" type="text" name="name" class="form-control" value="<?php echo $name; ?>" placeholder="Title...">
                </div>
            </div>
            <div class="form-group clearfix">
                <label class="col-sm-2 control-label">Slug</label>
                <div class="col-sm-10">
                    <input id="slug" type="text" name="slug" class="form-control" value="<?php echo $slug; ?>" placeholder="Slug...">


                </div>
            </div>
            <span id="slugMsg" style="display:none;" class=" label"></span>
        </div>
    </div>
</div>
