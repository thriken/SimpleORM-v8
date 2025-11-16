<?php ob_start(); ?>
<div class="card">
    <h2>学生列表</h2>
    <div style="margin-bottom: 1rem;">
        <a href="<?= SITE_ROOT ?>/?page=students&action=create" class="btn btn-success">添加学生</a>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>姓名</th>
                <th>学号</th>
                <th>性别</th>
                <th>班级</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($students)): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">暂无学生数据</td>
                </tr>
            <?php else: ?>
                <?php foreach ($students as $student): ?>
                    <?php
                    $classroom = \App\Models\Classroom::find($student['classroom_id']);
                    $className = $classroom ? $classroom->name : '未知班级';
                    ?>
                    <tr>
                        <td><?= $student['id'] ?></td>
                        <td><?= htmlspecialchars($student['name']) ?></td>
                        <td><?= htmlspecialchars($student['student_id']) ?></td>
                        <td><?= htmlspecialchars($student['gender']) ?></td>
                        <td><?= htmlspecialchars($className) ?></td>
                        <td>
                            <a href="<?= SITE_ROOT ?>/?page=students&action=show&id=<?= $student['id'] ?>" class="btn">查看详情</a>
                            <a href="<?= SITE_ROOT ?>/?page=students&action=edit&id=<?= $student['id'] ?>" class="btn btn-warning">编辑</a>
                            <a href="<?= SITE_ROOT ?>/?page=students&action=delete&id=<?= $student['id'] ?>" class="btn btn-danger" 
                               onclick="return confirm('确定要删除这个学生吗？')">删除</a>
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