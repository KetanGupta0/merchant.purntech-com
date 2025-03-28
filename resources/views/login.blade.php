<style>
    .form-control {
        box-shadow: none !important
    }
</style>

<div class="container d-flex align-items-center justify-content-center w-100" style="height: 100vh">
  
    <div class="card">
       
      <div class="image-container d-flex align-items-center justify-content-center">
            <img src="assets/images/img-log.png" style="height: 10vh; width: 10vh;" alt="IMG">
        </div>
      
        <div class="card-body p-5 py-4">
            <h2 class="text-center">Login🛡️</h2>
            <form method="POST" action="{{ url('login/submit') }}">
                @csrf
                <i class="fa fa-envelope" aria-hidden="true"></i><input type="email" class="form-control mb-3" id="email" name="email" placeholder="📧 Email" value="{{ old('email') }}" required>
            
           <i class="fa fa-lock" aria-hidden="true"></i> <input type="password" class="form-control mb-3" id="password" name="password" placeholder="🔑 Password" required>
              <i class="zmdi zmdi-eye"></i>
              
						

            <button type="submit" class="btn btn-primary w-100">
                🔒 Login
            </button>
              <div class="forgot-password">
                    <a href="{{ url('contact-us') }}">Forgot Password?</a><br>
                
                </div>
            </form>
          
        </div>
      <img src="assets/images/trust.jpg" style="height: 115px; width: 100%;" "align: center;" alt="IMG" align-items-center justify-content-center>
    </div>
</div>

