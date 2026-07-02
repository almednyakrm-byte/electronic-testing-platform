# منصة اختبارات إلكترونية مع تصحيح تلقائي
==========================

### Overview & Project Purpose

منصة اختبارات إلكترونية مع تصحيح تلقائي هي منصة تعليمية إلكترونية تهدف إلى تقديم اختبارات إلكترونية مع تصحيح تلقائي، مما يسهل عملية التقييم والتصحيح للمعلمين والطلاب. يمكن للمعلمين إنشاء اختبارات متقدمة مع تصحيح تلقائي، بينما يمكن للطلاب إجراء الاختبارات وتحليل النتائج بسهولة.

### Project Structure Mapping


.
├── docker-compose.yml
├── .env
├── app
│   ├── __init__.py
│   ├── models
│   │   ├── __init__.py
│   │   ├── question.py
│   │   ├── user.py
│   │   └── exam.py
│   ├── routes
│   │   ├── __init__.py
│   │   ├── exam_routes.py
│   │   └── user_routes.py
│   ├── schemas
│   │   ├── __init__.py
│   │   ├── exam_schema.py
│   │   └── user_schema.py
│   ├── services
│   │   ├── __init__.py
│   │   ├── exam_service.py
│   │   └── user_service.py
│   ├── utils
│   │   ├── __init__.py
│   │   └── constants.py
│   └── main.py
├── requirements.txt
└── tests
    ├── __init__.py
    ├── test_exam.py
    └── test_user.py


### Step-by-Step Instructions for Running the Environment using Docker-compose up

1. **Install Docker and Docker-compose**: تأكد من أن لديك Docker و Docker-compose مثبتين على جهازك.
2. **تحميل المشروع**: استخدم الأمر `git clone` لتحميل المشروع من GitHub.
3. **تكوين البيئة**: افتح الملف `.env` واضبط القيم اللازمة لتشغيل المشروع.
4. **تشغيل المشروع**: استخدم الأمر `docker-compose up` لتشغيل المشروع.
5. **تأكيد تشغيل المشروع**: افتح متصفحك واكتب `http://localhost:5000` لتحديد إذا كان المشروع يعمل بشكل صحيح.

### Modules, Tables, and Roles

#### Modules

*   `exam`: يحتوي على جميع العمليات المتعلقة بالاختبارات، مثل إنشاء اختبارات وتصحيحها.
*   `user`: يحتوي على جميع العمليات المتعلقة بالطلاب، مثل إنشاء حسابات وتصفح الاختبارات.

#### Tables

*   `exams`: يحتوي على جميع الاختبارات الموجودة في النظام.
*   `questions`: يحتوي على جميع الأسئلة الموجودة في النظام.
*   `users`: يحتوي على جميع الحسابات الموجودة في النظام.

#### Roles

*   `admin`: يمتلك جميع الصلاحيات في النظام، يمكنه إنشاء وتصحيح الاختبارات.
*   `student`: يمتلك صلاحية تصفح الاختبارات وتصحيحها.

### Contact Developer Details

*   **اسم المطور**: [اسمك]
*   **بريد الإلكتروني**: [بريدك الإلكتروني]
*   **رابط GitHub**: [رابط GitHub الخاص بك]

تحياتي.

---

## 📧 للتواصل (Contact)
almednyakrm@gmail.com
