<div class="language-switcher dropdown">
    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="language-flag">{{ $availableLocales[$currentLocale]['flag'] ?? '🌐' }}</span>
        <span class="d-none d-sm-inline">{{ $availableLocales[$currentLocale]['native'] ?? __t('common.language') }}</span>
    </button>
    
    <ul class="dropdown-menu" aria-labelledby="languageDropdown">
        @foreach($availableLocales as $localeCode => $localeData)
            <li>
                <a class="dropdown-item {{ $currentLocale === $localeCode ? 'active' : '' }}" 
                   href="{{ locale_url($localeCode) }}"
                   onclick="switchLanguage('{{ $localeCode }}')">
                    <span class="language-flag">{{ $localeData['flag'] }}</span>
                    {{ $localeData['native'] }}
                    @if($currentLocale === $localeCode)
                        <i class="fas fa-check float-end"></i>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>
</div>

<script>
function switchLanguage(locale) {
    // Store selected language in localStorage
    localStorage.setItem('preferred_language', locale);
    
    // Add loading state
    const switcher = document.getElementById('languageDropdown');
    if (switcher) {
        switcher.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __t("common.loading") }}';
        switcher.disabled = true;
    }
    
    // Navigate to new language
    window.location.href = locale_url(locale);
}

// Auto-detect language on first visit
document.addEventListener('DOMContentLoaded', function() {
    const preferredLang = localStorage.getItem('preferred_language');
    const currentLang = '{{ $currentLocale }}';
    
    if (preferredLang && preferredLang !== currentLang) {
        // Only redirect if user hasn't manually selected a language in this session
        if (!sessionStorage.getItem('manual_language_selection')) {
            window.location.href = locale_url(preferredLang);
        }
    }
    
    // Mark any language selection as manual
    document.querySelectorAll('.language-switcher .dropdown-item').forEach(item => {
        item.addEventListener('click', function() {
            sessionStorage.setItem('manual_language_selection', 'true');
        });
    });
});
</script>

<style>
.language-switcher .dropdown-item.active {
    background-color: var(--bs-primary);
    color: white;
}

.language-switcher .dropdown-item:hover {
    background-color: var(--bs-light);
}

.language-flag {
    font-size: 1.2em;
    margin-right: 8px;
}

[dir="rtl"] .language-flag {
    margin-right: 0;
    margin-left: 8px;
}

.language-switcher .btn {
    min-width: 80px;
}

@media (max-width: 576px) {
    .language-switcher .btn {
        min-width: 50px;
    }
    
    .language-flag {
        font-size: 1em;
    }
}
</style>