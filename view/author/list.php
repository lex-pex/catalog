<? require_once ROOT . '/view/layers/header.php' ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 pt-4">
                <? foreach($list as $item): ?>
                <div class="mc-list">
                    <div class="">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="mc-magazine-list-number"><? echo $item->id ?></div>
                                <img src="<? echo ($item->image ? $item->image : '/img/upload/authors/no_photo.jpg') ?>" width="100%"/>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-6">
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

                                <hr/>


                                <div class="mc-alert-yellow text-right">
                                    <a href="<? route('author/' . $item->id) ?>">Show</a> |
                                    <a href="<? route('author/' . $item->id . '/edit') ?>">Edit</a>
                                </div>


                            </div>
                        </div>
                    </div>
                    <hr/>
                </div>
                <? endforeach ?>
            </div>
        </div>
        <div class="row justify-content-center"> <!-- Pagination -->
            <div class="col-lg-3 col-md-5 col-sm-7">
                <ul class="pagination">
                    <? foreach($pager as $page): ?>
                        <li class="page-item <? echo $page['class'] ?>"><a class="page-link" href="/author/list/<? echo $page['urn'] ?>"><? echo $page['label'] ?></a></li>
                    <? endforeach ?>
                </ul>
            </div>
        </div>
    </div>
<? require_once ROOT . '/view/layers/footer.php' ?>




