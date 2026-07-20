<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Attendance Report</title>

    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
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

        .arabic {
            font-family: "DejaVu Sans", sans-serif;
            direction: rtl;
            text-align: right;
        }

        .summary {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
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
            vertical-align: middle;
        }

        .empty-message {
            padding: 30px;
            color: #66756f;
            text-align: center;
        }
    </style>
</head>

<body>

    <h1>Smart Attendance Report</h1>

    <div class="meta">
        Subject:

        @if ($subject)
            <span class="arabic">
                {{ $shapeArabic($subject->subject_name) }}
            </span>
        @else
            All Subjects
        @endif

        |

        Generated:
        {{ now()->format('Y-m-d H:i') }}
    </div>

    <table class="summary">
        <tr>
            <td>
                Total
                <br>
                <strong>{{ $summary['total'] }}</strong>
            </td>

            <td>
                Present
                <br>
                <strong>{{ $summary['present'] }}</strong>
            </td>

            <td>
                Late
                <br>
                <strong>{{ $summary['late'] }}</strong>
            </td>

            <td>
                Absent
                <br>
                <strong>{{ $summary['absent'] }}</strong>
            </td>

            <td>
                Excused
                <br>
                <strong>{{ $summary['excused'] }}</strong>
            </td>

            <td>
                Rate
                <br>
                <strong>
                    {{ $summary['attendance_rate'] }}%
                </strong>
            </td>
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
            @forelse ($records as $record)
                <tr>
                    <td class="arabic">
                        {{ $shapeArabic(
                            $record->student?->user?->name
                            ?? 'Unknown student'
                        ) }}
                    </td>

                    <td>
                        {{
                            $record->student?->university_number
                            ?? '-'
                        }}
                    </td>

                    <td class="arabic">
                        {{ $shapeArabic(
                            $record->session?->subject?->subject_name
                            ?? 'Unknown subject'
                        ) }}
                    </td>

                    <td class="arabic">
                        {{ $shapeArabic(
                            $record->session?->lecture_title
                            ?? 'Unknown lecture'
                        ) }}
                    </td>

                    <td>
                        {{ $record->status }}
                    </td>

                    <td>
                        {{
                            $record->scanned_at?->format(
                                'Y-m-d H:i:s'
                            ) ?? 'Not scanned'
                        }}
                    </td>

                    <td>
                        @if ($record->distance_meters !== null)
                            {{ $record->distance_meters }} m
                        @else
                            Not recorded
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td
                        colspan="7"
                        class="empty-message"
                    >
                        No attendance records found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>