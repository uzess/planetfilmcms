<div class="main-head">
    <h2>Setting</h2>
    <ol class="breadcrumb">
        <li>
            <a href="dashboard/manage_content">
                <i class="fa fa-home"></i>
            </a></li>
    </ol>
</div>

<div class="inner-container">
    <form action="dashboard/setting/save" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-sm-8">
                <div class="my-box">
                    <h3>Setting</h3>
                    <div class="form-group clearfix">
                        <label class="col-sm-2 control-label">Site Name</label>
                        <div class="col-sm-10">
                            <input name="web_name" type="text" value="<?php echo $setting->site_name; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label class="col-sm-2 control-label">Site Email</label>
                        <div class="col-sm-10">
                            <input name="web_email" type="text" value="<?php echo $setting->email; ?>" class="form-control">
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label class="col-sm-2 control-label">Meta Title</label>
                        <div class="col-sm-10">
                            <input name="meta_title" type="text" value="<?php echo $setting->meta_title; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label class="col-sm-2 control-label">Meta Keyword</label>
                        <div class="col-sm-10">
                            <input name="meta_keyword" type="text" value="<?php echo $setting->meta_keyword; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label class="col-sm-2 control-label">Meta Description</label>
                        <div class="col-sm-10">
                            <input name="meta_description" type="text" value="<?php echo $setting->meta_description; ?>" class="form-control">
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label class="col-sm-2 control-label">Fb Link</label>
                        <div class="col-sm-10">
                            <input name="fb_link" type="text" value="<?php echo $setting->fb_link; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label class="col-sm-2 control-label">Twitter Link</label>
                        <div class="col-sm-10">
                            <input name="twitter_link" type="text" value="<?php echo $setting->twitter_link; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label class="col-sm-2 control-label">Site Logo</label>
                        <div class="col-sm-10">
                            <input name="logo" type="file" class="form-control">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label class="col-sm-2 control-label">Current Logo</label>
                        <div class="col-sm-10">
                            <?php
                            $img = $this->config->item('logo_path') . '/' . $setting->logo;
                            if (file_exists($img) & !empty($setting->logo))
                            {
                                ?>
                                <img class="img-responsive thumbnail" src="<?php echo $img; ?>" alt="Logo">
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label class="col-sm-2 control-label">Footer</label>
                        <div class="col-sm-10">
                            <input name="footer" type="text" value="<?php echo $setting->footer; ?>" class="form-control">
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-2">
                            <input type="submit" name="submit" class="btn my-btn" value="Save">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">

        </div>
    </form>
</div>