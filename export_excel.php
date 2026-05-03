<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require_once 'db.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Fetch all submissions
try {
    $stmt = $pdo->query("SELECT * FROM submissions ORDER BY id DESC");
    $submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}

// Map database columns to Arabic headers
$headersMapping = [
    'id' => 'الرقم',
    'timestamp' => 'الطابع الزمني',
    'department_name' => 'اسم القسم',
    'college' => 'الكلية',
    'coordinator_name' => 'اسم منسِّق المشروع',
    'email' => 'البريد الإلكتروني',
    'phone' => 'رقم الهاتف',
    'submission_date' => 'تاريخ التقديم',
    'project_title' => 'عنوان المشروع',
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
    'additional_notes' => 'ملاحظات إضافية',
    'created_at' => 'تاريخ التحديث في النظام'
];

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('المشاريع الإبداعية');

// Set Right-to-Left (RTL) for the entire sheet
$sheet->setRightToLeft(true);

if (count($submissions) > 0) {
    // Get columns
    $dbColumns = array_keys($submissions[0]);
    
    // Write Headers
    $colIndex = 1;
    foreach ($dbColumns as $dbCol) {
        $headerText = isset($headersMapping[$dbCol]) ? $headersMapping[$dbCol] : $dbCol;
        // Convert column index to letter (A, B, C...)
        $letter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
        
        $sheet->setCellValue($letter . '1', $headerText);
        
        // Auto-size the column
        $sheet->getColumnDimension($letter)->setAutoSize(true);
        $colIndex++;
    }

    // Style Header Row
    $lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($dbColumns));
    $headerStyleArray = [
        'font' => [
            'bold' => true,
            'color' => ['argb' => 'FFFFFFFF'],
            'name' => 'Cairo',
            'size' => 12,
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'FF0D1F3C', // University Navy Blue
            ],
        ],
    ];
    $sheet->getStyle('A1:' . $lastColLetter . '1')->applyFromArray($headerStyleArray);
    $sheet->getRowDimension(1)->setRowHeight(30);

    // Write Data Rows
    $rowIndex = 2;
    foreach ($submissions as $row) {
        $colIndex = 1;
        foreach ($dbColumns as $dbCol) {
            $letter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            
            // Apply text wrapping for long text fields
            $cellValue = $row[$dbCol];
            $sheet->setCellValue($letter . $rowIndex, $cellValue);
            
            // Enable wrap text for description fields
            if (in_array($dbCol, ['idea_desc', 'current_problem', 'ai_reason', 'core_justifications', 'short_term_results', 'long_term_results', 'kpis', 'additional_notes'])) {
                $sheet->getStyle($letter . $rowIndex)->getAlignment()->setWrapText(true);
                $sheet->getColumnDimension($letter)->setAutoSize(false);
                $sheet->getColumnDimension($letter)->setWidth(40); // Set fixed width for long texts
            } else {
                $sheet->getStyle($letter . $rowIndex)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            }
            
            $colIndex++;
        }
        $rowIndex++;
    }
} else {
    $sheet->setCellValue('A1', 'لا توجد بيانات متاحة');
    $sheet->setRightToLeft(true);
}

// Setup download headers for native .xlsx
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="submissions_' . date('Y-m-d') . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
