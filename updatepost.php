<?php
//error_reporting(-1);
error_reporting(E_ERROR | E_PARSE);
include 'header.php';
include 'postclass.php';
$user = $_SESSION["username"];
$aid = null;

if(empty($_POST))
{
  $aid = $_GET['postid'];
}
else{
  $aid = $_POST['postid'];
}


$crud = new Post();
$errors = [
        'article_name' => null,
        'article_category' => null,
        'article_content' => null,
        'article_img' => null,
        'form' => null
    ];
$article = [
        'a_id' => $aid,
        'a_name' => null,
        'a_con' => null,
        'a_img' => null,
        'form' => null
    ];

    $result = $crud->getarticle($aid);

    while($row = $result->fetch_assoc())
        {
          $article['a_name'] = $row['article_name'];
          $article['a_con'] = $row['article_content'];
          $article['a_img'] = $row['img'];
        }

    $conne='';
    $form = true;
    $target_dir = "postimages/";
    $target_file = null;
    $imageFileType = null;
    $Upload = false;

  //  var_dump($article);
if(!empty($_POST))
{	
	
	$target_file = $target_dir . basename($_FILES["article_img"]["name"]);
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
  $check = false;
	$check = getimagesize($_FILES["article_img"]["tmp_name"]);

	if($_POST['a_name'] !== $article['a_name'])
	{
		$article['a_name'] = $_POST['a_name'];
	
	}
	if ($check !== false)
	{
  	$Upload = true;
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" ) 
	{
	   $errors['article_img'] = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
	   $form =false;
	}
  }

	if($_POST['article_content'] !== $article['a_con'])
	{
		$article['a_con'] = $_POST['article_content'];
		
	}

if($form)
{
  
  
  if($Upload)
  {
            $image = rand(1,100);
            $imgname = $image .".".$imageFileType;
            $result = $crud->updatearticle($article['a_id'],$article['a_con'],$article['a_name'],$imgname);
          if($result == 'true')
              {
                move_uploaded_file($_FILES["article_img"]["tmp_name"], $target_dir.$imgname);
                          header("Location: postview.php?postid=$aid");
                               return;
              }
          else
              {
                $errors['form'] = $result;
              }
  }
  else
  {
          var_dump($article);
          $result =  $crud->updatearticle($article['a_id'],$article['a_con'],$article['a_name'],$article['a_img']);
          if($result == 'true')
              {
                
               header("Location: postview.php?postid=$aid");
                               return;
              }
          else
              {
                $errors['form'] = $result;
              }
  }

  
	
}

}
else
{
	$errors['form'] = "Kindly Fill All the Fields";
}


?>
<!--<script type="text/javascript" src="tinymce/tinymce.min.js"></script>-->
<script type="text/javascript" src="style/js/nicEdit.js"></script>
    <script type="text/javascript">
        // tinymce.init({
        //     selector: "#article_content"
        // });

bkLib.onDomLoaded(function() {
  new nicEditor().panelInstance('article_content');
  
});
    </script>

<form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<fieldset>
<input type="hidden" name="postid" value="<?php echo $_GET['postid']?>">
<!-- Form Name -->
<legend>Update Article</legend>

<!-- Text input-->
<div class="form-group col-md-10">
  <label class="control-label" for="a_name">Title</label>  
  
  <input id="a_name" name="a_name" type="text" placeholder="Enter Title For Your Article" class="form-control input-md"  value="<?php echo $article['a_name'];  ?>" required>
    <p class="text-danger"><?php echo $errors['article_name']?></p>
  
</div>

<!-- File Button --> 
<div class="form-group col-md-10">
  <label class="control-label" for="article_img">Upload Image</label>
  
    <input id="article_img" name="article_img" class="input-file" type="file" value="<?php echo $article['a_img']?>">

     <p class="text-danger"><?php echo $errors['article_img']?></p>
  
</div>

<!-- Textarea -->
<div class="form-group col-md-10" >
  <label class="control-label" for="article_content">Content</label>
                      
    <textarea class="form-control" id="article_content" name="article_content" rows="13"><?php echo $article['a_con'];  ?></textarea>
    <p class="text-danger"><?php echo $errors['article_content']?></p>
  
</div>

<!-- Button -->
<div class="form-group col-md-10">
  <label class="control-label" for="submit"></label>
 
    <button id="submit" name="submit" class="btn btn-success">Update</button>
    <a href="articlelist" id="cancel" name="cancel" class="btn btn-danger">Cancel</a>
    <p class="text-danger"><?php echo $errors['form']?></p>
  
</div>

</fieldset>
</form>


<?php include 'footer.php';?>