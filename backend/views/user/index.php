<?php
use yii\helpers\Url;

// 定义标题和面包屑信息
$this->title = '用户信息';
?>
<?= \backend\widgets\MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>
<script type="text/javascript">
    var aStatus = <?=\yii\helpers\Json::encode($status)?>,
        aStatusColor = <?=\yii\helpers\Json::encode($statusColor)?>,
        m = meTables({
            title: "<?=$this->title?>",
            bCheckbox: false,
            buttons: {
                "updateAll": {bShow: false},
                "deleteAll": {bShow: false}
            },
            operations: {
                width: "200px",
                buttons: {
                    "see": {"cClass": "user-see"},
                    "other": {
                        "title": "编辑",
                        "button-title": "编辑",
                        "className": "btn-warning",
                        "cClass": "user-edit",
                        "icon": "fa-pencil-square-o",
                        "sClass": "yellow"
                    }
                }
            },
            table: {
                "aoColumns": [
                    {
                        "title": "用户ID",
                        "data": "id",
                        "sName": "id",
                        "edit": {"type": "hidden"},
                        "search": {"type": "text"},
                        "defaultOrder": "desc"
                    },
                    {
                        "title": "用户账号",
                        "data": "username",
                        "sName": "username",
                        "isHide": false,
                        "edit": {"type": "text", "required": true, "rangelength": "[2, 64]"},
                        "search": {"type": "text"}
                    },
                    {
                        "title": "角色名称",
                        "data": "username",
                        "isHide": true,
                        "sName": "newName",
                        "edit": {"type": "hidden"},
                        "bSortable": false
                    },
                    {
                        "title": "密码",
                        "data": "password",
                        "sName": "password",
                        "isHide": true,
                        "edit": {"type": "password", "rangelength": "[2, 20]"},
                        "bSortable": false,
                        "defaultContent": "",
                        "bViews": false
                    },
                    {
                        "title": "确认密码",
                        "data": "repassword",
                        "sName": "repassword",
                        "isHide": true,
                        "edit": {"type": "password", "rangelength": "[2, 20]", "equalTo": "input[name=password]:first"},
                        "bSortable": false,
                        "defaultContent": "",
                        "bViews": false
                    },
                    {
                        "title": "邮箱",
                        "data": "email",
                        "sName": "email",
                        "edit": {"type": "text", "required": true, "rangelength": "[2, 255]", "email": true},
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
                    {
                        "title": "创建时间",
                        "data": "created_at",
                        "sName": "created_at",
                        "createdCell": meTables.dateTimeString
                    },
                    {"title": "修改时间", "data": "updated_at", "sName": "updated_at", "createdCell": mt.dateTimeString},
                ]
            }
        });

    mt.fn.extend({
        beforeShow: function (data) {
            if (this.action === "update") {
                data.newName = data.username;
            }

            return true;
        },
    });

    var mixLayer = null;

    function layerClose() {
        layer.close(mixLayer);
        mixLayer = null;
    }

    function layerOpen(title, url) {
        if (mixLayer) {
            layer.msg("请先关闭当前的弹出窗口");
        } else {
            mixLayer = layer.open({
                type: 2,
                area: ["90%", "90%"],
                title: title,
                content: url,
                anim: 2,
                maxmin: true,
                cancel: function () {
                    mixLayer = null;
                }
            });
        }
    }

    $(function () {
        m.init();

        // 添加查看事件
        $(document).on('click', '.user-see', function () {
            var data = m.table.data()[$(this).attr('table-data')];
            if (data) {
                layerOpen(
                    "查看" + data["username"] + "(id为" + data["username"] + ") 详情",
                    "<?=Url::toRoute(['user/view'])?>?name=" + data['username']
                );
            }
        });

        // 添加修改权限事件
        $(document).on('click', '.user-edit', function () {
            var data = m.table.data()[$(this).attr('table-data')];
            if (data) {
                layerOpen(
                    "编辑" + data["username"] + "(id为" + data["id"] + ") 信息",
                    "<?=Url::toRoute(['user/edit'])?>?name=" + data['username']
                );
            }
        })
    })
</script>
<?php $this->endBlock(); ?>
