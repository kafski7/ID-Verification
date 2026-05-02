<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card — {{ $staff->full_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: "Montserrat", sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            gap: 24px;
        }

        /* ── Card shell ── */
        .card-container {
            perspective: 1000px;
            width: 340px;
            height: 540px;
        }

        .card {
            width: 100%;
            height: 100%;
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.8s cubic-bezier(0.4, 0.2, 0.2, 1);
            cursor: pointer;
        }

        .card.flipped { transform: rotateY(180deg); }

        .card-face {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .card-back { transform: rotateY(180deg); }

        /* ── Shared card content ── */
        .card-content {
            position: relative;
            height: 100%;
            background-image: url('{{ asset('img/card/card-bg.png') }}');
            background-size: cover;
            background-position: center;
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* ── Header ── */
        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 35px;
            margin-bottom: 24px;
            text-align: center;
        }

        .logo { height: 35px; display: flex; align-items: center; }
        .logo img { height: 100%; width: auto; }

        .header-text {
            font-size: 15px;
            font-weight: 700;
            color: #1a1a1a;
            letter-spacing: 0.5px;
            line-height: 1.25;
        }

        .badge { height: 35px; display: flex; align-items: center; }
        .badge img { height: 100%; width: auto; }

        .expanded-text { letter-spacing: 4.6px; font-weight: 900; }

        /* ── Photo ── */
        .photo-container {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 5px solid #0066cc;
            overflow: hidden;
            margin-bottom: 20px;
            background: #f0f0f0;
            flex-shrink: 0;
        }

        .photo-container img { width: 100%; height: 100%; object-fit: cover; }

        .photo-placeholder {
            width: 100%; height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 48px; font-weight: 700;
        }

        /* ── Name ── */
        .name {
            font-size: 20px;
            font-weight: 900;
            color: #1a1a1a;
            text-align: center;
            margin-bottom: 20px;
            line-height: 1.2;
            text-transform: uppercase;
        }

        /* ── Details ── */
        .details { width: 65%; text-align: left; }

        .detail-row {
            display: flex;
            gap: 8px;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .detail-label { font-weight: 600; color: #555; min-width: 65px; }
        .detail-value { color: #1a1a1a; font-weight: 500; }

        /* ── Back side ── */
        .back-content { padding: 30px; text-align: center; }

        .back-title {
            font-size: 14px; font-weight: 700; color: #1a1a1a;
            margin-bottom: 16px; margin-top: 20px;
        }

        .terms-text { font-size: 12px; color: #333; line-height: 1.6; margin-bottom: 12px; }
        .office-details { font-weight: 600; margin-bottom: 16px; }
        .gps-address { font-size: 13px; font-weight: 600; color: #0066cc; margin-bottom: 16px; }

        .cardholder-name {
            font-size: 14px; font-weight: 700; color: #1a1a1a;
            margin-bottom: 20px; padding-bottom: 8px;
            border-bottom: 2px solid #1a1a1a;
        }

        .qr-code { margin: 0 auto 20px; }
        .qr-code img { width: 120px; height: 120px; display: block; border: 3px solid #1a1a1a; }

        .footer-logo {
            display: flex; align-items: center; justify-content: center;
            gap: 8px; margin-top: auto; margin-bottom: 6px;
        }
        .footer-logo .logo,
        .footer-logo .badge { height: 22px; }
        .footer-text {
            font-size: 10px; font-weight: 700; color: #1a1a1a; line-height: 1.2;
        }
        .footer-text .expanded-text { letter-spacing: 3px; }

        /* ── Page chrome ── */
        .controls {
            display: flex; gap: 12px; flex-wrap: wrap; justify-content: center;
        }

        .btn {
            display: inline-flex; align-items: center; gap-8px: 8px;
            padding: 10px 22px; border-radius: 8px; font-family: inherit;
            font-size: 14px; font-weight: 600; cursor: pointer;
            border: none; text-decoration: none; transition: opacity .15s;
        }
        .btn:hover { opacity: .85; }
        .btn-white  { background: white; color: #1a1a1a; }
        .btn-pdf    { background: #ef4444; color: white; }
        .btn-back   { background: rgba(255,255,255,.2); color: white; border: 1px solid rgba(255,255,255,.4); }
        .btn-img    { background: #10b981; color: white; }

        .flip-hint { color: rgba(255,255,255,.8); font-size: 13px; }

        /* ── Off-screen flat card faces for image capture ── */
        .capture-face {
            position: fixed;
            left: -9999px;
            top: 0;
            width: 340px;
            height: 540px;
            border-radius: 20px;
            overflow: hidden;
        }

        .capture-face .card-content {
            border-radius: 0;
        }
    </style>
</head>
<body>

    <div class="controls">
        <a href="{{ route('admin.staff.show', $staff) }}" class="btn btn-back">&larr; Back to Profile</a>
        <button onclick="document.getElementById('card').classList.toggle('flipped')" class="btn btn-white">Flip Card</button>
        <button onclick="downloadCardPDF()" class="btn btn-pdf" id="btn-pdf">⬇ Download PDF</button>
        <button onclick="downloadCardImage('front')" class="btn btn-img">⬇ Front Image</button>
        <button onclick="downloadCardImage('back')" class="btn btn-img">⬇ Back Image</button>
    </div>

    <div class="card-container">
        <div class="card" id="card" onclick="this.classList.toggle('flipped')">

            {{-- ══ FRONT FACE ══ --}}
            <div class="card-face card-front">
                <div class="card-content">
                    <div class="header">
                        <div class="logo"><img src="{{ asset('img/card/ghana.png') }}" alt="Ghana Coat of Arms"></div>
                        <div class="header-text">
                            PUBLIC SERVICES<br>
                            <span class="expanded-text">COMMISSION</span>
                        </div>
                        <div class="badge"><img src="{{ asset('img/card/psc.png') }}" alt="PSC logo"></div>
                    </div>

                    <div class="photo-container">
                        @if($photoDataUri)
                            <img src="{{ $photoDataUri }}" alt="Photo of {{ $staff->full_name }}">
                        @else
                            <div class="photo-placeholder">{{ strtoupper(substr($staff->full_name, 0, 1)) }}</div>
                        @endif
                    </div>

                    <div class="name">{{ $staff->full_name }}</div>

                    <div class="details">
                        <div class="detail-row">
                            <span class="detail-label">Staff ID</span>
                            <span class="detail-value">: {{ $staff->staff_id }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">ID No</span>
                            <span class="detail-value">: {{ $staff->id_no ?? '—' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Sex</span>
                            <span class="detail-value">: {{ $staff->sex ?? '—' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Position</span>
                            <span class="detail-value">: {{ $staff->position }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Dept</span>
                            <span class="detail-value">: {{ $staff->department }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ BACK FACE ══ --}}
            <div class="card-face card-back">
                <div class="card-content back-content">
                    <div class="back-title">TERMS AND CONDITIONS</div>

                    <div class="terms-text">
                        This is a property of the Office<br>
                        of the <strong>Public Services Commission</strong>
                    </div>

                    <div class="terms-text office-details">
                        If found, please return it to<br>
                        The Secretary<br>
                        Office of the<br>
                        <strong>PUBLIC SERVICES COMMISSION</strong>
                    </div>

                    <div class="gps-address">GPS Address: GA 144 4112</div>

                    <div class="cardholder-name">{{ $staff->full_name }}</div>

                    @if($staff->other_contacts)
                        <div class="terms-text" style="margin-bottom:12px;font-size:11px;">
                            {{ $staff->other_contacts }}
                        </div>
                    @endif

                    <div class="qr-code">
                        <img src="{{ $qrDataUri }}" alt="QR Code">
                    </div>

                    <div class="footer-logo">
                        <div class="logo"><img src="{{ asset('img/card/ghana.png') }}" alt="Ghana Coat of Arms"></div>
                        <div class="footer-text">
                            PUBLIC SERVICES<br>
                            <span class="expanded-text">COMMISSION</span>
                        </div>
                        <div class="badge"><img src="{{ asset('img/card/psc.png') }}" alt="PSC logo"></div>
                    </div>
                </div>
            </div>

        </div>{{-- .card --}}
    </div>

    <p class="flip-hint">Click the card or use the button above to flip</p>

    {{-- ── Off-screen flat card faces used by html2canvas ── --}}

    {{-- FRONT capture target --}}
    <div id="capture-front" class="capture-face" aria-hidden="true">
        <div class="card-content">
            <div class="header">
                <div class="logo"><img src="{{ asset('img/card/ghana.png') }}" alt=""></div>
                <div class="header-text">PUBLIC SERVICES<br><span class="expanded-text">COMMISSION</span></div>
                <div class="badge"><img src="{{ asset('img/card/psc.png') }}" alt=""></div>
            </div>
            <div class="photo-container">
                @if($photoDataUri)
                    <img src="{{ $photoDataUri }}" alt="">
                @else
                    <div class="photo-placeholder">{{ strtoupper(substr($staff->full_name, 0, 1)) }}</div>
                @endif
            </div>
            <div class="name">{{ $staff->full_name }}</div>
            <div class="details">
                <div class="detail-row"><span class="detail-label">Staff ID</span><span class="detail-value">: {{ $staff->staff_id }}</span></div>
                <div class="detail-row"><span class="detail-label">ID No</span><span class="detail-value">: {{ $staff->id_no ?? '—' }}</span></div>
                <div class="detail-row"><span class="detail-label">Sex</span><span class="detail-value">: {{ $staff->sex ?? '—' }}</span></div>
                <div class="detail-row"><span class="detail-label">Position</span><span class="detail-value">: {{ $staff->position }}</span></div>
                <div class="detail-row"><span class="detail-label">Dept</span><span class="detail-value">: {{ $staff->department }}</span></div>
            </div>
        </div>
    </div>

    {{-- BACK capture target --}}
    <div id="capture-back" class="capture-face" aria-hidden="true">
        <div class="card-content back-content">
            <div class="back-title">TERMS AND CONDITIONS</div>
            <div class="terms-text">This is a property of the Office<br>of the <strong>Public Services Commission</strong></div>
            <div class="terms-text office-details">If found, please return it to<br>The Secretary<br>Office of the<br><strong>PUBLIC SERVICES COMMISSION</strong></div>
            <div class="gps-address">GPS Address: GA 144 4112</div>
            <div class="cardholder-name">{{ $staff->full_name }}</div>
            @if($staff->other_contacts)
                <div class="terms-text" style="margin-bottom:12px;font-size:11px;">{{ $staff->other_contacts }}</div>
            @endif
            <div class="qr-code"><img src="{{ $qrDataUri }}" alt=""></div>
            <div class="footer-logo">
                <div class="logo"><img src="{{ asset('img/card/ghana.png') }}" alt=""></div>
                <div class="footer-text">PUBLIC SERVICES<br><span class="expanded-text">COMMISSION</span></div>
                <div class="badge"><img src="{{ asset('img/card/psc.png') }}" alt=""></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.2/dist/jspdf.umd.min.js"></script>
    <script>
        const staffSlug = '{{ str_replace('/', '-', $staff->staff_id) }}';
        const CARD_W = 340;
        const CARD_H = 540;

        async function captureCard(side) {
            const el = document.getElementById('capture-' + side);
            return html2canvas(el, {
                scale: 3,
                useCORS: true,
                allowTaint: false,
                backgroundColor: null,
                logging: false,
            });
        }

        async function downloadCardPDF() {
            const btn = document.getElementById('btn-pdf');
            const origText = btn.textContent;
            btn.textContent = 'Generating…';
            btn.disabled = true;

            try {
                const [frontCanvas, backCanvas] = await Promise.all([
                    captureCard('front'),
                    captureCard('back'),
                ]);

                const { jsPDF } = window.jspdf;
                // Page size matches the card pixel dimensions (1 pt = 1 px here)
                const pdf = new jsPDF({
                    orientation: 'portrait',
                    unit: 'px',
                    format: [CARD_W, CARD_H],
                    hotfixes: ['px_scaling'],
                });

                pdf.addImage(frontCanvas.toDataURL('image/png'), 'PNG', 0, 0, CARD_W, CARD_H);
                pdf.addPage([CARD_W, CARD_H], 'portrait');
                pdf.addImage(backCanvas.toDataURL('image/png'), 'PNG', 0, 0, CARD_W, CARD_H);

                pdf.save(`psc-id-${staffSlug}.pdf`);
            } finally {
                btn.textContent = origText;
                btn.disabled = false;
            }
        }

        async function downloadCardImage(side) {
            const btn = document.querySelector(`button[onclick="downloadCardImage('${side}')"]`);
            const origText = btn.textContent;
            btn.textContent = 'Generating…';
            btn.disabled = true;

            try {
                const canvas = await captureCard(side);
                const a = document.createElement('a');
                a.download = `psc-id-${staffSlug}-${side}.png`;
                a.href = canvas.toDataURL('image/png');
                a.click();
            } finally {
                btn.textContent = origText;
                btn.disabled = false;
            }
        }
    </script>

</body>
</html>
