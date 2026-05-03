<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require_once 'db.php';

try {
    $stmt = $pdo->query("SELECT * FROM submissions ORDER BY id DESC");
    $submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}

$headersMapping = [
    'timestamp' => 'الطابع الزمني',
    'department_name' => 'اسم القسم',
    'college' => 'الكلية',
    'coordinator_name' => 'اسم منسِّق المشروع',
    'email' => 'البريد الإلكتروني',
    'phone' => 'رقم الهاتف',
    'submission_date' => 'تاريخ التقديم',
    'idea_desc' => 'وصف الفكرة',
    'project_type' => 'طبيعة المشروع',
    'supported_activity' => 'النشاط المدعوم',
    'current_problem' => 'المشكلة الحالية',
    'ai_reason' => 'سبب اختيار الذكاء الاصطناعي',
    'previous_solution' => 'حل سابق',
    'core_justifications' => 'المبررات الجوهرية',
    'target_audience' => 'الفئة المستهدفة',
    'users_count' => 'عدد المستخدمين المتوقعين',
    'development_party' => 'جهة التطوير',
    'ai_technologies' => 'تقنيات الذكاء الاصطناعي',
    'estimated_budget' => 'الميزانية التقديرية',
    'execution_duration' => 'مدة التنفيذ',
    'short_term_results' => 'النتائج قصيرة المدى',
    'long_term_results' => 'النتائج بعيدة المدى',
    'kpis' => 'مؤشرات النجاح KPIs',
    'dept_head_approval' => 'موافقة رئيس القسم',
    'priority_level' => 'مستوى الأولوية',
    'additional_notes' => 'ملاحظات إضافية'
];

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المشاريع الإبداعية</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        :root {
            --navy: #0d1f3c;
            --gold: #b8972a;
            --cream: #faf8f3;
            --border: #d9d0bc;
            --text: #1a1a2e;
            --accent: #1a4a8a;
            --muted: #5c5c7a;
        }
        body {
            font-family: 'Cairo', sans-serif;
            background: #e8e4da;
            margin: 0;
            padding: 30px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .main-header {
            background: var(--navy);
            color: #fff;
            padding: 24px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(13,31,60,0.1);
            border-top: 6px solid var(--gold);
            margin-bottom: 30px;
        }
        .main-header h1 {
            font-family: 'Amiri', serif;
            margin: 0;
            font-size: 24px;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .btn {
            background: var(--gold);
            color: var(--navy);
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-family: 'Cairo', sans-serif;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
            display: inline-block;
        }
        .btn:hover {
            background: #e8c96a;
            transform: translateY(-2px);
        }
        .btn-pdf {
            background: #c0392b;
            color: #fff;
        }
        .btn-pdf:hover {
            background: #e74c3c;
        }
        
        .pdf-wrapper {
            background: transparent;
        }

        .submission-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            overflow: hidden;
            page-break-inside: avoid;
        }
        .card-header {
            background: var(--section-bg);
            padding: 20px 24px;
            border-bottom: 2px solid var(--border);
            border-right: 6px solid var(--gold);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-header h2 {
            margin: 0;
            color: var(--navy);
            font-size: 20px;
        }
        .badge {
            background: var(--navy);
            color: var(--gold);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
        }
        .card-body {
            padding: 24px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .full-width {
            grid-column: span 2;
        }
        .field-group {
            background: var(--cream);
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid #eee;
        }
        .field-label {
            font-size: 12px;
            color: var(--muted);
            font-weight: 700;
            margin-bottom: 6px;
            display: block;
        }
        .field-value {
            font-size: 15px;
            color: var(--text);
            line-height: 1.6;
            white-space: pre-wrap;
        }

        /* Print adjustments for html2pdf */
        .html2pdf__page-break { height: 0; }

        @media (max-width: 768px) {
            .card-body { grid-template-columns: 1fr; }
            .full-width { grid-column: span 1; }
            .main-header { flex-direction: column; gap: 15px; text-align: center; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="main-header" data-html2canvas-ignore>
        <h1>لوحة تحكم المشاريع الإبداعية</h1>
        <div class="actions">
            <a href="export_excel.php" class="btn">تصدير إلى Excel</a>
            <button onclick="exportPDF()" class="btn btn-pdf">تصدير إلى PDF</button>
            <a href="logout.php" class="btn" style="background:#e74c3c; color:#fff;">تسجيل الخروج</a>
        </div>
    </div>

    <!-- This wrapper is what html2pdf captures -->
    <div id="content-to-print" class="pdf-wrapper">
        <?php if (count($submissions) > 0): ?>
            <?php foreach($submissions as $index => $row): ?>
                
                <div class="submission-card">
                    <div class="card-header">
                        <h2><?= htmlspecialchars($row['project_title'] ?: 'بدون عنوان') ?></h2>
                        <span class="badge">طلب رقم #<?= htmlspecialchars($row['id']) ?></span>
                    </div>
                    <div class="card-body">
                        
                        <?php 
                        // Long text fields that should take full width
                        $fullWidthFields = ['idea_desc', 'current_problem', 'ai_reason', 'core_justifications', 'short_term_results', 'long_term_results', 'kpis', 'additional_notes'];

                        foreach ($headersMapping as $dbCol => $arabicLabel): 
                            $value = $row[$dbCol];
                            if (empty($value)) continue; // Skip empty fields to save space
                            $isFullWidth = in_array($dbCol, $fullWidthFields);
                        ?>
                            <div class="field-group <?= $isFullWidth ? 'full-width' : '' ?>">
                                <span class="field-label"><?= htmlspecialchars($arabicLabel) ?></span>
                                <div class="field-value"><?= htmlspecialchars($value) ?></div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>

                <!-- Page break after every 2 cards for neat PDF layout, except the last card -->
                <?php if (($index + 1) % 2 === 0 && ($index + 1) < count($submissions)): ?>
                    <div class="html2pdf__page-break"></div>
                <?php endif; ?>

            <?php endforeach; ?>
        <?php else: ?>
            <div class="submission-card" style="text-align: center; padding: 50px;">
                <h2>لا توجد استمارات مقدمة حتى الآن.</h2>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function exportPDF() {
    const element = document.getElementById('content-to-print');
    
    // We dynamically apply a white background to the wrapper before printing 
    // so the PDF doesn't have transparency/weird backgrounds.
    element.style.background = '#e8e4da';
    element.style.padding = '20px';

    const opt = {
        margin:       10,
        filename:     'submissions_full_report.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true, backgroundColor: '#e8e4da' },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };
    
    // Generate PDF and then reset styles
    html2pdf().set(opt).from(element).save().then(() => {
        element.style.background = 'transparent';
        element.style.padding = '0';
    });
}
</script>

</body>
</html>
