<?php
/* @var $this AlbumController */
/* @var $model Album */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'album-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'year_release'); ?>
		<?php echo $form->textField($model,'year_release',array('size'=>4,'maxlength'=>4)); ?>
		<?php echo $form->error($model,'year_release'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'genre_id'); ?>
		<?php echo $form->textField($model,'genre_id'); ?>
		<?php echo $form->error($model,'genre_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'picture_id'); ?>
		<?php echo $form->textField($model,'picture_id'); ?>
		<?php echo $form->error($model,'picture_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->