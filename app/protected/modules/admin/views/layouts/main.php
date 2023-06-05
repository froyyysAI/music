<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="en"/>

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/styles.css"/>
    <?php Yii::app()->clientScript->registerCoreScript('jquery',CClientScript::POS_END); ?>
    <?php Yii::app()->bootstrap->registerCoreCss(); ?>
    <?php Yii::app()->bootstrap->registerCoreScripts(); ?>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

</head>

<body screen_capture_injected="true">
<!--顶部导航-->
<?php $this->widget('bootstrap.widgets.TbNavbar', array(
    'type'=>'inverse', // null or 'inverse'
    'brand'=>'音乐库后台',
    'brandUrl'=>'javascript:void(0);',
    'collapse'=>true, // requires bootstrap-responsive.css
    'items'=>array(

        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(
                array('label'=>'首页', 'url'=>'#', 'active'=>true),
                array('label'=>'流派', 'url'=>'#','visible' => !Yii::app()->user->isGuest),
                array('label'=>'音乐库管理', 'url'=>'#', 'items'=>array(
                    array('label'=>'添加音乐', 'url'=>'#'),
                    array('label'=>'搜索音乐', 'url'=>'#'),
                    array('label'=>'添加歌手', 'url'=>'#'),
                    '---',
                    array('label'=>'推送音乐'),
                    array('label'=>'购买音乐', 'url'=>'#'),
                    array('label'=>'推广音乐', 'url'=>'#'),
                ),'visible' => !Yii::app()->user->isGuest),
            ),
        ),

        '<form class="navbar-search pull-left" action=""><input type="text" class="search-query span2" placeholder="Search"></form>',

        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'htmlOptions' => array('class' => 'pull-right'),
            'items' => array(
                array('label' => '网站前台', 'url' => array('site/index')),
                array('label' => '站点配置', 'icon' => 'wrench', 'url' => array('/settings/index'), 'visible' => !Yii::app()->user->isGuest),
                array('label' => '登录', 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
                array(
                    'label' => Yii::app()->user->name, 'icon' => 'user', 'url' => '#',
                    'items' => array(
                        array('label' => '个人资料', 'icon' => 'user', 'url' => '#'),
                        array('label' => '退出', 'icon' => 'off', 'url' => array('/site/logout'))
                    ),
                    'visible' => !Yii::app()->user->isGuest
                ),
            ),
        ),

    ),
)); ?>


<!--主体body内容-->
<div class="container-fluid" id="page">
    <?php echo $content; ?>
    <div class="clear"></div>

    <!--底部footer内容-->
    <footer>
        <div class="row-fluid">
            <div class="span12">
                <p class="powered">
                   Powerd By <?php echo CHtml::link('BeiJingRuiDiOu', 'http://www.meikemusic.com'); ?>
                    <span class="copy">Copyright © 2014 by Yincart demo site . All Rights Reserved.</span>
                </p>
            </div>
        </div>
    </footer>
</div>

</body>
</html>
