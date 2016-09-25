<?php
$data['type_arr'] = $this->config->item('type');
$image_status = $target = $slug = $meta_title = $meta_keyword = $meta_description = $status = $id = $image = $image_caption = NULL;
$submit_btn = 'save';
if (isset($row))
{
    //Edit
    $data['row'] = $row;
    $slug = $row->slug;
    $image = $row->image;
    $image_status = (int) $row->image_status;
    $image_caption = $row->image_caption;
    $status = (int) $row->status;
    $weight = $row->weight;
    $target = (int) $row->target;
    $meta_title = $row->meta_title;
    $meta_keyword = $row->meta_keyword;
    $meta_description = $row->meta_description;
    $id = $row->id;
    $submit_btn = 'update';
}
?>
<div class="main-head">
    <h2><?php echo $heading; ?></h2>
    <ol class="breadcrumb">
        <li>
            <a href="dashboard/manage_content/view/<?php echo $section; ?>">
                <i class="fa fa-home"></i>
            </a></li>
        <?php
        echo $bread_crumb;
        ?>
    </ol>
</div>

<div class="inner-container">
    <div class="row">
        <form action="dashboard/manage_content/save" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="section" value="<?php echo $section; ?>">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="type" value="Normal Group">
            <div class="col-sm-8">
                <?php
                $this->load->view('dashboard/includes/common_v', $data);
                $this->load->view('dashboard/includes/normal-group_v', $data);
                ?>
                <div class="row">
                    <div class="form-group clearfix">
                        <div class="col-sm-2">
                            <input type="submit" name="<?php echo $submit_btn; ?>" class="btn my-btn" value="<?php echo ucfirst($submit_btn); ?>">
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-sm-4">
                <?php
                if ($slug == 'about' || $slug == 'contact')
                {
                    $display = 'none';
                } else
                {
                    $display = 'block';
                }
                ?>
                <div class="my-box" style="display:<?php echo $display; ?>">
                    <?php
                    $img = $this->config->item('image_path') . '/' . $image;
                    ?>
                    <h3 data-id="#media" data-state="<?php echo (!empty($image) & file_exists($img)) ? "in" : "" ?>" class="my-toggle nmb">Media</h3>

                    <div id="media">
                        <?php
                        if (!empty($image) & file_exists($img))
                        {
                            ?>
                            <div class="form-group">
                                <img class="img-responsive thumbnail" src="<?php echo $img; ?>" alt="">
                            </div>
                        <?php } ?>
                        <div class="form-group clearfix">
                            <div class="col-sm-12">
                                <input name="image" type="file" class="form-control">
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="col-sm-2 control-label">Caption</label>
                            <div class="col-sm-10">
                                <input name="image_caption" type="text" value="<?php echo $image_caption; ?>" class="form-control">
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="col-sm-2 control-label">Hide</label>
                            <div class="col-sm-10">
                                <select name="image_status" class="form-control">
                                    <option value="0" <?php echo ($image_status === 0) ? "selected" : "" ?>>No</option>
                                    <option value="1" <?php echo ($image_status === 1) ? "selected" : "" ?>>Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="my-box">
                    <h3 data-id="#setting" data-state="" class="my-toggle nmb">Settings</h3>
                    <div id="setting">
                        <div class="form-group clearfix">
                            <label class="col-sm-3 control-label">Status</label>
                            <div class="col-sm-9">
                                <select name="status" class="form-control">
                                    <option value="1" <?php echo ($status === 1) ? "selected" : "" ?>>Show</option>
                                    <option value="0" <?php echo ($status === 0) ? "selected" : "" ?>>Hide</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group clearfix">
                            <label class="col-sm-3 control-label">Weight</label>
                            <div class="col-sm-9">
                                <input type="text" name="weight" class="form-control" value="<?php echo $weight; ?>">
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label class="col-sm-3 control-label">Target</label>
                            <div class="col-sm-9">
                                <select name="target" class="form-control">
                                    <option value="0" <?php echo ($target === 0) ? "selected" : "" ?>>Default</option>
                                    <option value="1" <?php echo ($target === 1) ? "selected" : "" ?>>New Page</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="my-box">
                    <h3 data-id="#meta" class="my-toggle nmb">Meta</h3>
                    <div id="meta">
                        <div class="form-group clearfix">
                            <label class="col-sm-3 control-label">Title</label>
                            <div class="col-sm-9">
                                <input type="text" name="meta_title" class="form-control" value="<?php echo $meta_title; ?>">
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="col-sm-3 control-label">Keywords</label>
                            <div class="col-sm-9">
                                <input type="text" name="meta_keyword" class="form-control" value="<?php echo $meta_keyword; ?>">
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="meta_description"><?php echo $meta_description; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="clearfix"></div>
        <div class="col-xs-12">
            <?php
            $this->load->view('dashboard/includes/listings_v', $data);
            ?>
        </div>
    </div>

</div><!-- /inner-container -->
