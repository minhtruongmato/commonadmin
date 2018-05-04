<link rel="stylesheet" href="<?php echo site_url('assets/sass/admin/') ?>detail.css">


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Chi tiết
            <small>
                <?php 
                    switch ($controller) {
                        case 'post_category':
                            echo "Danh Mục";
                            break;
                        case 'post':
                            echo "Bài Viết";
                            break;
                        default:
                            # code...
                            break;
                    }
                             ?>
            </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="#"><i class="fa fa-dashboard"></i> Chi tiết</a></li>
            <li class="active">
                <?php 
                    switch ($controller) {
                        case 'post_category':
                            echo "Danh Mục";
                            break;
                        case 'post':
                            echo "Bài Viết";
                            break;
                        default:
                            # code...
                            break;
                    }
                             ?>
            </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-9">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Chi tiết</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="detail-image col-md-6">
                                <label>Hình ảnh</label>
                                <div class="row">
                                    <div class="item col-md-12">
                                        <div class="mask-lg">
                                            <img src="<?php echo base_url('assets/upload/'.$controller.'/'. $detail['image'] ) ?>" alt="Image Detail" width=300px>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="detail-info col-md-6">
                                <div class="table-responsive">
                                    <label>Thông tin</label>
                                    <table class="table table-striped">
                                        <tr>
                                            <th colspan="2">Thông tin cơ bản</th>
                                        </tr>
                                        <tr>
                                            <th>Slug</th>
                                            <td><?php echo $detail['slug'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Danh Mục</th>
                                            <td><?php echo $detail['parent_title'] ?></td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12">

                                <!-- Nav tabs -->
                                <ul class="nav nav-pills nav-justified" role="tablist">
                                    <?php $i = 0; ?>
                                    <?php foreach ($page_languages as $key => $value): ?>
                                        <li role="presentation" class="<?php echo ($i == 0)? 'active' : '' ?>">
                                            <a href="#<?php echo $key ?>" aria-controls="<?php echo $key ?>" role="tab" data-toggle="tab">
                                                <span class="badge"><?php echo $i + 1 ?></span> <?php echo $value ?>
                                            </a>
                                        </li>
                                        <?php $i++; ?>
                                    <?php endforeach ?>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <?php $i = 0; ?>
                                    <?php foreach ($template as $key => $value): ?>
                                        <div role="tabpanel" class="tab-pane <?php echo ($i == 0)? 'active' : '' ?>" id="<?php echo $key ?>">
                                            <?php foreach ($value as $k => $val): ?>
                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <tbody>
                                                            <?php if ($k == 'title' && in_array($k, $request_language_template)): ?>
                                                                <tr>
                                                                    <th style="width: 100px">Tiêu đề: </th>
                                                                    <td><?php echo $detail['title_'. $key] ?></td>
                                                                </tr>
                                                            <?php elseif($k == 'description' && in_array($k, $request_language_template)): ?>
                                                                <tr>
                                                                    <th style="width: 100px">Giới thiệu: </th>
                                                                    <td><?php echo $detail['description_'. $key] ?></td>
                                                                </tr>
                                                            <?php elseif($k == 'content' && in_array($k, $request_language_template)): ?>
                                                                <tr>
                                                                    <th style="width: 100px">Nội dung: </th>
                                                                    <td><?php echo $detail['content_'. $key] ?></td>
                                                                </tr>
                                                            <?php endif ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php endforeach ?>
                                        </div>
                                    <?php $i++; ?>
                                    <?php endforeach ?>
                                    <div role="tabpanel" class="tab-pane" id="en">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <tbody>
                                                    <tr>
                                                        <th style="width: 100px">Title: </th>
                                                        <td><?php echo $detail['title_en'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 100px">Description: </th>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 100px">Content: </th>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title">Chỉnh sửa 
                            <?php 
                                switch ($controller) {
                                    case 'post_category':
                                        echo "Danh Mục";
                                        break;
                                    case 'post':
                                        echo "Bài Viết";
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                             ?>
                         này?</h3>
                    </div>
                    <div class="box-body">
                        <a href="<?php echo base_url('admin/'.$controller.'/edit/'.$detail['id']) ?>" class="btn btn-warning" role="button">Chỉnh sửa</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
        <!-- END ACCORDION & CAROUSEL-->
    </section>
</div>