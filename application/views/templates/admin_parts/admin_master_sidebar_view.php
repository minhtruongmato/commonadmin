<?php
//if($this->ion_auth->logged_in()) {
//?>
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="<?php echo site_url('assets/public/img/user2-160x160.jpg') ?>" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>Alexander Pierce</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <!-- search form -->
            <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
                </div>
            </form>
            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>
                <li class="active">
                    <a href="<?php echo base_url('admin') ?>">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="">
                        <i class="fa fa-home"></i>
                        <span>Homepage</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo base_url('admin/homepage') ?>"><i class="fa fa-desktop"></i> Overview</a></li>
                        <li><a href="<?php echo base_url('admin/homepage/slider') ?>"><i class="fa fa-picture-o"></i> Slider</a></li>
                        <li><a href="<?php echo base_url('admin/homepage/featured') ?>"><i class="fa fa-star-o"></i> Featured</a></li>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/post_category') ?>">
                        <i class="fa fa-inbox"></i> <span>Danh Mục Bài Viết</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/post') ?>">
                        <i class="fa fa-envelope-o"></i> <span>Bài Viết</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/menu') ?>">
                        <i class="fa fa-envelope-o"></i> <span>Menu</span>
                    </a>
                </li>
                <li class="header">DOCUMENTATION</li>
                <li>
                    <a href="<?php echo base_url('admin/documentation') ?>">
                        <i class="fa fa-book"></i> <span>Documentation</span>
                    </a>
                </li>

            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>
<?php //} ?>



