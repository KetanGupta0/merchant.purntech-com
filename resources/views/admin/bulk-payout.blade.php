<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Bulk Payout Requests</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Bulk Payout Requests</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="bulk-payout-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Merchant Name</th>
                            <th>Account ID</th>
                            <th>File Name</th>
                            <th>Total Payouts</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="bulk-payout-table-data">
                        @if (isset($bulkPayout))
                            @foreach ($bulkPayout as $bp)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$bp->merchant_name}}</td>
                                    <td>{{$bp->account_id}}</td>
                                    <td><a href="javascript:void(0);" target="blank">{{$bp->file_path}}</a></td>
                                    <td>{{$bp->total_payouts}}</td>
                                    <td style="text-transform: capitalize;">{{$bp->status}}</td>
                                    <td>N/A</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Merchant Name</th>
                            <th>Account ID</th>
                            <th>File Name</th>
                            <th>Total Payouts</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#bulk-payout-table').dataTable();
    });
</script>