<div class="main-head">
    <h2><?php echo $title; ?>
    </h2>
</div>
<span data-type="<?php echo $feature_type; ?>" id="addFeature"  class="add-more fa fa-plus"></span>

<form id="featureForm" action="dashboard/video/saveFeature" method="post" class="form-horizontal">
    <input type="hidden" name="feature_type" value="<?php echo $feature_type; ?>">

    <div class="inner-container">
        <div class="row">
            <div class="col-sm-9" id="featureContainer">
                <?php
                foreach ($feature_videos->result() as $row)
                {
                    $id = 'opt-' . time() . $row->id;
                    if ($feature_type == 'home')
                    {
                        $weight = $row->home_weight;
                    } else
                    {
                        $weight = $row->feature_weight;
                    }
                    ?>

                    <div id="myBox-<?php echo $id; ?>" class="my-box">
                        <div class="form-group">
                            <label class="col-sm-1 control-label">Director</label>
                            <div class="col-sm-4">
                                <select data-option="#<?php echo $id; ?>" class="my-dir-sel form-control">
                                    <option value="0">Select Director</option>
                                    <?php
                                    foreach ($directors->result() as $dir)
                                    {
                                        ?>
                                        <option value="<?php echo $dir->id; ?>" <?php if ($dir->id == $row->parent_id) echo "selected" ?>><?php echo $dir->name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <label class="col-sm-1 control-label">Videos</label>
                            <div class="col-sm-5">
                                <select name="vid_id[]" id="<?php echo $id; ?>" class="my-vid form-control">
                                    <option value="0">Select Video</option>

                                    <?php
                                    foreach ($videos->result() as $vid)
                                    {
                                        if ($vid->id == $row->id)
                                        {
                                            ?>
                                            <option value="<?php echo $vid->id; ?>" selected><?php echo $vid->name; ?></option>

                                            <?php
                                        } else
                                        {
                                            if (!in_array($vid->id, $feature_video_id))
                                            {
                                                ?>
                                                <option value="<?php echo $vid->id; ?>" <?php if ($vid->id == $row->id) echo "selected" ?>><?php echo $vid->name; ?></option>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div style="display:none;">
                                <label class="col-sm-1 control-label">Weight</label>
                                <div class="col-sm-2">
                                    <input type="text" name="weight[]" class="my-weight form-control" value="<?php echo $weight; ?>">
                                </div>

                            </div>
                            <div class="col-sm-1">
                                <span data-close-id="#myBox-<?php echo $id; ?>" class="close-feature fa fa-close"></span>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>


        </div>
        <div class="form-group">
            <div class="col-xs-2">
                <div id="btnFeatureSubmit" class="btn my-btn">Save</div>
            </div>
        </div>
    </div>
</form>