<?php

namespace App\Controllers;

use App\Models\Student;
use App\Models\Classroom;

class StudentController
{
    public function index()
    {
        $students = Student::all();
        $classrooms = Classroom::all();
        return $this->render('students/index', [
            'students' => $students,
            'classrooms' => $classrooms
        ]);
    }

    public function show($id)
    {
        $student = Student::find($id);
        if (!$student) {
            return $this->render('errors/404');
        }

        $classroom = Classroom::find($student->classroom_id);

        return $this->render('students/show', [
            'id' => $student->id,
            'student' => $student,
            'classroom' => $classroom
        ]);
    }

    public function create()
    {
        $classrooms = Classroom::all();
        return $this->render('students/create', ['classrooms' => $classrooms]);
    }

    public function store($data)
    {
        // 验证数据
        if (empty($data['name']) || empty($data['student_id']) || empty($data['classroom_id'])) {
            return ['success' => false, 'message' => '请填写所有必填字段'];
        }

        // 检查学号是否已存在
        $existing = Student::query()->where('student_id', $data['student_id'])->first();
        if ($existing) {
            return ['success' => false, 'message' => '学号已存在'];
        }

        // 创建学生
        $student = Student::create([
            'name' => $data['name'],
            'student_id' => $data['student_id'],
            'classroom_id' => $data['classroom_id'],
            'gender' => $data['gender'] ?? '男',
            'birth_date' => $data['birth_date'] ?? date('Y-m-d'),
            'phone' => $data['phone'] ?? '',
            'address' => $data['address'] ?? ''
        ]);

        return ['success' => true, 'message' => '学生创建成功', 'id' => $student->id];
    }

    public function edit($id)
    {
        $student = Student::find($id);
        if (!$student) {
            return $this->render('errors/404');
        }

        $classrooms = Classroom::all();
        return $this->render('students/edit', [
            'student' => $student,
            'classrooms' => $classrooms
        ]);
    }

    public function update($id, $data)
    {
        $student = Student::find($id);
        if (!$student) {
            return ['success' => false, 'message' => '学生不存在'];
        }

        // 更新学生信息
        $student->name = $data['name'];
        $student->student_id = $data['student_id'];
        $student->classroom_id = $data['classroom_id'];
        $student->gender = $data['gender'];
        $student->birth_date = $data['birth_date'];
        $student->phone = $data['phone'];
        $student->address = $data['address'];
        $student->save();

        return ['success' => true, 'message' => '学生信息更新成功'];
    }

    public function delete($id)
    {
        $student = Student::find($id);
        if (!$student) {
            return ['success' => false, 'message' => '学生不存在'];
        }

        $student->delete();
        return ['success' => true, 'message' => '学生删除成功'];
    }

    protected function render($view, $data = [])
    {
        extract($data);
        ob_start();
        include __DIR__ . "/../views/{$view}.php";
        return ob_get_clean();
    }
}