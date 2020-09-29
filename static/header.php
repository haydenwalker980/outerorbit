<div class="headerTop">
    <a href="/index.php"><img src="/static/spacemy.png"></a> &nbsp; <small id="floatRight"><a href="/login.php">Login</a> &bull; <a href="/register.php">Register</a>&nbsp;&nbsp;</small><br>
    <span id="floatRight">
        <form method="get" action="/browse.php">
        <select name="searchmethod">
            <option value="users">User</option>
            <option value="blog">Blog</option>
            <option value="groups">Group</option>
        </select>
        <input type="text" size="30" name="search"> <input type="submit" value="Search">
        </form> 
    </span>
</div>
<div class="headerBottom">
    <small><a href="/groups">Groups</a> &bull; <a href="/blogs">Blogs</a> &bull; <a href="/pms.php">PMs</a> &bull; <a href="/friends/">Friends</a> &bull; <a href="/jukebox.php">Jukebox</a> &bull; <a href="/users.php">All Users</a> <?php if(isset($_SESSION['siteusername'])) { echo "<span id='floatRight'><span id='custompadding'><a href='/edit'>Edit Items</a> &bull; <a href='/manage.php'>Manage User</a> &bull; " . htmlspecialchars($_SESSION['siteusername']) . "</span></span>"; } ?></small>
</div>
<?php
if(isset($_SESSION['siteusername'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['siteusername']);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0) die("U have been ban");
    $stmt->close();
}
?>