# HW4_03

作業四：**Laravel 留言板**  

## 組別三 成員分工表

| 組員       | GitHub 個人頁面                                      | 負責項目                                                         |
|------------|-------------------------------------------------------|------------------------------------------------------------------|
| 許惟婷     | [GitHub](https://github.com/sdsfvvbn)                | profile頁面開發                                                    |
| 楊昊       | [GitHub](https://github.com/hunteryang0108)          | 環境建置、專案基礎資料庫建置、登入登出功能                   |
| 柴映竹     | [GitHub](https://github.com/Crazeah)                 | 留言板功能（CRUD、收藏、評論等）、搜尋功能、UI                       |


## 專案特色

### 用戶系統
- 用戶註冊、登入功能  
- 個人檔案管理與客製化  
- 密碼重設與帳號安全管理  

### 文章系統
- 發佈、編輯、刪除文章  
- 文章分類和標籤系統  
- 文章圖片上傳功能  

### 互動功能
- 文章評論系統  
- 點讚功能  
- 收藏文章功能  
- 社交分享功能  

### 搜尋與過濾
- 高級搜尋功能（標題、內容、用戶）  
- 搜尋建議與歷史記錄  
- 標籤過濾系統  
- 排序選項（最新、熱門討論）  

### 用戶體驗優化
- 響應式設計  
- 暗黑模式支援  

---

## 使用技術

### 後端框架
- Laravel 框架  
- PHP 8.2  

### 前端技術
- Livewire / Volt - 無需編寫 JavaScript 的互動式界面  
- Flux UI 元件庫 - 現代化 UI 元件  
- Tailwind CSS - 實用優先的 CSS 框架  
- Alpine.js - 輕量級 JavaScript 框架  

### 資料庫
- SQLite 數據庫  
- Eloquent ORM - 關聯模型管理  
- 資料庫遷移和種子數據  

### 開發工具
- Docker 容器化開發環境  
- Composer 依賴管理  
- Node.js / npm 前端工具  
- Vite 前端構建工具  

### 資料處理
- 標籤處理（使用 Tagify）  
- 圖片處理和上傳  
- 資料搜尋與過濾  

### 安全性措施
- CSRF 保護  
- 身份驗證與授權  
- 表單驗證  
- 密碼雜湊 

## Setup

### Requirements

- [**GNU Make**](https://www.gnu.org/software/make/)
- [**Docker Engine**](https://docs.docker.com/engine/)

### Package Installation

這是 `Ubuntu` 上的安裝流程，其他 OS 參考就好 :)

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y make ca-certificates curl

sudo install -m 0755 -d /etc/apt/keyrings
sudo curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
sudo chmod a+r /etc/apt/keyrings/docker.asc

echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/ubuntu \
  $(. /etc/os-release && echo "${UBUNTU_CODENAME:-$VERSION_CODENAME}") stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
```

### Clone Repository

這個其實不用寫的對吧哈哈，但 `make` 之前記得要 `cd` !

```git
git clone https://github.com/hunteryang0108/HW4_03.git
cd HW4_03
```

---

## Docker Service

### Initialization

`make` 會自動執行以下步驟：

 - 從 [Docker Official Image](https://hub.docker.com/_/docker) 下載 `Composer` 、 `nginx` 、 `PHP-FPM` 及 `PHP` 官方映像檔
 - 打包 **Dev Container** （包含 `PHP` 、 `Composer` 及 `Node`）開發容器映像檔
 - 安裝 `Composer` 所需套件、安裝 `Node.js` 所需套件並執行 `Vite` 資源打包
 - 建立並啟動「 `prod` 伺服器」及「 `dev` 伺服器」容器

```bash
make      # 初始化+啟動容器
make dev  # 進入開發容器
```

※ `php artisan` 、 `composer` 及 `npm` 等指令**要在開發容器中執行！**

### Make Commands

shell: `make <command>`

Command | Description
-|-
up | 建立並啟動所有容器
down | 停止並移除所有容器
reup | 重新建立並啟動所有容器
start | 啟動所有容器
stop | 停止所有容器
restart | 重新啟動所有容器
clean | 清理所有容器與映像檔
dev | 進入開發容器（ `dev` 伺服器）
prod | 部屬至生產環境（停用 `dev` 伺服器）
env | 初始化伺服器環境
seed | 進行資料填充（ `prod` 伺服器）

### Network Configuration

Bind Address | Network | Service
-|-|-
`0.0.0.0:80` | `prod` | nginx
`-:9000` | `prod` | PHP-FPM
`127.0.0.1:8000` | `dev` | Laravel Dev Server
`127.0.0.1:5173` | `dev` | Vite

---

## Dev Notes

### Services

 - #### `PORT 80` 是 `prod` 伺服器

   - 環境變數 `.env.local`
   - 資料庫 `/database/local.sqlite`
   - 可以從外網連線存取
   - 靜態資源需要經過 `Vite` 打包（`npm run build`）

 - #### `PORT 8000` 是 `dev` 伺服器

   - 環境變數 `.env.production`
   - 資料庫 `/database/production.sqlite`
   - 必須從本機連線存取
   - 支援模組熱替換（`HMR`）頁面即時更新
   - `HTML` 需要包含 `@vite()` 才會進行 `HMR`

### CSS Setup

 - 透過 `Vite` 載入 `CSS`　※ 可以嵌入 `partials/head` 中

```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
@vite(['resources/js/bootstrap.js']) {{-- 使用 Bootstrap }}
```

 - 使用 `Bootstrap` 的 `Class` 需要添加 `Prefix` ： `bs-`

```css
.btn          =>   .bs-btn
.btn.active   =>   .bs-btn.bs-active
```

### Database Migration

如果**資料庫結構**有更新（`/database/migrations`）  
在執行 `git pull` 後，需要進行 **Schema Migration**

```bash
git pull
make dev
php artisan migrate
```
