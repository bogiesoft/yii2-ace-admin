<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\Alert;

$this->title = '用戶分配组';

$depends = ['depends' => 'backend\assets\AdminAsset'];
$this->registerJsFile('@web/public/assets/js/jstree/jstree.min.js', $depends);
$this->registerCssFile('@web/public/assets/js/jstree/default/style.css', $depends);
?>
<?= Alert::widget() ?>
<?php $form = ActiveForm::begin(['enableClientValidation' => true]); ?>
    <div class="col-xs-12 col-sm-3">
        <div class="col-xs-12 col-sm-12 widget-container-col  ui-sortable">
            <!-- #section:custom/widget-box -->
            <div class="widget-box  ui-sortable-handle">
                <div class="widget-header">
                    <h5 class="widget-title"><?= Yii::t('app', 'Group'); ?></h5>
                    <!-- #section:custom/widget-box.toolbar -->
                    <div class="widget-toolbar">
                        <a class="orange2" data-action="fullscreen" href="#">
                            <i class="ace-icon fa fa-expand"></i>
                        </a>
                        <a data-action="reload" href="#">
                            <i class="ace-icon fa fa-refresh"></i>
                        </a>
                        <a data-action="collapse" href="#">
                            <i class="ace-icon fa fa-chevron-up"></i>
                        </a>
                    </div>

                    <!-- /section:custom/widget-box.toolbar -->
                </div>


                <div class="widget-body">
                    <div class="widget-main">
                        <input type="hidden" name="UserGroups[id]" value="<?= $model->username ?>"/>
                        <?php
                        echo $form->field($model, 'username')
                            ->textInput($model->isNewRecord ? [] : ['disabled' => 'disabled']) .
                            $form->field($model, 'email')->textarea(['style' => 'height: 100px'])
                            .Html::submitButton(
                                $model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'),
                                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
                            );
                        ?>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-xs-12 col-sm-12 widget-container-col  ui-sortable">
            <!-- #section:custom/widget-box -->
            <div class="widget-box ui-sortable-handle">
                <div class="widget-header">
                    <h5 class="widget-title">组别</h5>
                    <!-- #section:custom/widget-box.toolbar -->
                    <div class="widget-toolbar">
                        <a class="orange2" data-action="fullscreen" href="#">
                            <i class="ace-icon fa fa-expand"></i>
                        </a>
                        <a data-action="reload" href="#">
                            <i class="ace-icon fa fa-refresh"></i>
                        </a>
                        <a data-action="collapse" href="#">
                            <i class="ace-icon fa fa-chevron-up"></i>
                        </a>
                    </div>

                    <!-- /section:custom/widget-box.toolbar -->
                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <div id="tree-one" class="tree tree-selectable"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 widget-container-col  ui-sortable">
            <!-- #section:custom/widget-box -->
            <div class="widget-box ui-sortable-handle">
                <div class="widget-header">
                    <h5 class="widget-title">平台</h5>
                    <!-- #section:custom/widget-box.toolbar -->
                    <div class="widget-toolbar">
                        <a class="orange2" data-action="fullscreen" href="#">
                            <i class="ace-icon fa fa-expand"></i>
                        </a>
                        <a data-action="reload" href="#">
                            <i class="ace-icon fa fa-refresh"></i>
                        </a>
                        <a data-action="collapse" href="#">
                            <i class="ace-icon fa fa-chevron-up"></i>
                        </a>
                    </div>

                    <!-- /section:custom/widget-box.toolbar -->
                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <div id="tree-two" class="tree tree-selectable"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 widget-container-col  ui-sortable">
            <!-- #section:custom/widget-box -->
            <div class="widget-box ui-sortable-handle">
                <div class="widget-header">
                    <h5 class="widget-title">菜单</h5>
                    <!-- #section:custom/widget-box.toolbar -->
                    <div class="widget-toolbar">
                        <a class="orange2" data-action="fullscreen" href="#">
                            <i class="ace-icon fa fa-expand"></i>
                        </a>
                        <a data-action="reload" href="#">
                            <i class="ace-icon fa fa-refresh"></i>
                        </a>
                        <a data-action="collapse" href="#">
                            <i class="ace-icon fa fa-chevron-up"></i>
                        </a>
                    </div>

                    <!-- /section:custom/widget-box.toolbar -->
                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <div id="tree-three" class="tree tree-selectable"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-9 widget-container-col  ui-sortable">
        <!-- #section:custom/widget-box -->
        <div class="widget-box ui-sortable-handle">
            <div class="widget-header">
                <h5 class="widget-title"><?= Yii::t('app', 'Permissions'); ?></h5>
                <!-- #section:custom/widget-box.toolbar -->
                <div class="widget-toolbar">
                    <a class="orange2" data-action="fullscreen" href="#">
                        <i class="ace-icon fa fa-expand"></i>
                    </a>
                    <a data-action="reload" href="#">
                        <i class="ace-icon fa fa-refresh"></i>
                    </a>
                    <a data-action="collapse" href="#">
                        <i class="ace-icon fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
<?php $this->beginBlock('javascript') ?>
<script type="text/javascript">
    $(function(){
        $("#tree-one").jstree({
            "plugins" : ["checkbox" ],
            core: {
                "animation" : 0,
                "check_callback" : true,
                data: <?=yii\helpers\Json::encode($trees)?>
            }
        }).on('changed.jstree', function(e, data){
            var i, j, str;
            for(i = 0, j = data.selected.length; i < j; i++) {
                str = data.instance.get_node(data.selected[i]).data;
                if (str) {
                    var arr = str.split("/");
                    $("input[value^=" + arr[0] + "]").attr('checked', true).each(function(){
                        this.checked = true;
                    });
                }
            }
        }).on('deselect_node.jstree', function(obj, data, event) {
            var str = data.node.data;
            if (str) {
                str = str.split("/");
                $("input[value^=" + str[0] + "]").attr('checked', false).each(function(){
                    this.checked = false;
                })
            }

            var i, j;
            for(i = 0, j = data.selected.length; i < j; i++) {
                str = data.instance.get_node(data.selected[i]).data;
                console.info(str);
                if (str) {
                    var arr = str.split("/");
                    $("input[value^=" + arr[0] + "]").attr('checked', false).each(function(){
                        this.checked = false;
                    });
                }
            }
        });

        // 全部选择
        $('.allChecked').click(function(){
            var isChecked = this.checked;
            $('input[type=checkbox]').each(function(){if ($(this).attr('checked', isChecked).get(0)) $(this).get(0).checked = isChecked;});
        });


    });
</script>
<?php $this->endBlock(); ?>
