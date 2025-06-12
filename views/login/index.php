<section class="vh-100 gradient-custom">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card bg-dark text-white" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">

            <form id="FormLogin">
              <div class="mb-md-5 mt-md-4 pb-5">

                <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                <p class="text-white-50 mb-5">Please enter your login and password!</p>

                <div data-mdb-input-init class="form-outline form-white mb-4">
                  <input type="text" name="usu_codigo" id="usu_codigo" class="form-control form-control-lg" />
                  <label class="form-label" for="usu_codigo">DPI</label>
                </div>

                <div data-mdb-input-init class="form-outline form-white mb-4">
                  <input type="password" name="usu_password" id="usu_password" class="form-control form-control-lg" />
                  <label class="form-label" for="usu_password">Password</label>
                </div>

                <p class="small mb-5 pb-lg-2"><a class="text-white-50" href="#!">Forgot password?</a></p>

                <button type="submit" id="BtnIniciar" class="btn btn-outline-light btn-lg px-5">Login</button>

                <div class="d-flex justify-content-center text-center mt-4 pt-1">
                  <a href="#!" class="text-white"><i class="fab fa-facebook-f fa-lg"></i></a>
                  <a href="#!" class="text-white"><i class="fab fa-twitter fa-lg mx-4 px-2"></i></a>
                  <a href="#!" class="text-white"><i class="fab fa-google fa-lg"></i></a>
                </div>

              </div>

              <div>
                <p class="mb-0">Don't have an account? <a href="#!" class="text-white-50 fw-bold">Sign Up</a>
                </p>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="<?= asset('build/js/login/login.js') ?>"></script>

<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        background: #6a11cb;
        background: -webkit-linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));
        background: linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));
    }
    body {
        min-height: 100vh;
        height: 100%;
    }
    .gradient-custom {
        min-height: 100vh;
        height: 100%;
    }
</style>