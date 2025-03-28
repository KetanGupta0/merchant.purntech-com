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
{{-- <div class="alert alert-danger alert-dismissible fade show" role="alert">
    This page is currently under development and will be functional soon. Thank you for your patience!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div> --}}
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-end mt-2">
                        <button class="btn btn-success rounded-pill" data-bs-toggle="modal"
                            data-bs-target="#reportModal"> <i class="ri-download-2-line"></i> Download
                            Report</button>
                    </div>
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
        $("#settlement-table").DataTable({
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "pageLength": -1
        });
    });
</script>

{{-- Added on 12-03-2025 by Ketan --}}
<script>
    $(document).ready(function() {
        // 1. Determine the min and max dates from the settlement table data (Settlement Time is in the 12th column)
        var minDate = null;
        var maxDate = null;

        $('#settlement-table tbody tr').each(function() {
            var dateText = $(this).find('td:nth-child(12)').text().trim();
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

        // Helper: Format a Date object as 'YYYY-MM-DD'
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
                Swal.fire({
                    icon: "warning",
                    title: "Warning",
                    text: "Please select both start and end dates"
                });
                return;
            }

            // 3. Filter table data within the selected date range.
            var filteredData = [];
            // Get header texts excluding the "Action" column (last column)
            var headers = [];
            $('#settlement-table thead tr th:not(:last-child)').each(function() {
                headers.push($(this).text().trim());
            });
            filteredData.push(headers);

            // Loop through each row in tbody.
            $('#settlement-table tbody tr').each(function() {
                var dateText = $(this).find('td:nth-child(12)').text().trim();
                var dt = new Date(dateText);
                if (!isNaN(dt.getTime())) {
                    // Convert the date from the table into a string format YYYY-MM-DD
                    var dtOnlyStr = formatDateInput(dt);
                    // Compare the date strings (this handles the case when start and end dates are the same)
                    if (dtOnlyStr >= startDateStr && dtOnlyStr <= endDateStr) {
                        var row = [];
                        $(this).find('td:not(:last-child)').each(function() {
                            row.push($(this).text().trim());
                        });
                        filteredData.push(row);
                    }
                }
            });

            if (filteredData.length <= 1) {
                Swal.fire({
                    icon: "warning",
                    title: "Warning",
                    text: "No data available for selected date range."
                });
                return;
            }

            // 4. Create file based on the selected download type.
            if (downloadType === "Excel") {
                var csvContent = "";
                $.each(filteredData, function(i, row) {
                    var escapedRow = row.map(function(cell) {
                        var cellText = cell.replace(/"/g, '""');
                        if (cellText.indexOf(',') !== -1 || cellText.indexOf('"') !== -
                            1) {
                            cellText = '"' + cellText + '"';
                        }
                        return cellText;
                    });
                    csvContent += escapedRow.join(",") + "\n";
                });

                // Prepend BOM to ensure proper UTF-8 encoding (for the ₹ symbol)
                var blob = new Blob(["\uFEFF" + csvContent], {
                    type: 'text/csv;charset=utf-8;'
                });
                var url = URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.href = url;
                a.download = "settlement_report.csv";
                document.body.append(a);
                a.click();
                a.remove();
            } else if (downloadType === "PDF") {
                var printWindow = window.open('', '', 'height=600,width=800');
                printWindow.document.write('<html><head><title>Settlement Report</title>');
                printWindow.document.write(
                    '<style>table { width: 100%; border-collapse: collapse; } table, th, td { border: 1px solid black; padding: 5px; text-align: left; }</style>'
                    );
                printWindow.document.write('</head><body>');
                printWindow.document.write('<h3>Settlement Report</h3>');
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