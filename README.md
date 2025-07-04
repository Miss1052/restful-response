# restful-response

一个专业的 Laravel 扩展包，用于统一 RESTful API 响应格式，采用接口模式设计，提供标准化的 JSON 响应格式。

## 特性

- 🎯 **统一响应格式**  
  标准化的 JSON 响应结构，让前后端协作更高效。
- 🔌 **接口驱动**  
  基于接口的可扩展设计，便于自定义和扩展。
- 🛡️ **异常处理**  
  自动处理常见异常并格式化为友好的 API 响应。
- 🌐 **多语言支持**  
  内置中英文语言包，支持响应消息国际化。
- 🧪 **完整测试**  
  100% 代码覆盖率，保障稳定可靠。
- 📦 **开箱即用**  
  零配置即可快速开始，轻松集成到项目中。
- 🎨 **灵活配置**  
  响应格式、字段名称均可自定义，适配各种前端需求。

---

## 本地安装

当前扩展包暂未上传至 Packagist/composer，推荐使用本地安装方式：

### 1. 克隆源码到本地

```bash
git clone https://github.com/Miss1052/restful-response.git
```
> 或直接下载源码 zip 并解压到你的项目根目录下的 `restful-response`。

### 2. 配置 composer.json

在你的 Laravel 项目的 `composer.json` 文件中，加入本地包路径（假设你解压到 `restful-response`）：

```json
    "require": {
      "cjj/restful-response": "^1.0.0"
    },
    "repositories": [
      {
        "type": "path",
        "url": "./restful-response"
      }
    ]
```

### 3. 引入扩展包

在项目根目录执行：

```bash
composer require cjj/restful-response 
```
> 注意：如果包名未在 composer.json 中声明

### 4. 发布配置文件（可选）

```bash
php artisan vendor:publish --provider="Cjj\RestfulResponse\Providers\RestfulResponseServiceProvider"
```

### 5. 在 Laravel 项目中使用

安装完成后即可在控制器等地方直接引用扩展包提供的门面、trait 或响应方法：

```php
use Cjj\RestfulResponse\Facades\ApiResponse;

return ApiResponse::success(['foo' => 'bar']);
```

---

如需后续升级 composer 方式安装，可留意本仓库的文档更新。
