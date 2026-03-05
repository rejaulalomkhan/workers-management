<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monthly Attendance - {{ date('F Y', mktime(0,0,0,$filterMonth, 1, $filterYear)) }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #333; margin: 0; padding: 10px; }
        .comp-name { font-size: 20px; font-bold: true; color: #1e3a8a; margin-bottom: 4px; }
        .report-title { font-size: 16px; font-weight: bold; margin-bottom: 5px; }
        .meta { font-size: 11px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #999; padding: 2px 1px; text-align: center; }
        th { background-color: #f3f4f6; font-size: 9px; font-weight: bold; }
        td { font-size: 9px; }
        .sn-col { width: 15px; font-weight: bold; background-color: #f9f9f9; }
        .name-col { text-align: left; padding-left: 3px; min-width: 70px; }
        .trade-col { white-space: nowrap; text-align: left; padding-left: 3px; }
        .total-col { font-weight: bold; background-color: #e0f2fe; width: 30px; }
        .absent { color: #dc2626; font-weight: bold; }
        .present { color: #166534; }
        .empty { color: #ccc; }
        @page { margin: 20px; }
    </style>
</head>
<body>
    @php
        $settings = \App\Models\Setting::first();
    @endphp

    <div style="text-align: center; border: 1px solid #999; padding: 5px 1px; font-size: 12px; font-weight: bold;">
        {{ $settings->company_name ?? 'FHTS Software' }} &nbsp; &nbsp; &nbsp; 
        DATE: {{ $dateLabel ?? date('d-m-Y', mktime(0,0,0,$filterMonth, 1, $filterYear)) . '-' . date('d-m-Y', mktime(0,0,0,$filterMonth, date('t', mktime(0,0,0,$filterMonth,1,$filterYear)), $filterYear)) }}
    </div>
    <table style="margin-top: -2px;">
        <thead>
            <tr>
                <th class="sn-col">SN</th>
                <th class="sn-col" style="width: 40px; white-space: nowrap;">ID No.</th>
                <th class="name-col">Name</th>
                <th class="trade-col">Trade</th>
                @for($d=1; $d<=$daysInMonth; $d++)
                    <th>{{ $d }}</th>
                @endfor
                <th class="total-col">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $index => $row)
                <tr>
                    <td class="sn-col">{{ $index + 1 }}</td>
                    <td class="sn-col">{{ $row['worker']->worker_id_number }}</td>
                    <td class="name-col">{{ $row['worker']->name }}</td>
                    <td class="trade-col">{{ $row['worker']->trade }}</td>
                    @foreach($row['days'] as $day => $val)
                        <td class="{{ $val === 'A' ? 'absent' : ($val !== '-' ? 'present' : 'empty') }}">
                            {{ $val }}
                        </td>
                    @endforeach
                    <td class="total-col">{{ $row['totalHours'] }}</td>
                </tr>
            @endforeach
            @if(count($reportData) > 0)
                <tr>
                    <td colspan="{{ $daysInMonth + 4 }}" style="text-align: right; font-weight: bold; padding-right: 6px; background-color: #f0f9ff;">TOTAL</td>
                    <td class="total-col" style="font-size: 9px; font-weight: bold; background-color: #bfdbfe;">{{ collect($reportData)->sum('totalHours') }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="{{ $daysInMonth + 4 }}" style="padding: 20px; text-align: center; color: #666;">
                        No records found
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
