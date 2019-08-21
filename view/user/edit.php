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
                        <form class="mc-form" method="post" action="<? route('user/update') ?>">
                            <div class="form-group">
                                <label for="name">Name (login):</label>
                                <input type="text" name="name" class="form-control" id="name" value="<? echo old('name') ? old('name') : $user->name ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail Address:</label>
                                <input type="text" name="email" class="form-control" id="email" value="<? echo old('email') ? old('email') : $user->email ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Password:</label>
                                <input type="password" name="password" class="form-control" id="password">
                            </div>
                            <div class="form-group">
                                <label for="description">Status / Message:</label>
                                <textarea type="text" class="form-control mc-text-area" name="description" id="description"><? echo old('description') ? old('description') : $user->description ?></textarea>
                            </div>
                            <input type="hidden" name="id" value="<? echo $user->id ?>">
                            <input type="hidden" name="csrf_token" value="<? echo csrf_token() ?>" />
                            <button class="btn btn-outline-danger" onclick="event.preventDefault();document.getElementById('delete-form').submit();">Delete</button>
                            <button type="submit" class="btn btn-outline-primary float-right">Submit</button>
                        </form>
                        <form id="delete-form" action="<? route('user/destroy') ?>" method="post" style="display:none;">
                            <input type="hidden" name="csrf_token" value="<? echo csrf_token() ?>" />
                            <input type="hidden" name="_method" value="delete" />
                            <input type="hidden" name="id" value="<? echo $user->id ?>">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? require_once ROOT . '/view/layers/footer.php' ?>
