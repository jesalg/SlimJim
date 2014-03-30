<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>SlimJim CMS</title>
        <link rel="stylesheet" type="text/css" href="<?= base_url?>css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url?>css/slimjim.css" />
    </head>

    <body>
        <?php if(isset($data['currNav'])):?>
            <div class="navbar navbar-inverse navbar-fixed-top">
                <div class="navbar-inner">
                    <div class="container">
                        <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="brand" href="/admin/logout" style="float:right;">Logout <?= isset ($_SESSION['username']) ? $_SESSION['username'] : ''?></a>
                        <div class="nav-collapse collapse">
                            <ul class="nav">
                                <li class="<?= ($data['currNav'] == 'home') ? 'active' : ''?>">
                                    <a href="/admin">Home</a>
                                </li>
                                <li class="<?= ($data['currNav'] == 'projects') ? 'active' : ''?>">
                                    <a href="/admin/projects">Projects</a>
                                </li>
                                <li class="<?= ($data['currNav'] == 'settings') ? 'active' : ''?>">
                                    <a href="/admin/settings">Settings</a>
                                </li>
                                <li class="<?= ($data['currNav'] == 'profile') ? 'active' : ''?>">
                                    <a href="/admin/profile">User Profile</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="header masthead" style="margin-top: 35px;">
                <div class="container">
                    <h1>SlimJim Admin</h1>
                    <p>Add your git project to the database to deploy it fast!</p>
                </div>
            </div>
        <?php endif;?>

        <div id="content">
            <div id="flash-message">
                <?php if(isset($flash['error'])):?>
                    <div class="error">
                        <?= $flash['error']?>
                    </div>
                <?php endif;?>
                <?php if(isset($flash['success'])):?>
                    <div class="success">
                        <?= $flash['success']?>
                    </div>
                <?php endif;?>
            </div>

            <?= $_html?>
        </div>

        <div id="footer">
            SlimJim 2014
        </div>

    </body>
</html>