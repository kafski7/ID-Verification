<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    @page {
        size: 240pt 384pt;
        margin: 0;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    html, body {
        width: 240pt;
        height: 384pt;
    }

    body {
        font-family: "DejaVu Sans", sans-serif;
        color: #1a1a1a;
    }

    .card-side {
        position: relative;
        width: 240pt;
        height: 384pt;
        overflow: hidden;
        page-break-after: always;
    }

    .card-side.last { page-break-after: auto; }

    .bg {
        position: absolute;
        top: 0; left: 0;
        width: 240pt;
        height: 384pt;
    }

    .content {
        position: absolute;
        top: 0; left: 0;
        width: 240pt;
        height: 384pt;
        padding: 18pt 20pt;
        text-align: center;
    }

    /* ───── HEADER (front + footer back) ───── */
    .header {
        text-align: center;
        margin-top: 8pt;
        margin-bottom: 14pt;
        white-space: nowrap;
    }

    .header img {
        height: 26pt;
        width: 26pt;
        vertical-align: middle;
    }

    .header .ht {
        display: inline-block;
        vertical-align: middle;
        font-size: 9pt;
        font-weight: bold;
        line-height: 1.15;
        margin: 0 4pt;
        text-align: center;
    }

    .ht .exp {
        letter-spacing: 2.4pt;
        font-weight: bold;
        font-size: 9pt;
    }

    /* ───── FRONT photo ───── */
    .photo-wrap {
        width: 100pt;
        height: 100pt;
        border-radius: 50pt;
        border: 4pt solid #0066cc;
        overflow: hidden;
        margin: 0 auto 10pt;
        background: #f0f0f0;
    }

    .photo-wrap img {
        width: 100pt;
        height: 100pt;
    }

    .photo-placeholder {
        width: 100pt;
        height: 100pt;
        border-radius: 50pt;
        border: 4pt solid #0066cc;
        background: #6a76d4;
        margin: 0 auto 10pt;
        font-size: 40pt;
        font-weight: bold;
        color: white;
        line-height: 92pt;
        text-align: center;
    }

    .name {
        font-size: 13pt;
        font-weight: bold;
        text-transform: uppercase;
        margin-bottom: 12pt;
        line-height: 1.2;
    }

    table.details {
        width: 75%;
        margin: 0 auto;
        border-collapse: collapse;
        font-size: 9pt;
        text-align: left;
    }

    table.details td { padding: 2pt 0; }
    .dl { font-weight: bold; color: #555; width: 60pt; }
    .dv { color: #1a1a1a; }

    /* ───── BACK ───── */
    .back-title {
        font-size: 11pt;
        font-weight: bold;
        margin-top: 4pt;
        margin-bottom: 10pt;
    }

    .terms {
        font-size: 9pt;
        color: #333;
        line-height: 1.5;
        margin-bottom: 10pt;
    }

    .gps {
        font-size: 10pt;
        font-weight: bold;
        color: #0066cc;
        margin-bottom: 10pt;
    }

    .cardholder {
        font-size: 11pt;
        font-weight: bold;
        border-bottom: 1.5pt solid #1a1a1a;
        padding-bottom: 5pt;
        margin: 0 18pt 8pt;
    }

    .other-contacts {
        font-size: 8pt;
        color: #555;
        margin-bottom: 6pt;
    }

    .qr-img {
        width: 90pt;
        height: 90pt;
        margin: 4pt auto 8pt;
        display: block;
        border: 2pt solid #1a1a1a;
    }

    .footer {
        text-align: center;
        margin-top: 4pt;
        white-space: nowrap;
    }

    .footer img {
        height: 20pt;
        width: 20pt;
        vertical-align: middle;
    }

    .footer .ft {
        display: inline-block;
        vertical-align: middle;
        font-size: 8pt;
        font-weight: bold;
        line-height: 1.15;
        margin: 0 3pt;
        text-align: center;
    }

    .ft .exp {
        letter-spacing: 1.8pt;
        font-weight: bold;
    }
</style>
</head>
<body>

{{-- ══════════════════ FRONT ══════════════════ --}}
<div class="card-side">
    <img class="bg" src="{{ $cardBgDataUri }}" alt="">
    <div class="content">

        <div class="header">
            <img src="{{ $ghanaDataUri }}" alt="">
            <span class="ht">PUBLIC SERVICES<br><span class="exp">COMMISSION</span></span>
            <img src="{{ $pscDataUri }}" alt="">
        </div>

        @if($photoDataUri)
            <div class="photo-wrap"><img src="{{ $photoDataUri }}" alt=""></div>
        @else
            <div class="photo-placeholder">{{ strtoupper(substr($staff->full_name, 0, 1)) }}</div>
        @endif

        <div class="name">{{ $staff->full_name }}</div>

        <table class="details">
            <tr><td class="dl">Staff ID</td><td class="dv">: {{ $staff->staff_id }}</td></tr>
            <tr><td class="dl">ID No</td><td class="dv">: {{ $staff->id_no ?? '—' }}</td></tr>
            <tr><td class="dl">Sex</td><td class="dv">: {{ $staff->sex ?? '—' }}</td></tr>
            <tr><td class="dl">Position</td><td class="dv">: {{ $staff->position }}</td></tr>
            <tr><td class="dl">Department</td><td class="dv">: {{ $staff->department }}</td></tr>
        </table>

    </div>
</div>

{{-- ══════════════════ BACK ══════════════════ --}}
<div class="card-side last">
    <img class="bg" src="{{ $cardBgDataUri }}" alt="">
    <div class="content">

        <div class="back-title">TERMS AND CONDITIONS</div>

        <div class="terms">
            This is a property of the Office<br>
            of the <strong>Public Services Commission</strong>
        </div>

        <div class="terms">
            If found, please return it to<br>
            The Secretary, Office of the<br>
            <strong>PUBLIC SERVICES COMMISSION</strong>
        </div>

        <div class="gps">GPS Address: GA 144 4112</div>

        <div class="cardholder">{{ $staff->full_name }}</div>

        @if($staff->other_contacts)
            <div class="other-contacts">{{ $staff->other_contacts }}</div>
        @endif

        <img class="qr-img" src="{{ $qrDataUri }}" alt="QR">

        <div class="footer">
            <img src="{{ $ghanaDataUri }}" alt="">
            <span class="ft">PUBLIC SERVICES<br><span class="exp">COMMISSION</span></span>
            <img src="{{ $pscDataUri }}" alt="">
        </div>

    </div>
</div>

</body>
</html>
