<div class="main-head">
    <h2>User
    </h2>
    <ol class="breadcrumb">
        <li>
            <a href="dashboard/user">
                <i class="fa fa-home"></i>
            </a>
        </li>
    </ol>
</div>
<div class="inner-container">
    <form action="dashboard/user/change_password" method="POST" enctype="multipart/form-data">

        <div class="row">
            <div class="col-sm-8">
                <div class="my-box">
                    <h3>Change Password</h3>
                    <div class="form-group clearfix">
                        <label class="col-sm-2 control-label">Old Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="old_password" placeholder="Old Password">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label class="col-sm-2 control-label">New Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="new_password" placeholder="New Password">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label class="col-sm-2 control-label">Confirm Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-2">
                            <input type="submit" name="submit" class="btn my-btn" value="Save">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>