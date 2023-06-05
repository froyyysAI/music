

<form method="post" action="http://up.qiniu.com" enctype="multipart/form-data">
    <input name="token" type="hidden" value="<?php echo $token;?>">
    <input class="input" name="key" type="text" value="<?php echo time(); ?>">

    <input name="file" type="file" />
    <input type="submit" value="提交"/>
</form>
