# 💑 Love Space - 情侣空间网站

<div align="center">

![Love Space](icon.png)

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://www.php.net/)
[![MySQL Version](https://img.shields.io/badge/MySQL-5.6%2B-orange.svg)](https://www.mysql.com/)

一个温馨浪漫的情侣空间网站，包含多个实用功能模块，帮助情侣记录美好时光。

[反馈建议](https://github.com/yenyoong99/love_space/issues)

</div>

## 📑 目录

- [✨ 功能特色](#-功能特色)
- [🖼️ 项目预览](#-项目预览) 
- [🚀 快速开始](#-快速开始)
- [🔧 配置说明](#-配置说明)
- [🤝 参与贡献](#-参与贡献)
- [📝 开源协议](#-开源协议)
- [🙏 鸣谢](#-鸣谢)

## ✨ 功能特色

<table>
<tr>
<td>

### 核心功能
- 📝 **留言板** - 随时写下甜蜜的话语
- 💰 **情侣账本** - AI智能账单识别，轻松记账
- 📅 **恋爱日历** - 记录重要的纪念日

</td>
<td>

### 特色亮点
- 🌳 **许愿树** - 记录你们的愿望清单
- ⏳ **时光轴** - 记录你们的点点滴滴
- 🔐 **安全可靠** - 支持人脸/指纹登录验证，保护隐私

</td>
</tr>
</table>

## 🖼️ 项目预览

<table>
<tr>
<td><img src="preview/login.jpg" alt="登录预览"/></td>
<td><img src="preview/love_tree.jpg" alt="许愿树预览"/></td>
</tr>
<tr>
<td><img src="preview/love_tree.jpg" alt="许愿树预览"/></td>
<td><img src="preview/timeline.jpg" alt="时光轴预览"/></td>
</tr>
<tr>
<td><img src="preview/calendar.jpg" alt="日历预览"/></td>
<td><img src="preview/bill.jpg" alt="账单识别预览"/></td>
</tr>
</table>

## 🚀 快速开始

### 环境要求

- PHP 8.2+
- MySQL 5.6+
- Web服务器 (Apache/Nginx)
- OpenAI API Key (用于账单识别功能)

### 部署步骤

<details>
<summary>1. 克隆项目到本地</summary>

```bash
git clone https://github.com/yenyoong99/love_space.git
```
</details>

<details>
<summary>2. 配置数据库</summary>

- 创建新的数据库
- 导入 `database.sql` 文件
- 修改 `conn.php` 中的数据库配置
</details>

<details>
<summary>3. 配置 OpenAI API</summary>

- 在 `api/config/config.php` 中设置你的 OpenAI API Key
</details>

<details>
<summary>4. 配置网站</summary>

- 将项目文件上传到网站根目录
- 确保 `uploads` 目录有写入权限
- 配置网站域名指向项目目录

- Nginx 保护upload图片目录（只有登入用户才能浏览照片）
```nginx
location ~ ^/api/uploads/(.*)$ {
  rewrite ^/api/uploads/(.*)$ /api/view_image.php?image=$1 last;
}
```
</details>

<details>
<summary>5. 完成设置</summary>

- 访问网站首页
- 使用默认密码登录（admin123）
- 建议首次登录后修改密码

```php
<?php
// api/verify_password.php
$correctPassword = "admin123";
```
</details>

### 🔧 配置说明
```php
<?php
// api/config/conn.php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'your_database');
```

### OpenAI配置
```php
<?php
// api/config/config.php
define('OPEN_AI_KEY', 'your_openai_api_key');
```

### 🤝 参与贡献
1. Fork 本项目
2. 创建新的功能分支 (git checkout -b feature/AmazingFeature)
3. 提交更改 (git commit -m 'Add some AmazingFeature')
4. 推送到分支 (git push origin feature/AmazingFeature)
5. 提交 Pull Request

### 📝 开源协议
本项目基于 MIT 协议开源 - 查看 LICENSE 文件了解更多详情

### 🙏 鸣谢
- OpenAI API - 提供智能账单识别功能
- Font Awesome - 提供图标支持
- Tailwind CSS - 提供界面样式支持

📞 联系方式
如有问题或建议，欢迎提出 Issue 或直接联系我邮箱： [yy@yybloger.com](mailto:yy@yybloger.com)。

⭐️ 如果这个项目对你有帮助，欢迎 star 支持！

