<div id="data-content">
    <div style="text-align: center; margin-bottom: 35px;">
        <a class="btn btn-primary" href="/admin/settings/add/0">Add New Setting</a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>key</th>
                <th>value</th>
                <th>actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data['settings'] as $setting):?>
                <tr>
                    <td><?= $setting->key?></td>
                    <td><?= $setting->value?></td>
                    <td><a class="btn btn-success" href="/admin/settings/add/<?= $setting->id?>">Edit</a>&nbsp;<a class="btn btn-danger" href="/admin/settings/delete/<?= $setting->id?>" onclick="return confirm('Are you sure you want to delete this setting?')">Remove</a></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>