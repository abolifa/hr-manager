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
        h1 {
            font-size: 16px;
            font-weight: bold;
        }

        body {
            font-family: 'tajawal', sans-serif;
            margin: 0;
        }

        .date {
            text-align: left;
            font-size: 13px;
        }

        .issue {
            text-align: right;
            font-size: 13px;
        }

        .main {
            font-size: 15px;
            line-height: 1.55;
            text-align: justify;
            margin: 3mm 0;
        }


        .greeting {
            font-size: 15px;
            line-height: 1.55;
            text-align: center;
            font-weight: bold;
        }

        .signatureWrapper {
            float: left;
            width: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-top: 30px;
        }

        .signature {
            font-size: 15px;
            line-height: 1.55;
            font-weight: bold;
            text-align: center;
        }


        .greeting .signatureWrapper {
            page-break-before: avoid;
        }

    </style>
</head>
<body>
<table style="width: 100%; border-collapse: collapse;margin-bottom: 10mm">
    <tr>
        <td class="issue">
            الرقم الإشاري / {{ $issue_number }}
        </td>
        <td class="date">
            التاريخ / {{ Carbon::parse($issue_date)->format('d/m/Y') }}
        </td>
    </tr>
</table>

<h1>{{$receiver}}</h1>
<h1>{{$title}}</h1>

<p>بعد التحية،،،</p>

<div class="main">
    {!! $body !!}
</div>

<div class="greeting">شاكرين حسن تعاونكم معنا</div>
<div class="greeting">والسلام عليكم ورحمة الله وبركاته</div>


<div class="signatureWrapper">
    <div class="signature">مفوض الشركة</div>
    <div class="signature">{{ $ceo_name }}</div>
</div>
</body>
</html>
