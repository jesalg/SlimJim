<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>SlimJim CMS</title>
        <link rel="stylesheet" type="text/css" href="<?= base_url?>css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url?>css/slimjim.css" />
    </head>

    <body>

        <div id="overview" class="header masthead">
            <div class="container">
                <h1>SlimJim Log In</h1>
                <p>Add your git project to the database to deploy them!</p>
            </div>
        </div>

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