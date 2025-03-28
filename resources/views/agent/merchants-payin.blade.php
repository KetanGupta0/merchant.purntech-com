<!-- start css file  -->
<style>
    .header {
        /* background: #405289; */
        background: linear-gradient(to bottom, #405289, #6a89cc);
        color: white;
        padding: 10px 10px;
        position: relative;
        text-align: left;
        z-index: -1;
        height: 250px;
    }

    .top-cards {
        margin-top: -50px;
        z-index: 3;
        border-radius: 50px;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        /* Ensures responsiveness */
        padding: 10px;
    }

    .dashboard-header>.dashboard-button {
        border: 1px solid white;
        color: white;
        background: transparent;
        padding: 8px 20px;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }

    .dashboard-button:hover {
        background: white;
        color: #a00000;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .dashboard-header {
            flex-direction: column;
            text-align: center;
        }

        .dashboard-header>.dashboard-button {
            margin-top: 10px;
            width: 100%;
            /* Makes the button full width on small screens */
        }
    }
</style>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Merchant Payin</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Merchant Payin</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="header  ">
        <div class="dashboard-header">
            <div class="fw-bold fs-5 text-capitalize">{{$merchantName}}</div>
            <button class="dashboard-button btn">Your Commission - @if (isset($agentPayinCommision))
                    ₹{{ sprintf('%.2f', $agentPayinCommision) }}
                @else
                    ₹0.00
                @endif </button>
        </div>
    </div>
</div>
<!-- cards section -->
<div class="container-fluid  top-cards">
    <div class="row ">
        <div class="col-12 col-md-6 col-lg-3 ">
            <div class="card shadow-sm ">
                <div class="card-body text-center">
                    <p class="card-title fs-6">Total Transactions</p>
                    <p class="card-text  fs-4">
                        @if (isset($totalTransactions))
                            {{ $totalTransactions }}
                        @else
                            0
                        @endif
                    </p>
                    <p class="card-text text-muted  fs-6">
                        @if (isset($successRate))
                            {{ sprintf('%.2f', $successRate) }}%
                        @else
                            0.00%
                        @endif success rate
                    </p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3 ">
            <div class="card shadow-sm ">
                <div class="card-body text-center">
                    <p class="card-title fs-6  ">Total Amount</p>
                    <p class="card-text text-success  fs-4">
                        @if (isset($totalAmount))
                            ₹{{ sprintf('%.2f', $totalAmount) }}
                        @else
                            ₹0.00
                        @endif
                    </p>
                    <p class="card-text text-muted  fs-6">
                        @if (isset($totalSuccessTransactions))
                            {{ $totalSuccessTransactions }}
                        @else
                            0
                        @endif completed ransactions
                    </p>

                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3 ">
            <div class="card shadow-sm ">
                <div class="card-body text-center">
                    <p class="card-title fs-6">Total Commission</p>
                    <p class="card-text text-danger  fs-4">
                        @if (isset($commision))
                            ₹{{ sprintf('%.2f', $commision) }}
                        @else
                            ₹0.00
                        @endif
                    </p>
                    <p class="card-text text-muted  fs-6">
                        Total Commission
                    </p>
                </div>
            </div>
        </div>
        <div class="  col-12 col-md-6 col-lg-3 ">
            <div class="card shadow-sm ">
                <div class="card-body text-center">
                    <p class="card-title fs-6">Net Balance</p>
                    <p class="card-text text-warning  fs-4 ">
                        @if (isset($netAmount))
                            ₹{{ sprintf('%.2f', $netAmount) }}
                        @else
                            ₹0.00
                        @endif
                    </p>
                    <p class="card-text text-muted  fs-6 ">After commission deduction</p>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- end section outer div -->
<div class="container-fluid mt-3">
    <div class="card shadow-lg">
        <div class="card-body ">
            <!-- end page title -->
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="text-end mb-2">
                                    <button class="btn btn-success rounded-pill" data-bs-toggle="modal"
                                        data-bs-target="#reportModal"> <i class="ri-download-2-line"></i> Download
                                        Report</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped" id="transactionTable">
                                        <thead>
                                            <tr>
                                                <th>S.No.</th>
                                                <th>Transaction ID</th>
                                                <th>Amount</th>
                                                <th>Charge</th>
                                                <th>Net Amount</th>
                                                <th>UTR</th>
                                                <th>Type</th>
                                                <th>Payment Status</th>
                                                <th>Date</th>
                                                <th>Settlement Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @isset($transactions)
                                                @foreach ($transactions as $trx)
                                                    <tr>
                                                        <td>{{ $loop->index + 1 }}</td>
                                                        <td>{{ $trx->transaction_id }}</td>
                                                        <td>₹{{ $trx->amount }}</td>
                                                        <td>₹{{ $trx->charge }}</td>
                                                        <td>₹{{ sprintf('%.2f', (float) $trx->amount - (float) $trx->charge) }}
                                                        </td>
                                                        <td>
                                                            @php
                                                                $data = json_decode($trx->response_data);
                                                            @endphp
                                                            @if ($trx->utr != null)
                                                                {{ $trx->utr }}
                                                            @else
                                                                {{ $data->data->utr ?? 'N/A' }}
                                                            @endif
                                                        </td>
                                                        <td style="text-transform: capitalize;">
                                                            {{ $trx->type == 'credit' ? 'Payin' : 'Payout' }}</td>
                                                        
                                                        <td style="text-transform: capitalize;">{{ $trx->status }}</td>
                                                        <td>{{ date('d M Y h:i:s A', strtotime($trx->created_at)) }}</td>
                                                        <td style="text-transform: capitalize;">{{ $trx->settlement_status ?? "-" }}</td>
                                                    </tr>
                                                @endforeach
                                            @endisset
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>S.No.</th>
                                                <th>Transaction ID</th>
                                                <th>Amount</th>
                                                <th>Charge</th>
                                                <th>Net Amount</th>
                                                <th>UTR</th>
                                                <th>Type</th>
                                                <th>Payment Status</th>
                                                <th>Date</th>
                                                <th>Settlement Status</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->
        </div>
    </div>
</div>

<div id="reportModal" class="modal fade" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center w-100" id="reportModalLabel">Download Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="start-date" class="form-label">Start Date:</label>
                            <input type="date" class="form-control" id="start-date" onfocus="this.showPicker()">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="end-date" class="form-label">End Date:</label>
                            <input type="date" class="form-control" id="end-date" onfocus="this.showPicker()">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label for="downloadas" class="form-label">Download as:</label>
                        <select class="form-select" name="downloadas" id="downloadas">
                            <option value="Excel" selected>Excel</option>
                            <option value="PDF">PDF</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary"> <i class="ri-download-2-line"></i> Download</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script>
    $(document).ready(function() {
        $("#transactionTable").DataTable({
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "pageLength": -1
        });
    });
</script>

{{-- Added on 12-03-2025 by Ketan --}}
<script>
    $(document).ready(function() {
        // 1. Determine the min and max dates from the table data.
        var minDate = null;
        var maxDate = null;

        $('#transactionTable tbody tr').each(function() {
            // Get the date from the 9th column (Date column)
            var dateText = $(this).find('td:nth-child(9)').text().trim();
            var dt = new Date(dateText);
            if (!isNaN(dt.getTime())) {
                if (minDate === null || dt < minDate) {
                    minDate = dt;
                }
                if (maxDate === null || dt > maxDate) {
                    maxDate = dt;
                }
            }
        });

        // Helper function: Format a Date object to 'YYYY-MM-DD'
        function formatDateInput(date) {
            var year = date.getFullYear();
            var month = ('0' + (date.getMonth() + 1)).slice(-2);
            var day = ('0' + date.getDate()).slice(-2);
            return year + '-' + month + '-' + day;
        }

        if (minDate && maxDate) {
            var minDateStr = formatDateInput(minDate);
            var maxDateStr = formatDateInput(maxDate);
            $('#start-date, #end-date').attr('min', minDateStr);
            $('#start-date, #end-date').attr('max', maxDateStr);
        }

        // 2. Handle the Download button click in the modal.
        $('#reportModal button.btn-primary').click(function() {
            var startDateStr = $('#start-date').val();
            var endDateStr = $('#end-date').val();
            var downloadType = $('#downloadas').val(); // "Excel" or "PDF"

            if (!startDateStr || !endDateStr) {
                alert("Please select both start and end dates.");
                return;
            }

            var startDate = new Date(startDateStr);
            var endDate = new Date(endDateStr);

            // 3. Filter table data within the selected date range.
            var filteredData = [];
            // Grab header texts excluding the last column ("Action").
            var headers = [];
            $('#transactionTable thead tr th').each(function() {
                headers.push($(this).text().trim());
            });
            filteredData.push(headers);

            // Loop through each row in the tbody.
            $('#transactionTable tbody tr').each(function() {
                var dateText = $(this).find('td:nth-child(9)').text().trim();
                var dt = new Date(dateText);
                if (!isNaN(dt.getTime())) {
                    // Convert the date to 'YYYY-MM-DD' for comparison.
                    var dtOnlyStr = formatDateInput(dt);
                    if (dtOnlyStr >= startDateStr && dtOnlyStr <= endDateStr) {
                        var row = [];
                        $(this).find('td').each(function() {
                            row.push($(this).text().trim());
                        });
                        filteredData.push(row);
                    }
                }
            });

            if (filteredData.length <= 1) {
                alert("No data available for selected date range.");
                return;
            }

            // 4. Create the file based on the download type.
            if (downloadType === "Excel") {
                var csvContent = "";
                $.each(filteredData, function(i, row) {
                    var escapedRow = row.map(function(cell) {
                        var cellText = cell.replace(/"/g, '""');
                        if (cellText.indexOf(',') !== -1 || cellText.indexOf('"') !== -1) {
                            cellText = '"' + cellText + '"';
                        }
                        return cellText;
                    });
                    csvContent += escapedRow.join(",") + "\n";
                });

                // Prepend BOM to fix the currency symbol encoding.
                var blob = new Blob(["\uFEFF" + csvContent], {
                    type: 'text/csv;charset=utf-8;'
                });
                var url = URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.href = url;
                a.download = 'payin_report_' + "{{ date('d-m-Y') }}" + '.csv';
                document.body.append(a);
                a.click();
                a.remove();
            } else if (downloadType === "PDF") {
                var printWindow = window.open('', '', 'height=600,width=800');
                printWindow.document.write('<html><head><title>payin_report' + "{{ date('d-m-Y') }}" +
                    '</title>');
                printWindow.document.write(
                    '<style>table { width: 100%; border-collapse: collapse; } table, th, td { border: 1px solid black; padding: 5px; text-align: left; }</style>'
                );
                printWindow.document.write('</head><body>');
                printWindow.document.write('<h3>Payin Report</h3>');
                printWindow.document.write('<table>');

                // Write header row.
                printWindow.document.write('<tr>');
                $.each(filteredData[0], function(i, header) {
                    printWindow.document.write('<th>' + header + '</th>');
                });
                printWindow.document.write('</tr>');

                // Write each data row.
                for (var i = 1; i < filteredData.length; i++) {
                    printWindow.document.write('<tr>');
                    $.each(filteredData[i], function(j, cell) {
                        printWindow.document.write('<td>' + cell + '</td>');
                    });
                    printWindow.document.write('</tr>');
                }

                printWindow.document.write('</table>');
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            }
        });
    });
</script>

