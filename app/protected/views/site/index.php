<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>


<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<div class="row">
    <label class="text-success">日历插件 <small>zii.widgets.jui.CJuiDatePicker</small></label>

    <?php
    $this->widget('zii.widgets.jui.CJuiDatePicker',array(
        'language'=>'zh_cn',
        'name'=>'worktime[start]',
        'options'=>array(
            'maxDate'=>'new Date("+3day")',
            'dateFormat'=>'yy-mm-dd',
        ),
        'htmlOptions'=>array(
            'class'=>'search-query',
        ),
    ));
    ?>
</div>


<div class="row">
    <label class="text-success">弹窗插件 <small>zii.widgets.jui.CJuiDialog</small></label>
    <?php
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'mydialog',//弹窗ID
        // additional javascript options for the dialog plugin
        'options'=>array(//传递给JUI插件的参数
            'title'=>'弹窗标题',
            'autoOpen'=>false,//是否自动打开
            'width'=>'auto',//宽度
            'height'=>'auto',//高度
            'buttons'=>array(
                '关闭'=>'js:function(){ $(this).dialog("close");}',//关闭按钮
                '确认'=>'js:function(){ $(this).dialog("close");}'
            ),

        ),
    ));
    echo '我是主体内容';
    $this->endWidget('zii.widgets.jui.CJuiDialog');
    // 这是弹窗链接,
    echo CHtml::link('open dialog', 'javascript:void(0);', array(
        'onclick'=>'$("#mydialog").dialog("open"); return false;',
        'class'=>'btn btn-primary'
    ));
    ?>
</div>


<div class="row">
    <label class="text-success">拖拽插件 <small>zii.widgets.jui.CJuiDraggable</small></label>

    <?php $this->beginWidget('zii.widgets.jui.CJuiDraggable', array(
        'options'=>array(
            'cursor'=>'move',
        ),
        'htmlOptions'=>array(
            'style'=>'width: 200px; height: 200px;
					 padding: 5px; border: none solid #e3e3e3;
					 background: #0'
        ),
    ));

    $this->endWidget();
    ?>
</div>











