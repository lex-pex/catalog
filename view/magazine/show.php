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
                                        <img src="<? echo ($item->image ? $item->image : '/img/upload/magazines/cover.jpg') ?>" width="100%"/>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-6">
                                        <h5><? echo $item->name ?></h5>
                                        Description:<br/>
                                        <p class="mc-alert"><? echo $item->description ?></p>
                                        <p class="mc-alert-ok">Authors:
                                            <? foreach($item->authors as $author): ?>
                                            <span class="mc-mark"><? echo $author['name'] . ' ' . $author['surname'] ?></span>
                                            <? endforeach ?>
                                        </p>
                                        <p class="mc-alert">Release Date:
                                            <span class="mc-mark-urge"><? echo ($item->release_at) ? date('d.m.Y', strtotime($item->release_at)) : '--:--' ?></span>
                                        </p>
                                        <p class="text-right">
                                        <small>Registration: <? echo ($item->created_at) ? date('d.m.y H:i', strtotime($item->created_at)) : '--:--' ?>
                                           Updated at: <? echo ($item->updated_at) ? date('d.m.y H:i', strtotime($item->updated_at)) : '--:--' ?></small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <? if(admin()): ?>
                                <a href="<? route('admin') ?>">Admin</a> |
                                <a href="<? route('magazine/list') ?>">List</a> |
                                <a href="<? route('magazine/' . $item->id . '/edit') ?>">Edit</a>
                                <? endif ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? require_once ROOT . '/view/layers/footer.php' ?>
