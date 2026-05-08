<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Users List</title>
    <style>
        /**
         * PHASE 1: PAGE ARCHITECTURE & A4 STANDARDIZATION
         * OBJECTIVE: Force exact physical dimensions to ensure cross-browser print consistency.
         * CONFIGURATION: Sets portrait orientation and a 15mm safety margin for industrial printers.
         */
        @page { 
            size: A4 portrait; 
            margin: 15mm; 
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
            background: white;
        }

        /* Header styling */
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h2 { 
            margin: 0 0 5px 0; 
            text-transform: uppercase; 
        }
        .header p { 
            margin: 0; 
            color: #555; 
        }

        /* Data Table Styling */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th, 
        .data-table td {
            border: 1px solid #999;
            padding: 8px 10px;
            text-align: left;
            vertical-align: middle;
        }
        .data-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }

        .text-center { 
            text-align: center !important; 
        }

        /* Print Floating Button */
        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 12px 24px;
            background: #0d6efd;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 6px 12px rgba(0,0,0,0.2);
            z-index: 1000;
        }

        /* Hide elements when actually printing */
        @media print { 
            .no-print { display: none !important; } 
        }
    </style>
</head>
<body>
    
    {{-- 
      PHASE 2: INTERACTIVE PRINT CONTROLS
      OBJECTIVE: Provide a manual trigger for users while ensuring the button does not appear on the physical paper.
      PROCEDURE: Utilizes the .no-print utility class to exclude the element during the print spooling phase.
    --}}
    <button onclick="window.print()" class="print-btn no-print">🖨️ Print Document</button>

    {{-- 
      PHASE 3: REPORT METADATA & BRANDING
      OBJECTIVE: Establish document authenticity and audit trailing.
      PROCEDURE: Dynamically injects the generation timestamp to track the report's data freshness.
    --}}
    <div class="header">
        <h2>System Users List</h2>
        <p>Generated on {{ now()->format('d M Y, h:i A') }}</p>
    </div>

    {{-- 
      PHASE 4: TABULAR DATA INGESTION
      OBJECTIVE: Present the full system directory in a high-readability grid format.
      RELATIONAL MAPPING: Resolves user roles and departments while highlighting Device IDs for biometric verification.
    --}}
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Department</th>
                <th class="text-center">Device ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role->name ?? 'N/A' }}</td>
                    <td>{{ $user->department->name ?? 'N/A' }}</td>
                    <td class="text-center">
                        @if($user->device_user_id)
                            <strong>{{ $user->device_user_id }}</strong>
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- 
      PHASE 5: AUTOMATED TRANSACTION TRIGGER (JS)
      OBJECTIVE: Streamline the administrative workflow by initiating the print dialog on page load.
      SAFETY: Implements a 500ms delay to ensure all assets and fonts are fully rendered before spooling.
    --}}
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>