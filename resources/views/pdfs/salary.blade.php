<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Salary Report {{ $dateLabel }}</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .page-break { page-break-after: always; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; border: 1px solid #000; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-lg { font-size: 1.125rem; }
        .header { text-align: center; margin-bottom: 20px; }
        .company-name { font-size: 24px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ $setting->company_name ?? 'COMPANY NAME' }}</div>
        <div>{{ $setting->address }} | {{ $setting->phone }}</div>
        <h2 style="margin-top:20px;">Monthly Salary Report</h2>
        <div>For the period of {{ $dateLabel }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Worker ID</th>
                <th>Name</th>
                <th>Trade</th>
                <th class="text-right">Rate</th>
                <th class="text-right">Total Hr</th>
                <th class="text-right">Total Pay ({{ $setting->currency ?? 'AED' }})</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $row)
            <tr>
                <td>{{ $row['worker_id_number'] }}</td>
                <td>{{ $row['name'] }}</td>
                <td>{{ $row['trade'] }}</td>
                <td class="text-right">{{ number_format($row['rate'], 2) }}</td>
                <td class="text-right">{{ $row['total_hours'] }}</td>
                <td class="text-right font-bold">{{ number_format($row['total_pay'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right font-bold">TOTAL:</td>
                <td class="text-right font-bold text-lg">{{ number_format(collect($reportData)->sum('total_pay'), 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
