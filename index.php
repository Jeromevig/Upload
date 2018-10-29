<?php
include('inc/head.php');

// POST Update file
if (isset($_POST["content"]) || isset($_POST["file"])) {
    $fichier = $_POST["file"];
    $file = fopen($fichier, "w");
    fwrite ($file, $_POST["content"]);
    fclose ($file);
}
// POST remove
if (isset($_POST["remove"])){
    if (is_dir($_POST["remove"]))
        rrmdir($_POST["remove"]);
    else
        unlink ($_POST["remove"]);
}
// Function remove
function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir."/".$object) == "dir") rmdir($dir."/".$object);
                else unlink($dir."/".$object);
            }
        }
        reset($objects);
        rmdir($dir);
    }
}
// Function view folder
function viewList($source = "./files")
{
    $dir = scandir($source);
    foreach ($dir as $file) {
        $info = new SplFileInfo($file);
        if ($info->getExtension()=="html" || $info->getExtension()=="txt") {
            echo '<div style="float:left; margin:0 10px; text-align:center;"><a href="?f=' . $source . "/" . $file . '"><img src="/assets/images/FBI-LOGO2.png" width="60"/><br>';
            echo $file;
            echo '</a><br>';?>
            <form method="POST" action="index.php">
                <input type="hidden" name="remove" value="<?php echo $source . "/" . $file;?>">
                <input type="submit" value="Delete">
            </form></div><?php
        } elseif ($info->getExtension()!="jpg" && $file!= "." && $file != "..") {
            echo '<div style="float:left; margin:0 10px; text-align:center;"><a href="?d=' . $source . "/" . $file . '"><img src="/assets/images/dossier.png" width="60"/><br>';
            echo $file;
            echo '</a><br>';?>
            <form method="POST" action="index.php">
                <input type="hidden" name="remove" value="<?php echo $source . "/" . $file;?>">
                <input type="submit" value="Delete">
            </form></div><?php
        }
    }
}
?>



<?php
// v"Files"
if (isset($_GET["d"]))
    viewList($_GET["d"]);
else
    viewList();
?>
    <br>
<?php
// Content file
if(isset($_GET["f"])) {
    $files = $_GET["f"];
    $file = basename($files, '.php');
    echo "<h3>{$file}</h3>";
    $content = file_get_contents($files);
    ?>
    <form action="/" method="POST">
        <div class="row">
            <br>
            <label for="content"></label>
            <textarea name="content" style="width: 100%;" rows="20"><?= $content; ?></textarea>
        </div>
        <div class="row">
            <input type="hidden" name="file" value="<?php echo $_GET["f"]; ?>">
            <input type="submit" style="width: 100%;" value="Enregistrer les modifications">
        </div>
    </form>
    <?php
}
?>

<?php include('inc/foot.php'); ?>