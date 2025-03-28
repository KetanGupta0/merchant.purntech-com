<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Activity Log</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Activity Log</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
@isset($logs)
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="logTable">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Event Name</th>
                                <th>Event Message</th>
                                <th>Event Date</th>
                                <th>Event User IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$log->log_event_type}}</td>
                                    <td>{{ json_decode($log->log_description)->message ?? 'No message available' }}</td>
                                    <td>{{date('d M Y h:i:s A',strtotime($log->created_at))}}</td>
                                    <td>{{$log->log_ip_address}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endisset
<script>
    $(document).ready(function(){
        $('#logTable').DataTable();
    });
</script>