
<form class="form-horizontal" action="https://acc.qbox.me/oauth2/token" method="POST" enctype="application/x-www-form-urlencoded">


    <input name="username" type="hidden" value="543884953@qq.com">
    <input name="password" type="hidden" value="yzh4825390">
    <input name="grant_type" type="hidden" value="refresh_token">
    <input name="refresh_token" type="hidden" value="<?php echo $token;?>">

    <div class="control-group">
        <label class="control-label" for="submit" ></label>
        <div class="controls">
            <input type="submit" class="btn btn-success" id="submit" value="重新生成token" />
        </div>
    </div>


</form>


<form class="form-horizontal" action="http://api.qiniu.com/pfop/" method="POST">

    <div class="control-group">
        <label class="control-label" for="key" >key</label>
        <div class="controls">
            <input name="key" type="text" id="key" placeholder="key">
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="fops" >fops</label>
        <div class="controls">
            <input name="x:fops" type="text" id="fops" placeholder="fops">
        </div>
    </div>

    <input name="x:" type="hidden" value="">

    <input name="bucket" type="hidden" value="yangzhihua">
    <input name="token" type="hidden" value="<?php echo $token;?>">

    <div class="control-group">
        <label class="control-label" for="submit" ></label>
        <div class="controls">
            <input type="submit" class="btn btn-primary" id="submit" value="开始请求" />
        </div>
    </div>


</form>