@include('components.header')
@include('components.sidebar')

<div class="main-container">

    <div class="page-header">
        <h1>Daily Work Report</h1>

        <div class="page-actions">
            <a href="#add-report-form" class="btn-primary">
                <i class="fas fa-plus"></i> Add Work Report
            </a>
        </div>
    </div>
    @if(session('error'))
    <div class="alert alert-danger">
        <span>{{ session('error') }}</span>
        <button type="button" onclick="this.parentElement.remove()">×</button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success">
        <span>{{ session('success') }}</span>
        <button type="button" onclick="this.parentElement.remove()">×</button>
    </div>
    @endif


    <div class="table-container">

        <table class="data-table">

            <thead>
                <tr>
                    <th>Date</th>
                    <th>Employee</th>
                    <th>Project</th>
                    <th>Task</th>
                    <th>Hours</th>
                    <th>Attachment</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Reviewed By</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($reports as $report)

                <tr>

                    <td>{{ \Carbon\Carbon::parse($report->report_date)->format('d M Y') }}</td>

                    <td>{{ $report->member->full_name ?? '-' }}</td>

                    <td>{{ $report->project->project_name ?? '-' }}</td>

                    <td>{{ $report->task->task_name ?? $report->task_title }}</td>

                    <td>{{ $report->hours_worked }} Hours</td>

                    <td>
                        @if($report->attachment)
                        <a href="{{ asset('work-reports/'.$report->attachment) }}" target="_blank">
                            View
                        </a>
                        @else
                        -
                        @endif
                    </td>

                    <td>
                        <span class="status-badge {{ strtolower($report->status) }}">
                            {{ $report->status }}
                        </span>
                    </td>

                    <td>{{ $report->remarks ?? '-' }}</td>

                    <td>{{ $report->reviewed_by ?? '-' }}</td>

                    <td>

                        <a href="{{ route('daily-work-report.review', $report->id) }}">
                            View
                        </a>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="10" style="text-align:center;">No reports found</td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>



    <div class="form-section" id="add-report-form">

        <h2>Add Daily Work Report</h2>

        <form action="{{ route('daily-work-report.store') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="grid-container" style="grid-template-columns: 1fr 1fr;">

                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="date" required>
                </div>

                <div class="form-group">
                    <label>Project</label>
                    <select name="project" required>

                        <option value="">Select Project</option>

                        @foreach($projects as $project)
                        <option value="{{ $project->id }}">
                            {{ $project->project_name }}
                        </option>
                        @endforeach

                    </select>
                </div>

                <div class="form-group">
                    <label>Task Title</label>
                    <input type="text" name="task_title" required>
                </div>

                <div class="form-group">
                    <label>Hours Worked</label>
                    <input type="number" name="hours_worked" required>
                </div>

            </div>

            <div class="form-group">
                <label>Work Description</label>
                <textarea name="description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label>Attachment</label>
                <input type="file" name="attachment">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Submit Report</button>
            </div>

        </form>

    </div>

</div>