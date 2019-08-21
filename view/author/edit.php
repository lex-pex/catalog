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
                        <form class="mc-form" method="post" action="<? route('author/update') ?>" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name">Author Name:</label>
                                <input type="text" name="name" class="form-control" id="name" value="<? echo old('name') ? old('name') : $item->name ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="surname">Surname:</label>
                                <input type="text" name="surname" class="form-control" id="surname" value="<? echo old('surname') ? old('surname') : $item->surname ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="father_name">Father Name:</label>
                                <input type="text" name="father_name" class="form-control" id="father_name" value="<? echo old('father_name') ? old('father_name') : $item->father_name ?>">
                            </div>
                            <img src="<? echo $item->image ? $item->image : '/img/upload/authors/no_photo.jpg' ?>" width="100%" />
                            <div class="form-group">
                                <label for="image">Cover Image <small class="mc-mark">( Less 500 kB )</small>:</label>
                                <input id="image" type="file" class="form-control btn btn-primary" name="image" disabled />
                            </div>
                            <div class="from-group text-center mc-alert mb-2">
                                <label for="delete_image">Delete Image:</label>
                                <input type="checkbox" class="inpu" name="delete_image" id="delete_image" disabled/>
                            </div>
                            <input type="hidden" name="id" value="<? echo $item->id ?>">
                            <input type="hidden" name="csrf_token" value="<? echo token() ?>" />
                            <button class="btn btn-outline-danger" onclick="event.preventDefault();document.getElementById('delete-form').submit();">Delete</button>
                            <button type="submit" class="btn btn-outline-primary float-right">Submit</button>
                        </form>
                        <form id="delete-form" action="<? route('author/destroy') ?>" method="post" style="display:none;">
                            <input type="hidden" name="csrf_token" value="<? echo token() ?>" />
                            <input type="hidden" name="_method" value="delete" />
                            <input type="hidden" name="id" value="<? echo $item->id ?>">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? require_once ROOT . '/view/layers/footer.php' ?>







































