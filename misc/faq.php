<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $config['pr_title']; ?></title>
        <link rel="stylesheet" href="/static/css/required.css"> 
    </head>
    <body>
        <div class="container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/header.php"); ?>
            <br>
            <div class="padding">
                <h1 id="noMargin">Q&A</h1>
                <b>Why?</b><br>
                I just thought making something like this would be a good project and a way to make my code practices better. This is mostly for fun and I did not expect so many people to join.<br><br>
                <b>What is CSS?</b><br>
                CSS is a way to customize your profile and your blogs. It's a markup language (HTML, XML, XHTML, etc.) There are tons of tutorials on how to use CSS. You can edit your CSS by going to the manage page.<br><br>
                <b>Why don't my images load?</b><br> 
                We use a think called an "Image Proxy" which blacklist certain sites (rule34, pornhub, etc) and also checks if the link is malicious (IP logger, etc) and filters out everything. If you want to use images in your CSS or markup images, you have to prefix the link with <b>https://images.weserv.nl/?url=</b><br><br>
                <b>What is markdown?</b><br>
                Markdown is a "chat interpreter" similar to BBCode. It turns "tags" into HTML (ex. ** = bold), and you can use the same thing for images. Read https://www.markdownguide.org/ for more info about markdown and how to use it in comments. It is also the same thing that Discord uses.
            </div>
        </div>
        <br>
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
    </body>
</html>