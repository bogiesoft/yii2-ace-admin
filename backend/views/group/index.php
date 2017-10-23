<?php
// 定义标题和面包屑信息
$this->title = '子系统组别';
?>
<?= \backend\widgets\MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>
    <script type="text/javascript">
        var aStatus = <?=\yii\helpers\Json::encode($status)?>,
            aStatusColor = <?=\yii\helpers\Json::encode($statusColor)?>;
        var m = meTables({
            title: "子系统组别",
            table: {
                "aoColumns": [
                    {
                        "title": "id",
                        "data": "id",
                        "sName": "id",
                        "edit": {"type": "text", "required": true, "number": true},
                        "search": {"type": "text"}
                    },
                    {
                        "title": "用户组名",
                        "data": "group_name",
                        "sName": "group_name",
                        "edit": {"type": "text", "required": true, "rangelength": "[2, 255]"},
                        "search": {"type": "text"},
                        "bSortable": false
                    },
                    {"title": "用户组描述", "data": "desc", "sName": "desc", "edit": {"type": "text",}, "bSortable": false},
                    {
                        "title": "创建时间",
                        "data": "created_at",
                        "sName": "created_at",
                        "edit": {"type": "text", "number": true},
                        "search": {"type": "text"},
                        "createdCell": meTables.dateTimeString
                    },
                    {
                        "title": "更新时间",
                        "data": "updated_at",
                        "sName": "updated_at",
                        "edit": {"type": "text", "number": true},
                        "search": {"type": "text"},
                        "createdCell": meTables.dateTimeString
                    },
                    {
                        "title": "排序",
                        "data": "order",
                        "sName": "order",
                        "edit": {"type": "text", "required": true, "number": true},
                        "bSortable": false
                    },
                    {
                        "title": "创建者",
                        "data": "creator",
                        "sName": "creator",
                        "edit": {"type": "text", "rangelength": "[2, 20]"},
                        "search": {"type": "text"},
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
                    },

                ]
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