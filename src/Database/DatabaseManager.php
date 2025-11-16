<?php

namespace SimpleORM\Database;

class DatabaseManager
{
    /**
     * @var array
     */
    protected array $connections = [];

    /**
     * @var array
     */
    protected array $config = [];

    /**
     * 构造函数
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * 获取数据库连接实例
     *
     * @param string|null $name 连接名称
     * @return Connection
     */
    public function connection(?string $name = null): Connection
    {
        $name = $name ?: $this->getDefaultConnection();

        if (!isset($this->connections[$name])) {
            $this->connections[$name] = $this->makeConnection($name);
        }

        return $this->connections[$name];
    }

    /**
     * 创建数据库连接实例
     *
     * @param string $name
     * @return Connection
     */
    protected function makeConnection(string $name): Connection
    {
        $config = $this->getConfig($name);
        return new Connection($config);
    }

    /**
     * 获取默认连接名称
     *
     * @return string
     */
    protected function getDefaultConnection(): string
    {
        return $this->config['default'] ?? 'mysql';
    }

    /**
     * 获取连接配置
     *
     * @param string $name
     * @return array
     */
    protected function getConfig(string $name): array
    {
        return $this->config['connections'][$name] ?? [];
    }

    /**
     * 获取PDO实例
     *
     * @param string|null $name
     * @return \PDO
     */
    public function getPdo(?string $name = null): \PDO
    {
        return $this->connection($name)->getPdo();
    }
}