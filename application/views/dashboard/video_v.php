<div class="main-head">
  <h2>
    <?php
    if ($action == 'video')
    {
      $href = 'dashboard/video/open/' . $parent_id;
      $title = 'Videos';
    }
    else
    {
      $href = 'dashboard/video';
      $title = 'Directors';
    }
    ?>
    <a href="<?php echo $href; ?>"> <?php echo $title; ?> </a> 

    <a href="" class="pull-right add-new" data-target="#video">
      <span style="font-size:15px; position: relative; top: 6px;" class="fa fa-<?php echo (!isset($row)) ? 'plus' : 'minus'; ?>"></span>
    </a>
  </h2>
  <ol class="breadcrumb">
    <li>
      <a href="dashboard/video">
        <i class="fa fa-home"></i>
      </a>
    </li>
    <?php
    if ($action == 'video')
    {
      ?>
      <li>
        <?php echo $current_director; ?>
      </li>
      <?php
    }
    ?>
  </ol>
</div>

<div class="inner-container">
  <div class="row">
    <?php
    $display = 'none';
    $status = 1;
    if ($action == 'director')
    {
      $name = $description = $id = NULL;
      if (isset($row))
      {
        $name = $row->name;
        $description = $row->content;
        $status = $row->status;
        $weight = $row->weight;
        $id = $row->id;
        $display = 'block';
      }
      ?>
      <form action="dashboard/video/save" method="POST" enctype="multipart/form-data">
        <div id="video" style="display:<?php echo $display; ?>;">
          <input type="hidden" name="action" value="director">
          <input type="hidden" name="id" value="<?php echo $id; ?>">
          <div class="col-sm-8">
            <div class="my-box">
              <h3>Director Info</h3>
              <div class="form-group clearfix">
                <label class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10">
                  <input type="text" name="name" class="form-control" value="<?php echo $name; ?>" placeholder="Title...">
                </div>
              </div>


            </div>

            <div class="my-box">
              <h3>Description</h3>
              <textarea id="content" name="description" class="form-control"><?php echo $description; ?></textarea>
            </div>
            <div class="row">
              <div class="form-group clearfix">
                <div class="col-sm-2">
                  <input type="submit" name="submit" class="btn my-btn" value="Save">
                </div>
              </div>
            </div>

          </div>
          <div class="col-sm-4">
            <div class="my-box">
              <h3 data-id="#setting" data-state="in" class="my-toggle nmb">Settings</h3>
              <div id="setting">
                <div class="form-group clearfix">
                  <label class="col-sm-3 control-label">Status</label>
                  <div class="col-sm-9">
                    <select name="status" class="form-control">
                      <option value="1" <?php echo ($status == 1) ? "selected" : "" ?>>Show</option>
                      <option value="0" <?php echo ($status == 0) ? "selected" : "" ?>>Hide</option>
                    </select>
                  </div>
                </div>

                <div class="form-group clearfix">
                  <label class="col-sm-3 control-label">Weight</label>
                  <div class="col-sm-9">
                    <input type="text" name="weight" class="form-control" value="<?php echo $weight; ?>">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
      <div class="clearfix"></div>
      <div class="col-xs-12">
        <div class="my-box">
          <h3>List</h3>
          <table id="dataTable" class="table table-bordered table-condensed">
            <thead>
              <tr>
                <th>S no.</th>
                <th>Directors</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($directors != FALSE)
              {
                foreach ($directors->result() as $key => $dir)
                {
                  ?>
                  <tr>
                    <td><?php echo++$key; ?></td>
                    <td><?php echo $dir->name; ?></td>
                    <td>
                      <a href="dashboard/video/open/<?php echo $dir->id; ?>">
                        <i class="glyphicon glyphicon-folder-open"></i>
                      </a> 
                      &nbsp; &nbsp;
                      <a href="dashboard/video/edit/<?php echo $dir->id; ?>">
                        <i class="glyphicon glyphicon-edit"></i>
                      </a> 
                      &nbsp; &nbsp;
                      <a onclick="delete_confirmation('dashboard/video/delete/<?php echo $dir->id; ?>');" >
                        <i class="glyphicon glyphicon-remove"></i>
                      </a>
                    </td>
                  </tr>
                  <?php
                }
              }
              ?>

            </tbody>
          </table>
        </div>  </div>



      <?php
    }
    elseif ($action == 'video')
    {
      $image_status = 0;
      $id = $video_id = $name = $image = $short_content = NULL;
      $display = 'none';

      if (isset($row))
      {
        $name = $row->name;
        $video_id = $row->content;
        $status = $row->status;
        $weight = $row->weight;
        $image_status = $row->image_status;
        $image = $row->image;
        $id = $row->id;
        $short_content = $row->short_content;
        $display = 'block';
      }
      ?>
      <form action="dashboard/video/save" method="POST" enctype="multipart/form-data">

        <div id="video" style="display:<?php echo $display; ?>;">

          <input type="hidden" name="action" value="video">
          <input type="hidden" name="id" value="<?php echo $id; ?>">
          <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>">
          <div class="col-sm-8">
            <div class="my-box">
              <h3>Video Title</h3>
              <div class="form-group clearfix">
                <label class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10">
                  <input type="text" name="name" class="form-control" value="<?php echo $name; ?>" placeholder="Video Title">
                </div>
              </div>
              <div class="form-group clearfix">
                <label class="col-sm-2 control-label">Video Id</label>
                <div class="col-sm-10">
                  <input type="text" name="video_id" class="form-control" value="<?php echo $video_id; ?>" placeholder="Video Id">
                </div>
              </div>
              <div class="form-group clearfix">
                <label class="col-sm-2 control-label">Description</label>
                <div class="col-sm-10">
                  <textarea class="form-control" name="short_content" rows="5"><?php echo $short_content; ?></textarea>
                </div>
              </div>
              <div class="form-group clearfix">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-2">
                  <input type="submit" name="submit" class="btn my-btn" value="Save">
                </div>
              </div>
            </div>

          </div>
          <div class="col-sm-4">
            <div class="my-box">
              <h3 data-id="#setting" data-state="in" class="my-toggle nmb">Settings</h3>
              <div id="setting">
                <div class="form-group clearfix">
                  <label class="col-sm-3 control-label">Status</label>
                  <div class="col-sm-9">
                    <select name="status" class="form-control">
                      <option value="1" <?php echo ($status == 1) ? "selected" : "" ?>>Show</option>
                      <option value="0" <?php echo ($status == 0) ? "selected" : "" ?>>Hide</option>
                    </select>
                  </div>
                </div>


                <div class="form-group clearfix">
                  <label class="col-sm-3 control-label">Weight</label>
                  <div class="col-sm-9">
                    <input type="text" name="weight" class="form-control" value="<?php echo $weight; ?>">
                  </div>
                </div>
              </div>
            </div>

            <div class="my-box">
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
                  <label class="col-sm-2 control-label">Hide</label>
                  <div class="col-sm-10">
                    <select name="image_status" class="form-control">
                      <option value="0" <?php echo ($image_status == 0) ? "selected" : "" ?>>No</option>
                      <option value="1" <?php echo ($image_status == 1) ? "selected" : "" ?>>Yes</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>

      <div class="clearfix"></div>
      <div class="col-xs-12">
        <div class="my-box ">
          <h3>
            List  
            <div class="row">
              <div class="col-xs-2 pull-right">
                <input id="list-submit" type="submit" class="btn my-btn" name="submit" value="Save">
              </div>
            </div>
          </h3>
          <div style="display:none;">
            <!--
            this input checkbox is related to input checkbox inside the datatable
           
            this is done because 
            checkbox of first page on datatable become uncheck if we are in another page
            -->
            <?php
            if ($videos != FALSE)
            {
              foreach ($videos->result() as $key => $dir)
              {
                ?>
                <input id="<?php echo 'fea-' . $dir->id; ?>" class="feature" type="checkbox" value="<?php echo $dir->id; ?>" <?php echo ($dir->show_in_feature == 1) ? 'checked' : ''; ?>>
                <input id="<?php echo 'hom-' . $dir->id; ?>" class="home-page" type="checkbox" value="<?php echo $dir->id; ?>" <?php echo ($dir->show_in_homepage == 1) ? 'checked' : ''; ?>>

                <?php
              }
            }
            ?>
          </div>
          <table id="dataTable" class="table table-bordered table-condensed">
            <thead>
              <tr>
                <th>S no.</th>
                <th>Name</th>
<!--                <th>Feature</th>
                <th>Home Page</th>-->
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($videos != FALSE)
              {
                foreach ($videos->result() as $key => $dir)
                {
                  ?>
                  <tr class="<?php echo ($id == $dir->id) ? 'selected' : ''; ?>">
                    <td><?php echo++$key; ?></td>
                    <td><?php echo $dir->name; ?></td>
<!--                    <td>
                      <label style="display: block; margin:0; text-align:center;" for="<?php echo 'make-fea-' . $dir->id; ?>">
                        <input id="<?php echo 'make-fea-' . $dir->id; ?>" data-id="#<?php echo 'fea-' . $dir->id; ?>" class="make-feature" type="checkbox" value="<?php echo $dir->id; ?>" <?php echo ($dir->show_in_feature == 1) ? 'checked' : ''; ?>>
                      </label>
                    </td>-->
<!--                    <td>
                      <label style="display: block; margin:0; text-align:center;" for="<?php echo 'make-hom-' . $dir->id; ?>">
                        <input id="<?php echo 'make-hom-' . $dir->id; ?>" data-id="#<?php echo 'hom-' . $dir->id; ?>" class="make-home-page" type="checkbox" value="<?php echo $dir->id; ?>" <?php echo ($dir->show_in_homepage == 1) ? 'checked' : ''; ?>>
                      </label></td>-->
                    <td>
                      <a href="dashboard/video/edit/<?php echo $dir->id; ?>">
                        <i class="glyphicon glyphicon-edit"></i>
                      </a> 
                      &nbsp; &nbsp;
                      <a onclick="delete_confirmation('dashboard/video/delete/<?php echo $dir->id; ?>');" >
                        <i class="glyphicon glyphicon-remove"></i>
                      </a>
                    </td>
                  </tr>
                  <?php
                }
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
      <?php
    }
    ?>
  </div>

</div>