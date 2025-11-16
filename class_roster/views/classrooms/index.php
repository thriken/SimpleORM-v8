<?php ob_start(); ?>
<div class="card">
    <h2>班级列表</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>班级名称</th>
                <th>年级</th>
                <th>学生数</th>
                <th>老师数</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($classrooms)): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">暂无班级数据</td>
                </tr>
            <?php else: ?>
                <?php foreach ($classrooms as $classroom): ?>
                    <?php
                    // 获取学生数量
                    $studentCount = \App\Models\Student::query()->where('classroom_id', $classroom['id'])->count();
                    
                    // 获取老师数量
                    $teacherCount = \App\Models\ClassroomTeacher::query()->where('classroom_id', $classroom['id'])->count();
                    ?>
                    <tr>
                        <td><?= $classroom['id'] ?></td>
                        <td><?= htmlspecialchars($classroom['name']) ?></td>
                        <td><?= htmlspecialchars($classroom['grade']) ?></td>
                        <td><?= $studentCount ?></td>
                        <td><?= $teacherCount ?></td>
                        <td>
                            <a href="<?= SITE_ROOT ?>/?page=classrooms&action=show&id=<?= $classroom['id'] ?>" class="btn">查看详情</a>
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