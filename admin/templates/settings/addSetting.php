<div id="login" class="container" style="text-align: center; margin: 0px auto; width: 450px;">
    <form class="form-horizontal" action="/admin/settings/add" method="POST">
        <legend>Add a setting</legend>
        <div class="control-group">
            <label class="control-label" for="inputkey">Key</label>
            <div class="controls">
                <input type="text" name="key" id="inputKey" placeholder="example: allowed_from" value="<?= (isset($data['setting']->key)) ? $data['setting']->key : ''?>"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputValue">Value</label>
            <div class="controls">
                <input type="text" name="value" id="inputValue" placeholder="value" value="<?= (isset($data['setting']->value)) ? $data['setting']->value : ''?>" />
            </div>
        </div>
        <?php if(isset($data['setting']->id)):?>
            <input type="hidden" name="id" value="<?= $data['setting']->id?>">
        <?php endif;?>
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="btn btn-primary"><?= (isset($data['setting']->id)) ? 'Update' : 'Add setting'?></button>
            </div>
        </div>
    </form>
</div>