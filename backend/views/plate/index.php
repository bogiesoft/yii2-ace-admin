<?php
// 定义标题和面包屑信息
$this->title = '子系统管理模块';
?>
<?= \backend\widgets\MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>
    <script type="text/javascript">
        var aStatus = <?=\yii\helpers\Json::encode($status)?>,
            aStatusColor = <?=\yii\helpers\Json::encode($statusColor)?>,
            m = meTables({
                title: "子系统管理模块",
                table: {
                    "aoColumns": [{
                        "title": "id",
                        "data": "id",
                        "sName": "id",
                        "edit": {"type": "text", "required": true, "number": true},
                        "search": {"type": "text"}
                    },
                        {
                            "title": "平台唯一标识",
                            "data": "app_id",
                            "sName": "app_id",
                            "edit": {"type": "text", "required": true, "rangelength": "[2, 30]"},
                            "search": {"type": "text"},
                            "bSortable": false
                        },
                        {
                            "title": "密钥",
                            "data": "app_secret",
                            "sName": "app_secret",
                            "edit": {"type": "text", "required": true, "rangelength": "[2, 60]"},
                            "search": {"type": "text"},
                            "bSortable": false
                        },
                        {
                            "title": "平台地址",
                            "data": "plate_host",
                            "sName": "plate_host",
                            "edit": {"type": "text", "required": true, "rangelength": "[2, 200]"},
                            "search": {"type": "text"},
                            "bSortable": false
                        },
                        {
                            "title": "平台名称",
                            "data": "plate_name",
                            "sName": "plate_name",
                            "edit": {"type": "text", "required": true, "rangelength": "[2, 50]"},
                            "search": {"type": "text"},
                            "bSortable": false
                        },
                        {
                            "title": "平台描述",
                            "data": "plate_desc",
                            "sName": "plate_desc",
                            "edit": {"type": "text",},
                            "bSortable": false
                        },
                        {
                            "title": "平台用户默认用户组",
                            "data": "default_group",
                            "sName": "default_group",
                            "edit": {"type": "text", "required": true, "number": true},
                            "bSortable": false
                        },
                        {
                            "title": "创建时间",
                            "data": "created_at",
                            "sName": "created_at",
                            "edit": {"type": "text", "required": true, "number": true},
                            "createdCell": meTables.dateTimeString
                        },
                        {
                            "title": "更新时间",
                            "data": "updated_at",
                            "sName": "updated_at",
                            "edit": {"type": "text", "required": true, "number": true},
                            "createdCell": meTables.dateTimeString
                        },
                        {
                            "title": "创建者",
                            "data": "creator",
                            "sName": "creator",
                            "edit": {"type": "text", "rangelength": "[2, 20]"},
                            "bSortable": false
                        },
                        {
                            "title": "状态", "data": "status", "sName": "status", "value": aStatus,
                            "edit": {"type": "radio", "default": 10, "required": true, "number": true},
                            "bSortable": false,
                            "search": {"type": "select"},
                            "createdCell": function (td, data) {
                                $(td).html(mt.valuesString(aStatus, aStatusColor, data));
                            }
                        }]
                }
            });

        /**
         meTables.fn.extend({
        // 显示的前置和后置操作
        beforeShow: function(data, child) {
            return true;
        },
        afterShow: function(data, child) {
            return true;
        },
        
        // 编辑的前置和后置操作
        beforeSave: function(data, child) {
            return true;
        },
        afterSave: function(data, child) {
            return true;
        }
    });
         */

        $(function () {
            m.init();
        });
    </script>
<?php $this->endBlock(); ?>