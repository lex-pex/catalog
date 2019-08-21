<? require_once ROOT . '/view/layers/header.php' ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 pt-4">
                <div class="card">
                    <div class="card-body">
                        <? if(!empty($errors)): ?>
                            <? foreach ($errors as $e): ?>
                                <div class="alert alert-danger"><? echo $e ?></div>
                            <? endforeach ?>
                        <? endif ?>
                        <form class="mc-form" method="post" action="<? route('login') ?>">
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" name="email" class="form-control" id="email" value="<? old('email') ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Password:</label>
                                <input type="password" name="password" class="form-control" id="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? require_once ROOT . '/view/layers/footer.php' ?>















