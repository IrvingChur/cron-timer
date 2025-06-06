# Cron Timer - PHP 定时任务管理系统

基于 PHP + Swoole 的高性能多进程定时任务管理系统，支持 Cron 表达式调度、进程监控和自动恢复。

## 特性

- 🚀 **高性能**: 基于 Swoole 多进程架构，支持并发任务执行
- ⏰ **灵活调度**: 支持标准 Cron 表达式，精确到秒级调度
- 🔄 **自动恢复**: 进程监控机制，自动检测并重启异常任务
- 📊 **数据持久化**: 基于 Eloquent ORM，支持任务状态和执行记录
- 🔧 **易于扩展**: 插件化任务架构，简单实现新的业务任务
- 📈 **监控告警**: 集成 DingTalk 告警，实时监控系统状态

## 系统要求

- PHP >= 7.2
- Swoole 扩展
- MySQL/MariaDB
- Redis (可选)
- Composer (依赖管理)

## 安装

1. **克隆项目**
   ```bash
   git clone <repository-url>
   cd cron-timer
   ```

2. **安装依赖**
   ```bash
   composer install
   ```

3. **配置数据库**
   - 复制并修改配置文件
   - 导入数据库表结构
   - 配置 `Configure/` 目录下的相关配置

4. **权限设置**
   ```bash
   chmod +x Main.php
   ```

## 快速开始

### 启动系统

```bash
# 前台运行（调试模式）
php Main.php listen

# 后台运行（守护进程）
php Main.php daemon

# 停止系统
php Main.php stop
```

### 调试单个任务

```bash
# 运行示例任务
php Main.php debug "Application\\Example\\ExampleTask" '{"test": true}'

# 运行指定任务（如果有其他任务模块）
php Main.php debug "Application\\YourModule\\YourTask" '{"param": "value"}'
```

## 架构概览

```
cron-timer/
├── Application/          # 业务任务实现
│   ├── ApplicationInterface.php  # 任务接口
│   └── Example/         # 示例任务
│       └── ExampleTask.php
├── Kernel/              # 核心系统
│   ├── Process/         # 进程管理
│   ├── Task/           # 任务调度
│   ├── Register/       # 组件注册
│   └── KernelMain.php  # 主调度器
├── Dao/                # 数据访问层
├── Model/              # 数据模型
├── Configure/          # 系统配置
├── Library/            # 工具库
├── composer.json       # 依赖配置
├── CLAUDE.md          # 开发指南
└── Main.php           # 入口文件
```

## 核心组件

### 1. 任务调度器 (KernelMain)

负责主要的任务调度逻辑：
- 从数据库读取待执行任务
- 根据 Cron 表达式计算执行时间
- 创建子进程执行具体任务
- 监控进程状态和异常处理

### 2. 进程管理器 (ProcessKernel)

管理 Swoole 多进程：
- 进程生命周期管理
- 进程状态监控
- 异常进程自动重启
- 进程命名规范化

### 3. 任务接口 (ApplicationInterface)

统一的任务实现接口：
```php
interface ApplicationInterface {
    public function execute(string $param);
}
```

## 开发指南

### 创建新任务

1. **创建任务类**
   ```php
   <?php
   namespace Application\YourModule;
   
   use Application\ApplicationInterface;
   
   class YourTask implements ApplicationInterface
   {
       public function execute(string $param): void
       {
           // 解析参数
           $data = json_decode($param, true);
           
           // 任务实现逻辑
           echo "任务开始执行...\n";
           echo "参数: {$param}\n";
           
           // 业务处理逻辑
           // ...
           
           echo "任务执行完成\n";
       }
   }
   ```

2. **数据库配置**
   在 `kernel` 表中添加任务配置：
   ```sql
   INSERT INTO kernel (class, cron, param, status) VALUES 
   ('Application\\YourModule\\YourTask', '0 */5 * * * *', '{"key": "value"}', 1);
   ```
   
   字段说明：
   - `class`: 任务类的完整命名空间路径
   - `cron`: Cron 表达式（格式: 秒 分 时 日 月 周）
   - `param`: JSON 格式的任务参数
   - `status`: 任务状态（0=禁用，1=启用）

### 任务开发最佳实践

1. **错误处理**
   ```php
   try {
       // 业务逻辑
   } catch (\Exception $e) {
       // 记录错误日志
       error_log($e->getMessage());
       return false;
   }
   ```

2. **内存管理**
   ```php
   // 处理大量数据时使用 chunk
   Model::chunk(1000, function ($items) {
       foreach ($items as $item) {
           // 处理单条记录
       }
   });
   ```

3. **数据库事务**
   ```php
   DB::transaction(function () {
       // 数据库操作
   });
   ```

## 配置说明

### 数据库配置
在 `Configure/` 目录下配置数据库连接信息。

### 任务配置
通过 `kernel` 表管理任务：
- `class`: 任务类完整路径
- `cron`: Cron 表达式（支持秒级）
- `param`: JSON 格式的任务参数
- `status`: 任务状态（0=禁用，1=启用）

### Cron 表达式格式
```
秒 分 时 日 月 周
0  0  1  *  *  *    # 每天凌晨1点执行
0  */5 * * * *      # 每5分钟执行
0  0  0  */2 * *    # 每2天执行
```

## 监控和运维

### 进程监控
系统提供进程状态监控：
- 进程存活检测
- 资源使用监控
- 异常自动重启

### 日志系统
- 任务执行日志
- 错误异常日志
- 系统运行日志

### 告警通知
集成 DingTalk 机器人，支持：
- 任务执行失败告警
- 系统异常告警
- 进程状态告警

## 性能优化

### 建议配置
- 根据服务器配置调整最大并发进程数
- 合理设置任务执行超时时间
- 定期清理历史日志和数据

### 监控指标
- 进程数量和内存使用
- 任务执行成功率
- 平均执行时间

## 故障排除

### 常见问题

1. **进程无法启动**
   - 检查 Swoole 扩展是否安装
   - 确认数据库连接配置正确
   - 检查文件权限

2. **任务不执行**
   - 检查 Cron 表达式格式
   - 确认任务状态为启用
   - 查看错误日志

3. **内存泄漏**
   - 检查任务中是否有未释放的资源
   - 适当设置进程重启策略

### 调试模式
```bash
# 前台运行查看详细输出
php Main.php listen

# 调试单个任务
php Main.php debug "Application\\Example\\ExampleTask" '{"param": "value"}'

# 查看进程状态
ps aux | grep cron_task_worker
```

## 依赖包

- `mtdowling/cron-expression` ^1.2 - Cron 表达式解析
- `illuminate/database` ^5.8 - Eloquent ORM
- `nesbot/carbon` ^2.19 - 时间处理
- `guzzlehttp/guzzle` ^6.3 - HTTP 客户端
- `predis/predis` ^1.1 - Redis 客户端
- `aliyuncs/oss-sdk-php` ^2.3 - 阿里云 OSS
- `james-heinrich/getid3` ^1.9 - 媒体文件信息

## 贡献指南

1. Fork 本项目
2. 创建特性分支 (`git checkout -b feature/AmazingFeature`)
3. 提交改动 (`git commit -m 'Add some AmazingFeature'`)
4. 推送分支 (`git push origin feature/AmazingFeature`)
5. 创建 Pull Request

## 许可证

请查看 LICENSE 文件了解详细信息。

## 更新日志

### v1.0.0
- 初始版本发布
- 基础任务调度功能
- Swoole 多进程支持
- 进程监控和自动恢复

## 联系方式

如有问题或建议，请提交 Issue 或联系维护团队。