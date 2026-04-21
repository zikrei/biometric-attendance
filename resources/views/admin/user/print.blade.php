<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Users List</title>
    <style>
        @page { size: A4 portrait; margin: 15mm; }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
            background: white;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        .header h2 { margin: 0 0 5px 0; text-transform: uppercase; }
        .header p { margin: 0; color: #555; }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table th, .data-table td {
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

        .text-center { text-align: center !important; }
        
        @media print { .no-print { display: none !important; } }
        
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
    </style>
</head>
<body>
    
    <button onclick="window.print()" class="print-btn no-print">🖨️ Print Document</button>

    <div class="header">
        <h2>System Users List</h2>
        <p>Generated on {{ now()->format('d M Y, h:i A') }}</p>
    </div>

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

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>