<?php
$id = 'opt-' . rand(111111,9999999);
?>
<div id="myBox-<?php echo $id; ?>" class="my-box">
    <span class="badge">New</span>
    <div class="form-group">
        <label class="col-sm-1 control-label">Director</label>
        <div class="col-sm-4">
            <select data-option="#<?php echo $id; ?>" class="my-dir-sel form-control">
                <option value="0" >Select Director</option>
                <?php
                foreach ($directors->result() as $dir)
                {
                    ?>
                    <option value="<?php echo $dir->id; ?>"><?php echo $dir->name; ?></option>
                <?php } ?>
            </select>
        </div>
        <label class="col-sm-1 control-label">Videos</label>
        <div class="col-sm-5">
            <select name="vid_id[]" id="<?php echo $id; ?>" class="my-vid form-control">
                <option value="0" slected>Select Video</option>

                <?php
                foreach ($videos as $vid)
                {
                    ?>
                    <option value="<?php echo $vid->id; ?>"><?php echo $vid->name; ?></option>
                <?php } ?>
            </select>
        </div>
        <div style="display:none;">
            <label class="col-sm-1 control-label">Weight</label>
            <div class="col-sm-2">
                <input type="text" name="weight[]" value="" class="my-weight form-control">
            </div>
        </div>
        <div class="col-sm-1">
            <span    data-close-id="#myBox-<?php echo $id; ?>" class="close-feature fa fa-close"></span>
        </div>
    </div>
</div>