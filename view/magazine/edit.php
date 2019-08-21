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
                        <form class="mc-form" method="post" action="<? route('magazine/update') ?>" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name">Journal Title:</label>
                                <input type="text" name="name" class="form-control" id="name" value="<? echo old('name') ? old('name') : $item->name ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Status / Message:</label>
                                <textarea type="text" class="form-control mc-text-area" name="description" id="description"><? echo old('description') ? old('description') : $item->description ?></textarea>
                            </div>
                            <!-- AUTHORS TO DELETE -->
                            <? if($staff = $item->authors): ?>
                            <div class="form-group">
                                <label>Authors Staff:</label>
                                <? foreach ($staff as $a):  $staff_ids[] = $a['id'] ?>
                                    <div>
                                        <span class="mc-mark"><? echo $a['name'].' '.$a['surname'] ?></span>
                                        <input id="del_check_<? echo $a['id'] ?>" type="checkbox" name="staff[]" value="<? echo $a['id'] ?>" id="delete_image"/> <label for="del_check_<? echo $a['id'] ?>">delete</label>
                                    </div>
                                <? endforeach ?>
                            </div>
                            <? endif ?>
                            <!-- AUTHORS TO ADD -->
                            <div class="form-group">
                                <label>Available to add:</label>
                                <select size="5" class="form-control custom-select" name="authors[]" multiple>
                                    <option disabled> &nbsp; </option>
                                    <? foreach ($authors as $a): ?>
                                        <? if(!in_array($a->id, $staff_ids)): ?>
                                            <option value="<? echo $a->id ?>"><? echo $a->name.' '.$a->surname ?></option>
                                        <? endif ?>
                                    <? endforeach ?>
                                    <option disabled> &nbsp; </option>
                                </select>
                            </div>
                            <!-- Release date -->
                            <div class="form-group"><? $d = explode('-', date('Y-m-d', strtotime($item->release_at))) ?>
                                <label for="release_at">Release Date:</label>
                                <div class="text-center">
                                    <select class="form-control custom-select" name="year" style="width:25%;display:inline">
                                        <? for($i = 2018; $i <= 2025; $i ++): ?>
                                            <option<? echo $d[0] == $i ? ' selected="selected"' : '' ?>><? echo $i ?></option>
                                        <? endfor ?>
                                    </select>
                                    <select class="form-control custom-select" name="month" style="width:15%;display:inline">
                                        <? for($i = 1; $i <= 12; $i ++): ?>
                                            <option<? echo $d[1] == $i ? ' selected="selected"' : '' ?>><? echo $i ?></option>
                                        <? endfor ?>
                                    </select>
                                    <select class="form-control custom-select" name="day" style="width:15%;display:inline">
                                        <? for($i = 1; $i <= 31; $i ++): ?>
                                            <option<? echo $d[2] == $i ? ' selected="selected"' : '' ?>><? echo $i ?></option>
                                        <? endfor ?>
                                    </select>
                                </div>
                            </div>
                            <img src="<? echo $item->image ? $item->image : '/img/upload/magazines/cover.jpg' ?>" width="100%" />
                            <div class="form-group">
                                <label for="image">Cover Image <small class="mc-mark">( Less 500 kB )</small>:</label>
                                <input id="image" type="file" class="form-control btn btn-primary" name="image" />
                            </div>
                            <div class="from-group text-center mc-alert mb-2">
                                <label for="delete_image">Delete Image:</label>
                                <input type="checkbox" class="" name="delete_image" id="delete_image"/>
                            </div>
                            <input type="hidden" name="id" value="<? echo $item->id ?>">
                            <input type="hidden" name="csrf_token" value="<? echo token() ?>" />
                            <button class="btn btn-outline-danger" onclick="event.preventDefault();document.getElementById('delete-form').submit();">Delete</button>
                            <button type="submit" class="btn btn-outline-primary float-right">Submit</button>
                        </form>
                        <form id="delete-form" action="<? route('magazine/destroy') ?>" method="post" style="display:none;">
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
