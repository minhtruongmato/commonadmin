<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Cập nhật
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
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-default">
                    <div class="box-body">
                        <?php
                        echo form_open_multipart('', array('class' => 'form-horizontal'));
                        ?>
                        <div class="col-xs-12">
                            <h4 class="box-title">Basic Information</h4>
                        </div>
                        <div class="row">
                            <span><?php echo $this->session->flashdata('message'); ?></span>
                        </div>
                        <div class="form-group col-xs-12">
                            <label for="image_shared">Hình ảnh đang dùng</label>
                            <br>
                            <img src="<?php echo base_url('assets/public/upload/'. $controller .'/'. $detail['image']); ?>" width=250px>
                            <br>
                        </div>
                        <div class="form-group col-xs-12">
                            <?php
                            echo form_label('Ảnh đại diện', 'image_shared');
                            echo form_error('image_shared');
                            echo form_upload('image_shared', set_value('image_shared'), 'class="form-control"');
                            ?>
                            <br>
                        </div>
                        <div class="form-group col-xs-12">
                            <div class="form-group col-xs-12">
                                <?php
                                echo form_label('Slug', 'slug_shared');
                                echo form_error('slug_shared');
                                echo form_input('slug_shared', $detail['slug'], 'class="form-control" id="slug_shared" readonly');
                                ?>
                            </div>
                        </div>

                        <div class="form-group col-xs-12">
                            <div class="form-group col-xs-12">
                                <?php
                                echo form_label('Danh mục', 'parent_id_shared');
                                echo form_error('parent_id_shared');
                                if($controller == 'post_category'){
                                    echo form_dropdown('parent_id_shared', $category, $detail['parent_id'], 'class="form-control"');
                                }
                                if($controller == 'post'){
                                    echo form_dropdown('parent_id_shared', $category, $detail['post_category_id'], 'class="form-control"');
                                }
                                
                                ?>
                            </div>
                        </div>

                        <div>
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
                            <hr>
                            <div class="tab-content">
                                <?php $i = 0; ?>
                                <?php foreach ($template as $key => $value): ?>
                                    <div role="tabpanel" class="tab-pane <?php echo ($i == 0)? 'active' : '' ?>" id="<?php echo $key ?>">
                                        <?php foreach ($value as $k => $val): ?>
                                            <div class="form-group col-xs-12">
                                                <?php
                                                    if($k == 'title' && in_array($k, $request_language_template)){
                                                        echo form_label($val, $k .'_'. $key);
                                                        echo form_error($k .'_'. $key);
                                                        echo form_input($k .'_'. $key, $detail['title_'. $key], 'class="form-control" id="title_'.$key.'"');
                                                    }elseif($k == 'description' && in_array($k, $request_language_template)){
                                                        echo form_label($val, $k .'_'. $key);
                                                        echo form_error($k .'_'. $key);
                                                        echo form_textarea($k .'_'. $key, $detail['description_'. $key], 'class="form-control" rows="5"');
                                                    }elseif($k == 'content' && in_array($k, $request_language_template)){
                                                        echo form_label($val, $k .'_'. $key);
                                                        echo form_error($k .'_'. $key);
                                                        echo form_textarea($k .'_'. $key, $detail['content_'. $key], 'class="tinymce-area form-control" rows="5"');
                                                    }
                                                ?>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                <?php $i++; ?>
                                <?php endforeach ?>
                            </div>
                        </div>
                        <?php echo form_submit('submit_shared', 'OK', 'class="btn btn-primary"'); ?>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url('assets/public/js/admin/script.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/public/js/admin/common.js') ?>"></script>

