<?php

if (!function_exists('class_basename')) {
    /**
     * 获取类的basename（不包含命名空间的类名）
     *
     * @param  string|object  $class
     * @return string
     */
    function class_basename($class): string
    {
        $class = is_object($class) ? get_class($class) : $class;
        return basename(str_replace('\\', '/', $class));
    }
}

if (!function_exists('dd')) {
    /**
     * 调试函数：打印变量并终止脚本执行
     *
     * @param  mixed  $data
     * @return void
     */
    function dd(...$data): void
    {
        foreach ($data as $item) {
            var_dump($item);
        }
        exit(1);
    }
}