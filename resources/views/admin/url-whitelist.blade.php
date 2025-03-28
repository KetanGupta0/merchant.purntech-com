<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">URL White Listing</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">URL White Listing</li>
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
                <div class="col-md-4 mb-4">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        Add New
                    </button>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped" id="urlTable">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Merchant</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>URL</th>
                                    <th>IP Address</th>
                                    <th>Environment</th>
                                    <th>Status</th>
                                    <th>Request Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($urls))
                                    @foreach ($urls as $url)
                                        <tr>
                                            <th>{{$loop->index+1}}</th>
                                            <th>{{$url->merchant_name}}</th>
                                            <th>{{$url->merchant_email}}</th>
                                            <th>{{$url->merchant_phone}}</th>
                                            <th>{{$url->uwl_url}}</th>
                                            <th>{{$url->uwl_ip_address == "" ? "N/A" : $url->uwl_ip_address}}</th>
                                            <th>{{$url->uwl_environment}}</th>
                                            <th>{{$url->uwl_status}}</th>
                                            <th>{{ date('d M Y h:i:s A',strtotime($url->created_at)) }}</th>
                                            <th>
                                                <div class="dropdown">
                                                    <button class="btn btn-icon btn-sm fs-16 text-muted dropdown" type="button" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                        <i class="ri-more-fill"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" style="">
                                                        @if ($url->uwl_status == 'Active')
                                                            <li>
                                                                <a class="dropdown-item view" href="{{url('/admin/url/whitelisting/request-inactive')}}-{{$url->uwl_id}}">
                                                                    <i class="ri-eye-fill text-muted me-2 align-bottom"></i>Inactive
                                                                </a>
                                                            </li>
                                                        @elseif ($url->uwl_status == 'Inactive')
                                                            <li>
                                                                <a class="dropdown-item view" href="{{url('/admin/url/whitelisting/request-active')}}-{{$url->uwl_id}}">
                                                                    <i class="ri-eye-fill text-muted me-2 align-bottom"></i>Acitve
                                                                </a>
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <a class="dropdown-item delete" href="javascript:void(0);" data-id="{{ $url->merchant_id }}">
                                                                <i class="ri-delete-bin-5-fill text-muted me-2 align-bottom"></i>Delete
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </th>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Merchant</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>URL</th>
                                    <th>IP Address</th>
                                    <th>Environment</th>
                                    <th>Status</th>
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
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">New URL White Listing Request Form</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-floating">
                                <input type="text" name="merchant_email" id="merchant_email" class="form-control"
                                       placeholder="Merchant email">
                                <label for="merchant_email">Merchant email <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-floating">
                                <input type="text" name="uwl_url" id="uwl_url" class="form-control"
                                       placeholder="URL: https://example.com OR https://www.example.com">
                                <label for="uwl_url">Requested URL <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-floating">
                                <input type="text" name="uwl_ip_address" id="uwl_ip_address" class="form-control" placeholder="IP Address">
                                <label for="uwl_ip_address">IP Address</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-floating">
                                <select name="uwl_environment" id="uwl_environment" class="form-control" placeholder="Environment">
                                    <option value="">Select</option>
                                    <option value="Production" selected>Production</option>
                                    <option value="UAT">UAT</option>
                                </select>
                                <label for="uwl_environment">Environment <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-floating">
                                <select name="uwl_status" id="uwl_status" class="form-control" placeholder="Status">
                                    <option value="">Select</option>
                                    <option value="Active" selected>Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                                <label for="uwl_status">Status <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-12 text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("#urlTable").DataTable();
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
                    $.get("{{ url('/admin/url/whitelisting/request-delete') }}-"+$(this).attr('data-id'), function(response) {
                        if (response.status == true) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "URL has been deleted.",
                                icon: "success"
                            }).then(() => {
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