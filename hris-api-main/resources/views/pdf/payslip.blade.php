<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Slip Gaji - {{ $detail->employee->user->name ?? 'Karyawan' }} - {{ $payroll->month ?? '' }} {{ $payroll->year ?? '' }}</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
        body {
            color: #1F2937;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
            background-color: #ffffff;
        }
        .header {
            border-bottom: 2px solid #0C51D9;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }
        .header table {
            width: 100%;
            border-collapse: collapse;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #0C51D9;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .company-subtitle {
            font-size: 10px;
            color: #6B7280;
            margin-top: 3px;
        }
        .doc-title-box {
            text-align: right;
        }
        .doc-title {
            font-size: 16px;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            margin: 0;
        }
        .doc-period {
            font-size: 12px;
            font-weight: 600;
            color: #0C51D9;
            margin-top: 2px;
        }
        .status-badge {
            display: inline-block;
            background-color: #DEF7EC;
            color: #03543F;
            font-size: 10px;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 9999px;
            text-transform: uppercase;
            margin-top: 4px;
        }
        .employee-info-card {
            background-color: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 18px;
        }
        .employee-info-card table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-label {
            color: #6B7280;
            font-size: 11px;
            width: 18%;
            padding: 3px 0;
        }
        .info-val {
            color: #111827;
            font-size: 11px;
            font-weight: 600;
            width: 32%;
            padding: 3px 0;
        }
        .breakdown-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .breakdown-table th {
            background-color: #F3F4F6;
            color: #374151;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 12px;
            border: 1px solid #E5E7EB;
        }
        .breakdown-table td {
            padding: 8px 12px;
            border: 1px solid #E5E7EB;
            font-size: 11px;
        }
        .col-half {
            width: 50%;
            vertical-align: top;
        }
        .amount-row {
            display: table;
            width: 100%;
            padding: 4px 0;
        }
        .amount-label {
            display: table-cell;
            color: #4B5563;
        }
        .amount-value {
            display: table-cell;
            text-align: right;
            font-weight: 600;
            color: #111827;
        }
        .amount-total {
            border-top: 1px dashed #D1D5DB;
            margin-top: 6px;
            padding-top: 6px;
            font-weight: bold;
        }
        .net-salary-box {
            background-color: #EFF6FF;
            border: 2px solid #0C51D9;
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 24px;
        }
        .net-salary-box table {
            width: 100%;
            border-collapse: collapse;
        }
        .net-salary-title {
            font-size: 12px;
            font-weight: bold;
            color: #1E40AF;
            text-transform: uppercase;
        }
        .net-salary-subtitle {
            font-size: 10px;
            color: #60A5FA;
            margin-top: 2px;
        }
        .net-salary-amount {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            color: #0C51D9;
        }
        .signatures {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        .sig-box {
            width: 45%;
            text-align: center;
            vertical-align: top;
        }
        .sig-space {
            width: 10%;
        }
        .sig-line {
            margin-top: 60px;
            border-bottom: 1px solid #111827;
            font-weight: bold;
            font-size: 11px;
            padding-bottom: 4px;
        }
        .sig-title {
            font-size: 10px;
            color: #6B7280;
            margin-top: 2px;
        }
        .footer-note {
            margin-top: 30px;
            border-top: 1px solid #E5E7EB;
            padding-top: 8px;
            font-size: 9px;
            color: #9CA3AF;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <table>
            <tr>
                <td style="vertical-align: middle;">
                    <h1 class="company-name">PT SENJA SOLUSI DIGITAL</h1>
                    <div class="company-subtitle">Human Resources Information System • SenjaHRIS v1.0.0</div>
                    <div class="company-subtitle">Menara Senja Lt. 18, Jakarta Selatan • support@senjahris.com</div>
                </td>
                <td class="doc-title-box" style="vertical-align: middle;">
                    <div class="doc-title">SLIP GAJI KARYAWAN</div>
                    <div class="doc-period">Periode: {{ $payroll->month ?? date('F') }} {{ $payroll->year ?? date('Y') }}</div>
                    <div><span class="status-badge">{{ strtoupper($payroll->status ?? 'PAID') }}</span></div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Employee Details -->
    <div class="employee-info-card">
        <table>
            <tr>
                <td class="info-label">Nama Karyawan</td>
                <td class="info-val">: {{ $detail->employee->user->name ?? '-' }}</td>
                <td class="info-label">ID / Kode</td>
                <td class="info-val">: {{ $detail->employee->code ?? '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Jabatan</td>
                <td class="info-val">: {{ $detail->employee->jobInformation->job_title ?? '-' }}</td>
                <td class="info-label">Departemen / Tim</td>
                <td class="info-val">: {{ $detail->employee->jobInformation->team->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Nama Bank</td>
                <td class="info-val">: {{ $detail->employee->bankInformation->bank_name ?? 'BCA' }}</td>
                <td class="info-label">No. Rekening</td>
                <td class="info-val">: {{ $detail->employee->bankInformation->account_number ?? '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Total Hari Kerja</td>
                <td class="info-val">: 22 Hari</td>
                <td class="info-label">Kehadiran</td>
                <td class="info-val">: {{ $detail->attended_days ?? 0 }} Hadir / {{ $detail->sick_days ?? 0 }} Sakit</td>
            </tr>
        </table>
    </div>

    <!-- Earnings & Deductions Breakdown -->
    <table class="breakdown-table">
        <tr>
            <th class="col-half">Penerimaan (Earnings)</th>
            <th class="col-half">Potongan (Deductions)</th>
        </tr>
        <tr>
            <td class="col-half">
                <div class="amount-row">
                    <span class="amount-label">Gaji Pokok (Basic Salary)</span>
                    <span class="amount-value">Rp {{ number_format($detail->original_salary ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="amount-row">
                    <span class="amount-label">Tunjangan Operasional</span>
                    <span class="amount-value">Rp 0</span>
                </div>
                <div class="amount-row">
                    <span class="amount-label">Insentif / Bonus Kinerja</span>
                    <span class="amount-value">Rp 0</span>
                </div>
                <div class="amount-row amount-total">
                    <span class="amount-label">Total Penerimaan Kotor</span>
                    <span class="amount-value">Rp {{ number_format($detail->original_salary ?? 0, 0, ',', '.') }}</span>
                </div>
            </td>
            <td class="col-half">
                @php
                    $deductions = max(0, ($detail->original_salary ?? 0) - ($detail->final_salary ?? 0));
                @endphp
                <div class="amount-row">
                    <span class="amount-label">Potongan Ketidakhadiran</span>
                    <span class="amount-value" style="color: #DC2626;">Rp {{ number_format($deductions, 0, ',', '.') }}</span>
                </div>
                <div class="amount-row">
                    <span class="amount-label">PPh 21 / Pajak</span>
                    <span class="amount-value">Rp 0</span>
                </div>
                <div class="amount-row">
                    <span class="amount-label">BPJS & Asuransi</span>
                    <span class="amount-value">Rp 0</span>
                </div>
                <div class="amount-row amount-total">
                    <span class="amount-label">Total Potongan</span>
                    <span class="amount-value" style="color: #DC2626;">Rp {{ number_format($deductions, 0, ',', '.') }}</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Net Salary Highlight Box -->
    <div class="net-salary-box">
        <table>
            <tr>
                <td>
                    <div class="net-salary-title">TOTAL GAJI BERSIH (TAKE HOME PAY)</div>
                    <div class="net-salary-subtitle">Ditransfer ke rekening terdaftar pada tanggal pembayaran</div>
                </td>
                <td class="net-salary-amount">
                    Rp {{ number_format($detail->final_salary ?? 0, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Signatures -->
    <table class="signatures">
        <tr>
            <td class="sig-box">
                <div>Diterima Oleh,</div>
                <div class="sig-line">{{ $detail->employee->user->name ?? 'Karyawan' }}</div>
                <div class="sig-title">Karyawan</div>
            </td>
            <td class="sig-space"></td>
            <td class="sig-box">
                <div>Disahkan Oleh,</div>
                <div class="sig-line">Finance & HR Department</div>
                <div class="sig-title">PT Senja Solusi Digital</div>
            </td>
        </tr>
    </table>

    <!-- Footer Note -->
    <div class="footer-note">
        Dokumen ini dibuat dan diterbitkan secara otomatis oleh SenjaHRIS. Kerahasiaan slip gaji ini dilindungi oleh kebijakan privasi perusahaan.
        Dicetak pada: {{ date('d F Y, H:i') }} WIB • Verifikasi Sistem: SENJA-{{ strtoupper(substr(md5($detail->id . ($detail->final_salary ?? 0)), 0, 8)) }}
    </div>

</body>
</html>
