<?php ob_start(); ?>
<div class="card">
    <h2>班级详情</h2>
    <div style="margin-bottom: 1rem;">
        <a href="<?= SITE_ROOT ?>/?page=classrooms" class="btn">返回班级列表</a>
    </div>
    
    <div class="card">
        <h3>基本信息</h3>
        <table>
            <tr>
                <th>ID</th>
                <td><?= $classroom->id ?></td>
            </tr>
            <tr>
                <th>班级名称</th>
                <td><?= htmlspecialchars($classroom->name) ?></td>
            </tr>
            <tr>
                <th>年级</th>
                <td><?= htmlspecialchars($classroom->grade) ?></td>
            </tr>
        </table>
    </div>
    
    <div class="card">
        <h3>学生列表</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>姓名</th>
                    <th>学号</th>
                    <th>性别</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">该班级暂无学生</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student->id) ?></td>
                            <td><?= htmlspecialchars($student->name) ?></td>
                            <td><?= htmlspecialchars($student->student_id) ?></td>
                            <td><?= htmlspecialchars($student->gender) ?></td>
                            <td>
                                <a href="<?= SITE_ROOT ?>/?page=students&action=show&id=<?= $student->id ?>" class="btn">查看详情</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="card">
        <h3>任课老师</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>姓名</th>
                    <th>科目</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($teachers)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">该班级暂无任课老师</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($teachers as $teacher): ?>
                        <tr>
                            <td><?= $teacher->id ?></td>
                            <td><?= htmlspecialchars($teacher->name) ?></td>
                            <td><?= htmlspecialchars($teacher->subject) ?></td>
                            <td>
                                <a href="<?= SITE_ROOT ?>/?page=teachers&action=show&id=<?= $teacher->id ?>" class="btn">查看详情</a>
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