    <div id="login" class="container" style="text-align: center; margin: 0px auto; width: 450px;">
        <form class="form-horizontal" action="/admin/login" method="POST">
            <legend>Sign In</legend>
            <div class="control-group">
                <label class="control-label" for="inputUsername">Username</label>
                <div class="controls">
                    <input type="text" name ="username" id="inputUsername" placeholder="admin">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputPassword">Password</label>
                <div class="controls">
                    <input type="password" name="password" id="inputPassword" placeholder="admin">
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <button type="submit" class="btn">Sign in</button>
                </div>
            </div>
        </form>
    </div>