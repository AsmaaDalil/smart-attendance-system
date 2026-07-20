<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #26332f; font-size: 11px; }
        h1 { color: #184d42; margin-bottom: 4px; }
        .meta { color: #66736e; margin-bottom: 20px; }
        .summary { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .summary td { border: 1px solid #dce6e2; padding: 9px; text-align: center; }
        table.records { width: 100%; border-collapse: collapse; }
        .records th { background: #184d42; color: white; padding: 9px; text-align: left; }
        .records td { border-bottom: 1px solid #e2e8e5; padding: 8px; }
        .arabic { direction: rtl; text-align: right; }
    </style>
</head>
<body>
    <h1>Smart Attendance - Professor Report</h1>
    <div class="meta">
        Professor: {{ $shapeArabic(auth()->user()->name) }} |
        Subject: {{ $subject ? $shapeArabic($subject->subject_name) : 'All Subjects' }} |
        Generated: {{ now()->format('Y-m-d H:i') }}
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
                <th>Date</th>
                <th>Distance</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $record)
                <tr>
                    <td class="arabic">{{ $shapeArabic($record->student->user->name) }}</td>
                    <td>{{ $record->student->university_number }}</td>
                    <td class="arabic">{{ $shapeArabic($record->session->subject->subject_name) }}</td>
                    <td>
                        {{ $record->session->lecture_number }} -
                        <span class="arabic">{{ $shapeArabic($record->session->lecture_title) }}</span>
                    </td>
                    <td>{{ $record->status }}</td>
                    <td>{{ $record->session->start_time?->format('Y-m-d H:i') }}</td>
                    <td>{{ $record->distance_meters !== null ? $record->distance_meters.' m' : '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="7">No records found.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>