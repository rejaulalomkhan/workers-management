<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Salary Slip – {{ $worker->name }}</title>
    <style>
        @php echo '@page { margin: 12mm 10mm; }'; @endphp
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1a1a1a; background: #fff; }

        /* ── Header ── */
        .header { display: table; width: 100%; margin-bottom: 8px; border-bottom: 2px solid #1e3a5f; padding-bottom: 6px; }
        .header-logo { display: table-cell; width: 60px; vertical-align: middle; }
        .header-logo img { max-height: 50px; max-width: 55px; object-fit: contain; }
        .header-info  { display: table-cell; vertical-align: middle; padding-left: 10px; }
        .header-info .company { font-size: 14px; font-weight: bold; color: #1e3a5f; }
        .header-info .sub     { font-size: 10px; color: #555; margin-top: 2px; }
        .header-right { display: table-cell; text-align: right; vertical-align: middle; }
        .slip-title { font-size: 16px; font-weight: bold; color: #1e3a5f; letter-spacing: 1px; }
        .slip-period { font-size: 11px; color: #555; margin-top: 3px; }

        /* ── Worker Info ── */
        .worker-box { background: #f0f4f9; border: 1px solid #c8d8ec; border-radius: 4px;
                      padding: 8px 12px; margin-bottom: 8px; display: table; width: 100%; }
        .worker-box .col { display: table-cell; width: 33%; vertical-align: top; }
        .worker-box .label { font-size: 8px; color: #666; text-transform: uppercase; letter-spacing: 0.5px; }
        .worker-box .value { font-size: 11px; font-weight: bold; color: #1e3a5f; margin-top: 2px; }

        /* ── Attendance Grid ── */
        .section-title { font-size: 10px; font-weight: bold; color: #1e3a5f; text-transform: uppercase;
                         letter-spacing: 0.5px; border-bottom: 1px solid #1e3a5f; padding-bottom: 3px; margin-bottom: 5px; }

        table.att { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.att th { background: #1e3a5f; color: #fff; font-size: 8px; font-weight: bold;
                       text-align: center; padding: 4px 2px; border: 1px solid #1e3a5f; }
        table.att td { text-align: center; font-size: 9px; padding: 4px 2px;
                       border: 1px solid #dde4ef; vertical-align: middle; }
        table.att td.present { background: #e8f5e9; color: #2e7d32; font-weight: bold; }
        table.att td.absent  { background: #fce8e8; color: #c62828; font-weight: bold; }
        table.att td.empty   { color: #bbb; }

        /* ── Project per day ── */
        .proj-tag { font-size: 7px; color: #555; display: block; margin-top: 1px; line-height: 1.2; }

        /* ── Summary ── */
        .summary-wrap { display: table; width: 100%; margin-top: 8px; }
        .sum-cards { display: table-cell; width: 68%; vertical-align: top; }
        .sum-pay   { display: table-cell; width: 30%; vertical-align: top; padding-left: 10px; }

        .cards { display: table; width: 100%; border-collapse: separate; border-spacing: 5px; }
        .card  { display: table-cell; text-align: center; background: #f0f4f9; border: 1px solid #c8d8ec;
                 border-radius: 4px; padding: 6px 4px; }
        .card .num   { font-size: 18px; font-weight: bold; color: #1e3a5f; line-height: 1; }
        .card .lbl   { font-size: 8px; color: #666; margin-top: 3px; }

        .pay-box { background: #1e3a5f; color: #fff; border-radius: 5px; padding: 12px; text-align: center; }
        .pay-box .pay-label { font-size: 10px; letter-spacing: 1px; text-transform: uppercase; opacity: .8; }
        .pay-box .pay-amount { font-size: 22px; font-weight: bold; margin-top: 4px; }
        .pay-box .pay-rate   { font-size: 9px; opacity: .7; margin-top: 4px; }

        /* ── Footer sig ── */
        .footer { margin-top: 20px; display: table; width: 100%; border-top: 1px solid #ccc; padding-top: 8px; }
        .sig { display: table-cell; text-align: center; width: 33%; }
        .sig .line { border-bottom: 1px solid #666; width: 80%; margin: 0 auto 4px; }
        .sig .name { font-size: 9px; color: #555; }
    </style>
</head>
<body>

    {{-- ── HEADER ── --}}
    <div class="header">
        <div class="header-logo">
            @if($setting && $setting->logo_path)
            <img src="{{ public_path($setting->logo_path) }}" alt="Logo">
            @endif
        </div>
        <div class="header-info">
            <div class="company">{{ $setting->company_name ?? 'Company Name' }}</div>
            @if($setting->company_name_arabic)
            <div class="sub">{{ $setting->company_name_arabic }}</div>
            @endif
            @if($setting->address)
            <div class="sub">{{ $setting->address }}</div>
            @endif
            @if($setting->trn)
            <div class="sub">TRN: {{ $setting->trn }}</div>
            @endif
        </div>
        <div class="header-right">
            <div class="slip-title">SALARY SLIP</div>
            <div class="slip-period">{{ $dateLabel }}</div>
        </div>
    </div>

    {{-- ── WORKER INFO ── --}}
    <div class="worker-box">
        <div class="col">
            <div class="label">Employee Name</div>
            <div class="value">{{ strtoupper($worker->name) }}</div>
        </div>
        <div class="col">
            <div class="label">Trade / Designation</div>
            <div class="value">{{ $worker->trade }}</div>
        </div>
        <div class="col">
            <div class="label">Employee ID</div>
            <div class="value">{{ $worker->worker_id_number ?? '—' }}</div>
        </div>
    </div>

    {{-- ── ATTENDANCE GRID ── --}}
    <div class="section-title">Daily Attendance — {{ $dateLabel }}</div>
    <table class="att">
        <thead>
            <tr>
                @for($d = 1; $d <= $daysInMonth; $d++)
                <th>{{ $d }}</th>
                @endfor
                <th style="background:#2d5a9e;">Total<br>Hrs</th>
                <th style="background:#2d5a9e;">Days<br>Present</th>
                <th style="background:#c62828;">Absent</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                @for($d = 1; $d <= $daysInMonth; $d++)
                    @php $att = $attendances[$d] ?? null; @endphp
                    @if($att && is_numeric($att->hours))
                        <td class="present">
                            {{ $att->hours }}
                            @if($att->project)
                            <span class="proj-tag">{{ Str::limit($att->project->name, 6, '') }}</span>
                            @endif
                        </td>
                    @elseif($att && strtoupper($att->hours) === 'A')
                        <td class="absent">A</td>
                    @else
                        <td class="empty">—</td>
                    @endif
                @endfor
                <td style="font-weight:bold; font-size:11px; background:#e8f0fb;">{{ number_format($totalHours, 1) }}</td>
                <td style="font-weight:bold; background:#e8f5e9; color:#2e7d32;">{{ $daysPresent }}</td>
                <td style="font-weight:bold; background:#fce8e8; color:#c62828;">{{ $daysAbsent }}</td>
            </tr>
        </tbody>
    </table>

    {{-- ── SUMMARY + PAYMENT ── --}}
    <div class="summary-wrap">
        <div class="sum-cards">
            <div class="section-title" style="margin-bottom:6px;">Summary</div>
            <div class="cards">
                <div class="card">
                    <div class="num">{{ number_format($totalHours, 1) }}</div>
                    <div class="lbl">Total Hours</div>
                </div>
                <div class="card">
                    <div class="num">{{ $daysPresent }}</div>
                    <div class="lbl">Days Present</div>
                </div>
                <div class="card">
                    <div class="num">{{ $daysAbsent }}</div>
                    <div class="lbl">Days Absent</div>
                </div>
                <div class="card">
                    <div class="num">{{ number_format($worker->internal_pay_rate, 2) }}</div>
                    <div class="lbl">Rate / Hr ({{ $setting->currency ?? 'AED' }})</div>
                </div>
            </div>

            {{-- Calculation breakdown --}}
            <table style="width:100%; border-collapse:collapse; margin-top:8px; font-size:10px;">
                <tr style="background:#f5f5f5;">
                    <td style="padding:4px 8px; border:1px solid #ddd;">Total Hours Worked</td>
                    <td style="padding:4px 8px; border:1px solid #ddd; text-align:right;">{{ number_format($totalHours, 2) }} hrs</td>
                </tr>
                <tr>
                    <td style="padding:4px 8px; border:1px solid #ddd;">Rate per Hour</td>
                    <td style="padding:4px 8px; border:1px solid #ddd; text-align:right;">{{ $setting->currency ?? 'AED' }} {{ number_format($worker->internal_pay_rate, 2) }}</td>
                </tr>
                <tr style="background:#e8f0fb; font-weight:bold;">
                    <td style="padding:5px 8px; border:1px solid #c8d8ec;">GROSS SALARY</td>
                    <td style="padding:5px 8px; border:1px solid #c8d8ec; text-align:right; font-size:12px;">{{ $setting->currency ?? 'AED' }} {{ number_format($grossSalary, 2) }}</td>
                </tr>
                <tr style="background:#e8f5e9; color:#2e7d32;">
                    <td style="padding:4px 8px; border:1px solid #c8e6c9;">Paid This Month</td>
                    <td style="padding:4px 8px; border:1px solid #c8e6c9; text-align:right; font-weight:bold;">− {{ $setting->currency ?? 'AED' }} {{ number_format($monthPaid, 2) }}</td>
                </tr>
                <tr style="background:#fce8e8; color:#c62828; font-weight:bold;">
                    <td style="padding:5px 8px; border:1px solid #f5c6c6;">OUTSTANDING DUE (All Time)</td>
                    <td style="padding:5px 8px; border:1px solid #f5c6c6; text-align:right; font-size:12px;">{{ $setting->currency ?? 'AED' }} {{ number_format($overallDue, 2) }}</td>
                </tr>
            </table>

            {{-- Monthly payments list --}}
            @if($monthPayments->count() > 0)
            <div style="margin-top:8px;">
                <div style="font-size:9px; font-weight:bold; color:#1e3a5f; text-transform:uppercase; margin-bottom:4px;">Payments Received This Month</div>
                <table style="width:100%; border-collapse:collapse; font-size:9px;">
                    <tr style="background:#1e3a5f; color:#fff;">
                        <th style="padding:3px 6px; text-align:left;">Date</th>
                        <th style="padding:3px 6px; text-align:left;">Notes</th>
                        <th style="padding:3px 6px; text-align:right;">Amount</th>
                    </tr>
                    @foreach($monthPayments as $pay)
                    <tr style="border-bottom:1px solid #eee;">
                        <td style="padding:3px 6px;">{{ \Carbon\Carbon::parse($pay->payment_date)->format('d M Y') }}</td>
                        <td style="padding:3px 6px; color:#555;">{{ $pay->notes ?? '—' }}</td>
                        <td style="padding:3px 6px; text-align:right; color:#2e7d32; font-weight:bold;">{{ $setting->currency ?? 'AED' }} {{ number_format($pay->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
            @endif
        </div>

        <div class="sum-pay">
            <div class="pay-box">
                <div class="pay-label">Gross Salary</div>
                <div class="pay-amount" style="font-size:18px;">{{ number_format($grossSalary, 2) }}</div>
                <div class="pay-rate">{{ $setting->currency ?? 'AED' }} &nbsp;|&nbsp; {{ $dateLabel }}</div>
            </div>
            <div style="margin-top:6px; background:#e8f5e9; border:1px solid #c8e6c9; border-radius:4px; padding:8px; text-align:center;">
                <div style="font-size:9px; color:#2e7d32; text-transform:uppercase; letter-spacing:.5px;">Paid This Month</div>
                <div style="font-size:16px; font-weight:bold; color:#2e7d32; margin-top:2px;">{{ $setting->currency ?? 'AED' }} {{ number_format($monthPaid, 2) }}</div>
            </div>
            <div style="margin-top:6px; background:#fce8e8; border:1px solid #f5c6c6; border-radius:4px; padding:8px; text-align:center;">
                <div style="font-size:9px; color:#c62828; text-transform:uppercase; letter-spacing:.5px;">Outstanding Due</div>
                <div style="font-size:18px; font-weight:bold; color:#c62828; margin-top:2px;">{{ $setting->currency ?? 'AED' }} {{ number_format($overallDue, 2) }}</div>
                <div style="font-size:8px; color:#999; margin-top:2px;">All-time balance</div>
            </div>
        </div>
    </div>

    {{-- ── FOOTER SIGNATURES ── --}}
    <div class="footer">
        <div class="sig">
            <div class="line">&nbsp;</div>
            <div class="name">Employee Signature</div>
        </div>
        <div class="sig">
            <div class="line">&nbsp;</div>
            <div class="name">HR / Accounts</div>
        </div>
        <div class="sig">
            <div class="line">&nbsp;</div>
            <div class="name">Authorized Signature</div>
        </div>
    </div>

</body>
</html>
