<div  class="my-box">
  <h3>List</h3>
  <table id="dataTable" class="table table-bordered table-condensed clearfix">
    <thead>
      <tr>
        <th>S no.</th>
        <th>Name</th>
        <th>Slug</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($groups->result() as $key => $group)
      {
        ?>
        <tr class="<?php if(isset($row)) { if($group->id ==$row->id) echo 'selected'; }?>">
          <td><?php echo++$key; ?></td>
          <td><?php echo $group->name; ?></td>
          <td><?php echo $group->slug; ?></td>
          <td>
            <?php
            if ($group->type == "Normal Group")
            {
              ?>
              <a href="dashboard/manage_content/view/<?php echo $section; ?>/<?php echo $group->id; ?>">
                <i class="glyphicon glyphicon-folder-open"></i>
              </a> 
            &nbsp; &nbsp;
            <?php } ?>
            <a href="dashboard/manage_content/view/<?php echo $section; ?>/<?php echo $group->parent_id; ?>/<?php echo $group->id; ?>">
              <i class="glyphicon glyphicon-edit"></i>
            </a>
            &nbsp; &nbsp;
            <a onclick="delete_confirmation('dashboard/manage_content/delete/<?php echo $group->id; ?>');" >
              <i class="glyphicon glyphicon-trash"></i>
            </a>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>