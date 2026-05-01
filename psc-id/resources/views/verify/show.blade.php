<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Verification — PSC Ghana</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: "Montserrat", sans-serif;
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Header ── */
        .site-header {
            background: #003580;
            color: white;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .site-header img {
            height: 44px;
            width: auto;
        }

        .site-header-text { line-height: 1.25; }
        .site-header-text .org  { font-size: 11px; font-weight: 500; opacity: .85; }
        .site-header-text .title { font-size: 15px; font-weight: 700; letter-spacing: .4px; }

        /* ── Main ── */
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 32px 16px 48px;
            gap: 24px;
        }

        /* ── Status badge ── */
        .status-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            max-width: 480px;
            padding: 20px 24px;
            border-radius: 14px;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .status-badge.valid {
            background: #dcfce7;
            color: #15803d;
            border: 2px solid #86efac;
        }

        .status-badge.invalid {
            background: #fee2e2;
            color: #b91c1c;
            border: 2px solid #fca5a5;
        }

        .status-icon { font-size: 32px; line-height: 1; }

        /* ── Card ── */
        .result-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,.08);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
        }

        /* ── Photo band ── */
        .photo-band {
            background: linear-gradient(135deg, #003580 0%, #0057b8 100%);
            padding: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
        }

        .photo-wrap {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            border: 4px solid white;
            overflow: hidden;
            background: #e0eaf8;
            flex-shrink: 0;
        }

        .photo-wrap img { width: 100%; height: 100%; object-fit: cover; }

        .photo-placeholder {
            width: 100%; height: 100%;
            background: rgba(255,255,255,.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 42px; font-weight: 700; color: white;
        }

        .name-band {
            color: white;
            text-align: center;
            font-size: 18px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .5px;
            line-height: 1.25;
        }

        /* ── Details grid ── */
        .details { padding: 20px 24px; }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        .detail-row:last-child { border-bottom: none; }

        .detail-label { color: #64748b; font-weight: 500; min-width: 110px; }
        .detail-value { color: #0f172a; font-weight: 600; text-align: right; }

        .status-pill {
            display: inline-block;
            padding: 2px 12px;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 700;
        }
        .status-pill.active   { background: #dcfce7; color: #15803d; }
        .status-pill.inactive { background: #fee2e2; color: #b91c1c; }

        /* ── Reason box (for invalid) ── */
        .reason-box {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,.08);
            width: 100%;
            max-width: 480px;
            padding: 20px 24px;
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
        }

        .reason-box strong { color: #0f172a; }

        /* ── Timestamp ── */
        .scan-time {
            font-size: 12px;
            color: #94a3b8;
            text-align: center;
        }

        /* ── Footer ── */
        .site-footer {
            background: #1e293b;
            color: #94a3b8;
            text-align: center;
            font-size: 11px;
            padding: 14px 20px;
        }
    </style>
</head>
<body>

<header class="site-header">
    <img src="{{ asset('img/card/ghana.png') }}" alt="Ghana Coat of Arms">
    <div class="site-header-text">
        <div class="org">REPUBLIC OF GHANA</div>
        <div class="title">PUBLIC SERVICES COMMISSION</div>
    </div>
    <img src="{{ asset('img/card/psc.png') }}" alt="PSC Seal" style="margin-left:auto;">
</header>

<main>

    {{-- ── Status badge ── --}}
    @if($valid)
        <div class="status-badge valid">
            <span class="status-icon">✓</span>
            <span>VERIFIED &amp; VALID</span>
        </div>
    @else
        <div class="status-badge invalid">
            <span class="status-icon">✗</span>
            <span>INVALID</span>
        </div>
    @endif

    {{-- ── Staff card (only when valid or staff found) ── --}}
    @if($staff)
        <div class="result-card">
            <div class="photo-band">
                <div class="photo-wrap">
                    @if($photoDataUri)
                        <img src="{{ $photoDataUri }}" alt="Photo of {{ $staff->full_name }}">
                    @else
                        <div class="photo-placeholder">{{ strtoupper(substr($staff->full_name, 0, 1)) }}</div>
                    @endif
                </div>
                <div class="name-band">{{ $staff->full_name }}</div>
            </div>

            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">Staff ID</span>
                    <span class="detail-value">{{ $staff->staff_id }}</span>
                </div>
                @if($staff->id_no)
                <div class="detail-row">
                    <span class="detail-label">ID No</span>
                    <span class="detail-value">{{ $staff->id_no }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">Position</span>
                    <span class="detail-value">{{ $staff->position }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Department</span>
                    <span class="detail-value">{{ $staff->department }}</span>
                </div>
                @if($staff->job_grade)
                <div class="detail-row">
                    <span class="detail-label">Grade</span>
                    <span class="detail-value">{{ $staff->job_grade }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        <span class="status-pill {{ strtolower($staff->status) }}">{{ $staff->status }}</span>
                    </span>
                </div>
                @if($staff->card_expires)
                <div class="detail-row">
                    <span class="detail-label">Card Expires</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($staff->card_expires)->format('d M Y') }}</span>
                </div>
                @endif
            </div>
        </div>
    @endif

    {{-- ── Reason (when not valid) ── --}}
    @if(!$valid)
        <div class="reason-box">
            <strong>Reason:</strong> {{ $reason ?: 'This ID card could not be verified.' }}
            <p style="margin-top:10px;">
                If you believe this is an error, please contact the Public Services Commission directly.
            </p>
        </div>
    @endif

    <p class="scan-time">Scanned at {{ now()->format('d M Y, H:i:s T') }}</p>

</main>

<footer class="site-footer">
    &copy; {{ date('Y') }} Public Services Commission, Ghana &mdash; Official ID Verification System
</footer>

</body>
</html>
