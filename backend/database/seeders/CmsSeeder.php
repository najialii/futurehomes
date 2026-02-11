<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Project;
use App\Models\Partner;
use App\Models\Testimonial;
use App\Models\Page;
use App\Models\ContactSubmission;
use App\Models\Stat;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    
    public function run(): void
    {

        $stats = [
            [
                'name' => 'سنوات الخبرة',
                'number' => '15+',
                'icon' => 'calendar',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'مشاريع مكتملة',
                'number' => '150+',
                'icon' => 'building',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'عملاء راضون',
                'number' => '200+',
                'icon' => 'users',
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'مهندسون محترفون',
                'number' => '25+',
                'icon' => 'user-tie',
                'display_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($stats as $statData) {
            Stat::firstOrCreate(['name' => $statData['name']], $statData);
        }

        $services = [
            [
                'title' => 'التصميم',
                'description' => 'نقدم حلولاً تصميمية مبتكرة ومتكاملة، من المخططات المعمارية إلى التصميمات الداخلية، لتحقيق رؤيتك الفردية.',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'البناء والإنشاء',
                'description' => 'نلتزم بمعايير الجودة العالمية في تنفيذ المشاريع الإنشائية، بدءاً من الأساسات القوية وصولاً إلى الهيكل الكامل للمبنى.',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'التشطيب',
                'description' => 'نقدم خدمات تشطيب نهائية تضفي لمسة من الأناقة والاحترافية، مع الاهتمام بأدق التفاصيل لضمان رضا العميل التام.',
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'الترميم',
                'description' => 'نقوم بأعمال الترميم وإعادة التأهيل للمباني القديمة بطرق احترافية، لإعادة الحياة إلى المساحات مع الحفاظ على قيمتها الأصلية.',
                'display_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($services as $serviceData) {
            Service::firstOrCreate(['title' => $serviceData['title']], $serviceData);
        }

        $projects = [
            [
                'name' => 'مشروع حي الملك سلمان',
                'description' => 'تنفيذ وتصميم مشروع خارجي مميز يجمع بين الحداثة والعملية.',
                'service_id' => 1, // التصميم
                'status' => 'published',
                'display_order' => 1,
            ],
            [
                'name' => 'مشروع التعاون',
                'description' => 'مشروع فيلا خارجية بتصميم فريد يبرز جمال الواجهات والمساحات الخارجية.',
                'service_id' => 2, // البناء والإنشاء
                'status' => 'published',
                'display_order' => 2,
            ],
            [
                'name' => 'مشروع منتجع',
                'description' => 'تصميم وتنفيذ مشروع خارجي راقٍ يتوافق مع أحدث المعايير الهندسية.',
                'service_id' => 3, // التشطيب
                'status' => 'published',
                'display_order' => 3,
            ],
        ];

        foreach ($projects as $projectData) {
            Project::firstOrCreate(['name' => $projectData['name']], $projectData);
        }

        $projectImages = [

            [
                'project_id' => 1,
                'image_path' => 'projects/ksulman1.jpg',
                'alt_text' => 'مشروع حي الملك سلمان - صورة 1',
                'display_order' => 1,
            ],
            [
                'project_id' => 1,
                'image_path' => 'projects/ksulman2.JPG',
                'alt_text' => 'مشروع حي الملك سلمان - صورة 2',
                'display_order' => 2,
            ],
            [
                'project_id' => 1,
                'image_path' => 'projects/ksulman3.JPG',
                'alt_text' => 'مشروع حي الملك سلمان - صورة 3',
                'display_order' => 3,
            ],
            [
                'project_id' => 1,
                'image_path' => 'projects/ksulman4.JPG',
                'alt_text' => 'مشروع حي الملك سلمان - صورة 4',
                'display_order' => 4,
            ],

            [
                'project_id' => 2,
                'image_path' => 'projects/t3awn1.JPG',
                'alt_text' => 'مشروع التعاون - صورة 1',
                'display_order' => 1,
            ],
            [
                'project_id' => 2,
                'image_path' => 'projects/t3awn2.JPG',
                'alt_text' => 'مشروع التعاون - صورة 2',
                'display_order' => 2,
            ],
            [
                'project_id' => 2,
                'image_path' => 'projects/t3awn3.JPG',
                'alt_text' => 'مشروع التعاون - صورة 3',
                'display_order' => 3,
            ],
            [
                'project_id' => 2,
                'image_path' => 'projects/t3awn4.jpg',
                'alt_text' => 'مشروع التعاون - صورة 4',
                'display_order' => 4,
            ],

            [
                'project_id' => 3,
                'image_path' => 'projects/resort1.JPG',
                'alt_text' => 'مشروع منتجع - صورة 1',
                'display_order' => 1,
            ],
            [
                'project_id' => 3,
                'image_path' => 'projects/resort2.jpg',
                'alt_text' => 'مشروع منتجع - صورة 2',
                'display_order' => 2,
            ],
            [
                'project_id' => 3,
                'image_path' => 'projects/resort3.JPG',
                'alt_text' => 'مشروع منتجع - صورة 3',
                'display_order' => 3,
            ],
            [
                'project_id' => 3,
                'image_path' => 'projects/resort4.PNG',
                'alt_text' => 'مشروع منتجع - صورة 4',
                'display_order' => 4,
            ],
        ];

        foreach ($projectImages as $imageData) {
            \App\Models\ProjectImage::firstOrCreate(
                ['project_id' => $imageData['project_id'], 'image_path' => $imageData['image_path']], 
                $imageData
            );
        }

        $partners = [
            ['name' => '3nood', 'logo_path' => 'partners/logos/3nood.svg', 'website_url' => 'https://3nood.com', 'display_order' => 1],
            ['name' => 'Jood', 'logo_path' => 'partners/logos/joodwhite.svg', 'website_url' => 'https://jood.sa', 'display_order' => 2],
            ['name' => 'CLU', 'logo_path' => 'partners/logos/clu.png', 'website_url' => 'https://clu.sa', 'display_order' => 3],
            ['name' => 'شريك رابع', 'logo_path' => 'partners/logos/nobg.png', 'website_url' => 'https://partner4.sa', 'display_order' => 4],
        ];

        foreach ($partners as $partnerData) {
            Partner::firstOrCreate(['name' => $partnerData['name']], array_merge($partnerData, ['is_active' => true]));
        }

        $testimonials = [
            [
                'client_name' => 'أحمد محمد السعيد',
                'feedback' => 'شركة Future Homes تجاوزت توقعاتنا بكثير. الاهتمام بالتفاصيل وجودة العمل كانت استثنائية. أنصح بها بشدة.',
                'rating' => 5,
                'status' => 'approved',
            ],
            [
                'client_name' => 'فاطمة عبدالله الزهراني',
                'feedback' => 'فريق محترف، التسليم في الوقت المحدد، والنتائج رائعة. أنصح بهم بشدة!',
                'rating' => 5,
                'status' => 'approved',
            ],
            [
                'client_name' => 'خالد عبدالعزيز النمر',
                'feedback' => 'تواصل ممتاز طوال فترة المشروع. منزلنا الجديد هو كل ما حلمنا به وأكثر.',
                'rating' => 4,
                'status' => 'approved',
            ],
            [
                'client_name' => 'نورا سعد الغامدي',
                'feedback' => 'خبرة رائعة من البداية للنهاية. الفريق كان متعاوناً جداً ومتفهماً لاحتياجاتنا.',
                'rating' => 5,
                'status' => 'approved',
            ],
        ];

        foreach ($testimonials as $testimonialData) {
            Testimonial::firstOrCreate(['client_name' => $testimonialData['client_name']], $testimonialData);
        }

        $pages = [
            [
                'slug' => 'home',
                'title' => 'الصفحة الرئيسية',
                'content' => '<h1>مرحباً بكم في Future Homes</h1>
                <p>شركة مقاولات سعودية رائدة في مجال البناء والتشييد</p>
                
                <h2>نبني أحلامكم بأيدي خبيرة</h2>
                <p>نحن في Future Homes، نفخر بخبرة تزيد عن 15 عاماً في تحويل الأفكار المعمارية إلى واقع ملموس. يرتكز عملنا على الإتقان والابتكار.</p>',
                'meta_description' => 'Future Homes - شركة مقاولات سعودية رائدة في مجال البناء والتشييد',
                'is_published' => true,
            ],
            [
                'slug' => 'about-us',
                'title' => 'من نحن',
                'content' => '<h1>من نحن</h1>
                <p>نحن في <strong>Future Homes</strong>، شركة مقاولات سعودية نفخر بخبرة تزيد عن 15 عاماً في تحويل الأفكار المعمارية إلى واقع ملموس. يرتكز عملنا على الإتقان والابتكار، حيث يجمع فريقنا بين نخبة من المهندسين المحترفين وكادر فني متميز لتقديم حلول متكاملة تلبي أعلى معايير الجودة.</p>
                
                <h2>رؤيتنا</h2>
                <p>أن نصبح من الشركات الرائدة في مجال المقاولات والهندسة المعمارية في المملكة العربية السعودية بما يواكب رؤية المملكة 2030.</p>
                
                <h2>رسالتنا</h2>
                <p>تقديم خدمات عالية الجودة من خلال الالتزام بالإتقان والابتكار في العمل، والسعي لتحقيق جميع تطلعات العملاء.</p>
                
                <h2>مبادئنا الأساسية</h2>
                <ul>
                <li><strong>استشارات احترافية:</strong> نقدم استشارات هندسية دقيقة لضمان أعلى مستويات الجودة في جميع مراحل العمل.</li>
                <li><strong>كفاءات متميزة:</strong> نسعى دائماً لجذب أفضل الكفاءات وتطويرها، لضمان أن يكون فريقنا الأفضل في المجال.</li>
                <li><strong>شراكات موثوقة:</strong> نعقد شراكات مع أفضل الشركات المتميزة في التوريد والتركيب لضمان جودة المواد.</li>
                <li><strong>تميز تشغيلي:</strong> نعمل على تحقيق التميز التشغيلي في كافة أعمالنا لضمان سلاسة التنفيذ ورضا العميل.</li>
                </ul>',
                'meta_description' => 'تعرف على شركة Future Homes والتزامنا بالجودة والإتقان في مجال المقاولات والهندسة المعمارية.',
                'is_published' => true,
            ],
            [
                'slug' => 'projects',
                'title' => 'المشاريع',
                'content' => '<h1>مشاريعنا السابقة</h1>
                <p>نفخر بتنفيذ مجموعة متميزة من المشاريع التي تعكس خبرتنا الممتدة لأكثر من 15 عاماً.</p>
                
                <h2>مشروع حي الملك سلمان</h2>
                <p>تنفيذ وتصميم مشروع خارجي مميز يجمع بين الحداثة والعملية.</p>
                
                <h2>مشروع التعاون</h2>
                <p>مشروع فيلا خارجية بتصميم فريد يبرز جمال الواجهات والمساحات الخارجية.</p>
                
                <h2>مشروع منتجع</h2>
                <p>تصميم وتنفيذ مشروع خارجي راقٍ يتوافق مع أحدث المعايير الهندسية.</p>',
                'meta_description' => 'استعرض مشاريعنا السابقة المتميزة في مجال البناء والتشييد.',
                'is_published' => true,
            ],
            [
                'slug' => 'designs',
                'title' => 'تصاميمنا',
                'content' => '<h1>تصاميمنا</h1>
                <p>نقدم تصاميم معمارية مبتكرة تجمع بين الأصالة والحداثة.</p>
                
                <h2>التصميم المعماري</h2>
                <p>نقدم حلولاً تصميمية مبتكرة ومتكاملة، من المخططات المعمارية إلى التصميمات الداخلية، لتحقيق رؤيتك الفردية.</p>
                
                <h2>التصميم الداخلي</h2>
                <p>تصاميم داخلية عصرية تراعي الذوق السعودي الأصيل مع لمسة من الحداثة.</p>
                
                <h2>تصميم الواجهات</h2>
                <p>واجهات معمارية مميزة تعكس شخصية المبنى وتتناسب مع البيئة المحيطة.</p>',
                'meta_description' => 'اكتشف تصاميمنا المعمارية المبتكرة والحديثة.',
                'is_published' => true,
            ],
            [
                'slug' => 'contact',
                'title' => 'تواصل معنا',
                'content' => '<h1>تواصل معنا</h1>
                <p>نحن هنا لمساعدتك في تحقيق حلمك المعماري. تواصل معنا اليوم للحصول على استشارة مجانية.</p>
                
                <h2>معلومات التواصل</h2>
                <p><strong>الهاتف:</strong> +966 50 123 4567</p>
                <p><strong>البريد الإلكتروني:</strong> info@futurehomes.sa</p>
                <p><strong>العنوان:</strong> المملكة العربية السعودية، الرياض</p>
                
                <h2>ساعات العمل</h2>
                <p>السبت - الخميس: 8:00 صباحاً - 6:00 مساءً</p>
                <p>الجمعة: مغلق</p>
                
                <h2>خدماتنا</h2>
                <ul>
                <li>التصميم المعماري</li>
                <li>البناء والإنشاء</li>
                <li>التشطيب</li>
                <li>الترميم</li>
                </ul>',
                'meta_description' => 'تواصل مع فريق Future Homes للحصول على استشارة مجانية حول مشروعك.',
                'is_published' => true,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::firstOrCreate(['slug' => $pageData['slug']], $pageData);
        }

        $contactSubmissions = [
            [
                'name' => 'عبدالله محمد الأحمد',
                'email' => 'abdullah@example.com',
                'message' => 'أرغب في معرفة المزيد عن خدمات التصميم المعماري المخصص.',
                'status' => 'new',
                'ip_address' => '192.168.1.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ],
            [
                'name' => 'مريم سعد العتيبي',
                'email' => 'mariam@example.com',
                'message' => 'نحتاج إلى عرض سعر لمشروع فيلا سكنية في الرياض.',
                'status' => 'new',
                'ip_address' => '192.168.1.2',
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X)',
            ],
        ];

        foreach ($contactSubmissions as $submissionData) {
            ContactSubmission::firstOrCreate(['email' => $submissionData['email']], $submissionData);
        }
    }
}
