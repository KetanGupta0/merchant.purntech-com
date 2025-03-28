<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Settings</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Settings</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
@isset($agent)
    <div class="card">
        <div class="card-body">
            <h3>Personal Info</h3>
            <form action="{{ url('/agent/settings/update') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="agent_name" id="agent_name" class="form-control" placeholder="Name"
                                    value="{{ $agent->name }}" readonly>
                            <label for="agent_name">Name <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="email" name="agent_email" id="agent_email" class="form-control" placeholder="Email"
                                    value="{{ $agent->email }}" readonly>
                            <label for="agent_email">Email <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="text" name="agent_phone" id="agent_phone" class="form-control" placeholder="Primary Phone"
                                    value="{{ $agent->mobile }}" readonly>
                            <label for="agent_phone">Mobile <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="file" name="agent_profile" id="agent_profile" accept="image/*" class="form-control"
                                    placeholder="Profile Photo">
                            <label for="agent_profile">Profile Photo</label>
                        </div>
                    </div>
                </div>
                <h3>Password</h3>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="password" name="agent_password" id="agent_password" class="form-control" placeholder="Current Password">
                            <label for="agent_password">Current Password <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="password" name="agent_password_new" id="agent_password_new" class="form-control" placeholder="New Password">
                            <label for="agent_password_new">New Password</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input type="password" name="agent_password_new_confirmed" id="agent_password_new_confirmed" class="form-control" placeholder="Confirm New Password">
                            <label for="agent_password_new_confirmed">Confirm New Password</label>
                        </div>
                    </div>
                    <div class="col-md-12 text-end">
                        <input type="submit" value="Update" class="btn btn-primary">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endisset

