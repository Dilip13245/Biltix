<?php

namespace Database\Seeders;

use App\Models\StaticContent;
use Illuminate\Database\Seeder;

class StaticContentSeeder extends Seeder
{
    public function run()
    {
        // English Privacy Policy
        StaticContent::updateOrCreate(
            ['type' => 'privacy', 'language' => 'en'],
            [
                'title' => 'Privacy Policy',
                'content' => '<p>Your privacy is important to us. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our application.</p>',
                'is_active' => true,
                'is_deleted' => false,
            ]
        );

        // Arabic Privacy Policy
        StaticContent::updateOrCreate(
            ['type' => 'privacy', 'language' => 'ar'],
            [
                'title' => 'سياسة الخصوصية',
                'content' => '<p>خصوصيتك مهمة لنا. توضح سياسة الخصوصية هذه كيفية جمع واستخدام والكشف عن حماية معلوماتك عند استخدام تطبيقنا.</p>',
                'is_active' => true,
                'is_deleted' => false,
            ]
        );

        // English Terms & Conditions
        StaticContent::updateOrCreate(
            ['type' => 'terms', 'language' => 'en'],
            [
                'title' => 'Terms and Conditions',
                'content' => '<p>Welcome to our application. These Terms and Conditions govern your use of our service and establish the relationship between you and us.</p>',
                'is_active' => true,
                'is_deleted' => false,
            ]
        );

        // Arabic Terms & Conditions
        StaticContent::updateOrCreate(
            ['type' => 'terms', 'language' => 'ar'],
            [
                'title' => 'الشروط والأحكام',
                'content' => '<p>مرحبا بك في تطبيقنا. تحكم هذه الشروط والأحكام استخدامك لخدمتنا وتحدد العلاقة بينك وبيننا.</p>',
                'is_active' => true,
                'is_deleted' => false,
            ]
        );
    }
}
