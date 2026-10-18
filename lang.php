<?php


// اللغة الافتراضية أول مرة
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'ar'; // نخليها عربية أول ما يدخل
}

// لو المستخدم اختار لغة (عن طريق الزر)
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'en'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang = $_SESSION['lang'];

// الترجمة
$translations = [
    // login

    'register_title' => [
        'ar' => 'تسجيل جديد',
        'en' => 'Register',
    ],
    'login_title' => [
        'ar' => 'تسجيل الدخول',
        'en' => 'Login',
    ],
    'username' => [
        'ar' => 'اسم المستخدم',
        'en' => 'Username',
    ],
    'email' => [
        'ar' => 'البريد الإلكتروني',
        'en' => 'Email',
    ],
    'password' => [
        'ar' => 'كلمة المرور',
        'en' => 'Password',
    ],
    'department' => [
        'ar' => 'القسم',
        'en' => 'Department',
    ],
    'create_account' => [
        'ar' => 'إنشاء الحساب',
        'en' => 'Create Account',
    ],
    'login_btn' => [
        'ar' => 'دخول',
        'en' => 'Login',
    ],
    'forgot_password' => [
        'ar' => 'نسيت كلمة المرور؟',
        'en' => 'Forgot Password?',
    ],
    'change_lang' => [
        'ar' => 'English',
        'en' => 'عربي',
    ],

    // forget_password
    'forgot_title' => [
  'ar' => 'نسيت كلمة المرور؟',
  'en' => 'Forgot Password?'
],
'forgot_instruction' => [
  'ar' => 'ادخلي بريدك الإلكتروني وسنرسل لك رابطًا لإعادة تعيين كلمة المرور.',
  'en' => 'Enter your email and we will send you a link to reset your password.'
],
'forgot_email_placeholder' => [
  'ar' => 'البريد الإلكتروني',
  'en' => 'Email'
],
'forgot_send_button' => [
  'ar' => 'إرسال الرابط',
  'en' => 'Send Link'
],
'forgot_success' => [
  'ar' => 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.',
  'en' => 'A password reset link has been sent to your email.'
],
'forgot_error' => [
  'ar' => 'حدث خطأ أثناء إرسال الإيميل. جرّبي لاحقًا أو تأكدي من الإعدادات.',
  'en' => 'An error occurred while sending the email. Please try again later or check your settings.'
],
'forgot_email_not_found' => [
  'ar' => 'البريد الإلكتروني غير موجود.',
  'en' => 'Email not found.'
],
'forgot_test_link' => [
  'ar' => ' اضغطي هنا لتجربة الرابط',
  'en' => ' Click here to test the link'
],
//الريست

'reset_title' => [
  'ar' => 'إعادة تعيين كلمة المرور',
  'en' => 'Reset Password'
],
'new_password' => [
  'ar' => 'كلمة المرور الجديدة',
  'en' => 'New Password'
],
'update_password' => [
  'ar' => 'تحديث كلمة المرور',
  'en' => 'Update Password'
],
'reset_success' => [
  'ar' => "تم تحديث كلمة المرور بنجاح. <a href='index.php'>اضغطي هنا لتسجيل الدخول</a>",
  'en' => "Password updated successfully. <a href='index.php'>Click here to login</a>"
],
'reset_invalid' => [
  'ar' => "الرابط غير صالح.",
  'en' => "Invalid reset link."
],
'reset_expired' => [
  'ar' => "الرابط غير صالح أو انتهت صلاحيته. من فضلك أعيدي طلب رابط جديد.",
  'en' => "The link is invalid or expired. Please request a new reset link."
],


// home page
'home_title' => [
    'ar' => 'الصفحة الرئيسية',
    'en' => 'Home Page'
],
'system_name' => [
    'ar' => 'تقن - نظام البلاغات',
    'en' => 'Teqen - Reporting System'
],
'digital_health_board' => [
    'ar' => 'لوحة الصحة الرقمية',
    'en' => 'Digital Health Board'
],
'nav_home' => [
    'ar' => 'الرئيسية',
    'en' => 'Home'
],
'nav_archive' => [
    'ar' => 'الأرشيف',
    'en' => 'Archive'
],
'nav_reports' => [
    'ar' => 'التقارير',
    'en' => 'Reports'
],
'active_reports' => [
    'ar' => 'البلاغات الحالية',
    'en' => 'Active Reports'
],
'urgent' => [
    'ar' => 'عاجل',
    'en' => 'Urgent'
],
'status_new' => [
    'ar' => 'جديد',
    'en' => 'New'
],
'status_processing' => [
    'ar' => 'قيد المعالجة',
    'en' => 'In Progress'
],
'department' => [
    'ar' => 'القسم',
    'en' => 'Department'
],
'date' => [
    'ar' => 'التاريخ',
    'en' => 'Date'
],
'you_have_message' => [
    'ar' => 'لديك رسالة جديدة',
    'en' => 'You have a new message'
],
'view_details' => [
    'ar' => 'عرض التفاصيل',
    'en' => 'View Details'
],
'no_reports' => [
    'ar' => 'لا توجد بلاغات حالياً',
    'en' => 'No reports currently'
],

// تفاصيل البلاغ
'report_details' => [
    'ar' => 'تفاصيل البلاغ',
    'en' => 'Report Details'
],
'report_id' => [
    'ar' => 'رقم البلاغ',
    'en' => 'Report ID'
],
'reporter_name' => [
    'ar' => 'اسم المُبلّغ',
    'en' => 'Reporter Name'
],
'title' => [
    'ar' => 'عنوان البلاغ',
    'en' => 'Report Title'
],
'status' => [
    'ar' => 'الحالة',
    'en' => 'Status'
],
'status_done' => [
    'ar' => 'تم الحل',
    'en' => 'Resolved'
],
'handled_by' => [
    'ar' => 'تم الاستلام بواسطة',
    'en' => 'Handled by'
],
'attachments' => [
    'ar' => 'المرفقات',
    'en' => 'Attachments'
],
'btn_receive' => [
    'ar' => 'استلام البلاغ',
    'en' => 'Receive Report'
],
'btn_resolved' => [
    'ar' => 'تم حلها',
    'en' => 'Mark as Resolved'
],
'chat' => [
    'ar' => 'المحادثة',
    'en' => 'Chat'
],
'type_here' => [
    'ar' => 'اكتب رسالتك هنا...',
    'en' => 'Type your message here...'
],
'btn_send' => [
    'ar' => 'إرسال',
    'en' => 'Send'
],
'report_not_found' => [
    'ar' => 'البلاغ غير موجود.',
    'en' => 'Report not found.'
],

//صفحه الارشيف


'search_placeholder' => [
  'ar' => 'ابحث برقم البلاغ أو العنوان',
  'en' => 'Search by report ID or title'
],
'all_departments' => [
  'ar' => 'كل الأقسام',
  'en' => 'All Departments'
],
'btn_filter' => [
  'ar' => 'تصفية',
  'en' => 'Filter'
],
'btn_details' => [
  'ar' => 'عرض التفاصيل',
  'en' => 'View Details'
],
// التقارير

'reports_page_title' => [
  'ar' => 'صفحة التقارير',
  'en' => 'Reports Page'
],
'filter_by_year' => [
  'ar' => 'كل السنوات',
  'en' => 'All Years'
],
'filter_by_month' => [
  'ar' => 'اختر الشهر',
  'en' => 'Select Month'
],
'filter_by_department' => [
  'ar' => 'كل الأقسام',
  'en' => 'All Departments'
],
'filter_button' => [
  'ar' => 'عرض التقرير',
  'en' => 'View Report'
],
'total_reports' => [
  'ar' => 'البلاغات الكلية',
  'en' => 'Total Reports'
],
'resolved_reports' => [
  'ar' => 'تم الحل',
  'en' => 'Resolved'
],
'in_progress_reports' => [
  'ar' => 'تحت المعالجة',
  'en' => 'In Progress'
],
'urgent_reports' => [
  'ar' => 'بلاغات عاجلة',
  'en' => 'Urgent Reports'
],
'chart_by_departments' => [
  'ar' => 'نسبة البلاغات حسب الأقسام',
  'en' => 'Reports by Department'
],
'chart_by_issues' => [
  'ar' => 'الأعطال الشائعة',
  'en' => 'Common Issues'
],
'report_period' => [
  'ar' => 'الفترة',
  'en' => 'Period'
],
'download_pdf' => [
  'ar' => 'تحميل التقرير PDF',
  'en' => 'Download PDF Report'
],
'months' => [
  'ar' => ['يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'],
  'en' => ['January','February','March','April','May','June','July','August','September','October','November','December']
],
'repeats' => [
  'ar' => 'عدد التكرارات',
  'en' => 'Repetitions'
],
// الاعدادات
'account_settings_title' => [
  'ar' => 'إعدادات الحساب',
  'en' => 'Account Settings'
],

// العناوين داخل الأكورديون
'section_account' => [
  'ar' => 'حسابي',
  'en' => 'My Account'
],
'section_change_password' => [
  'ar' => 'تغيير كلمة المرور',
  'en' => 'Change Password'
],
'section_dark_mode' => [
  'ar' => 'الوضع الليلي',
  'en' => 'Dark Mode'
],
'about_system' => [
  'ar' => 'حول النظام',
  'en' => 'About the System'
],
'logout' => [
  'ar' => 'تسجيل الخروج',
  'en' => 'Logout'
],
'logout_now' => [
  'ar' => 'تسجيل الخروج الآن',
  'en' => 'Logout Now'
],

// الحقول داخل الفورم
'label_username' => [
  'ar' => 'اسم المستخدم:',
  'en' => 'Username:'
],
'label_email' => [
  'ar' => 'البريد الإلكتروني:',
  'en' => 'Email:'
],
'label_department' => [
  'ar' => 'القسم:',
  'en' => 'Department:'
],

// الأزرار
'edit_button' => [
  'ar' => 'تعديل البيانات',
  'en' => 'Edit Info'
],
'save_button' => [
  'ar' => 'حفظ التعديل',
  'en' => 'Save Changes'
],
'confirm_change_button' => [
  'ar' => 'تأكيد التغيير',
  'en' => 'Confirm Change'
],

// الحقول داخل تغيير كلمة المرور
'current_password' => [
  'ar' => 'كلمة المرور الحالية',
  'en' => 'Current Password'
],
'new_password' => [
  'ar' => 'كلمة المرور الجديدة',
  'en' => 'New Password'
],
'confirm_password' => [
  'ar' => 'تأكيد كلمة المرور',
  'en' => 'Confirm Password'
],

// نص الوضع الليلي
'toggle_dark_mode' => [
  'ar' => 'تفعيل الوضع الليلي',
  'en' => 'Enable Dark Mode'
],

// رسائل التنبيه
'success_update' => [
  'ar' => '✅ تم تحديث البيانات',
  'en' => '✅ Profile updated successfully'
],
'error_password_incorrect' => [
  'ar' => '❌ كلمة المرور الحالية غير صحيحة',
  'en' => '❌ Current password is incorrect'
],
'error_password_mismatch' => [
  'ar' => '❌ كلمة المرور غير متطابقة',
  'en' => '❌ Passwords do not match'
],
'password_changed' => [
  'ar' => '✅ تم تغيير كلمة المرور',
  'en' => '✅ Password changed successfully'
],

// نص حول النظام
'system_about_text' => [
  'ar' => "نِظام <strong>تِقن</strong> هو منصة داخلية ذكية صممت خصيصًا لتسهيل رفع البلاغات التقنية داخل المستشفى، من خلال نموذج إلكتروني بسيط وسريع.<br>
يقوم الموظفون بإنشاء البلاغات التقنية خلال دقائق، مع إمكانية تحديد درجة الأهمية وإرفاق الصور لدعم البلاغ.<br><br>
بمجرد إرسال البلاغ، يتلقى فريق الصحة الرقمية إشعارًا فوريًا عبر لوحة تحكم متقدمة، مما يمكّنه من متابعة الحالة خطوة بخطوة واتخاذ الإجراءات اللازمة.<br><br>
يُعزز النظام من سرعة الاستجابة وتقليل الوقت الضائع، كما يساهم في تحسين جودة الخدمات التقنية من خلال إتاحة بيانات إحصائية وتقارير تحليلية لفريق الدعم.<br><br>
كما تتميز المنصة بواجهة سهلة الاستخدام ومتجاوبة مع الأجهزة، مع دعم للوضع الليلي لراحة المستخدمين في مختلف أوقات العمل.<br><br>
وقد تم تطوير هذا النظام بالكامل من قِبل <strong>إحدى المتدربات في قسم الصحة الرقمية</strong>، استنادًا إلى متطلبات العمل الفعلية واحتياجات بيئة المستشفى.",
  'en' => "<strong>TEQEN</strong> is a smart internal platform designed to simplify the process of reporting technical issues in hospitals through a simple and fast electronic form.<br>
Employees can submit reports within minutes, with the ability to specify urgency and attach supporting images.<br><br>
Once submitted, the Digital Health team receives immediate notifications via an advanced control panel, enabling them to track and handle the report step by step.<br><br>
The system boosts response speed, minimizes wasted time, and enhances service quality by providing analytical reports and statistical data.<br><br>
The platform features an easy-to-use interface responsive to all devices, with dark mode support for better comfort.<br><br>
This platform was fully developed by <strong>an intern in the Digital Health department</strong>, based on real-world needs of the hospital environment."
],

// روابط القائمة الجانبية - الموظف العادي
'new_report' => [
  'ar' => 'بلاغ جديد',
  'en' => 'New Report'
],
'my_reports' => [
  'ar' => 'بلاغاتي',
  'en' => 'My Reports'
],
'user_settings' => [
  'ar' => 'الإعدادات',
  'en' => 'Settings'
],
// عنوان قسم الأسئلة الشائعة
'faq_section_title' => [
  'ar' => 'الأسئلة الشائعة',
  'en' => 'Frequently Asked Questions'
],

// سؤال 1
'faq_q1' => [
  'ar' => '1. كيف يمكنني تسجيل بلاغ جديد؟',
  'en' => '1. How can I submit a new report?'
],
'faq_a1' => [
  'ar' => 'يمكنك الانتقال إلى صفحة “بلاغ جديد”، ثم تعبئة البيانات المطلوبة بدقة، وشرح المشكلة بشكل واضح، مع تحديد درجة الأهمية.',
  'en' => 'You can go to the “New Report” page, fill in the required information accurately, describe the issue clearly, and select the urgency level.'
],

// سؤال 2
'faq_q2' => [
  'ar' => '2. ما الفرق بين البلاغ العادي والبلاغ العاجل؟',
  'en' => '2. What is the difference between a normal and urgent report?'
],
'faq_a2' => [
  'ar' => 'البلاغ العادي يُستخدم للمشكلات التي لا تؤثر على سير العمل. أما البلاغ العاجل فيُستخدم عند تعطل جهاز أساسي أو توقف خدمة تؤثر على أداء القسم مباشرة.',
  'en' => 'Normal reports are for issues that do not affect workflow. Urgent reports are used when a critical device fails or a service affecting the department directly stops.'
],

// سؤال 3
'faq_q3' => [
  'ar' => '3. كيف أتابع حالة البلاغ بعد الإرسال؟',
  'en' => '3. How can I track the report status after submission?'
],
'faq_a3' => [
  'ar' => 'من خلال صفحة “بلاغاتي”، يمكنك الاطلاع على جميع البلاغات التي قمت بإرسالها، ومعرفة حالتها الحالية: (جديد، قيد المعالجة، تم الحل).',
  'en' => 'Through the “My Reports” page, you can view all submitted reports and see their current status: (New, In Progress, Resolved).'
],

// سؤال 4
'faq_q4' => [
  'ar' => '4. هل يمكن تعديل البلاغ بعد إرساله؟',
  'en' => '4. Can I edit a report after submitting it?'
],
'faq_a4' => [
  'ar' => 'لا يمكن تعديل البلاغ بعد إرساله. إذا كانت هناك معلومات إضافية، يُفضل إرسال بلاغ جديد يوضّح التحديث أو الإضافة المطلوبة.',
  'en' => 'You cannot edit a report after submission. If you have additional information, it is recommended to submit a new report with the update.'
],

// سؤال 5
'faq_q5' => [
  'ar' => '5. هل يمكنني حذف بلاغ سابق؟',
  'en' => '5. Can I delete a previous report?'
],
'faq_a5' => [
  'ar' => 'لا، لا يمكن حذف البلاغ بعد الإرسال. جميع البلاغات تُحفظ تلقائيًا لأغراض المتابعة والتوثيق.',
  'en' => 'No, you cannot delete a report after submission. All reports are saved automatically for tracking and documentation purposes.'
],

// سؤال 6
'faq_q6' => [
  'ar' => '6. هل سأستلم إشعار عند معالجة البلاغ؟',
  'en' => '6. Will I receive a notification when my report is processed?'
],
'faq_a6' => [
  'ar' => 'حاليًا لا يتم إرسال إشعارات تلقائية، ولكن يمكنك الدخول إلى “بلاغاتي” في أي وقت لمتابعة التحديثات والحالة.',
  'en' => 'Currently, automatic notifications are not sent, but you can visit the “My Reports” page anytime to check updates.'
],

// سؤال 7
'faq_q7' => [
  'ar' => '7. متى يتم حل البلاغ؟',
  'en' => '7. When will my report be resolved?'
],
'faq_a7' => [
  'ar' => 'تعتمد سرعة المعالجة على طبيعة البلاغ ودرجة أهميته. يتم التعامل مع البلاغات حسب الترتيب والأولوية المحددة أثناء الإرسال.',
  'en' => 'Resolution speed depends on the type and urgency of the report. Reports are handled based on submission order and priority.'
],

// سؤال 8
'faq_q8' => [
  'ar' => '8. ماذا أفعل إذا أرسلت بلاغ بالخطأ؟',
  'en' => '8. What should I do if I submitted a report by mistake?'
],
'faq_a8' => [
  'ar' => 'يمكنك إرسال بلاغ جديد توضح فيه أن البلاغ السابق تم بالخطأ، أو إبلاغ الجهة المختصة شفهيًا داخل القسم.',
  'en' => 'You can submit a new report clarifying that the previous one was a mistake or inform the relevant team verbally within the department.'
],


// صفحة بلاغ جديد
'new_report' => [
  'ar' => 'بلاغ جديد',
  'en' => 'New Report'
],
'welcome_user' => [
  'ar' => 'أهلاً بك يا',
  'en' => 'Welcome'
],
'report_intro' => [
  'ar' => 'عندك مشكلة تقنية؟ بلغ عنها من هنا وتابع حلّها خطوة بخطوة.',
  'en' => 'Have a technical issue? Report it here and track it step by step.'
],
'report_title_label' => [
  'ar' => 'عنوان البلاغ',
  'en' => 'Report Title'
],
'title_placeholder' => [
  'ar' => 'مثال: مشكلة في الطابعة',
  'en' => 'e.g., Printer issue'
],
'description_label' => [
  'ar' => 'وصف المشكلة',
  'en' => 'Issue Description'
],
'description_placeholder' => [
  'ar' => 'اشرح المشكلة بشكل واضح...',
  'en' => 'Describe the issue clearly...'
],
'urgency_label' => [
  'ar' => 'درجة الأهمية',
  'en' => 'Urgency Level'
],
'choose_urgency' => [
  'ar' => 'اختر',
  'en' => 'Choose'
],
'urgency_low' => [
  'ar' => 'عادي',
  'en' => 'Low'
],
'urgency_medium' => [
  'ar' => 'متوسط',
  'en' => 'Medium'
],
'urgency_high' => [
  'ar' => 'عاجل',
  'en' => 'High'
],
'attach_image' => [
  'ar' => 'إرفاق صورة',
  'en' => 'Attach Image'
],
'submit_report' => [
  'ar' => 'إرسال البلاغ',
  'en' => 'Submit Report'
],
'report_success' => [
  'ar' => '✅ تم إرسال البلاغ بنجاح!',
  'en' => '✅ Report submitted successfully!'
],
'more_info_faq' => [
  'ar' => 'لمزيد من المعلومات اضغط',
  'en' => 'For more information click'
],
'here' => [
  'ar' => 'هنا',
  'en' => 'here'
],
'faq_note' => [
  'ar' => 'لمراجعة الأسئلة الشائعة',
  'en' => 'to review the FAQs'
],
'department_label' => [
  'ar' => 'القسم:',
  'en' => 'Department:'
],


'my_reports_intro' => [
  'ar' => 'هنا يمكنك متابعة البلاغات التي قمت بإرسالها ومعرفة حالتها بكل سهولة.',
  'en' => 'Here you can track the reports you’ve submitted and view their current status easily.'
],
];
?>