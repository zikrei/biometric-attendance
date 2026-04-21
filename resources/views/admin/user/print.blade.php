<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Users List</title>
    
    {{-- Connect the external app.css file so the print styles are loaded --}}
    {{-- Note: If you use Laravel Vite, replace the <link> below with: @vite(['resources/css/app.css']) --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

{{-- Added the print-layout class to trigger the specific print CSS --}}
<body class="print-layout">
    
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