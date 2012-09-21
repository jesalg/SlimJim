<div id="login" class="container" style="text-align: center; margin: 0px auto; width: 450px;">
    <form class="form-horizontal" action="/admin/projects/add" method="POST">
        <legend>Add a project</legend>
        <div class="control-group">
            <label class="control-label" for="inputCloneUrl">Clone URL</label>
            <div class="controls">
                <input type="text" name ="clone_url" id="inputCloneUrl" placeholder="git:github.com:[USERNAME]/project.git" value="<?= (isset($data['project']->clone_url)) ? $data['project']->clone_url : ''?>"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputName">Name</label>
            <div class="controls">
                <input type="text" name="name" id="inputName" placeholder="name" value="<?= (isset($data['project']->name)) ? $data['project']->name : ''?>" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputBranch">Branch</label>
            <div class="controls">
                <input type="text" name="branch" id="inputBranch" placeholder="Master/Develop" value="<?= (isset($data['project']->branch)) ? $data['project']->branch : ''?>"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputPath">Path</label>
            <div class="controls">
                <input type="text" name="path" id="inputPassword" placeholder="/srv/www/slimjim.calistolabs.com/public_html" value="<?= (isset($data['project']->path)) ? $data['project']->path : ''?>"/>
            </div>
        </div>
        <?php if(isset($data['project']->id)):?>
            <input type="hidden" name="id" value="<?= $data['project']->id?>">
        <?php endif;?>
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="btn btn-primary"><?= (isset($data['project']->id) ? 'Update' : 'Add Project')?></button>
            </div>
        </div>
    </form>
</div>