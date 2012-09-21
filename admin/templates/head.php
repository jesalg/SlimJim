<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>SlimJim CMS</title>
        <link rel="stylesheet" type="text/css" href="<?= $_SERVER['SERVER_NAME']?>:88/admin/css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?= $_SERVER['SERVER_NAME']?>:88/admin/css/slimjim.css" />
    </head>

    <body>
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="brand" href="/admin/logout" style="float:right;">Logout <?= $_SESSION['username']?></a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li class="<?= ($currNav == 'home') ? 'active' : ''?>">
                                <a href="/admin">Home</a>
                            </li>
                            <li class="<?= ($currNav == 'projects') ? 'active' : ''?>">
                                <a href="/admin/projects">Projects</a>
                            </li>
                            <li class="<?= ($currNav == 'settings') ? 'active' : ''?>">
                                <a href="/admin/settings">Settings</a>
                            </li>
                            <li class="<?= ($currNav == 'profile') ? 'active' : ''?>">
                                <a href="/admin/profile">User Profile</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="header masthead" style="margin-top: 35px;">
            <div class="container">
                <h1>SlimJim Log In</h1>
                <p>Add your git project to the database to deploy it fast!</p>
            </div>
        </div>