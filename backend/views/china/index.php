<?php
// 定义标题和面包屑信息
$this->title = 'TestModule';
?>
<?=\backend\widgets\MeTable::widget()?>
<?php $this->beginBlock('javascript') ?>
<script type="text/javascript">
    var m = meTables({
        title: "TestModule",
        table: {
            "aoColumns": [
            {"title": "地址ID", "data": "id", "sName": "id", "edit": {"type": "text", "required": true,"number": true}, "bSortable": false},
			{"title": "地址名称", "data": "name", "sName": "name", "edit": {"type": "text", "required": true,"rangelength": "[2, 32]"}, "bSortable": false}, 
			{"title": "父类ID", "data": "pid", "sName": "pid", "edit": {"type": "text", "number": true}, "bSortable": false},
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

     $(function(){
         m.init();
     });
</script>
<?php $this->endBlock(); ?>