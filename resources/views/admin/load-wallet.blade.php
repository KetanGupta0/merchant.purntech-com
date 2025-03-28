<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Load Wallet Requests</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Load Wallet Requests</li>
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
                <table class="table" id="load-wallet-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Merchant Name</th>
                            <th>Account ID</th>
                            <th>Transaction ID</th>
                            <th>Wallet Address</th>
                            <th>Send Amount</th>
                            <th>Get Amount</th>
                            <th>Conversion Rate</th>
                            <th>Tax</th>
                            <th>Charges</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="load-wallet-table-data">
                        @if (isset($loadWallet))
                            @foreach ($loadWallet as $lw)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$lw->merchant_name}}</td>
                                    <td>{{$lw->account_id}}</td>
                                    <td>{{$lw->transaction_id}}</td>
                                    <td>{{$lw->wallet_address}}</td>
                                    <td>{{$lw->send_amount}} {{$lw->send_currency}}</td>
                                    <td>{{$lw->get_amount}} {{$lw->get_currency}}</td>
                                    <td>{{$lw->conversion_rate}}</td>
                                    <td>{{$lw->tax}}</td>
                                    <td>{{$lw->other_charges}}</td>
                                    <td style="text-transform: capitalize;">{{$lw->status}}</td>
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
                            <th>Transaction ID</th>
                            <th>Wallet Address</th>
                            <th>Send Amount</th>
                            <th>Get Amount</th>
                            <th>Conversion Rate</th>
                            <th>Tax</th>
                            <th>Charges</th>
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
        $('#load-wallet-table').dataTable();
    });
</script>