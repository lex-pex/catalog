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
                <div class="row">
                    <div class="col-md-12 text-center">
                        <a href="/blog/1">< Prev Page</a> |<a href="/blog/2">Next Page ></a>
                    </div>
                </div>

            </div>
        </div>
    </div>
<? require_once ROOT . '/view/layers/footer.php' ?>























