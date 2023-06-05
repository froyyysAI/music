<?php
/* @var $this UserController */

$this->breadcrumbs=array(
	'User',
);
?>

<!--登录表单-->
<?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                    'id'=>'admin_user_form',
                    'clientOptions'=>array(
                        'validateOnSubmit'=>true,
                    ),
            ));
?>

<?php echo $form->textFieldRow($model, 'username', array('class'=>'span3')); ?>

<?php echo $form->passwordFieldRow($model, 'password', array('class'=>'span3')); ?>


<?php echo $form->errorSummary($model); ?>

<label>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'登录')); ?>
</label>


<?php $this->endWidget();?>

