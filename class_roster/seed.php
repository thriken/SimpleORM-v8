<?php

require_once __DIR__ . '/autoload.php';

use SimpleORM\Database\DatabaseManager;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassroomTeacher;

// 加载配置
$config = require_once __DIR__ . '/config.php';

// 创建数据库管理器
$dbManager = new DatabaseManager($config);

// 设置模型的数据库管理器
Classroom::setDatabaseManager($dbManager);
Student::setDatabaseManager($dbManager);
Teacher::setDatabaseManager($dbManager);
ClassroomTeacher::setDatabaseManager($dbManager);

echo "开始生成示例数据...\n";

// 创建班级
echo "创建班级...\n";
$classrooms = [];
for ($i = 1; $i <= 3; $i++) {
    $classroom = Classroom::create([
        'name' => "{$i}班",
        'grade' => '高一'
    ]);
    $classrooms[] = $classroom;
    echo "创建班级: {$classroom->name}\n";
}

// 创建老师
echo "创建老师...\n";
$subjects = ['语文', '数学', '英语', '音乐', '体育'];
$teachers = [];
for ($i = 0; $i < 10; $i++) {
    $teacher = Teacher::create([
        'name' => get_random_name(),
        'subject' => $subjects[array_rand($subjects)],
        'phone' => '138' . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT)
    ]);
    $teachers[] = $teacher;
    echo "创建老师: {$teacher->name} ({$teacher->subject})\n";
}

// 为每个班级分配老师
echo "为班级分配老师...\n";
foreach ($classrooms as $classroom) {
    // 随机选择3-5个老师
    $selectedTeachers = array_rand($teachers, rand(3, 5));
    if (!is_array($selectedTeachers)) {
        $selectedTeachers = [$selectedTeachers];
    }
    
    foreach ($selectedTeachers as $teacherIndex) {
        $teacher = $teachers[$teacherIndex];
        ClassroomTeacher::create([
            'classroom_id' => $classroom->id,
            'teacher_id' => $teacher->id
        ]);
        echo "分配老师 {$teacher->name} 到班级 {$classroom->name}\n";
    }
}

// 创建学生
echo "创建学生...\n";
$genders = ['男', '女'];
for ($i = 0; $i < count($classrooms) * 30; $i++) {
    $classroom = $classrooms[array_rand($classrooms)];
    
    $student = Student::create([
        'name' => get_random_name(),
        'student_id' => 'S' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
        'classroom_id' => $classroom->id,
        'gender' => $genders[array_rand($genders)],
        'birth_date' => date('Y-m-d', strtotime('-' . rand(15, 18) . ' years')),
        'phone' => '139' . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT),
        'address' => get_random_address()
    ]);
    
    echo "创建学生: {$student->name} ({$student->student_id}) 在 {$classroom->name}\n";
}

echo "示例数据生成完成!\n";

// 辅助函数：生成随机姓名
function get_random_name() {
    $surnames = ['赵', '钱', '孙', '李', '周', '吴', '郑', '王', '冯', '陈', '褚', '卫', '蒋', '沈', '韩', '杨'];
    $givenNames = ['伟', '芳', '娜', '敏', '静', '丽', '强', '磊', '军', '洋', '勇', '艳', '杰', '娟', '涛', '明', '超', '秀英', '霞', '平'];
    
    $surname = $surnames[array_rand($surnames)];
    $givenName1 = $givenNames[array_rand($givenNames)];
    $givenName2 = $givenNames[array_rand($givenNames)];
    
    return $surname . $givenName1 . $givenName2;
}

// 辅助函数：生成随机地址
function get_random_address() {
    $provinces = ['北京市', '上海市', '广东省', '江苏省', '浙江省', '山东省', '河南省', '河北省', '四川省', '湖南省'];
    $cities = ['朝阳区', '浦东新区', '广州市', '南京市', '杭州市', '济南市', '郑州市', '石家庄市', '成都市', '长沙市'];
    $districts = ['第一街道', '第二街道', '第三街道', '中心街道', '东区街道', '西区街道', '南区街道', '北区街道'];
    
    return $provinces[array_rand($provinces)] . $cities[array_rand($cities)] . $districts[array_rand($districts)] . rand(1, 100) . '号';
}