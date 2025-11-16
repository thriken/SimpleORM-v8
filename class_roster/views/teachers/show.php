<?php ob_start(); ?>
<div class="card">
    <h2>老师详情</h2>
    <div style="margin-bottom: 1rem;">
        <a href="<?= SITE_ROOT ?>/?page=teachers" class="btn">返回老师列表</a>
    </div>
    
    <div class="card">
        <h3>基本信息</h3>
        <table>
            <tr>
                <th>ID</th>
                <td><?= $teacher->id ?></td>
            </tr>
            <tr>
                <th>姓名</th>
                <td><?= htmlspecialchars($teacher->name) ?></td>
            </tr>
            <tr>
                <th>科目</th>
                <td><?= htmlspecialchars($teacher->subject) ?></td>
            </tr>
            <tr>
                <th>电话</th>
                <td><?= htmlspecialchars($teacher->phone) ?></td>
            </tr>
            <tr>
                <th>地址</th>
                <td><?= htmlspecialchars($teacher->address) ?></td>
            </tr>
        </table>
    </div>
    
    <div class="card">
        <h3>任教班级</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>班级名称</th>
                    <th>年级</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($classrooms)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">该老师暂未任教任何班级</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($classrooms as $classroom): ?>
                        <tr>
                            <td><?= $classroom->id ?></td>
                            <td><?= htmlspecialchars($classroom->name) ?></td>
                            <td><?= htmlspecialchars($classroom->grade) ?></td>
                            <td>
                                <a href="<?= SITE_ROOT ?>/?page=classrooms&action=show&id=<?= $classroom->id ?>" class="btn">查看详情</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>