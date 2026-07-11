<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoryApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'موبایل' => [
                'نوکیا',
                'هواوی',
                'شیائومی',
                'اپل',
                'سامسونگ',
            ],

            'لپتاپ' => [
                'مک‌بوک',
                'دل',
                'اچ‌پی',
                'لنوو',
                'ایسوس',
            ],

            'لوازم جانبی موبایل' => [
                'پاوربانک',
                'کابل و مبدل',
                'شارژر',
                'گلس و محافظ صفحه',
                'قاب گوشی',
            ],

            'صوتی و هدفون' => [
                'ساندبار',
                'ایرفون',
                'اسپیکر بلوتوث',
                'هدفون سیمی',
                'هدفون بی‌سیم',
            ],
        ];

        foreach ($data as $parentName => $children) {

            // ایجاد دسته‌بندی اصلی
            $parent = Category::create([
                'name' => $parentName,
                'slug' => Str::slug($parentName,'-',null),
                'parent_id' => null,
            ]);

            // ایجاد زیر دسته‌ها
            foreach ($children as $childName) {
                Category::create([
                    'name' => $childName,
                    'slug' => Str::slug($parentName,'-',$childName),
                    'parent_id' => $parent->id,
                ]);
            }
        }
    }


}
