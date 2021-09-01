<?php

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('', route('dashboard.index'), ['img' => '
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M8 3.293l6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"></path>
          <path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"></path>
        </svg>
    ']);
});

Breadcrumbs::for('worker', function ($trail, $inner_page = null) {
    $trail->parent('home');
    $trail->push(__("text.workers"), route('dashboard.worker.index'));
    if(!is_null($inner_page))
        $trail->push($inner_page);
});

Breadcrumbs::for('hall', function ($trail, $inner_page = null) {
    $trail->parent('home');
    $trail->push(__("text.halls"), route('dashboard.hall.index'));
    if(!is_null($inner_page))
        $trail->push($inner_page);
});

Breadcrumbs::for('template', function ($trail, $inner_page = null) {
    $trail->parent('home');
    $trail->push(__("text.templates"), route('dashboard.template.index'));
    if(!is_null($inner_page))
        $trail->push($inner_page);
});

Breadcrumbs::for('client', function ($trail, $inner_page = null) {
    $trail->parent('home');
    $trail->push(__("text.clients"), route('dashboard.client.index'));
    if(!is_null($inner_page))
        $trail->push($inner_page);
});

Breadcrumbs::for('booking', function ($trail, $inner_page = null) {
    $trail->parent('home');
    $trail->push(__("text.bookings"), route('dashboard.client.index'));
    if(!is_null($inner_page))
        $trail->push($inner_page);
});

Breadcrumbs::for('settings', function ($trail, $inner_pages = []) {
    // dd()
    $trail->parent('home');
    // $trail->push("settings", route('dashboard.client.index'));
    if(empty($inner_pages)){
        $trail->push("settings");
    }else{
        $trail->push("settings", route('dashboard.settings.index'));
    }
    if(!empty($inner_pages))
        foreach($inner_pages as $inner_page){
            if(count($inner_page) == 1){
                $trail->push($inner_page[0]);
            }else{
                $trail->push($inner_page[0], $inner_page[1]);
            }
        }
});


// Breadcrumbs::for('create_worker', function ($trail) {
//     $trail->parent('worker');
//     $trail->push("new worker");
// });

// Home > About
// Breadcrumbs::for('about', function ($trail) {
//     $trail->parent('home');
//     $trail->push('About', route('about'));
// });
// 
// // Home > Blog
// Breadcrumbs::for('blog', function ($trail) {
//     $trail->parent('home');
//     $trail->push('Blog', route('blog'));
// });
// 
// // Home > Blog > [Category]
// Breadcrumbs::for('category', function ($trail, $category) {
//     $trail->parent('blog');
//     $trail->push($category->title, route('category', $category->id));
// });
// 
// // Home > Blog > [Category] > [Post]
// Breadcrumbs::for('post', function ($trail, $post) {
//     $trail->parent('category', $post->category);
//     $trail->push($post->title, route('post', $post->id));
// });