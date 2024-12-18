<?php include "./layout_header.php"?>
<div class="page-top" style="background-image: url('https://placehold.co/1300x260')">
        <div class="bg"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>Customer Login</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-5 col-md-6 col-sm-12">
                    <div class="login-form">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="" class="form-label">Username</label>
                                <input type="text" name="" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Password</label>
                                <input type="password" name="" class="form-control">
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary bg-website">
                                    Login
                                </button>
                                <a href="forget-password.html" class="primary-color">Forget Password?</a>
                            </div>
                            <div class="mb-3">
                                <a href="<?php echo BASE_URL?>customer-register" class="primary-color">Don't have an account? Create Account</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include "./layout_footer.php"?>