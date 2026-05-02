<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    @font-face {
        font-family: 'Montserrat';
        font-weight: 400;
        font-style: normal;
        src: url('{{ $fontDir }}/Montserrat-Regular.ttf') format('truetype');
    }
    @font-face {
        font-family: 'Montserrat';
        font-weight: 500;
        font-style: normal;
        src: url('{{ $fontDir }}/Montserrat-Medium.ttf') format('truetype');
    }
    @font-face {
        font-family: 'Montserrat';
        font-weight: 600;
        font-style: normal;
        src: url('{{ $fontDir }}/Montserrat-SemiBold.ttf') format('truetype');
    }
    @font-face {
        font-family: 'Montserrat';
        font-weight: 700;
        font-style: normal;
        src: url('{{ $fontDir }}/Montserrat-Bold.ttf') format('truetype');
    }
    @font-face {
        font-family: 'Montserrat';
        font-weight: 800;
        font-style: normal;
        src: url('{{ $fontDir }}/Montserrat-ExtraBold.ttf') format('truetype');
    }
    @font-face {
        font-family: 'Montserrat';
        font-weight: 900;
        font-style: normal;
        src: url('{{ $fontDir }}/Montserrat-Black.ttf') format('truetype');
    }

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
        font-family: 'Montserrat', 'DejaVu Sans', sans-serif;
        color: #1a1a1a;
        font-weight: 500;
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
        padding: 18pt 16pt;
        text-align: center;
    }

    /* ───── HEADER ───── */
    .header-tbl {
        margin: 8pt auto 12pt;
        border-collapse: collapse;
    }

    .header-tbl td {
        vertical-align: middle;
        padding: 0 4pt;
    }

    .logo-cell img { width: 26pt; height: 26pt; display: block; }

    .header-text {
        font-size: 9pt;
        font-weight: 700;
        line-height: 1.15;
        letter-spacing: 0.3pt;
        color: #1a1a1a;
        text-align: center;
        white-space: nowrap;
    }

    .header-text .exp {
        letter-spacing: 2.6pt;
        font-weight: 900;
    }

    /* ───── FRONT photo ───── */
    .photo-wrap {
        width: 96pt;
        height: 96pt;
        border-radius: 48pt;
        border: 4pt solid #0066cc;
        overflow: hidden;
        margin: 0 auto 10pt;
        background: #f0f0f0;
    }

    .photo-wrap img { width: 96pt; height: 96pt; }

    .photo-placeholder {
        width: 96pt;
        height: 96pt;
        border-radius: 48pt;
        border: 4pt solid #0066cc;
        background: #6a76d4;
        margin: 0 auto 10pt;
        font-size: 38pt;
        font-weight: 700;
        color: white;
        line-height: 88pt;
        text-align: center;
    }

    .name {
        font-size: 13pt;
        font-weight: 900;
        text-transform: uppercase;
        margin-bottom: 12pt;
        line-height: 1.2;
        letter-spacing: 0.2pt;
    }

    table.details {
        margin: 0 auto;
        border-collapse: collapse;
        font-size: 8.5pt;
        text-align: left;
    }

    table.details td { padding: 2pt 0; }
    .dl { font-weight: 600; color: #555; padding-right: 6pt !important; }
    .dv { font-weight: 500; color: #1a1a1a; }

    /* ───── BACK ───── */
    .back-title {
        font-size: 11pt;
        font-weight: 700;
        margin-top: 10pt;
        margin-bottom: 12pt;
    }

    .terms {
        font-size: 8.5pt;
        color: #333;
        line-height: 1.55;
        margin-bottom: 10pt;
        font-weight: 500;
    }

    .gps {
        font-size: 9pt;
        font-weight: 600;
        color: #0066cc;
        margin-bottom: 12pt;
    }

    .cardholder {
        font-size: 11pt;
        font-weight: 700;
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
        width: 86pt;
        height: 86pt;
        margin: 4pt auto 10pt;
        display: block;
        border: 2pt solid #1a1a1a;
    }

    .footer-tbl {
        margin: 4pt auto 0;
        border-collapse: collapse;
    }

    .footer-tbl td {
        vertical-align: middle;
        padding: 0 3pt;
    }

    .footer-logo-cell img { width: 20pt; height: 20pt; display: block; }

    .footer-text {
        font-size: 7.5pt;
        font-weight: 700;
        line-height: 1.15;
        text-align: center;
        white-space: nowrap;
    }

    .footer-text .exp {
        letter-spacing: 1.8pt;
        font-weight: 900;
    }
</style>
</head>
<body>

{{-- ══════════════════ FRONT ══════════════════ --}}
<div class="card-side">
    <img class="bg" src="{{ $cardBgDataUri }}" alt="">
    <div class="content">

        <table class="header-tbl">
            <tr>
                <td class="logo-cell"><img src="{{ $ghanaDataUri }}" alt=""></td>
                <td><div class="header-text">PUBLIC SERVICES<br><span class="exp">COMMISSION</span></div></td>
                <td class="logo-cell"><img src="{{ $pscDataUri }}" alt=""></td>
            </tr>
        </table>

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

        <table class="footer-tbl">
            <tr>
                <td class="footer-logo-cell"><img src="{{ $ghanaDataUri }}" alt=""></td>
                <td><div class="footer-text">PUBLIC SERVICES<br><span class="exp">COMMISSION</span></div></td>
                <td class="footer-logo-cell"><img src="{{ $pscDataUri }}" alt=""></td>
            </tr>
        </table>

    </div>
</div>

</body>
</html>
