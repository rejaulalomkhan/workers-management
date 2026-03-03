<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>@php echo '@page { margin: 0; }'; @endphp</style>
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
            top: 2%; left: 0; right: 0; bottom: 0;
            z-index: -1;
            background-size: 60%;
        }
        @endif
        .container { padding: 5px 40px 5px 40px; }
        .header-image { width: 100%; max-height: 150px; object-fit: contain; margin-bottom: 10px;}
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
        <div style="margin: 15px 35px 0px 35px;">
            <img src="{{ public_path($setting->header_image_path) }}" class="header-image" alt="Header">
        </div>
    @else
        <div style="text-align:center; padding: 20px;">
            <h1 style="margin:0;">{{ $setting->company_name ?? 'COMPANY NAME' }}</h1>
            <p style="margin:5px 0 0 0;">{{ $setting->company_name_arabic }}</p>
        </div>
    @endif
    <div class="container">
        <div style="font-family: 'Times New Roman', Times, serif;">
        <h2 style="text-align: center; font-size: 25px; color: #000000ff; background-color: #dfdfdfff; padding: 2px 0;">TAX INVOICE</h2>
        <div style="float: right; text-align: right; margin-bottom: 10px; margin-top: -10px; font-size: 18px; font-weight: bold;">
            INVOICE NO: {{ $invoice->invoice_number }}<br>
            DATE: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}
        </div>
        </div>

        <div style="width: 100%; clear: both; gap: 10px;">
            <div style="float: left; width: 85%; font-family: 'Times New Roman', Times, serif; font-size: 16px;">
                Billing To:<br>
                Date Of Invoice:{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}<br>
                <div class="font-bold" style="font-size: 16px;">{{ strtoupper($invoice->project->customer_name) }}</div>
                @if($invoice->project->customer_trn)
                <div>TRN: {{ $invoice->project->customer_trn }}</div>
                @endif
                @if($invoice->project->customer_phone)
                <div>TEL: {{ $invoice->project->customer_phone }}</div>
                @endif
                <div>PROJECT: {{ $invoice->project->name }}</div>
                @if($invoice->project->customer_subject)
                <div>Sub: {{ $invoice->project->customer_subject }}</div>
                @endif
                <div>Address Office: {{ $invoice->project->customer_address }}</div>
                <br>
                Billing From:<br>
                {{ $setting->company_name }}<br>
                <br>  
                @if($setting->bank_details)
                <div style="white-space: pre-line; font-family: 'Roboto', sans-serif !important;">{{ $setting->bank_details }}</div>
                @endif     
                TRN: {{ $setting->trn }}
            </div>     
        </div>

        <div style="clear: both;"></div>

        @php
            $vatRate = $setting->vat_rate ?? 5;
            $currency = $setting->currency ?? 'AED';
        @endphp
        <table style="margin-top: 20px;">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Rate</th>
                    <th class="text-right">Unit</th>
                    <th class="text-right">Amount ({{ $currency }})</th>
                    <th class="text-right">Vat {{ $vatRate }}%</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                @php
                    $vatAmt = $item->amount * ($vatRate / 100);
                    // Strip "Labor Supply: " prefix if present
                    $desc = preg_replace('/^Labor Supply:\s*/i', '', $item->description);
                @endphp
                <tr>
                    <td style="font-weight:bold;">{{ strtoupper($desc) }}</td>
                    <td class="text-right">{{ number_format($item->quantity, 0) }}</td>
                    <td class="text-right">{{ number_format($item->rate, 2) }}</td>
                    <td class="text-right">Hr</td>
                    <td class="text-right">{{ number_format($item->amount, 0) }}</td>
                    <td class="text-right">{{ number_format($vatAmt, 2) }}</td>
                </tr>
                @endforeach

                {{-- Subtotal row --}}
                <tr>
                    <td colspan="4" class="text-right" style="font-weight:bold; border-top: 1px solid #999;">Total</td>
                    <td class="text-right" style="font-weight:bold; border-top: 1px solid #999;">{{ number_format($invoice->subtotal, 0) }}</td>
                    <td class="text-right" style="font-weight:bold; border-top: 1px solid #999;">{{ number_format($invoice->vat_amount, 2) }}</td>
                </tr>

                {{-- Grand Total row --}}
                <tr style="background-color: #f1f5f9;">
                    <td colspan="5" class="text-right" style="font-weight:bold; font-size: 13px; border-top: 2px solid #333; padding: 12px 10px;">
                        TOTAL AMOUNT INCLUDE VAT {{ $vatRate }}%
                    </td>
                    <td class="text-right" style="font-weight:bold; font-size: 14px; border-top: 2px solid #333;">
                        {{ number_format($invoice->total_amount, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="clear: both;"></div>
        <div style="margin-top: 20px;">
            For
            <br>{{ $setting->company_name }}
            <br>Mob:0544083365
        </div>
    </div>

    @if($setting->footer_image_path)
    <div style="position: fixed; bottom: 0; left: 0; right: 0; width: 100%; margin: 0; padding: 0; line-height: 0;">
        <img src="{{ public_path($setting->footer_image_path) }}" style="width: 100%; height: auto; display: block; margin: 0; padding: 0; border: none;" alt="Footer">
    </div>
    @endif

</body>
</html>
