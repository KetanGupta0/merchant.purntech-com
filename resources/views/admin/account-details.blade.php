<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Account Details</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Account Details</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
@isset($accounts)
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="accountTable">
                        <thead>
                            <tr>
                                <th class="text-start" style="width: 5%;">S.No.</th>
                                <th class="text-start" style="width: 16%;">Account No.</th>
                                <th class="text-start" style="width: 16%;">Account Status</th>
                                <th class="text-start" style="width: 16%;">Merchant Name</th>
                                <th class="text-start" style="width: 16%;">Email</th>
                                <th class="text-start" style="width: 16%;">Phone</th>
                                <th class="text-start" style="width: 15%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accounts as $acc)
                                <tr>
                                    <th class="text-start">{{ $loop->index + 1 }}</th>
                                    <th class="text-start"><a href="{{url('/admin/account/details/view')}}-{{$acc->acc_id}}">{{ $acc->acc_account_number }}</a></th>
                                    <th class="text-start">{{ $acc->acc_status }}</th>
                                    <th class="text-start">{{ $acc->merchant_name }}</th>
                                    <th class="text-start">{{ $acc->merchant_email }}</th>
                                    <th class="text-start">{{ $acc->merchant_phone }}</th>
                                    <th class="text-start">
                                        <div class="dropdown">
                                            <button class="btn btn-icon btn-sm fs-16 text-muted dropdown" type="button" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                <i class="ri-more-fill"></i>
                                            </button>
                                            <ul class="dropdown-menu" style="">
                                                <li>
                                                    <a class="dropdown-item view" href="{{url('/admin/account/details/view')}}-{{$acc->acc_id}}">
                                                        <i class="ri-eye-fill text-muted me-2 align-bottom"></i>View
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item delete" data-id="{{ $acc->acc_id }}" href="javascript:void(0);">
                                                        <i class="ri-delete-bin-5-fill text-muted me-2 align-bottom"></i>Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endisset
<script>
    $(document).ready(function() {
        $('#accountTable').DataTable();
        $(document).on('click', '.delete', function() {
            Swal.fire({
                html: `
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" 
                            trigger="loop" 
                            colors="primary:#f7b84b,secondary:#f06548" 
                            style="width:100px;height:100px;margin: 14px 0;">
                    </lord-icon>
                    <div style='margin: 14px 0;'><b>Are you sure?</b></div>
                    <div>You won't be able to revert this!</div>
                `,
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.get("{{ url('/admin/account/details/status/delete') }}-"+$(this).attr('data-id'), 
                    function(response) {
                        if (response == true) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Account has been deleted.",
                                icon: "success"
                            }).then(()=>{
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: "Something went wrong! Please try again later.",
                                icon: "error"
                            });
                        }
                    }).fail(function(error) {
                        Swal.fire({
                            title: "Error!",
                            html: error.responseJSON.message,
                            icon: "error"
                        });
                    });
                }
            });
        });
    });
</script>
