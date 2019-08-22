<? require_once ROOT . '/view/layers/header.php' ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row">
                    <? foreach($items as $i): ?>
                    <div class="col-md-6 pt-4">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="text-center"><? echo $i->name ?></h3>
                                <div class="row">
                                    <div class="col-6">
                                        <p>
                                            <? echo $i->description ?>
                                            <br/>
                                            <a href="/show/magazine/<? echo $i->id ?>"> &raquo; Read more...</a>
                                        </p>
                                    </div>
                                    <div class="col-6">
                                        <img src="<? echo $i->image ? $i->image : '/img/upload/magazines/cover.jpg' ?>" width="100%"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <? endforeach ?>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row justify-content-center"> <!-- Pagination -->
            <div class="col-lg-3 col-md-5 col-sm-7">
                <ul class="pagination" role="navigation">
                    <? foreach($pager as $page): ?>
                        <li class="page-item <? echo $page['class'] ?>"><a class="page-link" href="/<? echo $page['urn'] ?>"><? echo $page['label'] ?></a></li>
                    <? endforeach ?>
                </ul>
            </div>
        </div>
    </div>
<? require_once ROOT . '/view/layers/footer.php' ?>























