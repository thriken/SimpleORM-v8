<?php ob_start(); ?>
<div class="card">
    <h2>老师列表</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>姓名</th>
                <th>科目</th>
                <th>任教班级数</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($teachers)): ?>
                <tr>
                    <td colspan="5" style="text-align: center;">暂无老师数据</td>
                </tr>
            <?php else: ?>
                <?php foreach ($teachers as $teacher): ?>
                    <?php
                    // 获取任教班级数量
                    $classroomCount = \App\Models\ClassroomTeacher::query()->where('teacher_id', $teacher['id'])->count();
                    ?>
                    <tr>
                        <td><?= $teacher['id'] ?></td>
                        <td><?= htmlspecialchars($teacher['name']) ?></td>
                        <td><?= htmlspecialchars($teacher['subject']) ?></td>
                        <td><?= $classroomCount ?></td>
                        <td>
                            <a href="<?= SITE_ROOT ?>/?page=teachers&action=show&id=<?= $teacher['id'] ?>" class="btn">查看详情</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>