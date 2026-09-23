<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Slip - {{ $staff->full_name }}</title>
    <!-- Guna Tailwind untuk hiasan ringkas -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        /* Sembunyikan butang cetak apabila kertas dicetak keluar */
        @media print {
            .no-print { display: none; }
            body { background: white; padding: 0; }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans p-8">

    <!-- Butang Kawalan Di Atas Skrin (no-print) -->
    <div class="max-w-2xl mx-auto mb-6 flex justify-between items-center no-print">
        <a href="javascript:window.close();" class="text-sm font-semibold text-gray-600 hover:text-gray-900">← Close Window</a>
        <button onclick="window.print();" class="px-5 py-2 bg-purple-800 text-white font-medium rounded-md shadow hover:bg-purple-900 transition cursor-pointer">
             Click to Print / Save PDF
        </button>
    </div>

    <!-- Kotak Slip Gaji / Profil Rasmi -->
    <div class="max-w-2xl mx-auto bg-white border border-gray-300 p-8 shadow-sm rounded-lg">
        
        <!-- Header Kedai -->
        <div class="text-center border-b pb-4 mb-6">
            <h1 class="text-2xl font-bold tracking-wide" style="color: #4A154B;">ZAUJATI LAUNDRY</h1>
            <p class="text-xs text-gray-500 mt-1">Premium Cleaning & Fabric Care Services</p>
            <p class="text-xs text-gray-400">Automated Staff Statement & Salary Voucher</p>
        </div>

        <!-- Maklumat Utama Slip -->
        <div class="mb-6 bg-gray-50 p-4 rounded-md border border-gray-100 flex justify-between items-center">
            <div>
                <p class="text-xs uppercase tracking-wider text-gray-400 font-bold">Statement For</p>
                <p class="text-lg font-bold text-gray-800">{{ $staff->full_name }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs uppercase tracking-wider text-gray-400 font-bold">Date Generated</p>
                <p class="text-sm font-semibold text-gray-700">{{ date('d M Y') }}</p>
            </div>
        </div>

        <!-- Jadual Perincian Maklumat -->
        <div class="border border-gray-200 rounded-md overflow-hidden mb-8">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200 text-xs font-bold text-gray-600 uppercase">
                        <th class="p-3">Description Field</th>
                        <th class="p-3 text-right">Details</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    <tr>
                        <td class="p-3 font-medium text-gray-500">Employee Staff ID</td>
                        <td class="p-3 text-right font-mono font-bold text-gray-900">{{ $staff->staff_id }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium text-gray-500">Assigned Shop Position</td>
                        <td class="p-3 text-right font-semibold text-purple-700">{{ $staff->position }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-medium text-gray-500">Contact Number</td>
                        <td class="p-3 text-right">{{ $staff->phone_number }}</td>
                    </tr>
                    <tr class="bg-purple-50/50">
                        <td class="p-3 font-bold text-gray-800">Basic Rate / Salary (RM)</td>
                        <td class="p-3 text-right text-lg font-bold text-purple-900">
                            RM {{ number_format($staff->salary_rate, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Ruangan Tandatangan Pengesahan -->
        <div class="mt-16 flex justify-between text-center">
            <div class="w-48">
                <div class="border-b border-gray-400 h-12"></div>
                <p class="text-xs mt-2 font-medium text-gray-500">Employee Signature</p>
            </div>
            <div class="w-48">
                <div class="border-b border-gray-400 h-12"></div>
                <p class="text-xs mt-2 font-medium text-gray-500">Manager / Authorized Sign</p>
            </div>
        </div>

        <!-- Footer Notis -->
        <div class="text-center text-[10px] text-gray-400 mt-12 pt-4 border-t border-gray-100">
            This is a computer-generated voucher. No signature is required for electronic validation.
        </div>

    </div>

</body>
</html>