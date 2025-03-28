<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Merchants</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Merchants</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<style>
    th,td{
        text-align: left!important;
    }
</style>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                {{-- <div class="text-end mb-2">
                    <button class="btn btn-success rounded-pill" data-bs-toggle="modal"
                        data-bs-target="#reportModal"> <i class="ri-download-2-line"></i> Download
                        Report</button>
                </div> --}}
                <div class="table-responsive">
                    <table class="table table-striped" id="merchantsTable">
                        <thead>
                            <tr>
                                <th>Merchant ID.</th>
                                <th>Merchant Name</th>
                                <th>Merchant Email</th>
                                <th>Merchant Mobile</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($merchants)
                            @foreach($merchants as $val)
                                <tr>
                                    <td>{{$val->merchant_id}}</td>
                                    <td>{{$val->merchant_name}}</td>
                                    <td>{{$val->merchant_email}}</td>
                                    <td>{{$val->merchant_phone}}</td>
                                    <td>
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a href="{{url('/agent/merchants-payin/view-')}}{{ $val->merchant_id }}" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View PayIn Trans.</a></li>
                                                <li><a href="{{url('/agent/merchants-payout/view-')}}{{ $val->merchant_id }}" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View PayOut Trans.</a></li>
                                                <li><a href="{{url('/agent/merchants-transactions/view-')}}{{ $val->merchant_id }}" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View Total Trans.</a></li>
                                                <li><a href="{{url('/agent/merchants/view-')}}{{ $val->merchant_id }}" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View Profile</a></li>                                            
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @endisset
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Merchant ID.</th>
                                <th>Merchant Name</th>
                                <th>Merchant Email</th>
                                <th>Merchant Mobile</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#merchantsTable").DataTable();
    });
</script>