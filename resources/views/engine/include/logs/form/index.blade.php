
@foreach($logs as $log)
    <div class="activity">
        <div class="activity-icon bg-primary text-white shadow-primary">
            <i class="fas fa-sign-out-alt"></i>
        </div>
        <div class="activity-detail">
            <div class="mb-2 d-flex justify-content-between align-items-center">
                <span class="text-job">
                    {{-- 12 hour ago --}}
                    @php
                        $date = \Carbon\Carbon::parse($log['date']);
                        $date = $date->diffForHumans();
                        echo $date;
                    @endphp
                </span>
                <span class="bullet"></span>
                <div class="text-job" >by {{ $log['user'] ?? "" }}</div>
            </div>
            <p class="mb-0" style="font-size: 14px; font-weight: 500; color: #000;">
                {{ $log['description'] ?? "" }}
            </p>
            <span>
                from {{ $log['old'] ?? "" }} to {{ $log['new'] ?? "" }}
            </span>
        </div>
    </div>
@endforeach