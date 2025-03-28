<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Transactions</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Transactions</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<style>
    th,
    td {
        text-align: left !important;
    }
</style>
<!-- end page title -->
<!-- end page title -->
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="d-flex justify-content-between">
                        <div class="">
                            <h4 class="mb-sm-0">Wallet Transactions</h4>
                        </div>
                        <div class="">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop">Add New</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped" id="transactionTable">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Account ID</th>
                                    <th>Merchant Name</th>
                                    <th>Merchant Email</th>
                                    <th>Transaction ID</th>
                                    <th>Amount</th>
                                    <th>Charge</th>
                                    <th>UTR</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($transactions)
                                    @foreach ($transactions as $trx)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $trx->acc_id }}</td>
                                            <td>{{ $trx->merchant_name }}</td>
                                            <td>{{ $trx->merchant_email }}</td>
                                            <td>{{ $trx->transaction_id }}</td>
                                            <td>₹{{ $trx->amount }}</td>
                                            <td>₹{{ $trx->charge }}</td>
                                            <td>
                                                @php
                                                    $data = json_decode($trx->response_data);
                                                @endphp
                                                {{ $data->data->utr ?? 'N/A' }}
                                            </td>
                                            <td style="text-transform: capitalize;">{{ $trx->type }}</td>
                                            <td style="text-transform: capitalize;">{{ $trx->status }}</td>
                                            <td>{{ date('d M Y h:i:s A', strtotime($trx->created_at)) }}</td>
                                            <td>
                                                <button type="button" class="btn btn-info trx-edit" data-id="{{ $trx->transaction_id }}">Edit</button>
                                                @if ($trx->visibility === 'visible')
                                                    <button type="button" class="btn btn-warning trx-hide"
                                                        data-id="{{ $trx->transaction_id }}">Hide</button>
                                                @else
                                                    <button type="button" class="btn btn-info trx-show"
                                                        data-id="{{ $trx->transaction_id }}">Show</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endisset
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Account ID</th>
                                    <th>Merchant Name</th>
                                    <th>Merchant Email</th>
                                    <th>Transaction ID</th>
                                    <th>Amount</th>
                                    <th>Charge</th>
                                    <th>UTR</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <h4 class="mb-sm-0">API Transactions</h4>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped" id="apiTransactionTable">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Order ID</th>
                                    <th>Gateway</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($apiTransactions)
                                    @foreach ($apiTransactions as $trx)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $trx->order_id }}</td>
                                            <td style="text-transform: capitalize;">{{ $trx->gateway }}</td>
                                            <td style="text-transform: capitalize;">{{ $trx->trx_type }}</td>
                                            <td style="text-transform: capitalize;">{{ $trx->status }}</td>
                                            <td>{{ date('d M Y h:i:s A', strtotime($trx->created_at)) }}</td>
                                            <td>
                                                <a href="#" class="btn btn-outline-primary btn-sm">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endisset
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Order ID</th>
                                    <th>Gateway</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editTransaction" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="editTransactionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen">
        <form class="modal-content" method="POST" action="{{url('/admin/transaction/update')}}">
            @csrf
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editTransactionLabel">Edit Transaction</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="merchant_name" id="e_merchant_name" class="form-control" placeholder="" readonly>
                            <label for="e_merchant_name">Merchant Name</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="acc_id" id="e_account_id" class="form-control" placeholder="" readonly>
                            <label for="e_account_id">Account Id</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="transaction_id" id="e_transaction_id" class="form-control" placeholder="" readonly>
                            <label for="e_transaction_id">Transaction ID</label>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-floating">
                            <textarea class="form-control w-100" style="height: fit-content!important;" name="request_payload" id="e_request_payload" cols="30" rows="10" placeholder=""></textarea>
                            <label for="e_request_payload">Request Payload</label>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-floating">
                            <textarea class="form-control w-100" style="height: fit-content!important;" name="response_payload" id="e_response_payload" cols="30" rows="10" placeholder=""></textarea>
                            <label for="e_response_payload">Response Payload</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="amount" id="e_amount" class="form-control" placeholder="">
                            <label for="e_amount">Amount</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="charge" id="e_charge" class="form-control" placeholder="">
                            <label for="e_charge">Charge</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="type" id="e_type" class="form-control" placeholder="">
                                <option value="">Select</option>
                                <option value="payin">payin</option>
                                <option value="payout">payout</option>
                            </select>
                            <label for="e_type">Type</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="transaction_status" id="e_transaction_status" class="form-control" placeholder="">
                                <option value="">Select</option>
                                <option value="successful">successful</option>
                                <option value="initiated">initiated</option>
                                <option value="pending">pending</option>
                                <option value="processing">processing</option>
                                <option value="queued">queued</option>
                                <option value="failed">failed</option>
                                <option value="expired">expired</option>
                            </select>
                            <label for="e_transaction_status">Transaction Status</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="wallet_transaction_status" id="e_wallet_transaction_status" class="form-control" placeholder="">
                                <option value="">Select</option>
                                <option value="successful">successful</option>
                                <option value="completed">completed</option>
                                <option value="initiated">initiated</option>
                                <option value="pending">pending</option>
                                <option value="processing">processing</option>
                                <option value="queued">queued</option>
                                <option value="failed">failed</option>
                                <option value="expired">expired</option>
                            </select>
                            <label for="e_wallet_transaction_status">Wallet Transaction Status</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#transactionTable").DataTable({
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "pageLength": -1
        });
        $("#apiTransactionTable").DataTable({
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "pageLength": -1
        });
        // $('#editTransaction').modal('show');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('click', '.trx-hide', function() {
            const transaction_id = $(this).attr('data-id');
            const visibility = "hidden";
            $.post("{{ url('/admin/transaction/visibility/update') }}", {
                transaction_id: transaction_id,
                visibility: visibility
            }, function(res) {
                if (res.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        html: res.message
                    }).then(() => {
                        location.reload();
                    });
                }
            }).fail(function(error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: error.responseJSON.message
                });
            });
        });
        $(document).on('click', '.trx-show', function() {
            const transaction_id = $(this).attr('data-id');
            const visibility = "visible";
            $.post("{{ url('/admin/transaction/visibility/update') }}", {
                transaction_id: transaction_id,
                visibility: visibility
            }, function(res) {
                if (res.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        html: res.message
                    }).then(() => {
                        location.reload();
                    });
                }
            }).fail(function(error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: error.responseJSON.message
                });
            });
        });
        $(document).on('click', '.trx-edit', function() {
            const transaction_id = $(this).attr('data-id');
            $.post("{{ url('/admin/transaction/fetch') }}",{
                transaction_id: transaction_id
            },function(res){
                if(res.status){
                    $('#editTransaction').modal('show');
                    $('#e_merchant_name').val(res.transaction.merchant_name);
                    $('#e_account_id').val(res.transaction.acc_id);
                    $('#e_transaction_id').val(res.transaction.order_id);
                    $('#e_request_payload').val(res.transaction.request_payload);
                    $('#e_response_payload').val(res.transaction.response_payload);
                    $('#e_amount').val(res.transaction.amount);
                    $('#e_charge').val(res.transaction.charge);
                    $('#e_type').val(res.transaction.type);
                    $('#e_transaction_status').val(res.transaction.transaction_status);
                    $('#e_wallet_transaction_status').val(res.transaction.wallet_transaction_status);
                    $('#e_remarks').val(res.transaction.remarks);
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: res.message
                    });
                }
            }).fail(function(error){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: error.responseJSON.message
                });
            });
        });
    });
</script>
