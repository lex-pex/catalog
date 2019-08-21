<? require_once ROOT . '/view/layers/header.php' ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 pt-4">
                <? $num = 1; foreach($list as $item): ?>
                <div class="mc-list">
                    <div class="">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="mc-magazine-list-number"><? echo $num ?></div>
                                <img src="<? echo ($item->image ? $item->image : '/img/upload/magazines/cover.jpg') ?>" width="100%"/>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-6">
                                <h5><? echo $item->name ?></h5>
                                <p class="mc-alert-ok">Description: <? echo $item->description ?></p>
                                <p class="mc-alert-ok">Authors:
                                    <? foreach($item->authors as $author): ?>
                                        <span class="mc-mark"><? echo $author['name'] . ' ' . $author['surname'] ?></span>
                                    <? endforeach ?>
                                </p>
                                <p class="mc-alert">Release Date:
                                    <span class="mc-mark-urge"><? echo ($item->release_at) ? date('d.m.y', strtotime($item->release_at)) : '--:--' ?></span>
                                </p>
                                <p class="text-right">
                                    <small>Appear: <? echo ($item->created_at) ? date('d.m.y H:i', strtotime($item->created_at)) : '--:--' ?>
                                        Update: <? echo ($item->updated_at) ? date('d.m.y H:i', strtotime($item->updated_at)) : '--:--' ?></small>
                                </p>
                                <div class="mc-alert text-right">
                                    <a href="<? route('magazine/' . $item->id) ?>">Show</a> |
                                    <a href="<? route('magazine/' . $item->id . '/edit') ?>">Edit</a>
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
