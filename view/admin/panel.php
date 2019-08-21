<? require_once ROOT . '/view/layers/header.php' ?>
    <div class="container">
        <div class="row justify-content-center pt-4">
            <div class="col-md-6">
                <div class="card mc-cabinet">
                    <div class="card-header">
                        <h6 class="text-muted text-right">Admin Panel</h6>
                    </div>
                    <div class="card-body">
                        <p class="mc-alert"><a href="<? route('user/list') ?>">All Users</a></p>
                        <p class="mc-alert">
                            <a href="<? route('magazine/list') ?>">All Magazines</a> |
                            <a href="<? route('magazine/create') ?>">Add New</a>
                        </p>
                        <p class="mc-alert">
                            <a href="<? route('author/list') ?>">All Authors</a> |
                            <a href="<? route('author/create') ?>">Add New</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? require_once ROOT . '/view/layers/footer.php' ?>




