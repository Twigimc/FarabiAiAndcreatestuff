<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>استمارة المشاريع الإبداعية – جامعة الفارابي</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
<style>
  :root {
    --navy: #0d1f3c;
    --gold: #b8972a;
    --gold-light: #e8c96a;
    --cream: #faf8f3;
    --white: #ffffff;
    --border: #d9d0bc;
    --text: #1a1a2e;
    --muted: #5c5c7a;
    --accent: #1a4a8a;
    --success: #2e7d55;
    --section-bg: #f4f1ea;
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'Cairo', sans-serif;
    background: #e8e4da;
    background-image: 
      radial-gradient(circle at 20% 20%, rgba(184,151,42,0.08) 0%, transparent 50%),
      radial-gradient(circle at 80% 80%, rgba(13,31,60,0.06) 0%, transparent 50%);
    min-height: 100vh;
    padding: 30px 16px 60px;
  }

  .wrapper { max-width: 720px; margin: 0 auto; }

  /* HEADER */
  .header-card {
    background: var(--navy);
    border-radius: 12px 12px 0 0;
    overflow: hidden;
    position: relative;
    border-top: 8px solid var(--gold);
    box-shadow: 0 8px 32px rgba(13,31,60,0.25);
  }
  .header-inner { padding: 36px 40px 32px; position: relative; z-index: 1; }
  .header-card::after {
    content: '';
    position: absolute;
    bottom: -1px; right: -1px;
    width: 200px; height: 200px;
    background: radial-gradient(circle, rgba(184,151,42,0.12) 0%, transparent 70%);
    pointer-events: none;
  }
  .uni-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(184,151,42,0.15);
    border: 1px solid rgba(184,151,42,0.3);
    color: var(--gold-light);
    font-size: 12px;
    font-weight: 600;
    padding: 5px 14px;
    border-radius: 20px;
    margin-bottom: 18px;
  }
  .uni-badge::before { content: '🎓'; font-size: 13px; }
  .header-title {
    font-family: 'Amiri', serif;
    font-size: 28px;
    font-weight: 700;
    color: #fff;
    line-height: 1.4;
    margin-bottom: 10px;
  }
  .header-subtitle {
    font-size: 14px;
    color: rgba(255,255,255,0.6);
    line-height: 1.7;
    margin-bottom: 20px;
  }
  .header-meta { display: flex; gap: 20px; flex-wrap: wrap; }
  .meta-tag {
    display: flex; align-items: center; gap: 6px;
    font-size: 12px; color: var(--gold-light); opacity: 0.85;
  }
  .meta-tag .dot { width: 5px; height: 5px; background: var(--gold); border-radius: 50%; }

  /* SECTION DIVIDER */
  .section-header {
    background: var(--section-bg);
    border-right: 4px solid var(--gold);
    padding: 14px 24px;
    margin-top: 2px;
  }
  .section-header h3 { font-size: 13px; font-weight: 700; color: var(--navy); }
  .section-header p { font-size: 12px; color: var(--muted); margin-top: 2px; }

  /* CARDS */
  .card {
    background: var(--white);
    padding: 28px 32px;
    margin-top: 2px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
  }
  .form-group { margin-bottom: 26px; }
  .form-group:last-child { margin-bottom: 0; }

  label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 8px;
    line-height: 1.5;
  }
  label .required { color: #c0392b; margin-right: 3px; font-size: 13px; }
  label .hint { font-weight: 400; font-size: 12px; color: var(--muted); display: block; margin-top: 3px; }

  input[type="text"],
  input[type="email"],
  select,
  textarea {
    width: 100%;
    font-family: 'Cairo', sans-serif;
    font-size: 14px;
    color: var(--text);
    background: var(--cream);
    border: 1.5px solid var(--border);
    border-radius: 8px;
    padding: 11px 14px;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    direction: rtl;
  }
  input:focus, select:focus, textarea:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(26,74,138,0.1);
    background: #fff;
  }
  textarea { resize: vertical; min-height: 90px; line-height: 1.7; }
  textarea.tall { min-height: 120px; }
  select {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%235c5c7a' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: left 14px center;
  }

  .options-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
  .options-grid.single { grid-template-columns: 1fr; }

  .option-item {
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--cream);
    border: 1.5px solid var(--border);
    border-radius: 8px;
    padding: 10px 14px;
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
    font-size: 13.5px;
    color: var(--text);
    font-weight: 400;
  }
  .option-item:hover { border-color: var(--accent); background: #eef3fa; }
  .option-item input[type="radio"],
  .option-item input[type="checkbox"] {
    width: auto; accent-color: var(--accent); cursor: pointer; flex-shrink: 0;
  }

  .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

  .scale-row { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
  .scale-btn {
    width: 42px; height: 42px;
    border: 1.5px solid var(--border);
    border-radius: 8px;
    background: var(--cream);
    font-family: 'Cairo', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: var(--muted);
    cursor: pointer;
    transition: all 0.2s;
    display: flex; align-items: center; justify-content: center;
  }
  .scale-btn:hover { border-color: var(--accent); color: var(--accent); background: #eef3fa; }
  .scale-btn.active { background: var(--navy); color: var(--gold-light); border-color: var(--navy); }

  /* SUBMIT */
  .submit-area {
    background: var(--navy);
    padding: 32px;
    border-radius: 0 0 12px 12px;
    text-align: center;
    margin-top: 2px;
  }
  .submit-btn {
    background: var(--gold);
    color: var(--navy);
    font-family: 'Cairo', sans-serif;
    font-size: 15px;
    font-weight: 700;
    border: none;
    padding: 14px 56px;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.2s, transform 0.15s;
    position: relative;
    min-width: 200px;
  }
  .submit-btn:hover:not(:disabled) { background: var(--gold-light); transform: translateY(-1px); }
  .submit-btn:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

  .submit-note { font-size: 12px; color: rgba(255,255,255,0.45); margin-top: 14px; line-height: 1.6; }

  .divider { height: 1px; background: var(--border); margin: 22px 0; }

  .form-footer { text-align: center; margin-top: 24px; font-size: 12px; color: #8a8070; }
  .form-number {
    display: inline-block;
    background: var(--navy);
    color: var(--gold-light);
    font-size: 11px;
    padding: 4px 12px;
    border-radius: 12px;
    margin-bottom: 8px;
    font-weight: 600;
  }

  /* SUCCESS SCREEN */
  #success-screen {
    display: none;
    background: var(--white);
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(13,31,60,0.15);
    padding: 60px 40px;
    text-align: center;
  }
  .success-icon {
    width: 80px; height: 80px;
    background: #e8f5ee;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 24px;
    font-size: 36px;
  }
  .success-title { font-family: 'Amiri', serif; font-size: 26px; color: var(--navy); margin-bottom: 12px; }
  .success-msg { font-size: 14px; color: var(--muted); line-height: 1.8; }

  /* SPINNER */
  .spinner {
    display: inline-block;
    width: 18px; height: 18px;
    border: 2px solid rgba(13,31,60,0.3);
    border-top-color: var(--navy);
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
    vertical-align: middle;
    margin-left: 8px;
  }
  @keyframes spin { to { transform: rotate(360deg); } }

  /* ERROR */
  .error-box {
    background: #fef2f2;
    border: 1.5px solid #fca5a5;
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 13px;
    color: #991b1b;
    margin-top: 16px;
    display: none;
  }

  @media (max-width: 560px) {
    .header-inner { padding: 28px 22px 24px; }
    .card { padding: 22px 18px; }
    .row-2 { grid-template-columns: 1fr; }
    .options-grid { grid-template-columns: 1fr; }
    .header-title { font-size: 22px; }
  }
</style>
</head>
<body>

<div class="wrapper">

  <!-- SUCCESS SCREEN (hidden initially) -->
  <div id="success-screen">
    <div class="success-icon">✅</div>
    <h2 class="success-title">تم إرسال الاستمارة بنجاح</h2>
    <p class="success-msg">
      شكراً لتقديم مقترحكم. سيتم مراجعة الاستمارة من قِبَل لجنة المشاريع الإبداعية<br>
      وستتلقون ردّاً رسمياً خلال أسبوعين.<br><br>
      <strong>جامعة الفارابي — وحدة التحول الرقمي</strong>
    </p>
  </div>

  <!-- MAIN FORM -->
  <div id="form-wrapper">

    <!-- HEADER -->
    <div class="header-card">
      <div class="header-inner">
        <div class="uni-badge">جامعة الفارابي – وحدة المشاريع الإبداعية والتقنية</div>
        <h1 class="header-title">استمارة تقديم المشروع الإبداعي<br>بالذكاء الاصطناعي</h1>
        <p class="header-subtitle">
          تُملأ هذه الاستمارة من قِبَل القسم الأكاديمي المعني لاقتراح منصة أو تطبيق ذكي<br>
          ضمن برنامج التحول الرقمي لجامعة الفارابي — تُرفع إلى رئاسة الجامعة للاعتماد
        </p>
        <div class="header-meta">
          <span class="meta-tag"><span class="dot"></span> الدراسة الأكاديمية 2024–2025</span>
          <span class="meta-tag"><span class="dot"></span> مفتوح لجميع الأقسام</span>
          <span class="meta-tag"><span class="dot"></span> مدة التعبئة: 15 دقيقة</span>
        </div>
      </div>
    </div>

    <!-- Q1: بيانات القسم -->
    <div class="section-header">
      <h3>القسم الأول — بيانات القسم ومقدِّم الطلب</h3>
      <p>معلومات التعريف الأساسية</p>
    </div>
    <div class="card">
      <div class="row-2">
        <div class="form-group">
          <label>الكلية <span class="required">*</span></label>
          <select id="f_college" name="الكلية" onchange="updateDepts(this.value)">
            <option value="">— اختر الكلية —</option>
            <option value="كلية الهندسة">🏗 كلية الهندسة</option>
            <option value="كلية التقنيات الصحية والطبية">🔬 كلية التقنيات الصحية والطبية</option>
            <option value="كلية الهندسة التقنية">⚙️ كلية الهندسة التقنية</option>
            <option value="الكليات الطبية والإنسانية">🏥 الكليات الطبية والإنسانية</option>
            <option value="كلية العلوم">🔭 كلية العلوم</option>
            <option value="كلية التربية">📚 كلية التربية</option>
            <option value="كلية الإدارة والاقتصاد">💼 كلية الإدارة والاقتصاد</option>
            <option value="كلية الفنون الجميلة">🎨 كلية الفنون الجميلة</option>
          </select>
        </div>
        <div class="form-group">
          <label>القسم <span class="required">*</span></label>
          <select id="f_dept_name" name="اسم القسم" disabled style="opacity:0.6">
            <option value="">— اختر الكلية أولاً —</option>
          </select>
        </div>
      </div>

      <div class="row-2">
        <div class="form-group">
          <label>اسم منسِّق المشروع (رئيس القسم) <span class="required">*</span></label>
          <input type="text" id="f_coordinator" name="اسم المنسق" placeholder="الاسم الثلاثي">
        </div>
        <div class="form-group">
          <label>البريد الإلكتروني الرسمي <span class="required">*</span></label>
          <input type="email" id="f_email" name="البريد الإلكتروني" placeholder="example@uofarabi.edu.iq" dir="ltr">
        </div>
      </div>
      <div class="row-2">
        <div class="form-group">
          <label>رقم الهاتف / الواتساب</label>
          <input type="text" id="f_phone" name="رقم الهاتف" placeholder="+964 7XX XXX XXXX" dir="ltr">
        </div>
        <div class="form-group">
          <label>تاريخ التقديم <span class="required">*</span></label>
          <input type="text" id="f_date" name="تاريخ التقديم" placeholder="يوم / شهر / سنة">
        </div>
      </div>
    </div>

    <!-- Q2: الفكرة والنشاط -->
    <div class="section-header">
      <h3>القسم الثاني — الفكرة والنشاط المقترح</h3>
      <p>وصف واضح للمشروع وطبيعة النشاط الذي يستهدفه</p>
    </div>
    <div class="card">
      <div class="form-group">
        <label>عنوان المشروع <span class="required">*</span>
          <span class="hint">اختر عنواناً واضحاً ومعبِّراً (لا يتجاوز 15 كلمة)</span>
        </label>
        <input type="text" id="f_project_title" name="عنوان المشروع" placeholder="مثال: منصة ذكية لتقييم الأبحاث الطلابية تلقائياً">
      </div>
      <div class="form-group">
        <label>وصف الفكرة <span class="required">*</span>
          <span class="hint">اشرح الفكرة بشكل مبسّط كما لو تشرحها لغير المتخصص</span>
        </label>
        <textarea class="tall" id="f_idea_desc" name="وصف الفكرة" placeholder="يُقترح إنشاء... بهدف... حيث يتيح للمستخدم أن يقوم بـ..."></textarea>
      </div>
      <div class="form-group">
        <label>طبيعة المشروع <span class="required">*</span></label>
        <div class="options-grid" id="f_project_type">
          <label class="option-item"><input type="radio" name="طبيعة المشروع" value="منصة ويب"> منصة ويب (Web Platform)</label>
          <label class="option-item"><input type="radio" name="طبيعة المشروع" value="تطبيق موبايل"> تطبيق موبايل</label>
          <label class="option-item"><input type="radio" name="طبيعة المشروع" value="نظام داخلي"> نظام داخلي للجامعة</label>
          <label class="option-item"><input type="radio" name="طبيعة المشروع" value="أداة ذكاء اصطناعي"> أداة ذكاء اصطناعي مخصصة</label>
          <label class="option-item"><input type="radio" name="طبيعة المشروع" value="لوحة تحكم"> لوحة تحكم وتحليل بيانات</label>
          <label class="option-item"><input type="radio" name="طبيعة المشروع" value="مزيج"> مزيج من أكثر من نوع</label>
        </div>
      </div>
      <div class="form-group">
        <label>النشاط الذي يدعمه المشروع <span class="required">*</span>
          <span class="hint">يمكن اختيار أكثر من خيار</span>
        </label>
        <div class="options-grid" id="f_activity">
          <label class="option-item"><input type="checkbox" name="النشاط" value="التعليم والتدريس"> التعليم والتدريس</label>
          <label class="option-item"><input type="checkbox" name="النشاط" value="البحث العلمي"> البحث العلمي</label>
          <label class="option-item"><input type="checkbox" name="النشاط" value="خدمات الطلاب"> خدمات الطلاب</label>
          <label class="option-item"><input type="checkbox" name="النشاط" value="الإدارة الأكاديمية"> الإدارة الأكاديمية</label>
          <label class="option-item"><input type="checkbox" name="النشاط" value="الخدمة المجتمعية"> الخدمة المجتمعية</label>
          <label class="option-item"><input type="checkbox" name="النشاط" value="التقييم والامتحانات"> التقييم والامتحانات</label>
        </div>
      </div>
    </div>

    <!-- Q3: الحاجة والمبررات -->
    <div class="section-header">
      <h3>القسم الثالث — الحاجة والمبررات</h3>
      <p>لماذا هذا المشروع ضروري؟ وما الفجوة التي يعالجها؟</p>
    </div>
    <div class="card">
      <div class="form-group">
        <label>المشكلة أو التحدي الحالي <span class="required">*</span>
          <span class="hint">صف الواقع الحالي والصعوبات التي يواجهها القسم أو الطلاب</span>
        </label>
        <textarea class="tall" id="f_problem" name="المشكلة الحالية" placeholder="يعاني القسم حالياً من... مما يؤدي إلى..."></textarea>
      </div>
      <div class="form-group">
        <label>سبب اختيار الذكاء الاصطناعي حلاً لهذه المشكلة <span class="required">*</span></label>
        <textarea id="f_ai_reason" name="سبب اختيار الذكاء الاصطناعي" placeholder="يتميز الذكاء الاصطناعي بقدرته على..."></textarea>
      </div>
      <div class="form-group">
        <label>هل سبق تطبيق أي حل مشابه في القسم؟ <span class="required">*</span></label>
        <div class="options-grid single">
          <label class="option-item"><input type="radio" name="حل سابق" value="نعم"> نعم — سبق تطبيق حل مشابه</label>
          <label class="option-item"><input type="radio" name="حل سابق" value="لا"> لا — هذا المقترح جديد كلياً</label>
          <label class="option-item"><input type="radio" name="حل سابق" value="دراسة فقط"> تمت دراسة الفكرة لكن لم تُنفَّذ</label>
        </div>
      </div>
      <div class="form-group">
        <label>المبررات الجوهرية لاعتماد المشروع <span class="required">*</span>
          <span class="hint">قدِّم ثلاثة مبررات على الأقل: أكاديمي / اقتصادي / مجتمعي</span>
        </label>
        <textarea class="tall" id="f_justifications" name="المبررات الجوهرية" placeholder="١- أكاديمياً: ...&#10;٢- اقتصادياً: ...&#10;٣- مجتمعياً: ..."></textarea>
      </div>
    </div>

    <!-- Q4: التفاصيل التقنية -->
    <div class="section-header">
      <h3>القسم الرابع — التفاصيل التقنية والتنفيذية</h3>
      <p>المعطيات اللازمة لتقييم جدوى التنفيذ</p>
    </div>
    <div class="card">
      <div class="form-group">
        <label>الفئة المستهدفة <span class="required">*</span>
          <span class="hint">يمكن اختيار أكثر من فئة</span>
        </label>
        <div class="options-grid">
          <label class="option-item"><input type="checkbox" name="الفئة المستهدفة" value="طلاب البكالوريوس"> طلاب البكالوريوس</label>
          <label class="option-item"><input type="checkbox" name="الفئة المستهدفة" value="طلاب الدراسات العليا"> طلاب الدراسات العليا</label>
          <label class="option-item"><input type="checkbox" name="الفئة المستهدفة" value="أعضاء هيئة التدريس"> أعضاء هيئة التدريس</label>
          <label class="option-item"><input type="checkbox" name="الفئة المستهدفة" value="الإداريون"> الإداريون والموظفون</label>
          <label class="option-item"><input type="checkbox" name="الفئة المستهدفة" value="الباحثون"> الباحثون</label>
          <label class="option-item"><input type="checkbox" name="الفئة المستهدفة" value="المجتمع الخارجي"> المجتمع الخارجي / الشركاء</label>
        </div>
      </div>
      <div class="row-2">
        <div class="form-group">
          <label>العدد التقريبي للمستخدمين المتوقعين</label>
          <select id="f_users_count" name="عدد المستخدمين المتوقعين">
            <option value="">— اختر —</option>
            <option>أقل من 100 مستخدم</option>
            <option>100 – 500 مستخدم</option>
            <option>500 – 2000 مستخدم</option>
            <option>أكثر من 2000 مستخدم</option>
          </select>
        </div>
        <div class="form-group">
          <label>الجهة المقترحة لتطوير المشروع</label>
          <select id="f_dev_party" name="جهة التطوير">
            <option value="">— اختر —</option>
            <option>فريق داخلي من الطلاب والأساتذة</option>
            <option>شركة تقنية خارجية</option>
            <option>شراكة داخلية-خارجية</option>
            <option>قسم تكنولوجيا المعلومات</option>
            <option>لم يُحدَّد بعد</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label>تقنيات الذكاء الاصطناعي المقترحة
          <span class="hint">اختياري</span>
        </label>
        <div class="options-grid">
          <label class="option-item"><input type="checkbox" name="تقنيات الذكاء الاصطناعي" value="NLP/LLM"> معالجة اللغة الطبيعية (NLP/LLM)</label>
          <label class="option-item"><input type="checkbox" name="تقنيات الذكاء الاصطناعي" value="رؤية حاسوبية"> رؤية حاسوبية (Computer Vision)</label>
          <label class="option-item"><input type="checkbox" name="تقنيات الذكاء الاصطناعي" value="توصيات وتعلم آلي"> التوصيات والتعلم الآلي</label>
          <label class="option-item"><input type="checkbox" name="تقنيات الذكاء الاصطناعي" value="أتمتة RPA"> الأتمتة والروبوتات (RPA)</label>
          <label class="option-item"><input type="checkbox" name="تقنيات الذكاء الاصطناعي" value="تحليل بيانات"> تحليل البيانات والتنبؤ</label>
          <label class="option-item"><input type="checkbox" name="تقنيات الذكاء الاصطناعي" value="غير محدد"> غير محدد / مفتوح</label>
        </div>
      </div>
      <div class="row-2">
        <div class="form-group">
          <label>الميزانية التقديرية</label>
          <select id="f_budget" name="الميزانية التقديرية">
            <option value="">— اختر النطاق —</option>
            <option>أقل من 5,000 $</option>
            <option>5,000 – 15,000 $</option>
            <option>15,000 – 50,000 $</option>
            <option>أكثر من 50,000 $</option>
            <option>يحتاج دراسة جدوى أولاً</option>
          </select>
        </div>
        <div class="form-group">
          <label>المدة الزمنية المقترحة للتنفيذ</label>
          <select id="f_duration" name="مدة التنفيذ">
            <option value="">— اختر —</option>
            <option>أقل من 3 أشهر</option>
            <option>3 – 6 أشهر</option>
            <option>6 – 12 شهراً</option>
            <option>أكثر من سنة</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Q5: النتائج -->
    <div class="section-header">
      <h3>القسم الخامس — النتائج والأثر المتوقع</h3>
      <p>ما الذي سيتغير بعد تنفيذ المشروع؟</p>
    </div>
    <div class="card">
      <div class="form-group">
        <label>النتائج المتوقعة على المدى القصير (خلال 6 أشهر) <span class="required">*</span></label>
        <textarea id="f_results_short" name="النتائج قصيرة المدى" placeholder="مثال: تخفيض وقت تصحيح الأبحاث بنسبة 60%..."></textarea>
      </div>
      <div class="form-group">
        <label>النتائج المتوقعة على المدى البعيد (سنة فأكثر) <span class="required">*</span></label>
        <textarea id="f_results_long" name="النتائج بعيدة المدى" placeholder="مثال: أرشيف بحثي رقمي تراكمي، تحسين مستوى مخرجات التعلم..."></textarea>
      </div>
      <div class="form-group">
        <label>مؤشرات النجاح المقترحة (KPIs) <span class="required">*</span>
          <span class="hint">كيف ستقيسون نجاح المشروع؟</span>
        </label>
        <textarea id="f_kpis" name="مؤشرات النجاح KPIs" placeholder="مثال: نسبة اعتماد المستخدمين، متوسط وقت الإنجاز، درجة رضا الطلاب..."></textarea>
      </div>
    </div>

    <!-- Q6: الدعم -->
    <div class="section-header">
      <h3>القسم السادس — الدعم والموافقات الأولية</h3>
      <p>تأكيد الجاهزية والالتزام المؤسسي</p>
    </div>
    <div class="card">
      <div class="form-group">
        <label>هل حصل المقترح على موافقة رئيس القسم؟ <span class="required">*</span></label>
        <div class="options-grid">
          <label class="option-item"><input type="radio" name="موافقة رئيس القسم" value="موافقة رسمية"> نعم — موافقة رسمية</label>
          <label class="option-item"><input type="radio" name="موافقة رئيس القسم" value="موافقة شفهية"> موافقة مبدئية شفهية</label>
          <label class="option-item"><input type="radio" name="موافقة رئيس القسم" value="قيد المناقشة"> قيد المناقشة داخلياً</label>
        </div>
      </div>
      <div class="form-group">
        <label>مستوى الأولوية الذي يمنحه القسم لهذا المشروع <span class="required">*</span></label>
        <div class="scale-row">
          <span style="font-size:12px;color:var(--muted);">منخفضة</span>
          <button class="scale-btn" type="button" data-val="١">١</button>
          <button class="scale-btn" type="button" data-val="٢">٢</button>
          <button class="scale-btn" type="button" data-val="٣">٣</button>
          <button class="scale-btn" type="button" data-val="٤">٤</button>
          <button class="scale-btn" type="button" data-val="٥">٥</button>
          <span style="font-size:12px;color:var(--muted);">عالية جداً</span>
        </div>
        <input type="hidden" id="f_priority" name="مستوى الأولوية" value="">
      </div>
      <div class="form-group">
        <label>ملاحظات ختامية أو معلومات إضافية</label>
        <textarea class="tall" id="f_notes" name="ملاحظات إضافية" placeholder="أي سياق إضافي، نماذج مشابهة، قيود يجب أخذها بعين الاعتبار..."></textarea>
      </div>
      <div class="divider"></div>
      <div class="form-group">
        <label>إقرار المقدِّم <span class="required">*</span></label>
        <label class="option-item" style="background:#fffbf0;border-color:#d4a017;">
          <input type="checkbox" id="f_declaration">
          <span style="font-size:13px;line-height:1.6;">
            أُقرّ بأن جميع المعلومات الواردة في هذه الاستمارة صحيحة ودقيقة، وأن القسم الأكاديمي على استعداد للتعاون مع لجنة المشاريع الإبداعية لاستكمال دراسة الجدوى والتنفيذ عند الاعتماد.
          </span>
        </label>
      </div>
    </div>

    <!-- SUBMIT -->
    <div class="submit-area">
      <button class="submit-btn" id="submit-btn" type="button" onclick="submitForm()">
        إرسال الاستمارة للمراجعة
      </button>
      <div class="error-box" id="error-box"></div>
      <p class="submit-note">
        سيتم مراجعة الاستمارة من قِبَل لجنة المشاريع الإبداعية وإشعار القسم خلال أسبوعين.<br>
        للاستفسار: projects@uofarabi.edu.iq
      </p>
    </div>

    <div class="form-footer">
      <div class="form-number">UF-AI-FORM-01 · 2024</div>
      <p>جامعة الفارابي — وحدة التحول الرقمي والمشاريع الإبداعية بالتعاون مع قسم تكنولوجيا المعلومات</p>
    </div>

  </div><!-- /form-wrapper -->
</div><!-- /wrapper -->

<script>
  // ============================================================
  // ⬇️  ضع هنا رابط الـ Web App بعد نشر Google Apps Script
  // ============================================================
  const APPS_SCRIPT_URL = "submit.php";
  // ============================================================


  // ---- Linked College → Department dropdown ----
  const deptMap = {
    "كلية الهندسة": [
      "الهندسة المعمارية",
      "الهندسة المدنية",
      "هندسة النفط",
      "هندسة تكرير النفط والغاز",
      "هندسة الحاسوب",
      "هندسة نظم الطائرات المسيّرة",
      "هندسة الذكاء الاصطناعي",
      "هندسة الطب الحياتي",
      "هندسة الأمن السيبراني"
    ],
    "كلية التقنيات الصحية والطبية": [
      "تقنيات المختبرات الطبية",
      "تقنيات البصريات",
      "التحميل والبزر",
      "التخدير",
      "صناعة الأسنان",
      "الأشعة",
      "التغذية العلاجية",
      "التأهيل والعلاج الطبيعي"
    ],
    "كلية الهندسة التقنية": [
      "تقنيات الأجهزة الطبية",
      "الهندسة التقنية الكهربائية",
      "هندسة تقنيات الحاسوب",
      "الاستكشافات وحفر الآبار",
      "تقنيات الأمن السيبراني"
    ],
    "الكليات الطبية والإنسانية": [
      "كلية طب الأسنان",
      "كلية الصيدلة",
      "كلية التمريض",
      "كلية القانون",
      "كلية الإعلام",
      "كلية التربية البدنية وعلوم الرياضة"
    ],
    "كلية العلوم": [
      "علوم الحياة",
      "علوم الأمن السيبراني",
      "الأدلة الجنائية",
      "علوم الفيزياء الطبية"
    ],
    "كلية التربية": [
      "تربية اللغة الإنكليزية",
      "اللغة العربية",
      "الكيمياء",
      "الرياضيات",
      "علوم الحياة"
    ],
    "كلية الإدارة والاقتصاد": [
      "العلوم المحاسبية والمصرفية",
      "إدارة الأعمال",
      "التأمين وإدارة المخاطر",
      "الإدارة الصناعية"
    ],
    "كلية الفنون الجميلة": [
      "التصميم",
      "الأزياء والموضة"
    ]
  };

  function updateDepts(college) {
    const deptSelect = document.getElementById('f_dept_name');
    deptSelect.innerHTML = '';
    if (!college || !deptMap[college]) {
      deptSelect.innerHTML = '<option value="">— اختر الكلية أولاً —</option>';
      deptSelect.disabled = true;
      deptSelect.style.opacity = '0.6';
      return;
    }
    const placeholder = document.createElement('option');
    placeholder.value = '';
    placeholder.textContent = '— اختر القسم —';
    deptSelect.appendChild(placeholder);
    deptMap[college].forEach(dept => {
      const opt = document.createElement('option');
      opt.value = dept;
      opt.textContent = dept;
      deptSelect.appendChild(opt);
    });
    deptSelect.disabled = false;
    deptSelect.style.opacity = '1';
  }

  // Scale buttons
  document.querySelectorAll('.scale-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.scale-btn').forEach(s => s.classList.remove('active'));
      this.classList.add('active');
      document.getElementById('f_priority').value = this.dataset.val;
    });
  });

  // Collect checkboxes helper
  function getChecked(name) {
    return [...document.querySelectorAll(`input[type="checkbox"][name="${name}"]:checked`)]
      .map(el => el.value).join('، ') || '—';
  }

  // Collect radio helper
  function getRadio(name) {
    const el = document.querySelector(`input[type="radio"][name="${name}"]:checked`);
    return el ? el.value : '—';
  }

  async function submitForm() {
    const btn = document.getElementById('submit-btn');
    const errorBox = document.getElementById('error-box');
    errorBox.style.display = 'none';

    // Validate required fields
    const required = [
      { id: 'f_dept_name',      label: 'اسم القسم' },
      { id: 'f_college',        label: 'الكلية' },
      { id: 'f_coordinator',   label: 'اسم المنسق' },
      { id: 'f_email',         label: 'البريد الإلكتروني' },
      { id: 'f_date',          label: 'تاريخ التقديم' },
      { id: 'f_project_title', label: 'عنوان المشروع' },
      { id: 'f_idea_desc',     label: 'وصف الفكرة' },
      { id: 'f_problem',       label: 'المشكلة الحالية' },
      { id: 'f_ai_reason',     label: 'سبب اختيار الذكاء الاصطناعي' },
      { id: 'f_justifications',label: 'المبررات الجوهرية' },
      { id: 'f_results_short', label: 'النتائج قصيرة المدى' },
      { id: 'f_results_long',  label: 'النتائج بعيدة المدى' },
      { id: 'f_kpis',          label: 'مؤشرات النجاح' },
    ];

    for (const f of required) {
      const el = document.getElementById(f.id);
      if (!el || !el.value.trim()) {
        errorBox.textContent = `⚠️  حقل مطلوب: "${f.label}" — يرجى التأكد من تعبئة جميع الحقول الإلزامية.`;
        errorBox.style.display = 'block';
        el && el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
      }
    }

    if (!document.getElementById('f_declaration').checked) {
      errorBox.textContent = '⚠️  يرجى الموافقة على إقرار المقدِّم قبل الإرسال.';
      errorBox.style.display = 'block';
      return;
    }

    // Collect all data
    const data = {
      "الطابع الزمني":          new Date().toLocaleString('ar-IQ'),
      "اسم القسم":               document.getElementById('f_dept_name').value,
      "الكلية":                  document.getElementById('f_college').value,
      "اسم المنسق":              document.getElementById('f_coordinator').value,
      "البريد الإلكتروني":       document.getElementById('f_email').value,
      "رقم الهاتف":              document.getElementById('f_phone').value,
      "تاريخ التقديم":           document.getElementById('f_date').value,
      "عنوان المشروع":           document.getElementById('f_project_title').value,
      "وصف الفكرة":              document.getElementById('f_idea_desc').value,
      "طبيعة المشروع":           getRadio('طبيعة المشروع'),
      "النشاط المدعوم":          getChecked('النشاط'),
      "المشكلة الحالية":         document.getElementById('f_problem').value,
      "سبب الذكاء الاصطناعي":   document.getElementById('f_ai_reason').value,
      "حل سابق":                 getRadio('حل سابق'),
      "المبررات الجوهرية":       document.getElementById('f_justifications').value,
      "الفئة المستهدفة":         getChecked('الفئة المستهدفة'),
      "عدد المستخدمين":          document.getElementById('f_users_count').value,
      "جهة التطوير":             document.getElementById('f_dev_party').value,
      "تقنيات الذكاء الاصطناعي":getChecked('تقنيات الذكاء الاصطناعي'),
      "الميزانية التقديرية":     document.getElementById('f_budget').value,
      "مدة التنفيذ":             document.getElementById('f_duration').value,
      "النتائج قصيرة المدى":    document.getElementById('f_results_short').value,
      "النتائج بعيدة المدى":    document.getElementById('f_results_long').value,
      "مؤشرات النجاح KPIs":     document.getElementById('f_kpis').value,
      "موافقة رئيس القسم":       getRadio('موافقة رئيس القسم'),
      "مستوى الأولوية":          document.getElementById('f_priority').value || '—',
      "ملاحظات إضافية":         document.getElementById('f_notes').value,
      "csrf_token":             "<?php echo $_SESSION['csrf_token']; ?>"
    };

    // Send
    btn.disabled = true;
    btn.innerHTML = 'جارٍ الإرسال <span class="spinner"></span>';

    try {
      const response = await fetch(APPS_SCRIPT_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });
      const result = await response.json();

      if(result.status === 'success') {
        // Success
        document.getElementById('form-wrapper').style.display = 'none';
        document.getElementById('success-screen').style.display = 'block';
        window.scrollTo({ top: 0, behavior: 'smooth' });
      } else {
        throw new Error(result.message || 'Error occurred');
      }

    } catch (err) {
      btn.disabled = false;
      btn.innerHTML = 'إرسال الاستمارة للمراجعة';
      errorBox.textContent = '⚠️  حدث خطأ أثناء الإرسال. يرجى التحقق من اتصالك بالإنترنت والمحاولة مجدداً.';
      errorBox.style.display = 'block';
    }
  }
</script>
</body>
</html>
