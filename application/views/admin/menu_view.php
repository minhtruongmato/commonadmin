<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
    /*.numberlist{*/
        /*width:450px;*/
    /*}*/
    .numberlist ol{
        counter-reset: li;
        list-style: none;
        *list-style: decimal;
        font: 15px 'trebuchet MS', 'lucida sans';
        padding: 0;
        margin-bottom: 4em;
    }
    .numberlist ol ol{
        margin: 0 0 0 2em;
    }

    .numberlist a{
        width: 100%;
        position: relative;
        display: inline-block;
        padding: .4em .4em .4em 2em;
        *padding: .4em;
        margin: .5em 0;
        background: #FFF;
        color: #444;
        text-decoration: none;
        -moz-border-radius: .3em;
        -webkit-border-radius: .3em;
        border-radius: .3em;
        text-decoration: none;
    }

    .numberlist a:hover{
        background: #cbe7f8;
        text-decoration:none;
    }
    .numberlist a:before{
        content: counter(li);
        counter-increment: li;
        position: absolute;
        left: -1.3em;
        top: 57%;
        margin-top: -1.3em;
        background: #87ceeb;
        height: 2.4em;
        width: 2.4em;
        line-height: 2em;
        border: .3em solid #fff;
        text-align: center;
        font-weight: bold;
        -moz-border-radius: 2em;
        -webkit-border-radius: 2em;
        border-radius: 2em;
        color:#FFF;
    }

</style>
<div class="content-wrapper" style="min-height: 916px;">
    <section class="content row">
        <div class="container col-md-12">
            <div>
                <span><?php echo $this->session->flashdata('message'); ?></span>
            </div>
            <h3>Quản lý menu chính</h3>
            <?php if ($menus): ?>
                <div class="row">
                    <div class="col-lg-12 numberlist" style="margin-top: 10px;">
                        <ol id="sortable">
                            <?php
                            if (!empty($menus)):
                            foreach ($menus as $key => $item):
                            ?>
                                <li class="treeview ui-sortable-handle" id="<?php echo ($key + 1) . '-' . $item['id'] ?>" style="">
                                    <strong><a style="color:#f02561" href="javascript:void(0)"><?php echo $item['title'] ?></a></strong>
                                </li>
                            <?php
                            endforeach;
                            endif;
                            ?>
                        </ol>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-lg-12" style="margin-top: 10px;">
                        <table>
                            Không tồn tại!
                        </table>
                        
                    </div>
                    
                </div>
            <?php endif ?>
        </div>
    </section>
        <?php //$nav->showCategories($menus, 0); ?>
</div>

<script>
    $( function() {
        $('#sortable').sortable({
            axis: 'y',
            update: function (event, ui) {
                var data = $(this).sortable('serialize');
                console.log(data);

                $.ajax({
                    data: {
                        sort: data,
                    },
                    method: 'GET',
                    url: location.protocol + "//" + location.host + (location.port ? ':' + location.port : '') + "/commonadmin/admin/menu/sort",
                });
            }
        });
    } );
</script>
