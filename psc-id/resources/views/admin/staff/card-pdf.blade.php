<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: "Montserrat", "DejaVu Sans", sans-serif;
        background: #ffffff;
    }

    /* ── Each card side ── */
    .card-side {
        width: 242pt;
        height: 382pt;
        position: relative;
        overflow: hidden;
        page-break-after: always;
    }

    .card-side:last-child {
        page-break-after: auto;
    }

    .bg {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
    }

    .content {
        position: relative;
        z-index: 2;
        height: 100%;
        padding: 22pt;
        display: block;
        text-align: center;
    }

    /* ── FRONT ── */
    .header {
        margin-top: 20pt;
        margin-bottom: 14pt;
        display: block;
        text-align: center;
    }

    .header-inner {
        display: inline-block;
        vertical-align: middle;
    }

    .logo-img {
        height: 25pt;
        vertical-align: middle;
    }

    .psc-img {
        height: 25pt;
        vertical-align: middle;
    }

    .header-text {
        display: inline-block;
        vertical-align: middle;
        font-size: 10pt;
        font-weight: 700;
        color: #1a1a1a;
        line-height: 1.3;
        margin: 0 6pt;
        letter-spacing: 0.4pt;
    }

    .expanded {
        letter-spacing: 3pt;
        font-weight: 900;
    }

    .photo-wrap {
        width: 90pt;
        height: 90pt;
        border-radius: 50%;
        border: 3.5pt solid #0066cc;
        overflow: hidden;
        margin: 0 auto 12pt;
        background: #e8e8e8;
    }

    .photo-wrap img {
        width: 100%;
        height: 100%;
    }

    .photo-placeholder {
        width: 90pt;
        height: 90pt;
        border-radius: 50%;
        border: 3.5pt solid #0066cc;
        background: #6a76d4;
        margin: 0 auto 12pt;
        font-size: 36pt;
        font-weight: 700;
        color: white;
        line-height: 90pt;
        text-align: center;
    }

    .name {
        font-size: 13pt;
        font-weight: 900;
        color: #1a1a1a;
        text-transform: uppercase;
        text-align: center;
        margin-bottom: 14pt;
        line-height: 1.25;
    }

    table.details {
        width: 68%;
        margin: 0 auto;
        border-collapse: collapse;
        font-size: 8pt;
        text-align: left;
    }

    table.details td { padding: 2.5pt 2pt; }
    .dl { font-weight: 600; color: #555; width: 50pt; }
    .dv { font-weight: 500; color: #1a1a1a; }

    /* ── BACK ── */
    .back-title {
        font-size: 10pt;
        font-weight: 700;
        color: #1a1a1a;
        margin-top: 18pt;
        margin-bottom: 12pt;
    }

    .terms {
        font-size: 8pt;
        color: #333;
        line-height: 1.7;
        margin-bottom: 10pt;
    }

    .gps {
        font-size: 9pt;
        font-weight: 600;
        color: #0066cc;
        margin-bottom: 12pt;
    }

    .cardholder {
        font-size: 10pt;
        font-weight: 700;
        color: #1a1a1a;
        border-bottom: 1.5pt solid #1a1a1a;
        padding-bottom: 6pt;
        margin-bottom: 12pt;
    }

    .other-contacts {
        font-size: 8pt;
        color: #333;
        margin-bottom: 10pt;
    }

    .qr-img {
        width: 80pt;
        height: 80pt;
        border: 2pt solid #1a1a1a;
        margin: 0 auto 12pt;
        display: block;
    }

    .footer {
        margin-top: 6pt;
    }

    .footer-logo-img { height: 18pt; vertical-align: middle; }
    .footer-psc-img  { height: 18pt; vertical-align: middle; }

    .footer-text {
        display: inline-block;
        vertical-align: middle;
        font-size: 8pt;
        font-weight: 700;
        color: #1a1a1a;
        line-height: 1.3;
        margin: 0 5pt;
    }

    .footer-expanded { letter-spacing: 2pt; font-weight: 900; }
</style>
</head>
<body>

{{-- ══════════════════════════ FRONT ══════════════════════════ --}}
<div class="card-side">
    <img class="bg" src="{{ $cardBgDataUri }}" alt="">
    <div class="content">

        <div class="header">
            <img class="logo-img" src="{{ $ghanaDataUri }}" alt="Ghana Coat of Arms">
            <span class="header-text">PUBLIC SERVICES<br><span class="expanded">COMMISSION</span></span>
            <img class="psc-img" src="{{ $pscDataUri }}" alt="PSC">
        </div>

        @if($photoDataUri)
            <div class="photo-wrap"><img src="{{ $photoDataUri }}" alt="Photo"></div>
        @else
            <div class="photo-placeholder">{{ strtoupper(substr($staff->full_name, 0, 1)) }}</div>
        @endif

        <div class="name">{{ $staff->full_name }}</div>

        <table class="details">
            <tr>
                <td class="dl">Staff ID</td>
                <td class="dv">: {{ $staff->staff_id }}</td>
            </tr>
            <tr>
                <td class="dl">ID No</td>
                <td class="dv">: {{ $staff->id_no ?? '—' }}</td>
            </tr>
            <tr>
                <td class="dl">Sex</td>
                <td class="dv">: {{ $staff->sex ?? '—' }}</td>
            </tr>
            <tr>
                <td class="dl">Position</td>
                <td class="dv">: {{ $staff->position }}</td>
            </tr>
            <tr>
                <td class="dl">Department</td>
                <td class="dv">: {{ $staff->department }}</td>
            </tr>
        </table>

    </div>
</div>

{{-- ══════════════════════════ BACK ══════════════════════════ --}}
<div class="card-side">
    <img class="bg" src="{{ $cardBgDataUri }}" alt="">
    <div class="content">

        <div class="back-title">TERMS AND CONDITIONS</div>

        <div class="terms">
            This is a property of the Office<br>
            of the <strong>Public Services Commission</strong>
        </div>

        <div class="terms">
            If found, please return it to<br>
            The Secretary<br>
            Office of the<br>
            <strong>PUBLIC SERVICES COMMISSION</strong>
        </div>

        <div class="gps">GPS Address: GA 144 4112</div>

        <div class="cardholder">{{ $staff->full_name }}</div>

        @if($staff->other_contacts)
            <div class="other-contacts">{{ $staff->other_contacts }}</div>
        @endif

        <img class="qr-img" src="{{ $qrDataUri }}" alt="QR Code">

        <div class="footer">
            <img class="footer-logo-img" src="{{ $ghanaDataUri }}" alt="Ghana Coat of Arms">
            <span class="footer-text">PUBLIC SERVICES<br><span class="footer-expanded">COMMISSION</span></span>
            <img class="footer-psc-img" src="{{ $pscDataUri }}" alt="PSC">
        </div>

    </div>
</div>

</body>
</html>
