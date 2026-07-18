<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Attendance Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #24342f;
            font-size: 11px;
        }

        h1 {
            color: #184d42;
            margin-bottom: 4px;
        }

        .meta {
            color: #66756f;
            margin-bottom: 20px;
        }

        .summary {
            width: 100%;
            margin-bottom: 20px;
        }

        .summary td {
            padding: 9px;
            border: 1px solid #dce5e1;
            text-align: center;
        }

        table.records {
            width: 100%;
            border-collapse: collapse;
        }

        .records th {
            background: #184d42;
            color: white;
            padding: 8px;
            text-align: left;
        }

        .records td {
            padding: 8px;
            border-bottom: 1px solid #dce5e1;
        }
    </style>
</head>

<body>

    <h1>Smart Attendance Report</h1>

    <div class="meta">
        Subject:
        {{ $subject?->subject_name ?? 'All Subjects' }}
        |
        Generated:
        {{ now()->format('Y-m-d H:i') }}
    </div>

    <table class="summary">
        <tr>
            <td>Total<br><strong>{{ $summary['total'] }}</strong></td>
            <td>Present<br><strong>{{ $summary['present'] }}</strong></td>
            <td>Late<br><strong>{{ $summary['late'] }}</strong></td>
            <td>Absent<br><strong>{{ $summary['absent'] }}</strong></td>
            <td>Excused<br><strong>{{ $summary['excused'] }}</strong></td>
            <td>Rate<br><strong>{{ $summary['attendance_rate'] }}%</strong></td>
        </tr>
    </table>

    <table class="records">
        <thead>
            <tr>
                <th>Student</th>
                <th>University No.</th>
                <th>Subject</th>
                <th>Lecture</th>
                <th>Status</th>
                <th>Scanned At</th>
                <th>Distance</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($records as $record)
                <tr>
                    <td>{{ $record->student->user->name }}</td>
                    <td>{{ $record->student->university_number }}</td>
                    <td>{{ $record->session->subject->subject_name }}</td>
                    <td>{{ $record->session->lecture_title }}</td>
                    <td>{{ $record->status }}</td>
                    <td>
                        {{ $record->scanned_at?->format('Y-m-d H:i:s') ?? 'Not scanned' }}
                    </td>
                    <td>
                        {{ $record->distance_meters ?? 'Not recorded' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>