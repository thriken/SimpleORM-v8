<?php

namespace SimpleORM\Migration;

use SimpleORM\Database\DatabaseManager;

class Migrator
{
    /**
     * 数据库管理器实例
     *
     * @var DatabaseManager
     */
    protected DatabaseManager $dbManager;

    /**
     * 迁移表名
     *
     * @var string
     */
    protected string $migrationTable = 'migrations';

    /**
     * 构造函数
     *
     * @param DatabaseManager $dbManager
     */
    public function __construct(DatabaseManager $dbManager)
    {
        $this->dbManager = $dbManager;
    }

    /**
     * 运行所有未执行的迁移
     *
     * @param string $migrationPath 迁移文件路径
     * @return void
     */
    public function run(string $migrationPath = 'migrations'): void
    {
        $this->createMigrationsTable();
        
        $migrations = $this->getMigrationFiles($migrationPath);
        $ranMigrations = $this->getRanMigrations();
        
        foreach ($migrations as $migration) {
            $className = $this->getMigrationClassName($migration);
            
            if (!in_array($className, $ranMigrations)) {
                $this->runMigration($migration, $className);
            }
        }
    }

    /**
     * 回滚最后一次迁移
     *
     * @param string $migrationPath 迁移文件路径
     * @return void
     */
    public function rollback(string $migrationPath = 'migrations'): void
    {
        $migrations = $this->getMigrationFiles($migrationPath);
        $ranMigrations = $this->getRanMigrations();
        
        // 反向排序以回滚最后执行的迁移
        $migrations = array_reverse($migrations);
        
        foreach ($migrations as $migration) {
            $className = $this->getMigrationClassName($migration);
            
            if (in_array($className, $ranMigrations)) {
                $this->rollbackMigration($migration, $className);
                break;
            }
        }
    }

    /**
     * 创建迁移表
     *
     * @return void
     */
    protected function createMigrationsTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->migrationTable} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            batch INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $this->dbManager->connection()->statement($sql);
    }

    /**
     * 获取迁移文件列表
     *
     * @param string $path
     * @return array
     */
    protected function getMigrationFiles(string $path): array
    {
        if (!is_dir($path)) {
            return [];
        }
        
        $files = glob($path . '/*.php');
        sort($files);
        
        return $files;
    }

    /**
     * 获取已执行的迁移
     *
     * @return array
     */
    protected function getRanMigrations(): array
    {
        $results = $this->dbManager->connection()
            ->select("SELECT migration FROM {$this->migrationTable} ORDER BY batch, migration");
            
        return array_column($results, 'migration');
    }

    /**
     * 获取迁移类名
     *
     * @param string $file
     * @return string
     */
    protected function getMigrationClassName(string $file): string
    {
        $filename = basename($file, '.php');
        // 移除时间戳前缀
        $parts = explode('_', $filename, 2);
        return isset($parts[1]) ? $parts[1] : $filename;
    }

    /**
     * 执行迁移
     *
     * @param string $file
     * @param string $className
     * @return void
     */
    protected function runMigration(string $file, string $className): void
    {
        // 定义常量以允许迁移文件执行
        if (!defined('MIGRATION_RUNNER')) {
            define('MIGRATION_RUNNER', true);
        }
        require_once $file;
        
        if (class_exists($className)) {
            $migration = new $className();
            
            if (method_exists($migration, 'up')) {
                $migration->up($this->dbManager->connection());
                
                // 记录已执行的迁移
                $this->dbManager->connection()->insert(
                    "INSERT INTO {$this->migrationTable} (migration, batch) VALUES (?, ?)",
                    [$className, 1] // 简化处理，batch固定为1
                );
            }
        }
    }

    /**
     * 回滚迁移
     *
     * @param string $file
     * @param string $className
     * @return void
     */
    protected function rollbackMigration(string $file, string $className): void
    {
        // 定义常量以允许迁移文件执行
        if (!defined('MIGRATION_RUNNER')) {
            define('MIGRATION_RUNNER', true);
        }
        require_once $file;
        
        if (class_exists($className)) {
            $migration = new $className();
            
            if (method_exists($migration, 'down')) {
                $migration->down($this->dbManager->connection());
                
                // 从迁移表中删除记录
                $this->dbManager->connection()->statement(
                    "DELETE FROM {$this->migrationTable} WHERE migration = ?",
                    [$className]
                );
            }
        }
    }
}