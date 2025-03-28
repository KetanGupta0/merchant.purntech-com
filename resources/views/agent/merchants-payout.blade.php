<link rel="stylesheet" href="{{ asset('assets/libs/dropzone/dropzone.css') }}" type="text/css" />
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

    .button {
        background: #4096ff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 15px;
        width: 100%;
        text-align: center;
    }

    .top-cards {
        margin-top: -50px;
        z-index: 3;
        border-radius: 50px;
    }

    .button:hover {
        background-color: bl;
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

    .progress-container {
        width: 100%;
        background-color: #ddd;
        border-radius: 50px;
        overflow: hidden;
        height: 10px;
    }

    .progress-bar {
        width: 100%;
        height: 100%;
        background-color: blue;
        transition: width 1s linear;
    }

    .copy-icon {
        cursor: pointer;
        color: blue;
        margin-left: 5px;
    }
</style>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Merchant Payout</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Merchant Payout</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
<!-- header section -->
<div class="container-fluid">
    <div class="header  ">
        <div class="dashboard-header">
            <div class="fw-bold fs-5 text-capitalize">{{$merchantName}}</div>
            <button class="dashboard-button btn">Your Commission -  @if (isset($agentPayoutCommision))
                    ₹{{ sprintf('%.2f', $agentPayoutCommision) }}
                @else
                    ₹0.00
                @endif </button>
        </div>
    </div>
</div>
<!-- end -->
<!-- card section -->
<div class="container-fluid  top-cards">
    <div class="row ">
        <div class="col-12 col-md-6 col-lg-3 ">
            <div class="card shadow-sm ">
                <div class="card-body text-center">
                    <p class="card-title fs-6">Total Amount</p>
                    <p class="card-text  fs-4">
                        @if (isset($totalSuccessTransactions))
                            ₹{{ sprintf('%.2f', $totalSuccessTransactions) }}
                        @else
                            ₹0.00
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3 ">
            <div class="card shadow-sm ">
                <div class="card-body text-center">
                    <p class="card-title fs-6  ">Successful Amount</p>
                    <p class="card-text text-success  fs-4">
                        @if (isset($totalSuccessTransactions))
                            ₹{{ sprintf('%.2f', $totalSuccessTransactions) }}
                        @else
                            ₹0.00
                        @endif
                    </p>

                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3 ">
            <div class="card shadow-sm ">
                <div class="card-body text-center">
                    <p class="card-title fs-6">Failed Amount</p>
                    <p class="card-text text-danger  fs-4">
                        @if (isset($totalFailedTransactions))
                            ₹{{ sprintf('%.2f', $totalFailedTransactions) }}
                        @else
                            ₹0.00
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="  col-12 col-md-6 col-lg-3 ">
            <div class="card shadow-sm ">
                <div class="card-body text-center">
                    <p class="card-title fs-6">Processing Amount</p>
                    <p class="card-text text-warning  fs-4 ">
                        @if (isset($totalProcessingTransactions))
                            ₹{{ sprintf('%.2f', $totalProcessingTransactions) }}
                        @else
                            ₹0.00
                        @endif
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- card end -->

<!-- outer div  -->
<div class="container-fluid mt-4">
    <div class="card shadow-lg p-3">
        <div class="card-body ">
            {{-- <!-- Filters and Actions  -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                    <h5 class="card-title">Payout Transactions</h5>
                    <div class="d-flex align-items-center border rounded px-2 py-1">
                        <input type="date" class="border-0 form-control form-control-sm" placeholder="Start Date">
                        <span class="mx-2">→</span>
                        <input type="date" class="border-0 form-control form-control-sm" placeholder="End Date">
                        <i class="bi bi-calendar text-muted"></i>
                    </div>
                    <button class="btn btn-light  w-auto">Export Excel</button>
                    <button class="btn btn-secondary">Auto Status Updates: On</button>
                </div>
                <!-- end fliters --> --}}
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
                                            <th>UTR</th>
                                            <th>Type</th>
                                            <th>Account Number</th>
                                            <th>IFSC</th>
                                            <th>Beneficiary Name</th>
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
                                                    <td>{{ $trx->transaction_id }}</td>
                                                    <td>₹{{ $trx->amount }}</td>
                                                    <td>₹{{ $trx->charge }}</td>
                                                    <td>
                                                        @php
                                                            $req_data = json_decode($trx->request_data);
                                                        @endphp
                                                        @php
                                                            $firstDecode = json_decode($trx->response_data, true);
                                                            if (is_string($firstDecode)) {
                                                                $data = json_decode($firstDecode, false);
                                                            } else {
                                                                $data = json_decode($trx->response_data, false); // Ensure it's an object
                                                            }
                                                        @endphp

                                                        @if (isset($data->data->utr))
                                                            {{ $data->data->utr }}
                                                        @elseif (isset($data->utr))
                                                            {{ $data->utr }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td style="text-transform: capitalize;">
                                                        {{ $trx->type == 'credit' ? 'Payin' : 'Payout' }}</td>
                                                    <td style="text-transform: capitalize;">
                                                        {{ $req_data->account_number ?? ($req_data->payload->account_number ?? 'N/A') }}
                                                    </td>
                                                    <td style="text-transform: capitalize;">
                                                        {{ $req_data->bank_ifsc ?? ($req_data->payload->bank_ifsc ?? 'N/A') }}
                                                    </td>
                                                    <td style="text-transform: capitalize;">
                                                        {{ $req_data->bene_name ?? ($req_data->payload->bene_name ?? 'N/A') }}
                                                    </td>
                                                    <td style="text-transform: capitalize;">{{ $trx->status }}</td>
                                                    <td>{{ date('d M Y h:i:s A', strtotime($trx->created_at)) }}</td>
                                                    <td>
                                                        N/A
                                                    </td>
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
                                            <th>UTR</th>
                                            <th>Type</th>
                                            <th>Account Number</th>
                                            <th>IFSC</th>
                                            <th>Beneficiary Name</th>
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
<script src="{{ asset('assets/libs/dropzone/dropzone-min.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-file-upload.init.js') }}"></script>
<script>
    $(document).ready(function() {
        $("#transactionTable").DataTable({
            "lengthMenu": [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            "pageLength": -1
        });

        $(".copy-icon").on("click", function() {
            let walletText = $("#walletAddress").text(); // Span se text lena
            navigator.clipboard.writeText(walletText).then(() => {
                alert("Wallet address copied: " + walletText);
            }).catch(err => {
                console.error("Failed to copy: ", err);
            });
        });

        let timer; // Global variable for timer

        $("#loadWalletbtn").on("click", function() {
            clearInterval(timer);
            let totalTime = 300;
            let timeLeft = totalTime;
            let progressBar = $(".progress-bar");

            function updateTimer() {
                let minutes = Math.floor(timeLeft / 60);
                let seconds = timeLeft % 60;
                $("#countdown").text(`${minutes}:${seconds < 10 ? "0" + seconds : seconds}`);

                // Update progress bar width
                let percentage = (timeLeft / totalTime) * 100;
                progressBar.css("width", percentage + "%");

                if (timeLeft > 0) {
                    timeLeft--;
                } else {
                    clearInterval(timer);
                }
            }

            timer = setInterval(updateTimer, 1000);
            updateTimer();
        });
    });
</script>

{{-- Added on 12-03-2025 by Ketan --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.6.0/jspdf.plugin.autotable.min.js"></script>
<script>
    $(document).ready(function() {
        var minDate = null;
        var maxDate = null;

        $('#transactionTable tbody tr').each(function() {
            var dateText = $(this).find('td:nth-child(11)').text().trim();
            var dt = new Date(dateText);
            if (!isNaN(dt.getTime())) {
                if (minDate === null || dt < minDate) minDate = dt;
                if (maxDate === null || dt > maxDate) maxDate = dt;
            }
        });

        function formatDateInput(date) {
            return date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date
                .getDate()).slice(-2);
        }

        if (minDate && maxDate) {
            $('#start-date, #end-date').attr('min', formatDateInput(minDate));
            $('#start-date, #end-date').attr('max', formatDateInput(maxDate));
        }

        $('#reportModal button.btn-primary').click(function() {
            var startDateStr = $('#start-date').val();
            var endDateStr = $('#end-date').val();
            var downloadType = $('#downloadas').val();

            if (!startDateStr || !endDateStr) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Please select both start and end dates!"
                });
                return;
            }

            var filteredData = [];
            var headers = [];

            $('#transactionTable thead tr th:not(:last-child)').each(function() {
                headers.push($(this).text().trim());
            });
            filteredData.push(headers);

            $('#transactionTable tbody tr').each(function() {
                var dateText = $(this).find('td:nth-child(11)').text().trim();
                var dt = new Date(dateText);
                var dtOnlyStr = formatDateInput(dt);
                if (dtOnlyStr >= startDateStr && dtOnlyStr <= endDateStr) {
                    var row = [];
                    $(this).find('td:not(:last-child)').each(function() {
                        var text = $(this).text().trim();
                        if (/^[=+\-@]/.test(text)) text = "'" +
                        text; // Prevent CSV Injection
                        row.push(text);
                    });
                    filteredData.push(row);
                }
            });

            if (filteredData.length <= 1) {
                Swal.fire({
                    icon: "warning",
                    title: "No Data",
                    text: "No data available for the selected date range."
                });
                return;
            }

            if (downloadType === "Excel") {
                let csvContent = "\uFEFF"; // UTF-8 BOM for proper rupee symbol encoding
                filteredData.forEach(row => {
                    let formattedRow = row.map(cell => {
                        let text = cell.replace(/"/g, '""'); // Escape quotes
                        return text.includes(",") ? `"${text}"` : text; // Handle commas
                    }).join(",");
                    csvContent += formattedRow + "\n";
                });

                let blob = new Blob([csvContent], {
                    type: 'text/csv;charset=utf-8;'
                });
                let url = URL.createObjectURL(blob);
                let a = document.createElement('a');
                a.href = url;
                a.download = 'payout_report_' + new Date().toISOString().slice(0, 10) + '.csv';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            } else if (downloadType === "PDF") {
                // For PDF: Open a new window with the data in an HTML table and trigger print.
                var printWindow = window.open('', '', 'height=600,width=800');
                printWindow.document.write('<html><head><title>payout_report_' +
                    "{{ date('d-m-Y') }}" + '</title>');
                printWindow.document.write(
                    '<style>table { width: 100%; border-collapse: collapse; } table, th, td { border: 1px solid black; padding: 5px; text-align: left; }</style>'
                );
                printWindow.document.write('</head><body>');
                printWindow.document.write('<h3>Payout Report</h3>');
                printWindow.document.write('<table>');

                // Write header row.
                printWindow.document.write('<tr>');
                $.each(filteredData[0], function(i, header) {
                    printWindow.document.write('<th>' + header + '</th>');
                });
                printWindow.document.write('</tr>');

                // Write data rows.
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
