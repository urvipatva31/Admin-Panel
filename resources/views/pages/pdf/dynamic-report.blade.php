<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #2c3e50;
            font-size: 13px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #4CAF50;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #4CAF50;
        }

        .header p {
            margin: 4px 0;
            font-size: 12px;
            color: #555;
        }

        .section {
            margin-top: 25px;
        }

        .section h3 {
            background: #f4f6f9;
            padding: 10px;
            border-left: 5px solid #4CAF50;
            font-size: 16px;
        }

        .summary {
            margin: 10px 0;
            padding: 10px;
            background: #fafafa;
            border: 1px solid #eee;
        }

        .summary p {
            margin: 5px 0;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th {
            background: #4CAF50;
            color: #fff;
            padding: 8px;
            text-align: left;
            font-size: 13px;
        }

        table td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 12px;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .no-data {
            text-align: center;
            padding: 15px;
            border: 1px dashed #ccc;
            margin-top: 10px;
            color: #888;
            font-style: italic;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #aaa;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <h1>{{ $report->report_name }}</h1>
    <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $report->report_type)) }}</p>
    <p><strong>Date:</strong> {{ $report->start_date }} to {{ $report->end_date }}</p>
</div>

<!-- PROJECTS -->
@if(isset($data['projects']))
<div class="section">
    <h3>Project Summary</h3>

    @if(count($data['projects']) > 0)
        <div class="summary">
            <p><strong>Total:</strong> {{ $data['total_projects'] ?? 0 }}</p>
            <p><strong>Completed:</strong> {{ $data['completed_projects'] ?? 0 }}</p>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Project Name</th>
                <th>Status</th>
            </tr>
            @foreach($data['projects'] as $project)
            <tr>
                <td>{{ $project->id }}</td>
                <td>{{ $project->project_name }}</td>
                <td>{{ ucfirst($project->status) }}</td>
            </tr>
            @endforeach
        </table>
    @else
        <div class="no-data">No project data found for selected dates</div>
    @endif
</div>
@endif

<!-- TASKS -->
@if(isset($data['tasks']))
<div class="section">
    <h3>Task Summary</h3>

    @if(count($data['tasks']) > 0)
        <div class="summary">
            <p><strong>Total:</strong> {{ $data['total_tasks'] ?? 0 }}</p>
            <p><strong>Completed:</strong> {{ $data['completed_tasks'] ?? 0 }}</p>
            <p><strong>Pending:</strong> {{ $data['pending_tasks'] ?? 0 }}</p>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Task</th>
                <th>Status</th>
            </tr>
            @foreach($data['tasks'] as $task)
            <tr>
                <td>{{ $task->id }}</td>
                <td>{{ $task->title }}</td>
                <td>{{ ucfirst($task->status) }}</td>
            </tr>
            @endforeach
        </table>
    @else
        <div class="no-data">No task data found for selected dates</div>
    @endif
</div>
@endif

<!-- ATTENDANCE -->
@if(isset($data['attendances']))
<div class="section">
    <h3>Attendance Summary</h3>

    @if(count($data['attendances']) > 0)
        <div class="summary">
            <p><strong>Total Records:</strong> {{ count($data['attendances']) }}</p>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
            @foreach($data['attendances'] as $att)
            <tr>
                <td>{{ $att->id }}</td>
                <td>{{ $att->attendance_date }}</td>
                <td>{{ ucfirst($att->status) }}</td>
            </tr>
            @endforeach
        </table>
    @else
        <div class="no-data">No attendance data found for selected dates</div>
    @endif
</div>
@endif

<!-- FINANCIAL -->
@if(isset($data['salaries']))
<div class="section">
    <h3>Financial Overview</h3>

    @if(count($data['salaries']) > 0)
        <div class="summary">
            <p><strong>Total Records:</strong> {{ count($data['salaries']) }}</p>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Base Salary</th>
                <th>Total Salary</th>
            </tr>
            @foreach($data['salaries'] as $salary)
            <tr>
                <td>{{ $salary->id }}</td>
                <td>₹{{ $salary->base_salary }}</td>
                <td>₹{{ $salary->total_salary }}</td>
            </tr>
            @endforeach
        </table>
    @else
        <div class="no-data">No financial data found for selected dates</div>
    @endif
</div>
@endif

<!-- FOOTER -->
<div class="footer">
    Generated on {{ now()->format('Y-m-d H:i:s') }}
</div>

</body>
</html>