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
                                        <img src="<? echo ($item->image ? $item->image : '/img/upload/authors/no_photo.jpg') ?>" width="100%"/>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-6">
                                        <p class="mc-alert">Name: <mark><? echo $item->name ?></mark></p>
                                        <p class="mc-alert">Surname: <mark><? echo $item->surname ?></mark></p>
                                        <p class="mc-alert">Father name: <mark><? echo $item->father_name ?></mark></p>
                                        <p class="mc-alert-ok">Magazines:
                                            <? foreach($item->magazines as $m): ?>
                                                <a style="text-decoration:none" href="/magazine/<? echo $m['id'] ?>">
                                                    <span class="mc-mark"><? echo $m['name'] ?></span>
                                                </a>
                                            <? endforeach ?>
                                        </p>
                                        <p class="text-right">
                                            <small>
                                                Added: <mark><? echo ($item->created_at) ? date('d.m.y H:i', strtotime($item->created_at)) : '--:--' ?></mark>
                                                Updated: <mark><? echo ($item->updated_at) ? date('d.m.y H:i', strtotime($item->updated_at)) : '--:--' ?></mark>
                                            </small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <? if(admin()): ?>
                                <a href="<? route('admin') ?>">Admin</a> |
                                <a href="<? route('author/list') ?>">List</a> |
                                <a href="<? route('author/' . $item->id . '/edit') ?>">Edit</a>
                                <? endif ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? require_once ROOT . '/view/layers/footer.php' ?>




