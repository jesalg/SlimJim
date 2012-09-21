<div id="data-content">
    <div style="text-align: center; margin-bottom: 35px;">
        <a class="btn btn-primary" href="/admin/projects/add/0">Add New Project</a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>clone_url</th>
                <th>name</th>
                <th>branch</th>
                <th>path</th>
                <th>actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data['projects'] as $project):?>
                <tr>
                    <td><?= $project->clone_url?></td>
                    <td><?= $project->name?></td>
                    <td><?= $project->branch?></td>
                    <td><?= $project->path?></td>
                    <td><a class="btn btn-success" href="/admin/projects/add/<?= $project->id?>">Edit</a>&nbsp;<a class="btn btn-danger" href="/admin/projects/delete/<?= $project->id?>" onclick="return confirm('Are you sure you want to delete this project?')">Remove</a></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>