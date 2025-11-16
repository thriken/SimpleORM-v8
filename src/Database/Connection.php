<?php

namespace SimpleORM\Database;

use PDO;
use PDOException;

class Connection
{
    /**
     * @var PDO
     */
    protected PDO $pdo;

    /**
     * @var array
     */
    protected array $config;

    /**
     * 构造函数
     *
     * @param array $config 数据库配置
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->connect();
    }

    /**
     * 建立数据库连接
     *
     * @return void
     */
    protected function connect(): void
    {
        $dsn = "mysql:host={$this->config['host']};dbname={$this->config['database']};charset={$this->config['charset']}";
        
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->config['username'], $this->config['password'], $options);
        } catch (PDOException $e) {
            throw new \RuntimeException("数据库连接失败: " . $e->getMessage());
        }
    }

    /**
     * 获取PDO实例
     *
     * @return PDO
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * 开始事务
     *
     * @return void
     */
    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    /**
     * 提交事务
     *
     * @return void
     */
    public function commit(): void
    {
        $this->pdo->commit();
    }

    /**
     * 回滚事务
     *
     * @return void
     */
    public function rollback(): void
    {
        $this->pdo->rollback();
    }

    /**
     * 执行查询并返回所有结果
     *
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function select(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * 执行查询并返回第一行结果
     *
     * @param string $sql
     * @param array $params
     * @return array|null
     */
    public function selectOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() ?: null;
    }

    /**
     * 执行INSERT、UPDATE或DELETE语句
     *
     * @param string $sql
     * @param array $params
     * @return int 影响的行数
     */
    public function statement(string $sql, array $params = []): int
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * 插入记录并返回最后插入的ID
     *
     * @param string $sql
     * @param array $params
     * @return string 最后插入的ID
     */
    public function insert(string $sql, array $params = []): string
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $this->pdo->lastInsertId();
    }
}