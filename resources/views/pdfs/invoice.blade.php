<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { 
            font-family: sans-serif; 
            margin: 0; padding: 0;
            position: relative;
        }
        @if($setting->logo_path)
        body::before {
            content: "";
            background: url('{{ public_path($setting->logo_path) }}') no-repeat center center;
            opacity: 0.1;
            position: fixed;
            top: 20%; left: 0; right: 0; bottom: 0;
            z-index: -1;
            background-size: 50%;
        }
        @endif
        .container { padding: 40px; }
        .header-image { width: 100%; max-height: 150px; object-fit: contain; margin-bottom: 20px; border-bottom: 2px solid #ccc; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f8f9fa; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mt-4 { margin-top: 20px; }
        .totals-table { width: 40%; float: right; margin-top: 20px; }
        .totals-table th, .totals-table td { border: none; padding: 5px; }
        .totals-table td.amount { text-align: right; }
        .totals-table .grand-total { font-weight: bold; font-size: 18px; border-top: 2px solid #333; }
    </style>
</head>
<body>
    @if($setting->header_image_path)
        <img src="{{ public_path($setting->header_image_path) }}" class="header-image" alt="Header">
    @else
        <div style="text-align:center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px;">
            <h1 style="margin:0;">{{ $setting->company_name ?? 'COMPANY NAME' }}</h1>
            <p style="margin:5px 0 0 0;">{{ $setting->company_name_arabic }}</p>
        </div>
    @endif

    <div class="container">
        <h2 style="text-align: center; font-size: 28px; text-decoration: underline; margin-bottom: 30px; color: #333;">TAX INVOICE</h2>

        <div style="width: 100%; clear: both;">
            <div style="float: left; width: 48%; border: 1px solid #ccc; padding: 15px; border-radius: 5px;">
                <h4 style="margin-top:0; border-bottom: 1px solid #eee; padding-bottom: 5px;">Billed To:</h4>
                <div class="font-bold" style="font-size: 16px;">{{ $invoice->project->customer_name }}</div>
                <div>{{ $invoice->project->customer_address }}</div>
                @if($invoice->project->customer_trn)
                <div style="margin-top: 10px;"><strong>TRN:</strong> {{ $invoice->project->customer_trn }}</div>
                @endif
                <div style="margin-top: 10px;"><strong>Project:</strong> {{ $invoice->project->name }}</div>
            </div>

            <div style="float: right; width: 48%; padding: 15px;">
                <table style="margin-top:0; border: none;">
                    <tr><td style="border:none; padding:3px;"><strong>Invoice No:</strong></td><td style="border:none; padding:3px;" class="font-bold">{{ $invoice->invoice_number }}</td></tr>
                    <tr><td style="border:none; padding:3px;"><strong>Date:</strong></td><td style="border:none; padding:3px;">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td></tr>
                    <tr><td style="border:none; padding:3px;"><strong>TRN:</strong></td><td style="border:none; padding:3px;">{{ $setting->trn }}</td></tr>
                    <tr><td style="border:none; padding:3px;"><strong>Period:</strong></td><td style="border:none; padding:3px;">{{ \Carbon\Carbon::parse($invoice->period_start)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($invoice->period_end)->format('d/m/Y') }}</td></tr>
                </table>
            </div>
        </div>
        <div style="clear: both;"></div>

        <table>
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Description</th>
                    <th class="text-right">Quantity (Hours)</th>
                    <th class="text-right">Rate/Hr ({{ $setting->currency ?? 'AED' }})</th>
                    <th class="text-right">Amount ({{ $setting->currency ?? 'AED' }})</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->description }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->rate, 2) }}</td>
                    <td class="text-right">{{ number_format($item->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td>Subtotal</td>
                <td class="amount">{{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>VAT ({{ $setting->vat_rate ?? '5' }}%)</td>
                <td class="amount">{{ number_format($invoice->vat_amount, 2) }}</td>
            </tr>
            <tr class="grand-total">
                <td style="border-top: 1px solid #000; padding-top: 10px;">Total ({{ $setting->currency ?? 'AED' }})</td>
                <td class="amount" style="border-top: 1px solid #000; padding-top: 10px;">{{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
        </table>
        <div style="clear: both;"></div>

        <div style="margin-top: 80px; width: 100%;">
            <div style="float: left; width: 40%; text-align: center;">
                <hr style="border-color: #333; margin-bottom: 5px;">
                <strong>Receiver's Signature</strong>
            </div>
            <div style="float: right; width: 40%; text-align: center;">
                <hr style="border-color: #333; margin-bottom: 5px;">
                <strong>Authorized Signature</strong>
            </div>
        </div>
    </div>
</body>
</html>
