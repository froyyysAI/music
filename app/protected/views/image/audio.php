<?php
/* @var $this ImageController */

$this->breadcrumbs=array(
    'audio',
);
?>

<form class="form" action="<?php echo Yii::app()->createUrl('Image/uploadaudio');?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="exampleInputFile">请选择上传的音乐</label>
        <input type="file" id="exampleInputFile" name="audio_name" />
        <p class="help-block">mp3,wma </p>
    </div>
    <button type="submit" class="btn btn-default">提交</button>
</form>