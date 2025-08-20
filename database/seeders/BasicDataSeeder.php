<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BasicDataSeeder extends Seeder
{
    public function run(): void
    {
        // File Categories
        $fileCategories = [
            ['name' => 'Documents', 'icon' => 'document', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Drawings', 'icon' => 'blueprint', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Photos', 'icon' => 'camera', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Reports', 'icon' => 'report', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('file_categories')->insert($fileCategories);

        // Snag Categories
        $snagCategories = [
            ['name' => 'Electrical', 'color_code' => '#FFD700', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Plumbing', 'color_code' => '#4169E1', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Structural', 'color_code' => '#DC143C', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Finishing', 'color_code' => '#32CD32', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Safety', 'color_code' => '#FF4500', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('snag_categories')->insert($snagCategories);

        // Inspection Templates
        $inspectionTemplates = [
            [
                'name' => 'Safety Inspection',
                'category' => 'safety',
                'checklist_items' => json_encode([
                    ['id' => 'safety_001', 'name' => 'PPE Check', 'description' => 'All workers wearing proper PPE'],
                    ['id' => 'safety_002', 'name' => 'Scaffolding', 'description' => 'Scaffolding properly secured'],
                    ['id' => 'safety_003', 'name' => 'Fire Safety', 'description' => 'Fire extinguishers accessible'],
                ]),
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Quality Inspection',
                'category' => 'quality',
                'checklist_items' => json_encode([
                    ['id' => 'quality_001', 'name' => 'Material Quality', 'description' => 'Materials meet specifications'],
                    ['id' => 'quality_002', 'name' => 'Workmanship', 'description' => 'Work meets quality standards'],
                    ['id' => 'quality_003', 'name' => 'Dimensions', 'description' => 'Measurements within tolerance'],
                ]),
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('inspection_templates')->insert($inspectionTemplates);
    }
}