# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## 项目概述

这是一个基于 PHP 的定时任务管理系统，使用 Swoole 多进程架构，支持 Cron 表达式调度。系统主要组件包括：

- **内核系统**: `Kernel/` - 负责进程管理、任务调度和自动加载
- **任务应用**: `Application/` - 具体的业务任务实现，每个任务继承 `ApplicationInterface`
- **数据访问**: `Dao/` - 数据库访问层，使用 Eloquent ORM
- **模型层**: `Model/` - 数据模型定义
- **配置系统**: `Configure/` - 系统配置管理
- **公共库**: `Library/` - 工具类和第三方服务封装

## 核心架构

### 任务调度流程
1. 主进程通过 `KernelMain` 启动，循环检查待执行任务
2. 从数据库读取任务状态，使用 Cron 表达式计算下次执行时间
3. 为每个任务创建独立的 Swoole 子进程执行
4. 进程监控机制检测异常任务并自动恢复

### 任务定义
- 所有业务任务实现 `Application\ApplicationInterface`
- 任务类存放在 `Application/` 目录下的业务模块中
- 通过 `KernelModel` 配置任务的 Cron 表达式和执行参数

## 常用命令

```bash
# 启动定时任务系统
php Main.php listen      # 监听模式
php Main.php daemon      # 守护进程模式

# 调试模式运行单个任务
php Main.php debug [Application Class] [Param]

# 停止系统
php Main.php stop
```

## 开发规范

### 创建新任务
1. 在 `Application/` 对应业务目录下创建任务类
2. 实现 `ApplicationInterface::execute(string $param)` 方法
3. 在数据库中配置任务的 Cron 表达式和相关参数

### 数据库操作
- 使用 Eloquent ORM，模型继承 `BaseModel`
- DAO 层负责复杂查询逻辑
- 任务执行时会自动重连数据库避免连接超时

### 配置管理
- 系统配置通过 `ConfigureLibrary::getConfigure()` 获取
- 配置类实现 `ConfigureInterface`
- 数据库表名等通过配置动态获取

### 进程管理
- 使用 Swoole 多进程，主进程负责调度，子进程执行具体任务
- 进程名格式：`{env}_cron_task_worker_{task_id}`
- 自动检测进程异常并发送告警

## 依赖库

- `mtdowling/cron-expression`: Cron 表达式解析
- `illuminate/database`: Eloquent ORM
- `predis/predis`: Redis 缓存
- `guzzlehttp/guzzle`: HTTP 客户端
- `nesbot/carbon`: 时间处理