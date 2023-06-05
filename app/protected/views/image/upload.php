<?php
/* @var $this ImageController */

$this->breadcrumbs=array(
    'Image',
);
?>

<form role="form" action="<?php echo Yii::app()->createUrl('Image/upload');?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="exampleInputFile">请选择上传的图片</label>
        <input type="file" id="exampleInputFile" name="save_url" />
        <p class="help-block">文件大小 1M 格式 JPG/JPEG/PNG/GIF </p>
    </div>
    <button type="submit" class="btn btn-default">Submit</button>
</form>