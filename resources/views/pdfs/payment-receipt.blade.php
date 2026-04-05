<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Payment Receipt – {{ $worker->name }} – {{ $dateLabel }}</title>
    <style>
        @php echo '@page { margin: 0; }'; @endphp

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            background: #fff;
            position: relative;
        }

        /* ── Watermark ── */
        @if($setting && $setting->logo_path)
        body::before {
            content: "";
            background: url('{{ public_path($setting->logo_path) }}') no-repeat center center;
            background-size: 55%;
            opacity: 0.06;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 0;
        }
        @endif

        .page-wrap { position: relative; z-index: 1; }

        /* ── Header / Footer images ── */
        .header-img { width: 100%; display: block; line-height: 0; }
        .footer-img { width: 100%; display: block; line-height: 0; }
        .footer-fixed { position: fixed; bottom: 0; left: 0; right: 0; width: 100%; margin: 0; padding: 0; line-height: 0; }

        /* ── Body padding ── */
        .body-pad { padding: 30px 45px 20px 45px; }

        /* ── Receipt title band ── */
        .receipt-band {
            background: #1e3a5f;
            color: #fff;
            text-align: center;
            padding: 10px 0 8px;
            margin-bottom: 24px;
        }
        .receipt-band .title { font-size: 17px; font-weight: bold; letter-spacing: 2px; }
        .receipt-band .period { font-size: 11px; opacity: .75; margin-top: 3px; }

        /* ── Pay To / Pay From cards ── */
        .parties { display: table; width: 100%; margin-bottom: 22px; border-spacing: 0; }
        .party   { display: table-cell; width: 50%; vertical-align: top; }
        .party:first-child { padding-right: 16px; }
        .party-box {
            border: 1px solid #d0dae8;
            border-radius: 5px;
            padding: 12px 14px;
            background: #f8fafd;
        }
        .party-label {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #1e3a5f;
            margin-bottom: 6px;
            border-bottom: 1px solid #d0dae8;
            padding-bottom: 4px;
        }
        .party-name  { font-size: 13px; font-weight: bold; color: #111; margin-bottom: 3px; }
        .party-sub   { font-size: 10px; color: #555; line-height: 1.6; }

        /* ── Payments Table ── */
        .section-head {
            font-size: 10px; font-weight: bold; color: #1e3a5f;
            text-transform: uppercase; letter-spacing: .5px;
            border-bottom: 2px solid #1e3a5f;
            padding-bottom: 4px; margin-bottom: 8px;
        }

        table.pay-table { width: 100%; border-collapse: collapse; }
        table.pay-table thead tr { background: #1e3a5f; color: #fff; }
        table.pay-table thead th { padding: 8px 12px; text-align: left; font-size: 10px; }
        table.pay-table thead th.right { text-align: right; }
        table.pay-table tbody tr { border-bottom: 1px solid #e8edf5; }
        table.pay-table tbody tr:nth-child(even) { background: #f5f8fd; }
        table.pay-table tbody td { padding: 8px 12px; font-size: 11px; }
        table.pay-table tbody td.right { text-align: right; font-weight: bold; }
        table.pay-table tfoot tr { background: #1e3a5f; color: #fff; font-weight: bold; }
        table.pay-table tfoot td { padding: 9px 12px; font-size: 12px; }
        table.pay-table tfoot td.right { text-align: right; }

        /* ── Received stamp ── */
        .received-box {
            display: table;
            width: 100%;
            margin-top: 28px;
        }
        .received-left { display: table-cell; vertical-align: bottom; width: 50%; }
        .received-right { display: table-cell; vertical-align: bottom; width: 50%; text-align: center; }
        .sig-line { border-bottom: 1px solid #666; width: 80%; margin: 30px auto 4px; }
        .sig-name { font-size: 9px; color: #888; }

        .amount-words {
            background: #f0f4f9; border: 1px solid #c8d8ec; border-radius: 4px;
            padding: 8px 14px; margin-top: 14px; font-size: 10px; color: #1e3a5f;
        }
        .amount-words strong { font-size: 11px; }
    </style>
</head>
<body>
<div class="page-wrap">

    {{-- ── HEADER IMAGE ── --}}
    @if($setting && $setting->header_image_path)
    <div class="header-img">
        <img src="{{ public_path($setting->header_image_path) }}" style="width:100%; display:block;" alt="Header">
    </div>
    @else
    <div style="text-align:center; padding:18px 0 10px; border-bottom:2px solid #1e3a5f;">
        <div style="font-size:16px; font-weight:bold; color:#1e3a5f;">{{ $setting->company_name ?? 'COMPANY' }}</div>
    </div>
    @endif

    {{-- ── BODY ── --}}
    <div class="body-pad">

        {{-- Title Band --}}
        <div class="receipt-band">
            <div class="title">PAYMENT RECEIPT</div>
            <div class="period">{{ $dateLabel }}</div>
        </div>

        {{-- Pay To / Pay From --}}
        <div class="parties">
            <div class="party">
                <div class="party-box">
                    <div class="party-label">📤 Pay To (Employee)</div>
                    <div class="party-name">{{ strtoupper($worker->name) }}</div>
                    <div class="party-sub">
                        {{ $worker->trade }}
                        @if($worker->worker_id_number)
                        <br>ID: {{ $worker->worker_id_number }}
                        @endif
                    </div>
                </div>
            </div>
            <div class="party">
                <div class="party-box">
                    <div class="party-label">🏢 Pay From (Employer)</div>
                    <div class="party-name">{{ $setting->company_name ?? '—' }}</div>
                    <div class="party-sub">
                        @if($setting->phone){{ $setting->phone }}@endif
                        @if($setting->address)<br>{{ $setting->address }}@endif
                        @if($setting->trn)<br>TRN: {{ $setting->trn }}@endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Payments Table --}}
        <div class="section-head">Payment Details</div>
        <table class="pay-table">
            <thead>
                <tr>
                    <th style="width:30%;">Date</th>
                    <th style="width:50%;">Notes / Reference</th>
                    <th class="right" style="width:20%;">Amount ({{ $setting->currency ?? 'AED' }})</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $pay)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($pay->payment_date)->format('d M, Y') }}</td>
                    <td>{{ $pay->notes ?: '—' }}</td>
                    <td class="right" style="color:#2e7d32;">{{ number_format($pay->amount, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align:center; color:#999; padding:16px;">No payments recorded for this period.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">TOTAL AMOUNT PAID</td>
                    <td class="right">{{ $setting->currency ?? 'AED' }} {{ number_format($totalPaid, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- Amount in words box --}}
        @if($totalPaid > 0)
        <div class="amount-words">
            <strong>Amount: {{ $setting->currency ?? 'AED' }} {{ number_format($totalPaid, 2) }}</strong>
            &nbsp;&nbsp;|&nbsp;&nbsp; Period: {{ $dateLabel }}
        </div>
        @endif

        {{-- Signatures --}}
        <div class="received-box">
            <div class="received-left">
                <div style="font-size:9px; color:#666; line-height:1.8;">
                    Generated: {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}<br>
                    {{ $setting->company_name ?? '' }}
                </div>
            </div>
            <div class="received-right">
                <div class="sig-line"></div>
                <div class="sig-name">Employee Signature &amp; Date</div>
            </div>
        </div>

    </div>{{-- end body-pad --}}

    {{-- ── FOOTER IMAGE ── --}}
    @if($setting && $setting->footer_image_path)
    <div class="footer-fixed">
        <img src="{{ public_path($setting->footer_image_path) }}" style="width:100%; display:block;" alt="Footer">
    </div>
    @endif

</div>
</body>
</html>
