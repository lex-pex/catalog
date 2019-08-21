<? require_once ROOT . '/view/layers/header.php' ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-12 pt-4">
                        <div class="card mc-cabinet">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <img src="<? echo ($user->image ? $user->image : '/img/upload/users/avatar.jpg') ?>" width="100%"/>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-6">
                                        <h3><? echo $user->name ?></h3>
                                        <p><? echo $user->description ?></p>
                                        <p class="mc-alert">User Role: <span class="mc-mark"><? echo $user->role ?></span></p>
                                        <p class="mc-alert">User Email:  <span class="mc-mark"><? echo $user->email ?></span></p>
                                        <p class="mc-alert">Registration:  <span class="mc-mark"><? echo ($user->created_at) ? date('d.m.y H:i', strtotime($user->created_at)) : '__.__.__ --:--' ?></span></p>
                                        <? if(admin()): ?>
                                        <p class="mc-alert-yellow">Admin Panel: <a href="<? route('admin') ?>"> admin </a></p>
                                        <? endif ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <a href="<? route('user/' . $user->id . '/edit') ?>">Edit Profile</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? require_once ROOT . '/view/layers/footer.php' ?>




