<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Settlement Reports</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Settlement Reports</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-mb-12 text-end mb-3">
                    <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#addSettlementModal">Single Settlement</button>
                    <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#addBulkSettlementModal">Bulk Settlement</button>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped" id="settlement-table">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Transaction ID</th>
                                    <th>Settlement Amount</th>
                                    <th>Merchant Fee</th>
                                    <th>Tax</th>
                                    <th>Bank Fee</th>
                                    <th>Net Amount</th>
                                    <th>UTR Number</th>
                                    <th>VPA</th>
                                    <th>Currency</th>
                                    <th>Settlement Type</th>
                                    <th>Settlement Time</th>
                                    <th>Settlement Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($settlement as $stlmt)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$stlmt->order_id}}</td>
                                        <td>₹{{$stlmt->settlement_amount}}</td>
                                        <td>₹{{$stlmt->merchant_fee}}</td>
                                        <td>{{$stlmt->tax_amount}}%</td>
                                        <td>{{$stlmt->settlement_amount >= 500 ? "" : "₹" }}{{$stlmt->bank_fee}}{{$stlmt->settlement_amount >= 500 ? "%" : "" }}</td>
                                        <td>₹{{$stlmt->net_amount}}</td>
                                        <td>{{$stlmt->utr_number}}</td>
                                        <td>{{$stlmt->upi_id}}</td>
                                        <td>{{$stlmt->currency}}</td>
                                        <td>{{$stlmt->settlement_type}}</td>
                                        <td>{{date('d M Y h:i:s A',strtotime($stlmt->created_at))}}</td>
                                        <td>{{$stlmt->settlement_status}}</td>
                                        <td>N/A</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Transaction ID</th>
                                    <th>Settlement Amount</th>
                                    <th>Merchant Fee</th>
                                    <th>Tax</th>
                                    <th>Bank Fee</th>
                                    <th>Net Amount</th>
                                    <th>UTR Number</th>
                                    <th>VPA</th>
                                    <th>Currency</th>
                                    <th>Settlement Type</th>
                                    <th>Settlement Time</th>
                                    <th>Settlement Status</th>
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
<div class="modal fade" id="addSettlementModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addSettlementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form class="modal-content" method="POST" action="{{url('admin/settlement/manual')}}">
            @csrf
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addSettlementModalLabel">Add Single Settlement</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="transaction_id" id="transaction_id" class="form-control" placeholder="">
                            <label for="transaction_id">Transaction ID <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="settlement_mode" id="settlement_mode" class="form-control" disabled>
                                <option value="">Select</option>
                                <option value="Bank">Bank</option>
                                <option value="Wallet">Wallet</option>
                            </select>
                            <label for="settlement_mode">Settlement Mode <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="settlement_method" id="settlement_method" class="form-control" disabled>
                                <option value="">Select</option>
                                <option value="IMPS">IMPS</option>
                                <option value="NEFT">NEFT</option>
                                <option value="RTGS">RTGS</option>
                                <option value="UPI">UPI</option>
                            </select>
                            <label for="settlement_method">Settlement Method <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select name="settlement_status" id="settlement_status" class="form-control" disabled>
                                <option value="">Select</option>
                                <option value="completed">Completed</option>
                                <option value="failed">Failed</option>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                            </select>
                            <label for="settlement_status">Settlement Status<span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-8 mb-3">
                        <div class="form-floating" id="fail-remark">
                            {{-- <textarea name="failure_reason" id="failure_reason" cols="30" rows="10" class="form-control" placeholder=""></textarea>
                            <label for="failure_reason">Failure Reason <span class="text-danger">*</span></label> --}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h4>Merchant Info & Settlemanet Details</h4>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="merchant_name" id="merchant_name" class="form-control" value="" placeholder="" readonly>
                            <label for="merchant_name">Merchant Name <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="settlement_amount" id="settlement_amount" class="form-control" value="" placeholder="" readonly>
                            <label for="settlement_amount">Settlement Amount <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="merchant_fee" id="merchant_fee" class="form-control" value="" placeholder="" readonly>
                            <label for="merchant_fee">Merchant Fee <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="tax_amount" id="tax_amount" class="form-control" value="" placeholder="" readonly>
                            <label for="tax_amount">Tax Amount <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="tax_type" id="tax_type" class="form-control" value="" placeholder="" readonly>
                            <label for="tax_type">Tax Type <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="bank_fee" id="bank_fee" class="form-control" value="" placeholder="" readonly>
                            <label for="banck_fee">Bank Fee <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="bank_fee_type" id="bank_fee_type" class="form-control" value="" placeholder="" readonly>
                            <label for="banck_fee_type">Bank Fee Type <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="net_amount" id="net_amount" class="form-control" value="" placeholder="" readonly>
                            <label for="net_amount">Net Amount <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="row bank">
                </div>
                <div class="row upi">
                </div>
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                <button type="submit" class="btn btn-primary">Add Settlement</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="addBulkSettlementModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addBulkSettlementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen">
        <form class="modal-content" method="POST" action="#">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addBulkSettlementModalLabel">Add Bulk Settlement</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="date" name="start_date" id="start_date" class="form-control" onclick="showPicker()" placeholder="Start Date">
                            <label for="start_date">Date From</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="date" name="end_date" id="end_date" class="form-control" onclick="showPicker()" placeholder="End Date">
                            <label for="end_date">Date To <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="btn btn-primary rounded-pill">Get Transactions</div>
                    </div>
                </div>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table" id="pending-setllment-table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="settle-all" name="" id="#"></th>
                                    <th>Account ID</th>
                                    <th>Merchant Name</th>
                                    <th>Transaction ID</th>
                                    <th>Amount</th>
                                    <th>Charges</th>
                                    <th>Net Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody id="pending-setllment-data">
                                @if(isset($pendingSettlement))
                                    @foreach ($pendingSettlement as $ps)
                                        <tr>
                                            <td><input type="checkbox" class="settle" name="" id="settle-{{$ps->transaction_id}}"></td>
                                            <td>{{$ps->account_id}}</td>
                                            <td>{{$ps->merchant_name}}</td>
                                            <td>{{$ps->transaction_id}}</td>
                                            <td>{{sprintf('%.2f',$ps->amount)}}</td>
                                            <td>{{sprintf('%.2f',$ps->charge)}}</td>
                                            <td>{{sprintf('%.2f',((float)$ps->amount - (float)$ps->charge))}}</td>
                                            <td>{{date('d M Y h:i:s A', strtotime($ps->transaction_date))}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th><input type="checkbox" class="settle-all" name="" id="#"></th>
                                    <th>Account ID</th>
                                    <th>Merchant Name</th>
                                    <th>Transaction ID</th>
                                    <th>Amount</th>
                                    <th>Charges</th>
                                    <th>Net Amount</th>
                                    <th>Date</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row w-100">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select class="form-control" name="bulk_settlement_mode" id="bulk_settlement_mode" placeholder="">
                                <option value="">Select</option>
                                <option value="Wallet">Wallet</option>
                                <option value="Bank">Bank</option>
                            </select>
                            <label for="bulk_settlement_mode">Settlement Mode</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select class="form-control" name="bulk_settlement_status" id="bulk_settlement_status" placeholder="">
                                <option value="">Select</option>
                                <option value="completed">Completed</option>
                                <option value="failed">Failed</option>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                            </select>
                            <label for="bulk_settlement_status">Settlement Status</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" id="pending-submit" class="btn btn-primary">Process Settlement</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("#settlement-table").DataTable({
            "lengthMenu": [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            "pageLength": -1
        });
        $("#pending-setllment-table").DataTable({
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "pageLength": -1
        });
        $(document).on('blur','#transaction_id',function(){
            const transaction_id = $(this).val();
            if(transaction_id === "" || transaction_id === undefined || transaction_id === null){
                return;
            }
            $.post("{{url('admin/ajax/fetch/transaction')}}",{
                transaction_id: transaction_id
            },function(res){
                if(res.status){
                    const data = res.data;
                    $('#merchant_name').val(data.merchant_name);
                    $('#settlement_amount').val(data.settlement_amount);
                    $('#merchant_fee').val(data.merchant_fee);
                    $('#tax_amount').val(data.tax_amount);
                    $('#tax_type').val(data.tax_type);
                    $('#bank_fee').val(data.bank_fee);
                    $('#bank_fee_type').val(data.bank_fee_type);
                    $('#reserved_amount').val(data.reserved_amount);
                    $('#net_amount').val(data.net_amount);
                    $('#settlement_mode').removeAttr('disabled');
                    $('#settlement_status').removeAttr('disabled');
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: res.message ?? "Something went wrong!"
                    });
                }
            }).fail(function(err){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: err.responseJSON.message ?? "Something went wrong!"
                });
            });
        });
        $(document).on('change','#settlement_mode',function(){
            const transaction_id = $('#transaction_id').val();
            if(transaction_id === "" || transaction_id === undefined || transaction_id === null){
                $(this).val('');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Transaction id is required!'
                });
                return;
            }
            const value = $(this).val();
            if(value === "Bank"){
                $('#settlement_method').removeAttr('disabled');
            }else{
                $('#settlement_method').attr('disabled','disabled');
                $('#settlement_method').val('');
                $('.row.bank').html('');
                $('.row.upi').html('');
            }
        });
        $(document).on('change','#settlement_method',function(){
            const transaction_id = $('#transaction_id').val();
            if(transaction_id === "" || transaction_id === undefined || transaction_id === null){
                $(this).val('');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Transaction id is required!'
                });
                return;
            }
            const value = $(this).val();
            if(value === 'UPI'){
                $('.row.bank').html('');
                $('.row.upi').html(`
                    <h4>UPI details</h4>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="">
                            <label for="bank_name">UPI ID <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="reference_number" id="reference_number" class="form-control" placeholder="">
                            <label for="reference_number">Reference Number <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="utr_number" id="utr_number" class="form-control" placeholder="">
                            <label for="utr_number">UTR Number <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-floating">
                            <textarea name="remarks" id="remarks" cols="30" rows="10" class="form-control" placeholder=""></textarea>
                            <label for="remarks">Remarks</label>
                        </div>
                    </div>
                `);
            }else{
                $('.row.upi').html('');
                $('.row.bank').html(`
                    <h4>Bank details</h4>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="">
                            <label for="bank_name">Bank Name</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="bank_account" id="bank_account" class="form-control" placeholder="">
                            <label for="bank_account">Account Number <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="bank_ifsc" id="bank_ifsc" class="form-control" placeholder="">
                            <label for="bank_ifsc">IFSC <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="reference_number" id="reference_number" class="form-control" placeholder="">
                            <label for="reference_number">Reference Number <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="settlement_type" id="settlement_type" class="form-control" placeholder="">
                            <label for="settlement_type">Settlement Type <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-floating">
                            <textarea name="remarks" id="remarks" cols="30" rows="10" class="form-control" placeholder=""></textarea>
                            <label for="remarks">Remarks</label>
                        </div>
                    </div>
                `);
            }
        });
        $(document).on('change','#settlement_status',function(){
            const transaction_id = $('#transaction_id').val();
            if(transaction_id === "" || transaction_id === undefined || transaction_id === null){
                $(this).val('');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Transaction id is required!'
                });
                return;
            }
            const value = $(this).val();
            if(value === "failed"){
                $('#fail-remark').html(`
                    <textarea name="failure_reason" id="failure_reason" cols="30" rows="10" class="form-control" placeholder=""></textarea>
                    <label for="failure_reason">Failure Reason <span class="text-danger">*</span></label>
                `);
            }else{
                $('#fail-remark').html('');
            }
        });
        $(document).on('change', '.settle-all', function () {
            const check = $(this).prop('checked'); // Get checked state
            $('.settle').prop('checked', check); // Set all checkboxes
            $('.settle-all').prop('checked', check);
        });
        $(document).on('change', '.settle', function () {
            if (!$(this).prop('checked')) { // If any individual checkbox is unchecked
                $('.settle-all').prop('checked', false); // Uncheck "settle-all"
            } else if ($('.settle:checked').length === $('.settle').length) {
                // If all checkboxes are checked, check "settle-all"
                $('.settle-all').prop('checked', true);
            }
        });
        $(document).on('click','#pending-submit',function(){
            let selectedTransactions = [];

            // Loop through each checked transaction checkbox
            $('.settle:checked').each(function () {
                let transactionId = $(this).attr('id').replace('settle-', ''); // Extract ID
                selectedTransactions.push(transactionId);
            });

            // Check if there are selected transactions
            if (selectedTransactions.length === 0) {
                Swal.fire('Warning!', 'Please select at least one transaction.', 'warning');
                return;
            }

            const settlement_mode = $('#bulk_settlement_mode').val();
            const settlement_status = $('#bulk_settlement_status').val();

            if(settlement_mode === "" || settlement_mode === undefined || settlement_mode === null){
                Swal.fire('Warning!', 'Please select settlement mode.', 'warning');
                return;
            }

            if(settlement_status === "" || settlement_status === undefined || settlement_status === null){
                Swal.fire('Warning!', 'Please select settlement status.', 'warning');
                return;
            }

            // Send AJAX request
            $.ajax({
                url: "{{ url('admin/settlement/bulk') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}", // CSRF token for security
                    transactions: selectedTransactions,
                    settlement_mode: settlement_mode,
                    settlement_status: settlement_status
                },
                success: function (response) {
                    console.log(response);

                    if (response.report.length > 0) {
                        let reportHTML = `<table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Transaction ID</th>
                                                    <th>Status</th>
                                                    <th>Message</th>
                                                </tr>
                                            </thead>
                                            <tbody>`;

                        response.report.forEach(transaction => {
                            reportHTML += `<tr>
                                            <td>${transaction.transaction_id}</td>
                                            <td><span class="badge bg-${transaction.status === 'success' ? 'success' : 'danger'}">${transaction.status}</span></td>
                                            <td>${transaction.message}</td>
                                        </tr>`;
                        });

                        reportHTML += `</tbody></table>`;

                        Swal.fire({
                            title: 'Settlement Report',
                            html: reportHTML,
                            icon: 'info',
                            width: '700px'
                        }).then(() => location.reload()); // Reload after closing report
                    } else {
                        Swal.fire('Info', 'No transactions were processed.', 'info');
                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                    Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                }
            });
        });
    });
</script>
