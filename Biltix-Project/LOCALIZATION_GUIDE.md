# Multi-Language Support Guide

This Laravel application includes comprehensive multi-language support with English and Arabic (RTL) languages. Here's how to use and extend the localization system.

## Features

✅ **Automatic Language Detection** - Browser language detection  
✅ **RTL Support** - Full right-to-left layout for Arabic  
✅ **Session Persistence** - Language preference saved in session  
✅ **API Localization** - Complete API support for mobile apps  
✅ **Easy Language Switching** - UI components for language switching  
✅ **Fallback System** - Graceful fallback to English if translation missing  
✅ **SEO Friendly** - Proper hreflang tags and meta information  

## Quick Start

### 1. Language Switching

Users can switch languages through:
- **URL Parameter**: `?lang=ar` or `?lang=en`
- **Language Switcher Component**: Available in navigation
- **API Endpoint**: `POST /api/v1/lang/switch/{locale}`

### 2. Using Translations in Blade

```blade
<!-- Basic translation -->
{{ __t('common.welcome') }}

<!-- Translation with fallback -->
{{ __t('common.some_key', [], null, 'Default text if missing') }}

<!-- Translation with parameters -->
{{ __t('common.greeting', ['name' => 'Ahmed']) }}

<!-- Check if RTL -->
@if(is_rtl())
    <div class="text-right">Arabic content</div>
@endif

<!-- RTL CSS classes -->
<div class="{{ rtl_class('rtl-class', 'ltr-class') }}">
    Content
</div>
```

### 3. API Usage

#### Get Available Languages
```bash
GET /api/v1/locales
```

Response:
```json
{
  "current_locale": "en",
  "available_locales": {
    "en": {"name": "English", "native": "English", "flag": "🇺🇸", "dir": "ltr"},
    "ar": {"name": "Arabic", "native": "العربية", "flag": "🇸🇦", "dir": "rtl"}
  },
  "is_rtl": false
}
```

#### Get Translations
```bash
GET /api/v1/translations/common?locale=ar
```

#### Switch Language (API)
```bash
POST /api/v1/lang/switch/ar
```

## File Structure

```
resources/
├── lang/
│   ├── en/
│   │   ├── common.php      # Common translations
│   │   └── admin.php       # Admin panel translations
│   └── ar/
│       ├── common.php      # Arabic common translations
│       └── admin.php       # Arabic admin translations
├── views/
│   ├── layouts/
│   │   └── app.blade.php   # Main layout with RTL support
│   └── components/
│       └── language-switcher.blade.php
└── css/
    └── rtl.css            # RTL-specific styles

app/
├── Http/
│   ├── Controllers/
│   │   └── LocalizationController.php  # Language switching logic
│   └── Middleware/
│       └── SetLocale.php              # Automatic locale detection
└── Helpers/
    └── LocalizationHelper.php         # Helper functions
```

## Configuration

### Available Languages
Edit `config/app.php`:

```php
'available_locales' => [
    'en' => [
        'name' => 'English',
        'native' => 'English', 
        'flag' => '🇺🇸',
        'dir' => 'ltr'
    ],
    'ar' => [
        'name' => 'Arabic',
        'native' => 'العربية',
        'flag' => '🇸🇦', 
        'dir' => 'rtl'
    ]
],
```

### Adding New Languages

1. **Add to config** (`config/app.php`):
```php
'fr' => [
    'name' => 'French',
    'native' => 'Français',
    'flag' => '🇫🇷',
    'dir' => 'ltr'
]
```

2. **Create language files**:
```bash
mkdir resources/lang/fr
cp resources/lang/en/common.php resources/lang/fr/common.php
# Translate the content
```

3. **Update RTL detection** (if RTL language):
```php
// In LocalizationHelper.php
function is_rtl($locale = null): bool
{
    $locale = $locale ?: app()->getLocale();
    $rtlLocales = ['ar', 'fa', 'he', 'ur', 'yi', 'your_new_rtl_lang'];
    return in_array($locale, $rtlLocales);
}
```

## Helper Functions

### Available Helper Functions

```php
// Enhanced translation with fallback
__t('key', $replace, $locale, $fallback)

// Check if current locale is RTL
is_rtl($locale = null)

// Get RTL CSS class
rtl_class($rtlClass = 'rtl', $ltrClass = 'ltr')

// Get HTML dir attribute
dir_attr()

// Get current locale
current_locale()

// Get available locales
available_locales()

// Generate URL with locale parameter
locale_url($locale, $url = null)

// Generate localized route
localized_route($name, $parameters = [], $locale = null)

// Format date according to locale
format_date_localized($date, $format = null)

// Format number according to locale  
format_number_localized($number, $decimals = 0)

// Get translated model attribute
translate_model_attribute($model, $attribute, $locale = null)
```

## RTL Support

### CSS Classes
The system automatically applies RTL styles when Arabic is selected:

```css
/* Automatically applied for RTL languages */
[dir="rtl"] {
    direction: rtl;
    text-align: right;
}

/* Utility classes */
.rtl-only { display: none; }
[dir="rtl"] .rtl-only { display: block; }

.ltr-only { display: block; }
[dir="rtl"] .ltr-only { display: none; }
```

### Fonts
- **English**: Figtree, system fonts
- **Arabic**: Cairo, Noto Sans Arabic

## Database Considerations

### Storing User Language Preference
Add `locale` column to users table:

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('locale', 5)->default('en')->after('email');
});
```

### Multilingual Content
For content that needs translation, use separate columns:

```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title_en');
    $table->string('title_ar')->nullable();
    $table->text('content_en');
    $table->text('content_ar')->nullable();
    $table->timestamps();
});
```

Use the helper function:
```php
$title = translate_model_attribute($post, 'title');
```

## SEO Optimization

The system automatically adds:
- Language meta tags
- Hreflang attributes
- Proper HTML lang and dir attributes
- Localized URLs

## Testing

### Browser Testing
1. Visit `/` to see the demo page
2. Use the language switcher in the navigation
3. Test with `?lang=ar` URL parameter
4. Check RTL layout in Arabic

### API Testing
```bash
# Test locale detection
curl -H "Accept-Language: ar" http://localhost/api/v1/locales

# Test translations
curl http://localhost/api/v1/translations/common?locale=ar

# Test language switching
curl -X POST http://localhost/api/v1/lang/switch/ar
```

## Common Issues & Solutions

### Issue: Translations not appearing
**Solution**: 
1. Check if translation key exists in language files
2. Clear cache: `php artisan cache:clear`
3. Check middleware is applied to routes

### Issue: RTL layout not working
**Solution**:
1. Ensure RTL CSS is loaded
2. Check `dir` attribute in HTML
3. Verify `is_rtl()` function returns correct value

### Issue: Language not persisting
**Solution**:
1. Check session configuration
2. Verify middleware is in correct order
3. Check if `SetLocale` middleware is applied

## Performance Tips

1. **Cache translations** in production
2. **Lazy load** language files
3. **Use CDN** for font files
4. **Minimize** RTL CSS for better performance

## Mobile App Integration

The API provides complete localization support:

```javascript
// Get available languages
const locales = await fetch('/api/v1/locales');

// Get all translations for app
const translations = await fetch('/api/v1/translations-all?locale=ar');

// Switch language
await fetch('/api/v1/lang/switch/ar', { method: 'POST' });
```

## Extending the System

### Adding New Translation Namespaces
1. Create new PHP file in `resources/lang/{locale}/`
2. Add translations array
3. Use in templates: `{{ __t('namespace.key') }}`

### Custom Language Detection Logic
Modify `SetLocale` middleware to add custom detection logic.

### Adding More RTL Languages
Update the `isRtlLocale` method in both middleware and helper functions.

---

## Support

For questions or issues with the localization system, check the implementation in:
- `LocalizationController.php` - Main logic
- `SetLocale.php` - Middleware
- `LocalizationHelper.php` - Helper functions
- `resources/views/layouts/app.blade.php` - Frontend integration