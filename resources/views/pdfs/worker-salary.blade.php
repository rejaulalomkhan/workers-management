<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Salary Slip – {{ $worker->name }}</title>
    <style>
        @page { margin: 12mm 15mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #202020; line-height: 1.4; }
        
        /* Typography */
        .font-bold { font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-gray { color: #555; }
        .text-blue { color: #0d47a1; }
        .text-red { color: #c62828; }
        .text-green { color: #1b5e20; }

        /* General Tables */
        .w-full { width: 100%; }
        
        /* Header */
        table.tbl-header { width: 100%; border-bottom: 2px solid #0d47a1; padding-bottom: 8px; margin-bottom: 12px; }
        .logo-cell { width: 70px; vertical-align: middle; }
        .logo-cell img { max-height: 50px; max-width: 60px; object-fit: contain; }
        .company-cell { vertical-align: middle; padding-left: 10px; }
        .company-title { font-size: 16px; font-weight: bold; color: #0d47a1; margin-bottom: 2px; text-transform: uppercase; }
        .company-sub { font-size: 9px; color: #555; line-height: 1.3;}
        .slip-title-cell { text-align: right; vertical-align: middle; }
        .slip-title { font-size: 20px; font-weight: bold; color: #0d47a1; text-transform: uppercase; letter-spacing: 1px; }
        .slip-month { font-size: 11px; font-weight: bold; color: #333; margin-top: 4px; padding: 4px 8px; border: 1px solid #ccc; background:#f9f9f9; display: inline-block;}

        /* Worker Details */
        table.tbl-details { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        table.tbl-details td { padding: 6px 10px; border: 1px solid #ddd; background: #fdfdfd; font-size: 11px; width: 33.33%;}
        .lbl-title { font-size: 8px; color: #777; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 2px; }
        .lbl-val { font-size: 13px; font-weight: bold; color: #000; }

        /* Attendance Grid */
        .section-title { font-size: 10px; font-weight: bold; color: #0d47a1; border-bottom: 1px solid #bbb; padding-bottom: 3px; margin-bottom: 6px; text-transform: uppercase; }
        table.att-grid { width: 100%; border-collapse: collapse; margin-bottom: 15px; table-layout: fixed; }
        table.att-grid th { background: #0d47a1; color: #fff; font-size: 8px; font-weight: bold; text-align: center; padding: 5px 2px; border: 1px solid #1565c0; }
        table.att-grid td { text-align: center; font-size: 9px; padding: 5px 1px; border: 1px solid #ccc; vertical-align: middle; }
        
        .att-p { color: #1b5e20; font-weight: bold; background: #f1f8e9; }
        .att-a { color: #c62828; font-weight: bold; background: #ffebee; }
        
        .col-hrs { background: #e3f2fd; color: #0d47a1; font-weight: bold; }
        .col-prs { background: #e8f5e9; color: #1b5e20; font-weight: bold; }
        .col-abs { background: #ffebee; color: #c62828; font-weight: bold; }
        .proj-name { font-size: 6px; color: #666; display: block; padding-top: 2px; }

        /* Bottom Layout Split */
        table.bottom-split { width: 100%; border-collapse: collapse; }
        table.bottom-split td.left-panel { width: 55%; vertical-align: top; padding-right: 25px; }
        table.bottom-split td.right-panel { width: 45%; vertical-align: top; }

        /* Payments Table */
        table.tbl-payments { width: 100%; border-collapse: collapse; font-size: 9px; margin-bottom: 10px; }
        table.tbl-payments th { background: #f0f0f0; border: 1px solid #ccc; padding: 5px; text-align: left; }
        table.tbl-payments td { border: 1px solid #ddd; padding: 5px; }

        /* Invoice Totals Table */
        table.tbl-totals { width: 100%; border-collapse: collapse; font-size: 11px; }
        table.tbl-totals td { padding: 6px 10px; border-bottom: 1px solid #eee; }
        table.tbl-totals tr.gross td { font-size: 13px; font-weight: bold; background: #e3f2fd; border-bottom: 1px solid #bbdefb; color: #0d47a1;}
        table.tbl-totals tr.paid td { font-size: 11px; font-weight: bold; color: #1b5e20; border-bottom: 1px solid #c8e6c9; }
        table.tbl-totals tr.due td { font-size: 14px; font-weight: bold; background: #ffebee; border-bottom: 1px solid #ffcdd2; color: #c62828; }

        /* Footer */
        table.tbl-sigs { width: 100%; margin-top: 50px; text-align: center; }
        table.tbl-sigs td { width: 33.33%; vertical-align: bottom; }
        .sig-line { border-top: 1px solid #000; width: 60%; margin: 0 auto; padding-top: 4px; font-size: 9px; text-transform: uppercase; font-weight: bold; color: #444;}

        .system-footer { margin-top: 20px; text-align: center; font-size: 8px; color: #a0a0a0; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>

    {{-- ── HEADER ── --}}
    <table class="tbl-header">
        <tr>
            @if($setting && $setting->logo_path)
            <td class="logo-cell">
                <img src="{{ public_path($setting->logo_path) }}" alt="Logo">
            </td>
            @endif
            <td class="company-cell">
                <div class="company-title">{{ $setting->company_name ?? 'Company Name' }}</div>
                @if($setting->company_name_arabic)
                <div class="company-sub">{{ $setting->company_name_arabic }}</div>
                @endif
                <div class="company-sub">
                    @if($setting->address) {{ $setting->address }} <br> @endif
                    @if($setting->trn) <strong>TRN:</strong> {{ $setting->trn }} @endif
                </div>
            </td>
            <td class="slip-title-cell">
                <div class="slip-title">Salary Slip</div>
                <div class="slip-month">{{ $dateLabel }}</div>
            </td>
        </tr>
    </table>

    {{-- ── WORKER INFO ── --}}
    <table class="tbl-details">
        <tr>
            <td>
                <span class="lbl-title">Employee Name</span>
                <span class="lbl-val">{{ strtoupper($worker->name) }}</span>
            </td>
            <td>
                <span class="lbl-title">Trade / Designation</span>
                <span class="lbl-val">{{ strtoupper($worker->trade) }}</span>
            </td>
            <td class="text-right">
                <span class="lbl-title">Employee ID</span>
                <span class="lbl-val">{{ $worker->worker_id_number ?? '—' }}</span>
            </td>
        </tr>
    </table>

    {{-- ── ATTENDANCE GRID ── --}}
    <div class="section-title">Daily Attendance — {{ strtoupper($dateLabel) }}</div>
    <table class="att-grid">
        <thead>
            <tr>
                @for($d = 1; $d <= $daysInMonth; $d++)
                <th>{{ $d }}</th>
                @endfor
                <th style="background:#0d47a1; font-size:7px;">TOTAL<br>HRS</th>
                <th style="background:#1b5e20; font-size:7px;">DAYS<br>PRST</th>
                <th style="background:#c62828; font-size:7px;">ABST</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                @for($d = 1; $d <= $daysInMonth; $d++)
                    @php $att = $attendances[$d] ?? null; @endphp
                    @if($att && is_numeric($att->hours))
                        <td class="att-p">
                            {{ $att->hours }}
                            @if($att->project)
                            <span class="proj-name">{{ Str::limit($att->project->name ?? '', 5, '') }}</span>
                            @endif
                        </td>
                    @elseif($att && strtoupper($att->hours) === 'A')
                        <td class="att-a">A</td>
                    @else
                        <td style="color:#d1d5db;">-</td>
                    @endif
                @endfor
                <td class="col-hrs">{{ number_format($totalHours, 1) }}</td>
                <td class="col-prs">{{ $daysPresent }}</td>
                <td class="col-abs">{{ $daysAbsent }}</td>
            </tr>
        </tbody>
    </table>

    {{-- ── BOTTOM SPLIT ── --}}
    <table class="bottom-split">
        <tr>
            <td class="left-panel">
                @if($monthPayments->count() > 0)
                <div class="section-title">Payments Disbursed This Month</div>
                <table class="tbl-payments">
                    <thead>
                        <tr>
                            <th style="width:25%;">Date</th>
                            <th style="width:50%;">Notes / Reference</th>
                            <th style="width:25%;text-align:right;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthPayments as $pay)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($pay->payment_date)->format('d M Y') }}</td>
                            <td class="text-gray">{{ $pay->notes ?? '—' }}</td>
                            <td class="text-right font-bold text-green">{{ $setting->currency ?? 'AED' }} {{ number_format($pay->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="section-title">Payments Disbursed This Month</div>
                <p style="font-size:9px; color:#777; font-style:italic;">No payments processed during this period.</p>
                @endif
                
                <div style="margin-top: 20px; font-size: 8px; line-height: 1.5; color: #777; padding: 10px; background: #fafafa; border: 1px dotted #ccc;">
                    <strong>Notice:</strong> This is a system-generated salary slip and does not require a physical signature for validity. 
                    The "Outstanding Balance" accurately reflects the collective all-time pending dues at the exact moment of generating this document.
                </div>
            </td>
            
            <td class="right-panel">
                <div class="section-title">Salary Calculation</div>
                <table class="tbl-totals">
                    <tr>
                        <td class="text-gray">Total Hours Worked</td>
                        <td class="text-right font-bold">{{ number_format($totalHours, 2) }} hrs</td>
                    </tr>
                    <tr>
                        <td class="text-gray">Rate per Hour</td>
                        <td class="text-right font-bold">{{ $setting->currency ?? 'AED' }} {{ number_format($worker->internal_pay_rate, 2) }}</td>
                    </tr>
                    <tr class="gross">
                        <td>GROSS SALARY</td>
                        <td class="text-right">{{ $setting->currency ?? 'AED' }} {{ number_format($grossSalary, 2) }}</td>
                    </tr>
                    <tr class="paid">
                        <td>Less: Paid This Month</td>
                        <td class="text-right">− {{ $setting->currency ?? 'AED' }} {{ number_format($monthPaid, 2) }}</td>
                    </tr>
                    <tr class="due">
                        <td>OUTSTANDING BALANCE</td>
                        <td class="text-right">{{ $setting->currency ?? 'AED' }} {{ number_format($overallDue, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ── FOOTER SIGNATURES ── --}}
    <table class="tbl-sigs">
        <tr>
            <td>
                <div class="sig-line">Employee Signature</div>
            </td>
            <td>
                <div class="sig-line">HR / Accounts</div>
            </td>
            <td>
                <div class="sig-line">Authorized Signature</div>
            </td>
        </tr>
    </table>
    
    <div class="system-footer">
        Generated securely by {{ $setting->company_name ?? 'Payroll System' }} &bull; {{ date('d M Y H:i A') }}
    </div>

</body>
</html>
