<?php

require_once './head.php';

session_destroy();

?>

<body>
<div class="col-md-12">
    <h1> Bakkun's Coupon Code Generator</h1>
    <div class="col-md-12">
        <div class="col-md-12" style="margin-top: 2%;">
            <div class="col-md-12" style="align-items: center;">
                <div class="col-md-3">
                    <span style="font-size: 1.2em;">ID</span>
                </div>
                <div class="col-md-5">
                    <div class="single-input">
                        <input type="text" class="form-control" maxlength="15" id="input_user_id" placeholder="ID">
                    </div>
                </div>
            </div>
            <br><br>
            <div class="col-md-12" style="align-items: center;">
                <div class="col-md-3">
                    <span style="font-size: 1.2em;">비밀번호</span>
                </div>
                <div class="col-md-5">
                    <div class="single-input">
                        <input type="password" class="form-control" maxlength="20" id="input_user_pw" placeholder="Password">
                    </div>
                </div>
            </div>
            <br><br>
            <div class="col-md-12" style="margin-bottom: 2%;">
                <a role="button" id="btn_signin" class="btn btn-primary btn-xs">Sign In</a>
            </div>
        </div>
    </div>
</div>

<?php
require_once './footer.php';
?>