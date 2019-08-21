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
                        <form class="mc-form" method="post" action="<? route('magazine/store') ?>" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name">Journal Title:</label>
                                <input type="text" name="name" class="form-control" id="name" value="<? echo old('name') ? old('name') : '' ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Status / Message:</label>
                                <textarea type="text" class="form-control mc-text-area" name="description" id="description"><? echo old('description') ? old('description') : '' ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="release_at">Release Date:</label>
                                <div class="text-center">
                                    <select class="form-control custom-select" name="year" style="width:25%;display:inline">
                                        <? for($i = 2018; $i <= 2025; $i ++): ?>
                                            <option><? echo $i ?></option>
                                        <? endfor ?>
                                    </select>
                                    <select class="form-control custom-select" name="month" style="width:15%;display:inline">
                                        <? for($i = 1; $i <= 12; $i ++): ?>
                                            <option><? echo $i ?></option>
                                        <? endfor ?>
                                    </select>
                                    <select class="form-control custom-select" name="day" style="width:15%;display:inline">
                                        <? for($i = 1; $i <= 31; $i ++): ?>
                                            <option><? echo $i ?></option>
                                        <? endfor ?>
                                    </select>
                                </div>
                            </div>
                            <img src="/img/upload/magazines/cover.jpg" width="100%" />
                            <div class="form-group">
                                <label for="image">Cover Image <small class="mc-mark">( Less 500 kB )</small>:</label>
                                <input id="image" type="file" class="form-control btn btn-primary" name="image" />
                            </div>
                            <input type="hidden" name="csrf_token" value="<? echo token() ?>" />
                            <button type="submit" class="btn btn-outline-primary float-right">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? require_once ROOT . '/view/layers/footer.php' ?>
