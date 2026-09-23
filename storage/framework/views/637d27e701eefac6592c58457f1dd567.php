<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip_<?php echo e($payroll->full_name); ?>_<?php echo e($payroll->month_year); ?></title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; margin: 0; padding: 20px; background: #f8fafc; }
        .payslip-box { max-width: 800px; margin: auto; padding: 36px; background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06); }
        .header { text-align: center; margin-bottom: 24px; }
        .header h1 { margin: 0; color: #4A154B; font-size: 26px; letter-spacing: 1px; font-weight: 800; }
        .header p { margin: 5px 0; color: #6b7280; font-size: 13px; }
        .header .voucher-label { font-weight: 700; color: #E11D74; margin-top: 6px; letter-spacing: 0.5px; }
        .divider { border-top: 2px solid #E11D74; margin: 18px 0; }
        .divider-dashed { border-top: 1px dashed #d1d5db; margin: 18px 0; }
        .meta-table, .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .meta-table td { padding: 6px; font-size: 14px; }
        .meta-label { color: #6b7280; font-weight: 600; width: 20%; }
        .meta-value { font-weight: 500; color: #1f2937; }
        .meta-value.uppercase { text-transform: uppercase; }
        .meta-value.accent { color: #4A154B; font-weight: 700; }
        .meta-value.mono { font-family: monospace; font-weight: 700; }
        .data-table { border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb; }
        .data-table th { background-color: #4A154B; color: #fff; text-align: left; padding: 10px 12px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        .data-table td { padding: 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .text-right { text-align: right; }
        .text-danger { color: #dc2626; }
        .text-success { color: #16a34a; }
        .total-row { background-color: #f5f3ff; font-weight: 700; font-size: 16px !important; color: #4A154B; }
        .footer-note { text-align: center; margin-top: 32px; font-size: 12px; color: #9ca3af; }
        .print-btn { background-color: #4A154B; color: #fff; padding: 10px 24px; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; }
        .print-btn:hover { background-color: #5c1b5e; }
        @media print {
            body { padding: 0; background: #fff; }
            .payslip-box { border: none; box-shadow: none; padding: 10px; border-radius: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="payslip-box">
        <div class="header">
            <img src="<?php echo e(asset('images/zaujati-logo.png')); ?>" alt="Zaujati Laundry" style="width: 72px; height: 72px; object-fit: contain; margin-bottom: 8px; background: #0f172a; padding: 4px; border-radius: 16px;">
            <h1>Zaujati Laundry</h1>
            <p>Premium Cleaning &amp; Fabric Care Services</p>
            <p class="voucher-label">MONTHLY SALARY VOUCHER</p>
        </div>

        <div class="divider"></div>

        <table class="meta-table">
            <tr>
                <td class="meta-label">Employee Name:</td>
                <td class="meta-value uppercase"><?php echo e($payroll->full_name); ?></td>
                <td class="meta-label">Statement For:</td>
                <td class="meta-value accent"><?php echo e($payroll->month_year); ?></td>
            </tr>
            <tr>
                <td class="meta-label">Staff ID:</td>
                <td class="meta-value mono"><?php echo e($payroll->staff_id); ?></td>
                <td class="meta-label">Date Generated:</td>
                <td class="meta-value"><?php echo e(date('d M Y', strtotime($payroll->created_at))); ?></td>
            </tr>
            <tr>
                <td class="meta-label">Position:</td>
                <td class="meta-value"><?php echo e($payroll->position); ?></td>
                <td class="meta-label">Contact No:</td>
                <td class="meta-value"><?php echo e($payroll->phone_number); ?></td>
            </tr>
        </table>

        <div class="divider-dashed"></div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Earnings (RM)</th>
                    <th class="text-right">Deductions (RM)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Basic Salary Rate</td>
                    <td class="text-right"><?php echo e(number_format($payroll->basic_salary, 2)); ?></td>
                    <td class="text-right">&mdash;</td>
                </tr>
                <tr>
                    <td>Overtime Allowance <span style="font-size: 12px; color: #9ca3af;">(<?php echo e($payroll->ot_hours); ?> Hours)</span></td>
                    <td class="text-right text-success">+<?php echo e(number_format($payroll->ot_pay, 2)); ?></td>
                    <td class="text-right">&mdash;</td>
                </tr>
                <tr>
                    <td>EPF / KWSP Contribution (11%)</td>
                    <td class="text-right">&mdash;</td>
                    <td class="text-right text-danger"><?php echo e(number_format($payroll->epf_deduction, 2)); ?></td>
                </tr>
                <tr>
                    <td>SOCSO / PERKESO Contribution</td>
                    <td class="text-right">&mdash;</td>
                    <td class="text-right text-danger"><?php echo e(number_format($payroll->socso_deduction, 2)); ?></td>
                </tr>
                <tr>
                    <td>EIS / SIP Contribution (0.2%)</td>
                    <td class="text-right">&mdash;</td>
                    <td class="text-right text-danger"><?php echo e(number_format($payroll->eis_deduction, 2)); ?></td>
                </tr>
                <tr class="total-row">
                    <td>Net Salary (Payout)</td>
                    <td colspan="2" class="text-right" style="font-size: 18px;">RM <?php echo e(number_format($payroll->net_salary, 2)); ?></td>
                </tr>
            </tbody>
        </table>

        <p class="footer-note">This is a computer-generated salary voucher from Zaujati Laundry's automated system. No signature required.</p>

        <div class="text-right no-print" style="margin-top: 24px; display: flex; justify-content: flex-end; gap: 12px; align-items: center;">
            <a href="javascript:history.back()" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border: 1px solid #d1d5db; background: #fff; color: #374151; border-radius: 8px; font-weight: 600; font-size: 14px; text-decoration: none; cursor: pointer;">
                &larr; Back
            </a>
            <button onclick="window.print();" class="print-btn">Print Payslip</button>
        </div>
    </div>

</body>
</html><?php /**PATH C:\laragon\www\laundrystaff-pro\resources\views/staff/print_payroll.blade.php ENDPATH**/ ?>