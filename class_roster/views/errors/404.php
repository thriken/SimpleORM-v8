<?php ob_start(); ?>
<div class="card">
    <h2>页面未找到</h2>
    <div class="alert alert-error">
        <p>抱歉，您访问的页面不存在。</p>
    </div>
    <p><a href="<?= SITE_ROOT ?>/" class="btn">返回首页</a></p>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>