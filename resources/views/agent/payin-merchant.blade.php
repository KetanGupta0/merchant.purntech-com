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

    table tr {
        vertical-align: middle;
        text-align: start
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
            <h4 class="mb-sm-0">PayIn Reports (Merchants)</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">PayIn Reports (Merchants)</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
{{-- <div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Payout (Merchants)</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Payout (Merchants)</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="header">
        <div class="dashboard-header">
            <div class="fw-bold fs-5">Payout (Merchants)</div>
            <button class="dashboard-button btn">Active Amount ₹0.00 </button>
        </div>
    </div>
</div>
<!-- cards section -->
<div class="container-fluid  top-cards">
    <div class="row ">
        <div class="col-12 col-md-6 col-lg-3 ">
            <div class="card shadow-sm ">
                <div class="card-body text-center">
                    <p class="card-title fs-6">Total Amount</p>
                    <p class="card-text  fs-4">
                        ₹100
                    </p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3 ">
            <div class="card shadow-sm ">
                <div class="card-body text-center">
                    <p class="card-title fs-6  ">Successful Amount</p>
                    <p class="card-text text-success  fs-4">
                        ₹8071.00
                    </p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3 ">
            <div class="card shadow-sm ">
                <div class="card-body text-center">
                    <p class="card-title fs-6">Failed Amount</p>
                    <p class="card-text text-danger  fs-4">
                        ₹311.05
                    </p>
                </div>
            </div>
        </div>
        <div class="  col-12 col-md-6 col-lg-3 ">
            <div class="card shadow-sm ">
                <div class="card-body text-center">
                    <p class="card-title fs-6">Processing Amount</p>
                    <p class="card-text text-warning  fs-4 ">
                        ₹1000
                    </p>
                </div>
            </div>
        </div>
    </div>
</div> --}}


<div class="container-fluid mt-3">
    <div class="card shadow-lg">
        <div class="card-body ">
            <!-- end page title -->
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
                                    <div>
                                        <label for="merchantSelect" class="form-label">Select Merchant</label>
                                        <select class="form-select" name="merchantSelect" id="merchantSelect">
                                            <option>Select</option>
                                            @isset($merchants)
                                                @foreach ($merchants as $m)
                                                    <option value="{{ $m->merchant_id }}">{{ $m->merchant_name }}
                                                        ({{ $m->acc_id }})
                                                    </option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                    <div>
                                        <label for="start_date" class="form-label">Start Date</label>
                                        <input type="date" class="form-control" name="start_date" id="start_date"
                                            onclick="showPicker()">
                                    </div>
                                    <div>
                                        <label for="end_date" class="form-label">End Date</label>
                                        <input type="date" class="form-control" name="end_date" id="end_date"
                                            onclick="showPicker()">
                                    </div>
                                    <div>
                                        <button class="btn btn-primary rounded-pill" id="showTransBtn">Show</button>
                                    </div>
                                    <div class="text-end">
                                        <button class="btn btn-success rounded-pill" data-bs-toggle="modal"
                                            data-bs-target="#reportModal"> <i class="ri-download-2-line"></i> Download
                                            Report</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
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

<script src="{{ asset('assets/libs/dropzone/dropzone-min.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-file-upload.init.js') }}"></script>
<script>
    $(document).ready(function() {
        $("#transactionTable").DataTable();

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

{{-- Added on 20-03-2025 by Chandan --}}
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

        $("#showTransBtn").click(function() {
            let merchant_id = $("#merchantSelect").val();
            let start_date = $("#start_date").val();
            let end_date = $("#end_date").val();

            if (merchant_id === "Select") {
                alert("Please select a merchant.");
                return;
            }

            $.ajax({
                url: "{{ url('agent/fetch-transactions-payin') }}",
                type: "GET",
                data: {
                    merchant_id: merchant_id,
                    start_date: start_date,
                    end_date: end_date
                },
                success: function(response) {
                    if (response.length == 0) {
                        alert("No data Found")
                    }
                    $("#transactionTable").DataTable().destroy();
                    let tableBody = $("#transactionTable tbody");
                    tableBody.empty();

                    if (response.length > 0) {
                        $.each(response, function(index, transaction) {
                            tableBody.append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${transaction.transaction_id}</td>
                                    <td>${transaction.amount}</td>
                                    <td>${transaction.charge}</td>
                                   <td>₹${(parseFloat(transaction.amount) - parseFloat(transaction.charge)).toFixed(2)}</td>
                                    <td>${
                                        (() => {
                                            let data;
                                            try {
                                                data = JSON.parse(transaction.response_data);
                                                
                                                if (typeof data === "string") {
                                                    data = JSON.parse(data);
                                                }
                                            } catch (error) {
                                                data = {};
                                            }
                                            console.log(data?.data?.utr ?? data?.utr ?? "N/A");
                                            return transaction.utr ?? data?.data?.utr ?? data?.utr ?? "N/A";
                                        })()
                                    }</td>                                    
                                    <td>${transaction.type === 'credit' ? 'Payin' : 'Payout'}</td>
                                    <td>${transaction.status}</td>
                                    <td>${new Date(transaction.created_at).toLocaleString("en-US", { day: "2-digit", month: "short", year: "numeric", hour: "2-digit", minute: "2-digit", second: "2-digit", hour12: true })}</td>
                                   <td style="text-transform: capitalize;">
                                     ${transaction.settlement_status}
                                    </td>
                                </tr>
                            `);
                        });
                        $("#transactionTable").DataTable();
                    } else {
                        tableBody.append(
                            `<tr><td colspan="12" class="text-center">No transactions found</td></tr>`
                        );
                    }
                },
                error: function() {
                    alert("Something went wrong! Please try again.");
                }
            });
        });
    });
</script>
