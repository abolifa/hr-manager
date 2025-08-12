@php use Carbon\Carbon; @endphp

<style>
    :root {
        --page-w: 210mm;
        --page-h: 297mm;
        --scale: .55;
    }

    .preview-outer {
        display: flex;
        justify-content: center;
        padding: 10px;
        background: #ddd
    }

    .preview {
        position: relative;
        width: calc(var(--page-w) * var(--scale));
        height: calc(var(--page-h) * var(--scale));
        overflow: hidden
    }

    .page {
        position: absolute;
        inset: 0;
        width: var(--page-w);
        height: var(--page-h);
        transform-origin: top right;
        transform: scale(var(--scale));
        box-shadow: 0 0 5px rgba(0, 0, 0, .1);
        background: #fff;
        color: #0a0a0a;
        box-sizing: border-box;
    }

    .page::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image: var(--letterhead, none);
        background-repeat: no-repeat;
        background-position: top left;
        background-size: 100% 100%;
        pointer-events: none;
        z-index: 0;
    }

    .page[data-bg-mode="header"]::before {
        background-position: top center;
        background-size: 100% auto;
    }

    .content {
        position: relative;
        z-index: 1; /* above the letterhead */
        height: 100%;
        box-sizing: border-box;
        padding: 60mm 20mm 20mm 20mm;
        display: flex;
        flex-direction: column;
        gap: 5mm;
    }

    .issue {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        font-weight: 600
    }

    .heading {
        display: flex;
        flex-direction: column;
        gap: 5px;
        font-weight: 700;
        font-size: 16px;
        margin-top: 30px
    }

    .greeting {
        font-size: 15px;
        margin-top: 10px
    }

    .main {
        font-size: 15px;
        line-height: 1.5;
        margin-top: 10px;
        text-align: justify
    }

    .closing {
        font-size: 15px;
        font-weight: 700;
        margin-top: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px
    }

    .signature {
        margin-top: 30px;
        display: flex;
        flex-direction: column;
        align-items: end
    }

    .signature .names {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        font-size: 15px;
        font-weight: 700
    }

    @page {
        size: A4;
        margin: 0;
    }

    @media print {
        .preview-outer {
            padding: 0;
            background: #fff
        }

        .preview {
            width: auto;
            height: auto;
            overflow: visible
        }

        .page {
            position: static;
            transform: none !important;
            width: var(--page-w);
            height: var(--page-h);
            box-shadow: none;
        }

        .page::before {
            background-size: 100% 100%;
        }

        .content {
            padding: 40mm 20mm 20mm 20mm;
        }
    }
</style>

@php $letterheadUrl = $letterhead ? asset('storage/'.$letterhead) : null; @endphp

<div class="preview-outer">
    <div class="preview">
        <div class="page"
             style="--letterhead: url('{{ $letterheadUrl }}')"
             data-bg-mode="full">
            <div class="content">
                <div class="issue">
                    <p>الرقم الإشاري / {{ $issue_number ?? '__________________' }}</p>
                    <p>التاريخ / {{ ($issue_date ?? now()) }}</p>
                </div>

                <div class="heading">
                    <h3>السادة / {{ $receiver ?? '__________________' }}</h3>
                    <h3>الموضوع / {{ $title ?? '__________________' }}</h3>
                </div>

                <div class="greeting"><p>بعد التحية،،،</p></div>

                <div class="main">{!! $body ?? '<p>نص الرسالة</p>' !!}</div>

                <div class="closing">
                    <p>شاكرين حسن تعاونكم معنا</p>
                    <p>والسلام عليكم ورحمة الله وبركاته</p>
                </div>

                <div class="signature">
                    <div class="names">
                        <h3>مفوض الشركة</h3>
                        <p>{{ $ceo_name ?? '__________________' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
