<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "home.php";
require "route.php";
require "config.php";
require "session.php";
require "response.php";
require "panels/admin.php";
require "user/profile.php";
require "database/database.php";
require "authentication/login.php";
require "authentication/register.php";

$config   = new Config();
$session  = new Session();
$response = new MakeResponse();
$router   = new Router(
    session: $session
);

$database = new Database(
    dsn: $config->db_driver,
    username: $config->db_user,
    password: $config->db_pass,
);

$conn = $database->connect();
if ($conn != false){
    $index = new Index(
        conn: $database,
        session: $session,
    );
    $login = new Login(
        conn: $database,
        session: $session,
    );
    $register = new Register(
        conn: $database,
    );
    $profile = new Profile(
        conn: $database,
        session: $session
    );
    $admin = New Admin(
        conn: $database,
        session: $session
    );
}else {
    exit(0);
}




// index pages
$router->addRoute('GET', '/', false, function (Request $request) {
    global $index;
    global $session;
    $index->showPosts();
});

$router->addRoute('GET', '/search/:text', false,function (Request $request, string $text) {
    global $index;
    $index->search($text);
});

$router->addRoute('GET', '/category/:category_id',false, function (Request $request, $category_id) {
    global $index;
    $index->category($category_id);
});
// logout route

$router->addRoute('GET', '/logout',true, function (Request $request) {
    global $session;
    $session->destroy();
    
});

// login page
$router->addRoute('GET', '/login',false, function (Request $request) {
    global $login;
    $login->loadPage();
});

$router->addRoute('POST', '/login',false, function (Request $request) {
    global $login;
    $username = $request->getPost("username");
    $password = $request->getPost("password");
    $respones = $login->login(
        username: $username,
        passwd: $password
    );
    echo $respones;
});

// register page
$router->addRoute('POST', '/register',false, function (Request $request) {
    global $register;
    $fisrt_name = $request->getPost("first_name");
    $last_name = $request->getPost("last_name");
    $gmail = $request->getPost("gmail");
    $phone = $request->getPost("phone");
    $username = $request->getPost("username");
    $password = $request->getPost("password");
    $profile = "";
    $role = "user";
    $respones = $register->registerUser(
        firstName: $fisrt_name,
        lastName: $last_name,
        gmail: $gmail,
        phone: $phone,
        userName: $username,
        password: $password,
        profile: $profile,
        role: $role
    );
    echo $respones;
});


$router->addRoute('GET', '/register',false, function (Request $request) {
    global $register;
    $register->loadPage();
});

// profile page
$router->addRoute('GET', '/profile', true, function (Request $request) {
    global $profile;
    $profile->loadPage();
});

$router->addRoute('POST', '/profile',true, function (Request $request) {
    global $profile;
    $fisrt_name = $request->getPost("first_name");
    $last_name = $request->getPost("last_name");
    $gmail = $request->getPost("gmail");
    $password = $request->getPost("password");
    $profile_ = "";
    $respones = $profile->setupInfo(
        firstName: $fisrt_name,
        lastName: $last_name,
        password: $password,
        profile: $profile_,
        gmail: $gmail,

    );
    echo $respones;

});

// admin page
// GET METHOD 
$router->addRoute('GET', '/admin', true, function (Request $request) {
    global $admin;
    $admin->loadPage();
});

$router->addRoute('GET', '/admin/delete-user', true, function (Request $request) {
    global $database;
    $response = $database->getAllUser();
    echo $response;
});

$router->addRoute('GET', '/admin/delete-writer', true, function (Request $request) {
    global $database;
    $response = $database->getAllWriter();
    echo $response;
});

$router->addRoute('GET', '/admin/delete-post', true, function (Request $request) {
    global $database;
    $response = $database->getAllBlogs();
    echo $response;
});

$router->addRoute('GET', '/admin/add-writer', true, function (Request $request) {
    global $database;
    $response = $database->getAllUsername();
    echo $response;
});

// POST METHOD 
$router->addRoute('POST', '/admin/delete-user', true, function (Request $request) {
    global $admin;
    $userid = $request->getPost("userid");
    $response = $admin->deleteUser(
        userId: $userid
    );
    echo $response;
});

$router->addRoute('POST', '/admin/delete-writer', true, function (Request $request) {
    global $admin;
    $writerid = $request->getPost("writerid");
    $response = $admin->deleteWriter(
        writerId: $writerid
    );

    echo $response;
});

$router->addRoute('POST', '/admin/delete-post', true, function (Request $request) {
    global $admin;
    $postid = $request->getPost("postid");
    $response = $admin->deletePost(
        postId: $postid
    );
    echo $response;
});

$router->addRoute('POST', '/admin/add-writer', true, function (Request $request) {
    global $admin;
    $first_name = $request->getPost("first_name");
    $last_name = $request->getPost("last_name");
    $gmail = $request->getPost("gmail");
    $phone = $request->getPost("phone");
    $username = $request->getPost("username");
    $password = $request->getPost("password");
    $profile = "";
    $role = "writer";
    $respones = $admin->addWriter(
        first_name: $first_name,
        last_name: $last_name,
        gmail: $gmail,
        phone: $phone,
        username: $username,
        password: $password,
        profile: $profile,
        role: $role
    );
    echo $respones;

});

$router->addRoute('GET', '/admin/add-writer/:username', true, function (Request $request , string $username) {
    global $admin;
    $respones = $admin->upgradeToWriter(
        username: $username,
    );
    echo $respones;

});

// witer page



// $router->addRoute('POST', '/blogid', function (Request $request) {
//     $title = $request->getPost('title');
//     echo 'Blog post created successfully!';
//     exit;
// });

$router->matchRoute();
?>