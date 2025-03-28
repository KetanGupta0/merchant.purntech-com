<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Agents</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Agents</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="merchant_table">
                    <thead>
                        <tr>
                            <th>S. No.</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="table_data">
                        @if (isset($agents))
                            @foreach ($agents as $agents)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $agents->name }}</td>
                                    <td>{{ $agents->mobile }}</td>
                                    <td>{{ $agents->email }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-icon btn-sm fs-16 text-muted dropdown" type="button" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                <i class="ri-more-fill"></i>
                                            </button>
                                            <ul class="dropdown-menu" style="">
                                                <li><a class="dropdown-item view" href="{{url('/admin/agents/view-')}}{{ $agents->id }}"><i
                                                           class="ri-eye-fill text-muted me-2 align-bottom"></i>View</a>
                                            </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $("#merchant_table").DataTable();
</script>