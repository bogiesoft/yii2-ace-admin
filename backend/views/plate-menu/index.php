<?php
// 定义标题和面包屑信息
$this->title = '子系统菜单';
?>
<?= \backend\widgets\MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>
    <script type="text/javascript">
        var aStatus = <?=\yii\helpers\Json::encode($status)?>,
            aStatusColor = <?=\yii\helpers\Json::encode($statusColor)?>;
        var m = meTables({
            title: "子系统菜单",
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
                        "title": "父级ID",
                        "data": "pid",
                        "sName": "pid",
                        "edit": {"type": "text", "required": true, "number": true},
                        "search": {"type": "text"},
                        "bSortable": false
                    },
                    {
                        "title": "平台ID",
                        "data": "plate_id",
                        "sName": "plate_id",
                        "edit": {"type": "text", "required": true, "number": true},
                        "search": {"type": "text"},
                        "bSortable": false
                    },
                    {
                        "title": "路由地址",
                        "data": "url",
                        "sName": "url",
                        "edit": {"type": "text", "required": true, "rangelength": "[2, 100]"},
                        "search": {"type": "text"},
                        "bSortable": false
                    },
                    {
                        "title": "菜单名称",
                        "data": "name",
                        "sName": "name",
                        "edit": {"type": "text", "rangelength": "[2, 60]"},
                        "search": {"type": "text"},
                        "bSortable": false
                    },
                    {
                        "title": "图标",
                        "data": "icon",
                        "sName": "icon",
                        "edit": {"type": "text", "rangelength": "[2, 25]"},
                        "bSortable": false
                    },
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
                        "edit": {"type": "text", "required": true, "number": true}
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