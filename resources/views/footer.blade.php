<div class="container py-5">
    <div class="row align-items-center">
        <div class="col-lg-6 mb-3 mb-lg-0 text-center text-lg-start">
            <h1 class="text-white display-1 m-0" style="font-size: 10vw">Let's</h1>
            <h1 class="display-1 talk" style="color: #eb5d1e;font-size: 10vw;">Talk!</h1>
        </div>
        <div class="col-lg-6">
            <div class="card rounded-5 border-0" style="background: #0A0A0A">
                <div class="card-body rounded-5 p-4" style="background: #0A0A0A">
                    <form action="#" class="contact-form">
                        <div class="form-group mb-3">
                            <input type="text" class="form-control" placeholder="Name">
                        </div>
                        <div class="form-group mb-3">
                            <input type="text" class="form-control" placeholder="Phone">
                        </div>
                        <div class="form-group mb-3">
                            <textarea type="text" class="form-control" rows="4" placeholder="Message"></textarea>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary fw-100">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container my-5 py-5 rounded-5"
    style="backdrop-filter: blur(5px) !important;background-color: rgba(255, 255, 255, .05) !important;">
    <div class="row align-items-center">
        <div class="col-lg-4 mb-3 mb-lg-0">
            <div style="background: #222222" class="rounded-5 px-4 py-5">
                <div>
                    <i class="fa fa-envelope fs-3" style="color: #eb5d1e;"></i>
                </div>
                <a href="mailto:support@purntech.com"
                    class="text-white text-decoration-none mt-2">support@purntech.com</a>
            </div>
        </div>
        <div class="col-lg-8 footer-menu">
            <ul class="nav-menu">
                <li>
                    <a class="{{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">HOME</a>
                </li>
                <li>
                    <a class="{{ request()->is('about') ? 'active' : '' }}" href="{{ url('/about') }}">ABOUT</a>
                </li>
                <li>
                    <a class="{{ request()->is('resources') ? 'active' : '' }}"
                        href="{{ url('/resources') }}">RESOURCES</a>
                </li>
                <li>
                    <a class="{{ request()->is('merchants') ? 'active' : '' }}"
                        href="{{ url('/merchants') }}">MERCHANTS</a>
                </li>
                <li>
                    <a class="{{ request()->is('payments') ? 'active' : '' }}"
                        href="{{ url('/payments') }}">PAYMENTS</a>
                </li>
                <li>
                    <a class="{{ request()->is('merchant/onboarding') ? 'active' : '' }}"
                        href="{{ url('/merchant/onboarding') }}">ONBOARDING</a>
                </li>
                <li>
                    <a class="{{ request()->is('login') ? 'active' : '' }}" href="{{ url('/login') }}">LOGIN</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="container mt-1 py-5 rounded-5"
    style="backdrop-filter: blur(5px) !important;background-color: rgba(255, 255, 255, .05) !important;">
    <div class="row align-items-center">
        <div class="col-lg-12">
            <div class="rounded-5 px-4 py-5 d-flex flex-column gap-3 align-items-center text-center">
                <p style="color: #eb5d1e;" class="fw-bold m-0">Follow us:</p>
                <a href="#" class="p-2" style="border:1px solid #eb5d1e;width:fit-content">
                    <i class="fa fa-paper-plane fs-4" style="color: #eb5d1e;"></i>
                </a>
                <div class="d-flex gap-2 newsletter align-items-center flex-wrap justify-content-center">
                    <div>
                        <input type="text" placeholder="name@email.com" class="form-control">
                    </div>
                    <div>
                        <button class="btn btn-primary">Subscribe for newsletter</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container py-5">
    <hr style="height:2px !important;background:white">
    <div class="d-flex gap-3 align-items-center justify-content-between flex-wrap">
        <p class="m-0 text-white font-12">Copyright @ 2025</p>
        <p class="m-0 text-white font-12">Developed by <a class="text-white text-decoration-none"
                href="https://purntech.com/" target="_blank">PurnTech</a></p>
    </div>
</div>
<script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
<script>
    $(document).ready(function() {
        @if (Session::has('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: "{{ Session::get('error') }}"
            });
        @endif
        @if (Session::has('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                html: "{{ Session::get('success') }}"
            });
        @endif

        AOS.init({
            delay: 100
        });
    });
</script>
</body>

</html>
