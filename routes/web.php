<?php

/* ----------------------------------------------------
 *  .    .... .   .     ... .... .   .
 *  .    ..     .   ..  ... ...    .
 *  .... .... .   .     .   .... .   .
 *
 * Here are the routs of the Web-App
 * Routes assigned to Controllers actions
 * Actions are called after the slash
 * Parameters that need pass the action have
 * to be include in curly brackets {param}
 *
 * To quick start launch Migrations and Seeds
 * Init Data run on request: create_and_seed_all_tables
 *
 */

return [

    '/' => 'IndexController/index',
    'page/{n}' => 'IndexController/index',   // Pager

    '/show/magazine/{id}' => 'IndexController/showMagazine', // showMagazine

    /* __________ Template for Main Page __________ */
    'main_template' => 'TestController/mainTemplate',

    /* __________ User Routes ( login / register ) __________  */

    'register' => 'UserController/create',              // get
    'login' => 'UserController/login',                  // get - post
    'logout' => 'UserController/logout',                // post

    'cabinet' => 'UserController/cabinet',              // get    | show
    'user/create' => 'UserController/create',           // get    | create
    'user/store' => 'UserController/store',             // post   | store

    'user/{id}/edit' => 'UserController/edit',          // get    | edit
    'user/update' => 'UserController/update',           // post   | update
    'user/destroy' => 'UserController/destroy',         // delete | destroy

    /* __________ Admin Routes __________ */

    'user/list' => 'UserController/list',               // get
    'cabinet/{id}' => 'UserController/cabinet',         // get
    'admin' => 'admin/AdminController/panel',                 // get panel

    /* __________ Magazine Routes __________ */

    'magazine/create' => 'magazine/MagazineController/create',   // get    | create  (1)
    'magazine/store' => 'magazine/MagazineController/store',     // post   | store   (2)

    'magazine/{id}/edit' => 'magazine/MagazineController/edit',  // post   | edit    (3)
    'magazine/update' => 'magazine/MagazineController/update',   // post   | update  (4)

    'magazine/destroy' => 'magazine/MagazineController/destroy', // delete | destroy (5)

    'magazine/list' => 'magazine/MagazineController/list',       // get    | list    (6)
    'magazine/list/page/{n}' => 'magazine/MagazineController/list',   // Pager       (7)
    'magazine/{id}' => 'magazine/MagazineController/show',       // get    | show    (8)
    // Such route has to be last cause token parameter  takes away any other URI segments

    /* __________ Author Routes __________ */

    'author/create' => 'author/AuthorController/create',   // get    | create  (1)
    'author/store' => 'author/AuthorController/store',     // post   | store   (2)

    'author/{id}/edit' => 'author/AuthorController/edit',  // post   | edit    (3)
    'author/update' => 'author/AuthorController/update',   // post   | update  (4)

    'author/destroy' => 'author/AuthorController/destroy', // delete | destroy (5)

    'author/list' => 'author/AuthorController/list',       // get    | list    (6)
    'author/list/page/{n}' => 'author/AuthorController/list',   // Pager       (7)
    'author/{id}' => 'author/AuthorController/show',       // get    | show    (8)

    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
     * Data Base Adjustment. Migrations and Seeds for all tables
     * Populate with 10 users and 1 Admin
     * ( user-1@m.org / 87654321 ) ( admin@m.com / secret_admin )
     * - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
    // 'create_and_seed_all_tables' => 'Migrations/create_and_seed_all_tables'

    /* __________  Data Base Testing __________ */

    'data_base_testing' => 'zero_tests/DB/display',

    /* __________  Testing of This Router __________ */

    'one/two/{id}/{name}' => 'TestController/test',
    'one/two/three/four' => 'TestController/test',
    'one/{PAR_1}/two/{PAR_2}' => 'TestController/test',
];

/*  Reminder
                       ------- REST API -------
 | GET 	       /photos 	              index      photos. index      | 1
 | GET 	       /photos/create 	      create 	 photos. create     | 2
 | POST        /photos 	              store      photos. store      | 3
 | GET 	       /photos/{photo} 	      show 	     photos. show       | 4
 | GET 	       /photos/{photo}/edit   edit 	     photos. edit       | 5
 | PUT/PATCH   /photos/{photo} 	      update 	 photos. update     | 6
 | DELETE      /photos/{photo} 	      destroy 	 photos. destroy    | 7

*/










