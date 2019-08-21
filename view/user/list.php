<? require_once ROOT . '/view/layers/header.php' ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 pt-4">
                <? $num = 1; foreach($list as $user): ?>
                <div class="mc-list">
                    <div class="">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="mc-list-number"><? echo $num ?></div>
                                <img src="<? echo ($user->image ? $user->image : '/img/upload/users/avatar.jpg') ?>" width="100%"/>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-6">
                                <h5><? echo $user->name ?></h5>
                                <p><? echo $user->description ?></p>
                                <p class="mc-alert<? echo $user->role=='admin'?'-yellow':'' ?>">User Role: <span class="mc-mark"><? echo $user->role ?></span></p>
                                <p class="mc-alert">User Email:  <span class="mc-mark"><? echo $user->email ?></span></p>
                                <p class="mc-alert">Registration:  <span class="mc-mark"><? echo ($user->created_at) ? date('d.m.y H:i', strtotime($user->created_at)) : '--:--' ?></span>
                                Updated at: <span class="mc-mark"><? echo ($user->updated_at) ? date('d.m.y H:i', strtotime($user->updated_at)) : '--:--' ?></span></p>
                                <div class="mc-alert-ok text-right">
                                    <a href="<? route('user/' . $user->id . '/edit') ?>">Edit Profile</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>
                </div>
                <?  $num++; endforeach; ?>
            </div>
        </div>
    </div>
<? require_once ROOT . '/view/layers/footer.php' ?>
