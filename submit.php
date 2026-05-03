<?php
session_start();
header('Content-Type: application/json');
require_once 'db.php';

// Retrieve raw JSON data from POST
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'No data received']);
    exit;
}

// Validate CSRF token
if (empty($data['csrf_token']) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $data['csrf_token'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid CSRF token. Please refresh the page and try again.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO submissions (
        timestamp, department_name, college, coordinator_name, email, phone, submission_date, 
        project_title, idea_desc, project_type, supported_activity, current_problem, 
        ai_reason, previous_solution, core_justifications, target_audience, users_count, 
        development_party, ai_technologies, estimated_budget, execution_duration, 
        short_term_results, long_term_results, kpis, dept_head_approval, priority_level, additional_notes
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, 
        ?, ?, ?, ?, ?, 
        ?, ?, ?, ?, ?, 
        ?, ?, ?, ?, 
        ?, ?, ?, ?, ?, ?
    )");

    $stmt->execute([
        $data['الطابع الزمني'] ?? '',
        $data['اسم القسم'] ?? '',
        $data['الكلية'] ?? '',
        $data['اسم المنسق'] ?? '',
        $data['البريد الإلكتروني'] ?? '',
        $data['رقم الهاتف'] ?? '',
        $data['تاريخ التقديم'] ?? '',
        $data['عنوان المشروع'] ?? '',
        $data['وصف الفكرة'] ?? '',
        $data['طبيعة المشروع'] ?? '',
        $data['النشاط المدعوم'] ?? '',
        $data['المشكلة الحالية'] ?? '',
        $data['سبب الذكاء الاصطناعي'] ?? '',
        $data['حل سابق'] ?? '',
        $data['المبررات الجوهرية'] ?? '',
        $data['الفئة المستهدفة'] ?? '',
        $data['عدد المستخدمين'] ?? '',
        $data['جهة التطوير'] ?? '',
        $data['تقنيات الذكاء الاصطناعي'] ?? '',
        $data['الميزانية التقديرية'] ?? '',
        $data['مدة التنفيذ'] ?? '',
        $data['النتائج قصيرة المدى'] ?? '',
        $data['النتائج بعيدة المدى'] ?? '',
        $data['مؤشرات النجاح KPIs'] ?? '',
        $data['موافقة رئيس القسم'] ?? '',
        $data['مستوى الأولوية'] ?? '',
        $data['ملاحظات إضافية'] ?? ''
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Form submitted successfully']);
} catch(PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
