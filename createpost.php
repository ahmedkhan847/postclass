<?php

include 'header.php';
include 'postclass.php';
        
$crud   = new Post();
$errors = [
    'article_name'     => null,
    'article_category' => null,
    'article_content'  => null,
    'article_img'      => null,
    'form'             => null,
];

$conne         = '';
$form          = true;
$target_dir    = "postimages/";
$target_file   = null;
$imageFileType = null;
if (!empty($_POST)) {

    $target_file   = $target_dir . basename($_FILES["article_img"]["name"]);
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    $check         = getimagesize($_FILES["article_img"]["tmp_name"]);
    if (empty($_POST['a_name'])) {
        $errors['article_name'] = "Please Enter Your Title";
        $form                   = false;
    }
    if ($check === false) {
        $errors['article_img'] = 'Image is required';
        $form                  = false;
    } elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $errors['article_img'] = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
        $form                  = false;
    }

    if (empty($_POST['article_content'])) {
        $errors['article_content'] = "Please Fill Your Content";
        $form                      = false;
    }

    if ($form) {

        $articlename = $_POST['a_name'];
        $articlecont = $_POST['article_content'];
        
        $image       = rand(1, 100);
        $imgname     = $image . "." . $imageFileType;
        $result      = $crud->insertpost($articlename, $articlecont, $imgname);

        if ($result == 'true') {
            move_uploaded_file($_FILES["article_img"]["tmp_name"], $target_dir . $imgname);
            header('Location: index.php');
            return;
        } else {
            $errors['form'] = $result;
        }

    }

} else {
    $errors['form'] = "Kindly Fill All the Fields";
}

?>
<!--<script type="text/javascript" src="tinymce/tinymce.min.js"></script>-->
<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
    <script type="text/javascript">
        // tinymce.init({
        //     selector: "#article_content"
        // });

bkLib.onDomLoaded(function() {
  new nicEditor().panelInstance('article_content');

});
    </script>

<form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
<fieldset>

<!-- Form Name -->
<legend>Create Post</legend>

<!-- Text input-->
<div class="form-group col-md-10">
  <label class="control-label" for="a_name">Create Post</label>

  <input id="a_name" name="a_name" type="text" placeholder="Enter Title For Your Article" class="form-control input-md"  value="<?php if (isset($_POST['a_name'])) {echo $_POST['a_name'];}
?>" required>
    <p class="text-danger"><?php echo $errors['article_name'] ?></p>

</div>

<!-- File Button -->
<div class="form-group col-md-10">
  <label class="control-label" for="article_img">Upload Image</label>

    <input id="article_img" name="article_img" class="input-file" type="file" required>

     <p class="text-danger"><?php echo $errors['article_img'] ?></p>

</div>

<!-- Textarea -->
<div class="form-group col-md-10" >
  <label class="control-label" for="article_content">Content</label>

    <textarea class="form-control" id="article_content" name="article_content" rows="13"><?php if (isset($_POST['article_content'])) {echo $_POST['article_content'];}
?></textarea>
    <p class="text-danger"><?php echo $errors['article_content'] ?></p>

</div>

<!-- Button -->
<div class="form-group col-md-10">
  <label class="control-label" for="submit"></label>

    <button id="submit" name="submit" type="submit" class="btn btn-success">Publish</button>
    <p class="text-danger"><?php echo $errors['form'] ?></p>

</div>

</fieldset>
</form>


<?php include 'footer.php';?>