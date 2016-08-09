<?php
include 'header.php';
include 'postclass.php';

$postid = $_GET['postid'];

$article = new Post();
$result  = $article->getarticle($postid);

?>
<div class='row'>
<?php
while ($row = $result->fetch_assoc()) {
    ?>
  <div class="panel panel-default">
  <div class="panel-heading back">
    <h2> <?php echo $row['article_name'];?> </h2>
    <h5><span class="glyphicon glyphicon-time"></span> Posted On <?php echo $row['dates'];?></h5>
      
  </div>
  <div class="panel-body">
    <div class="col-md-3 pull-right">
    <img class='img-responsive img-rounded center' src="postimages/<?php echo $row['img'];?>">
    </div>
   <?php echo $row['article_content'];?>
  </div>
</div>
<?php
}
?>
</div>


  <?php
include 'footer.php';
?>