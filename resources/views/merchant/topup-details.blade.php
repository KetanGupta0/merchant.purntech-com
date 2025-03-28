<style>
.header {
    background: linear-gradient(to bottom, #405289, #6a89cc);
    color: white;
    padding: 10px;
    text-align: left;
    height: 250px;
}


.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    padding: 10px;
}

.dashboard-header .dashboard-button {
    border: 1px solid white;
    color: white;
    background: transparent;
    padding: 8px 20px;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}

.dashboard-button:hover {
    background: white;
    color: #a00000;
}

@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        text-align: center;
    }

    .dashboard-header .dashboard-button {
        margin-top: 10px;
        width: 100%;
    }
}
</style>



<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Topup Details</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Topup Details</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->


<!-- header section -->
<div class="container-fluid">
    <div class="header">
        <div class="dashboard-header">
            <div class="fw-bold fs-5">DASHBOARD</div>
            <button class="dashboard-button btn">Active balance @if(isset($balance)) ₹{{sprintf("%.2f",$balance)}} @else
                ₹0.00 @endif</button>
        </div>
    </div>
</div>
<!-- end header section -->


<!-- main section -->
<div class="container-fluid mt-3">
    <div class="card shadow-lg">
        <div class="card-body">

<!-- inner main section -->
            <div class="container-fluid mt-4">
                <div class="table-responsive overflow-auto p-3">
                    <table class="table table-hover text-center" id="topup-table">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Points</th>
                                <th>Date</th>
                                <th>Source</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- here show the output   --> 
                           
                        </tbody>
                    </table>
                    
                </div>
            </div>
<!-- inner main section -->
        </div>
    </div>
</div>

<!-- end main section -->



<script>
    $(document).ready(function() {
        $("#topup-table").DataTable();
    });
    </script>