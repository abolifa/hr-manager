@php
    use Carbon\Carbon;
    $issue_number = $issue_number ?? '__________________';
    $issue_date   = $issue_date ?? now();
    $receiver     = $receiver ?? '__________________';
    $title        = $title ?? '__________________';
    $body         = $body ?? '<p>نص الرسالة</p>';
    $ceo_name     = $ceo_name ?? '';
    $letterhead = $letterhead_data_url ?? '';
@endphp


    <!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>طباعة مستند - {{ $issue_number ?? '' }}</title>
    <style>
      
        html, body {
            background-color: #fff;
            color: #0a0a0a;
            font-family: 'Arial', sans-serif;
            direction: rtl;
            unicode-bidi: embed;
        }

        body {
            font-size: 13px;
        }


        .content {
        }

        .issue, .heading, .greeting, .closing, .signature {
            page-break-inside: avoid;
        }

        p {
            widows: 2;
            orphans: 2;
        }

        .issue {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            font-weight: 600;
        }


        .heading h3 {
            font-size: 16px;
            font-weight: 700;
        }

        .greeting {
            font-size: 15px;
        }

        .main {
            font-size: 15px;
            line-height: 1.55;
            text-align: justify;
        }

        .closing {
            font-size: 15px;
            font-weight: 700;
            text-align: center;
        }

        .signature {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .names {
            font-size: 15px;
            font-weight: 700;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="content">
    <div class="issue">
        <p>الرقم الإشاري / {{ $issue_number }}</p>
        <p>التاريخ / {{ Carbon::parse($issue_date)->format('d/m/Y') }}</p>
    </div>

    <div class="heading">
        <h3>السادة / {{ $receiver }}</h3>
        <h3>الموضوع / {{ $title }}</h3>
    </div>

    <div class="greeting"><p>بعد التحية،،،</p></div>

    <div class="main">{!! $body !!}</div>

    <div class="closing">
        <p>شاكرين حسن تعاونكم معنا</p>
        <p>والسلام عليكم ورحمة الله وبركاته</p>
    </div>

    <div class="signature">
        <div class="names">
            <h3>مفوض الشركة</h3>
            <p>{{ $ceo_name }}</p>
        </div>
    </div>
</div>
</body>
</html>
