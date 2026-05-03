<?php
require_once 'db.php';

try {
    $stmt = $pdo->query("SELECT * FROM submissions ORDER BY id DESC");
    $submissions = $stmt->fetchAll();
} catch(PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}
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
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(13,31,60,0.1);
            overflow: hidden;
        }
        .header {
            background: var(--navy);
            color: #fff;
            padding: 24px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 6px solid var(--gold);
        }
        .header h1 {
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
        .table-responsive {
            overflow-x: auto;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        th, td {
            border: 1px solid var(--border);
            padding: 12px 15px;
            text-align: right;
        }
        th {
            background: var(--cream);
            color: var(--navy);
            font-weight: 700;
            white-space: nowrap;
        }
        tr:nth-child(even) {
            background: #fafafa;
        }
        tr:hover {
            background: #f4f1ea;
        }
    </style>
</head>
<body>

<div class="container" id="content-to-print">
    <div class="header" data-html2canvas-ignore>
        <h1>لوحة تحكم المشاريع الإبداعية</h1>
        <div class="actions">
            <a href="export_excel.php" class="btn">تصدير إلى Excel</a>
            <button onclick="exportPDF()" class="btn btn-pdf">تصدير إلى PDF</button>
        </div>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>الرقم</th>
                    <th>تاريخ التقديم</th>
                    <th>الكلية</th>
                    <th>القسم</th>
                    <th>اسم المنسق</th>
                    <th>عنوان المشروع</th>
                    <th>طبيعة المشروع</th>
                    <th>الميزانية</th>
                    <th>الأولوية</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($submissions) > 0): ?>
                    <?php foreach($submissions as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['submission_date']) ?></td>
                        <td><?= htmlspecialchars($row['college']) ?></td>
                        <td><?= htmlspecialchars($row['department_name']) ?></td>
                        <td><?= htmlspecialchars($row['coordinator_name']) ?></td>
                        <td><?= htmlspecialchars($row['project_title']) ?></td>
                        <td><?= htmlspecialchars($row['project_type']) ?></td>
                        <td><?= htmlspecialchars($row['estimated_budget']) ?></td>
                        <td><?= htmlspecialchars($row['priority_level']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 30px;">لا توجد استمارات مقدمة حتى الآن.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function exportPDF() {
    const element = document.getElementById('content-to-print');
    const opt = {
        margin:       10,
        filename:     'submissions_report.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
    };
    
    // The library uses promises
    html2pdf().set(opt).from(element).save();
}
</script>

</body>
</html>
