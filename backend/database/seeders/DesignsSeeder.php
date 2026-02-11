<?php

namespace Database\Seeders;

use App\Models\Design;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DesignsSeeder extends Seeder
{
    
    public function run(): void
    {

        $designsPath = storage_path('app/public/designs');
        if (!File::exists($designsPath)) {
            File::makeDirectory($designsPath, 0755, true);
        }

        $projectsPath = storage_path('app/public/projects');
        $projectImages = [
            'ksulman1.jpg',
            'ksulman2.JPG',
            'ksulman3.JPG',
            'ksulman4.JPG',
            'resort1.JPG',
            'resort2.jpg',
            'resort3.JPG',
            'resort4.PNG',
            't3awn1.JPG',
            't3awn2.JPG',
            't3awn3.JPG',
            't3awn4.jpg',
        ];

        foreach ($projectImages as $image) {
            $sourcePath = $projectsPath . '/' . $image;
            $destinationPath = $designsPath . '/' . $image;
            
            if (File::exists($sourcePath) && !File::exists($destinationPath)) {
                File::copy($sourcePath, $destinationPath);
            }
        }

        $designs = [
            [
                'title' => 'تصميم فيلا حي الملك سلمان - الواجهة الأمامية',
                'description' => 'تصميم معماري حديث للواجهة الأمامية لفيلا في حي الملك سلمان، يجمع بين الأناقة والعملية مع استخدام مواد عالية الجودة.',
                'category' => 'exterior',
                'image_path' => 'designs/ksulman1.jpg',
                'alt_text' => 'تصميم الواجهة الأمامية لفيلا حي الملك سلمان',
                'display_order' => 1,
                'status' => 'published',
                'is_featured' => true,
                'tags' => ['modern', 'luxury', 'contemporary'],
            ],
            [
                'title' => 'تصميم فيلا حي الملك سلمان - المدخل الرئيسي',
                'description' => 'تصميم مدخل رئيسي فخم مع إضاءة معمارية مميزة وتنسيق حدائق أنيق يعكس الطابع العصري للمشروع.',
                'category' => 'exterior',
                'image_path' => 'designs/ksulman2.JPG',
                'alt_text' => 'تصميم المدخل الرئيسي لفيلا حي الملك سلمان',
                'display_order' => 2,
                'status' => 'published',
                'is_featured' => true,
                'tags' => ['modern', 'luxury', 'landscape'],
            ],
            [
                'title' => 'تصميم فيلا حي الملك سلمان - الحديقة الخلفية',
                'description' => 'تصميم حديقة خلفية متكاملة مع مساحات جلوس خارجية ونظام إضاءة ليلية يخلق أجواء مثالية للاسترخاء.',
                'category' => 'landscape',
                'image_path' => 'designs/ksulman3.JPG',
                'alt_text' => 'تصميم الحديقة الخلفية لفيلا حي الملك سلمان',
                'display_order' => 3,
                'status' => 'published',
                'is_featured' => false,
                'tags' => ['landscape', 'modern', 'outdoor'],
            ],
            [
                'title' => 'تصميم فيلا حي الملك سلمان - منطقة المسبح',
                'description' => 'تصميم منطقة مسبح عصرية مع ديكورات حجرية طبيعية ومساحات للاستجمام تحيط بالمسبح.',
                'category' => 'landscape',
                'image_path' => 'designs/ksulman4.JPG',
                'alt_text' => 'تصميم منطقة المسبح لفيلا حي الملك سلمان',
                'display_order' => 4,
                'status' => 'published',
                'is_featured' => false,
                'tags' => ['landscape', 'luxury', 'contemporary'],
            ],
            [
                'title' => 'تصميم منتجع سياحي - الواجهة الرئيسية',
                'description' => 'تصميم معماري فريد لمنتجع سياحي يمزج بين الطابع التقليدي والحداثة، مع استخدام مواد محلية وعناصر تصميمية مبتكرة.',
                'category' => 'architectural',
                'image_path' => 'designs/resort1.JPG',
                'alt_text' => 'تصميم الواجهة الرئيسية للمنتجع السياحي',
                'display_order' => 5,
                'status' => 'published',
                'is_featured' => true,
                'tags' => ['traditional', 'luxury', 'resort'],
            ],
            [
                'title' => 'تصميم منتجع سياحي - المساحات الداخلية',
                'description' => 'تصميم داخلي أنيق للمنتجع يعكس الهوية المحلية مع لمسات عصرية، يوفر تجربة ضيافة استثنائية.',
                'category' => 'interior',
                'image_path' => 'designs/resort2.jpg',
                'alt_text' => 'تصميم المساحات الداخلية للمنتجع السياحي',
                'display_order' => 6,
                'status' => 'published',
                'is_featured' => false,
                'tags' => ['interior', 'traditional', 'luxury'],
            ],
            [
                'title' => 'تصميم منتجع سياحي - الحدائق والمناظر الطبيعية',
                'description' => 'تصميم حدائق ومناظر طبيعية متكاملة للمنتجع تتناغم مع البيئة المحيطة وتوفر مساحات خضراء مريحة.',
                'category' => 'landscape',
                'image_path' => 'designs/resort3.JPG',
                'alt_text' => 'تصميم الحدائق والمناظر الطبيعية للمنتجع',
                'display_order' => 7,
                'status' => 'published',
                'is_featured' => false,
                'tags' => ['landscape', 'natural', 'resort'],
            ],
            [
                'title' => 'تصميم منتجع سياحي - المخطط العام',
                'description' => 'المخطط العام للمنتجع يوضح التوزيع المثالي للمرافق والخدمات مع مراعاة الخصوصية والراحة للنزلاء.',
                'category' => 'architectural',
                'image_path' => 'designs/resort4.PNG',
                'alt_text' => 'المخطط العام لتصميم المنتجع السياحي',
                'display_order' => 8,
                'status' => 'published',
                'is_featured' => false,
                'tags' => ['architectural', 'planning', 'resort'],
            ],
            [
                'title' => 'تصميم فيلا التعاون - الواجهة الحديثة',
                'description' => 'تصميم واجهة حديثة لفيلا التعاون تتميز بالخطوط النظيفة والمواد العصرية مع إضاءة معمارية مدروسة.',
                'category' => 'exterior',
                'image_path' => 'designs/t3awn1.JPG',
                'alt_text' => 'تصميم الواجهة الحديثة لفيلا التعاون',
                'display_order' => 9,
                'status' => 'published',
                'is_featured' => true,
                'tags' => ['modern', 'contemporary', 'minimalist'],
            ],
            [
                'title' => 'تصميم فيلا التعاون - المساحات الخارجية',
                'description' => 'تصميم المساحات الخارجية للفيلا مع تنسيق حدائق عصري ومناطق جلوس مريحة تناسب الأجواء العائلية.',
                'category' => 'landscape',
                'image_path' => 'designs/t3awn2.JPG',
                'alt_text' => 'تصميم المساحات الخارجية لفيلا التعاون',
                'display_order' => 10,
                'status' => 'published',
                'is_featured' => false,
                'tags' => ['landscape', 'modern', 'family'],
            ],
            [
                'title' => 'تصميم فيلا التعاون - الحديقة الأمامية',
                'description' => 'تصميم حديقة أمامية أنيقة مع ممرات حجرية ونباتات محلية تعزز من جمال الواجهة الخارجية للفيلا.',
                'category' => 'landscape',
                'image_path' => 'designs/t3awn3.JPG',
                'alt_text' => 'تصميم الحديقة الأمامية لفيلا التعاون',
                'display_order' => 11,
                'status' => 'published',
                'is_featured' => false,
                'tags' => ['landscape', 'natural', 'entrance'],
            ],
            [
                'title' => 'تصميم فيلا التعاون - التفاصيل المعمارية',
                'description' => 'التفاصيل المعمارية الدقيقة للفيلا تظهر الحرفية العالية في التنفيذ والاهتمام بأدق العناصر التصميمية.',
                'category' => 'architectural',
                'image_path' => 'designs/t3awn4.jpg',
                'alt_text' => 'التفاصيل المعمارية لفيلا التعاون',
                'display_order' => 12,
                'status' => 'published',
                'is_featured' => false,
                'tags' => ['architectural', 'details', 'craftsmanship'],
            ],
        ];

        foreach ($designs as $designData) {
            Design::create($designData);
        }

        $this->command->info('تم إنشاء ' . count($designs) . ' تصميم بنجاح!');
    }
}
