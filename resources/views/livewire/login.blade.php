<div>

    <div class="pt-4 pb-2">
        <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
        <p class="text-center small">Enter your email & password to login</p>
        <p class="text-center small text-danger">{{ session('error') }}</p>
    </div>

    <form class="row g-3 needs-validation" wire:submit='submit'>
        <div class="col-12">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="email" wire:model='email'>
            <div class="invalid-feedback">Please enter your email.</div>
            <div>
                @error('email')
                    <span class="text-danger text-xs">{{ $message }}</span>
                @enderror
            </div>

        </div>

        <div class="col-12">
            <label for="yourPassword" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="yourPassword" wire:model='password'>
            <div class="invalid-feedback">Please enter your password!</div>
            <div>
                @error('password')
                    <span class="text-danger text-xs">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- <div class="col-12">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
              <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>
          </div> -->
        <div class="col-12">
            <button class="btn btn-primary w-100" type="submit">Login</button>
        </div>
        {{-- <div class="col-12">
            <p class="small mb-0">Don't have account? <a href="pages-register.html">Create an account</a></p>
          </div> --}}
    </form>


</div>
