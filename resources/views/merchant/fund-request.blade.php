<style>
.header {
    background: linear-gradient(to bottom, #405289, #6a89cc);
    color: white;
    padding: 10px 10px;
    position: relative;
    text-align: left;
    z-index: -1;
    height: 250px;

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

.card {
    border-radius: 10px;
}

.top-card {
    z-index: 3;
    margin-top: -60px;
}

.btn-primary {
    background-color: #007bff;
    border: none;
}
</style>




<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Fund Request</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Fund Request</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->


<div class="container-fluid">
    <div class="header  ">
        <div class="dashboard-header">
            <div class="fw-bold fs-5">DASHBOARD</div>
            <button class="dashboard-button btn">Active balance @if(isset($balance)) ₹{{sprintf("%.2f",$balance)}} @else
                ₹0.00 @endif </button>
        </div>
    </div>
</div>

<div class="container-fluid mt-4">
    <!-- Stats Cards -->
    <div class="row top-card">
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6>Approved Requests</h6>
                <h4>0 ✅</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6>Rejected Requests</h6>
                <h4>0 ❌</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6>Pending Requests</h6>
                <h4>0 ⏳</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6>Total Approved Amount</h6>
                <h4>₹0.00</h4>
            </div>
        </div>
    </div>
    <!-- search section -->


    <div class="container-fluid mt-3">
        <div class="row g-2">
            <!-- Search Input -->
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search by ID or UTR">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- Status Dropdown -->
            <div class="col-md-3">
                <select class="form-select">
                    <option selected>All Status</option>
                    <option>Approved</option>
                    <option>Rejected</option>
                    <option>Pending</option>
                </select>
            </div>



        </div>
    </div>




    <!-- Submit Fund Request -->
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card p-4">
                <h5>Submit Fund Request</h5>
                <div class="alert alert-info">
                    <strong>Important Notice:</strong> Enter the correct details. Amount should be in numbers (e.g.,
                    1000.00) and UTR should be the reference number from your bank transfer.
                </div>
                <form>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount"
                            placeholder="Enter Amount (e.g., 1000.00)">
                    </div>
                    <div class="mb-3">
                        <label for="utr" class="form-label">Bank Reference (UTR)</label>
                        <input type="text" class="form-control" id="utr" placeholder="Enter Bank Reference (UTR)">
                    </div>
                    <button type="submit" class="btn btn-secondary w-100">Submit Request</button>
                </form>
            </div>
        </div>

        <!-- Bank Details -->
        <div class="col-md-4">
            <div class="card p-4">
                <h5>Bank Details</h5>
                <p><strong>Name:</strong> <span>PURNTECH PAY</span></p>
                <p><strong>Account Number:</strong> <span>2223002276932151</span></p>
                <p><strong>IFSC:</strong> <span>UTIB000RAZP</span></p>
                <p><strong>Bank Name:</strong> <span>AXIS BANK</span></p>
                <p><strong>Branch Name:</strong> <span>AXIS BANK</span></p>
                <p><strong>Account Type:</strong> <span>CURRENT</span></p>
                <p><i>Collection charges applied as per bank 2% - 3%</i></p>
            </div>
        </div>
    </div>

    <!-- Fund Requests Table -->
    <div class="row mt-4">
        <div class="col">
            <div class="card p-4">
                <h5>Your Fund Requests</h5>
                <div class="table-responsive">
                    <table class="table table-bordered text-center" id="fund-table">
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>Amount</th>
                                <th>UTR</th>
                                <th>Status</th>
                                <th>Requested At</th>
                                <th>Feedback</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    $("#fund-table").DataTable();
});
</script>