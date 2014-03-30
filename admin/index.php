<?php
/* 
 *CMS SECTION FOR SLIMJIM
 */

require '../libs/Slim/Slim.php';
require '../libs/Paris/idiorm.php';
require '../libs/Paris/paris.php';
require '../models/Project.php';
require '../models/Setting.php';
require '../models/Admin.php';
require '../libs/Slim/CustomView.php';
require '../config.php';

ORM::configure('mysql:host='.CUSTOM_CONFIG::$DB_HOST . ';dbname='.CUSTOM_CONFIG::$DB_NAME);
ORM::configure('username', CUSTOM_CONFIG::$DB_USER);
ORM::configure('password', CUSTOM_CONFIG::$DB_PASS);

$app = new Slim(array(
    'view' => new CustomView()
));

$app->add(new Slim_Middleware_SessionCookie(array(
    'expires' => '1440 minutes',
    'name' => 'slim_session',
    'secret' => 'slimjim'
)));

$app->add(new Slim_Middleware_Flash());

//default layout
CustomView::set_layout('/layouts/default.php');

//Set base URL
$app->hook('slim.before', function () use ($app) {
    if(isset($_SERVER['HTTP_HOST'])){
        $base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
        $base_url .= '://'. $_SERVER['HTTP_HOST'];
        $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    }else{
        $base_url = 'http://localhost';
    }

    define('base_url', $base_url);
});

// Auth Check.
$authCheck = function() use ($app) {
    if (!isset($_SESSION['username'])) {
        CustomView::set_layout('/layouts/login.php');
        $app->render('loginForm.php', array());
        exit;
    }
};

//Admin Home
$app->get('/', $authCheck, function () use ($app) {
    $data['currNav']  = 'home';

    $app->render('home.php', array('data' => $data));
});

//Validate the username and password
$app->post('/login', function () use ($app) {
    if($app->request()->isPost()){
        $req = $app->request();
        $inputUsername = $req->post('username');
        $inputpassword = $req->post('password');

        $user = Model::factory('Admin')
            ->where_equal('username', $inputUsername)
            ->where_equal('password', md5($inputpassword))
            ->find_one();
        if(isset($user->username)){
            if($user->username == $inputUsername){
                $_SESSION['username'] = $inputUsername;
                $app->flash('success', 'Welcome ' . $inputUsername);
            }
        }else{
            $app->flash('error', 'User does not exist!');
        }
    }else{
        $app->flash('error', 'you must insert your credentials!');
    }

    $app->redirect('/admin');
});

//Logout
$app->get('/logout', function () use ($app) {
    $_SESSION = array();

    $data['currNav'] = 'home';
    $app->flash('error', 'You are logged out!');
    $app->redirect('/admin', array('data' => $data));
});

//Show Projects
$app->get('/projects', $authCheck, function () use ($app) {
    $data['projects'] = Model::factory('Project')->find_many();
    $data['currNav']  = 'projects';
    $app->render('projects/projects.php', array('data' => $data));
});

//Add and edit project
$app->get('/projects/add/:id', $authCheck, function ($id = 0) use ($app) {
    $data['currNav']  = 'projects';

    //Edit
    if($id > 0){
        $data['project'] = ORM::for_table('projects')->find_one($id);
    }

    $app->render('projects/addProject.php', array('data' => $data));
});

//Create new project or edit it
$app->post('/projects/add', $authCheck, function () use ($app) {
    $data['currNav']  = 'projects';
    $data['projects'] = Model::factory('Project')->find_many();

    //Validate
    if($app->request()->post('clone_url')){

        //Edit
        if($app->request()->post('id')){
            $project = ORM::for_table('projects')->find_one($app->request()->post('id'));
            $app->flash('success', 'Project updated!');

        }else{ //New Item
            $project = ORM::for_table('projects')->create();
            $app->flash('success', 'Project created!');
        }

        $project->clone_url = $app->request()->post('clone_url');
        $project->name = $app->request()->post('name');
        $project->branch = $app->request()->post('branch');
        $project->path = $app->request()->post('path');
        $project->save();
    }else{
        $app->flash('error', 'Project could not be created!');
    }

    $app->redirect('/admin/projects', array('data' => $data));
});

//Remove the project
$app->get('/projects/delete/:id', $authCheck, function ($id) use ($app){
    $data['currNav']  = 'projects';
    $data['projects'] = Model::factory('Project')->find_many();

    $project = ORM::for_table('projects')->find_one($id);
    $project->delete();

    $app->flash('error', 'Project Removed!');

    $app->redirect('/admin/projects', array('data' => $data));
});

//Show settings
$app->get('/settings', $authCheck, function () use ($app) {
    $data['currNav']  = 'settings';
    $data['settings'] = Model::factory('Setting')->find_many();

    $app->render('settings/settings.php', array('data' => $data));
});

//Add and edit settings form
$app->get('/settings/add/:id', $authCheck, function ($id) use ($app) {
    $data['currNav']  = 'settings';

    //Edit
    if($id > 0){
        $data['setting'] = ORM::for_table('settings')->find_one($id);
    }

    $app->render('settings/addSetting.php', array('data' => $data));
});

//Create new setting or edit it
$app->post('/settings/add', $authCheck, function () use ($app) {
    $data['currNav']  = 'settings';
    $data['settings'] = Model::factory('Setting')->find_many();

    //Validate
    if($app->request()->post('key') != ''){

        //Edit
        if($app->request()->post('id')){
            $setting = ORM::for_table('settings')->find_one($app->request()->post('id'));
            $app->flash('success', 'Setting updated!');

        }else{ //New Item
            $setting = ORM::for_table('settings')->create();
            $app->flash('success', 'Setting created!');
        }

        $setting->key = $app->request()->post('key');
        $setting->value = $app->request()->post('value');
        $setting->save();
    }else{
        $app->flash('error', 'Setting could not be created!');
    }

    $app->redirect('/admin/settings', array('data' => $data));
});

//Remove the setting
$app->get('/settings/delete/:id', $authCheck, function ($id) use ($app){
    $data['currNav']  = 'settings';
    $data['settings'] = Model::factory('Setting')->find_many();

    $setting = ORM::for_table('settings')->find_one($id);
    $setting->delete();

    $app->flash('success', 'Setting Removed!');

    $app->redirect('/admin/settings', array('data' => $data));
});

//Show change password form
$app->get('/profile', $authCheck, function () use ($app) {
    $data['currNav'] = 'profile';
    $app->render('profile/profile.php', array('data' => $data));
});

//Change the password
$app->post('/profile', $authCheck, function () use ($app) {
    $data['currNav'] = 'profile';
    if($app->request()->post('password') == '' || $app->request()->post('repassword') == ''){
        $app->flashNow('error', 'Please inset a valid password!');
    }elseif($app->request()->post('password') == $app->request()->post('repassword')){
        $user = ORM::for_table('admins')->where('username', 'admin')->find_one();
        $user->password = md5($app->request()->post('password'));

        $user->save();
        $app->flashNow('success', 'Password Saved!');
    }else{
        $app->flashNow('error', 'Password did not match, Please try again!');
    }

    $app->render('profile/profile.php', array('data' => $data));
});


$app->run();

?>
