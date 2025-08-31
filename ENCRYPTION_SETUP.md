# 🔐 Biltix Encryption/Decryption Tool Setup

## Setup aur Configuration:

### 1. Routes Setup (web.php):
```php
Route::get('/enc-dec', 'App\Http\Controllers\Controller@encryptIndex')
    ->name('encryptpage')
    ->middleware('basic.auth');

Route::post('/enc-dec', 'App\Http\Controllers\Controller@changeEncDecData')
    ->name('web.enc-dec-data');
```

### 2. Middleware Setup:
- **BasicAuth Middleware** (`app/Http/Middleware/BasicAuth.php`) - HTTP Basic Authentication
- **Environment variables required:**
  - `USER_FOR_ENC_DEC` - Username
  - `PASSWORD_FOR_ENC_DEC` - Password

### 3. Controller Methods:
- `encryptIndex()` - Page load karta hai
- `changeEncDecData()` - Encryption/Decryption process karta hai

### 4. Helper Class:
- **EncryptDecrypt helper class** (`app/Helpers/EncryptDecrypt.php`)
- Uses **AES-256-CBC encryption**
- **Required config values:**
  - `SECRET` - 32 character secret key
  - `IV` - 16 character initialization vector

### 5. Environment Variables (.env):
```env
APP_SECRET=biltix_secret_key_construction_2024
APP_IV=biltix_iv_16char
USER_FOR_ENC_DEC=admin
PASSWORD_FOR_ENC_DEC=biltix123
```

## 🚀 Usage:

1. **Access URL:** `http://localhost:8000/enc-dec`
2. **Login:** Username: `admin`, Password: `biltix123`
3. **Enter text** to encrypt/decrypt
4. **Select operation** (Encrypt/Decrypt)
5. **Click Process Data**

## ✅ Files Created/Modified:

- ✅ Routes added to `web.php`
- ✅ `BasicAuth` middleware created
- ✅ Middleware registered in `Kernel.php`
- ✅ Environment variables added to `.env`
- ✅ Controller methods already exist in `Controller.php`
- ✅ `EncryptDecrypt` helper already exists

**Ready to use!** 🔐