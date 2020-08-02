<?php
include('includes/init.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Tasty Toast</title>
  <link rel="stylesheet" type="text/css" href="styles/theme.css" media="all"/>
</head>

<body class="gallery">

<main>
    <h2>A Type of Toast</h2>
    <div class="gallery-images">
      <div class="gallery-left">
        <p class="back"><a href=index.php>< Back</a></p>
      </div>
      <div class="gallery-right">
        <?php
          // record tag_name
          $tag_id = $_GET['fun'];
          $tag_name;

          $sql = "SELECT tag_name FROM tags WHERE tags.id = :id";
          $params = array(':id' => $tag_id);
          $records = exec_sql_query($db, $sql, $params)->fetchAll();
          foreach($records as $record) {
            $tag_name = $record[0];
          }
        ?>
        <h2>Ingredients for <strong><?php echo $record["tag_name"]?></strong></h2>
        <?php
        // Display all images for a clicked tag
        $tag_id = $_GET['fun'];

        $sql = "SELECT * FROM images INNER JOIN images_tags ON images.id = images_tags.image_id
        WHERE images_tags.tag_id = :id";
        $params = array(':id' => $tag_id);
        $records = exec_sql_query($db, $sql, $params)->fetchAll();
        foreach($records as $record) {
          $url = array( 'stay'=> $$record["id"]+3,
          'calm' => $record["id"],
          'and' => $record["id"]+9,
          'eat' => $record["id"]+1);
          echo "<a href= \"single.php?" . http_build_query($url) . "\"><img class='gallery-image' alt='" . $record["image_name"] . "' src= \"" . IMAGE_UPLOADS_PATH . $record["image_id"] . "." . $record["image_ext"] . "\">";
        }
        ?>
      </div>
    </div>

    <div class="gallery-tags">
      <?php
      // Display all tags
      $records = exec_sql_query($db, "SELECT * FROM tags")->fetchAll(PDO::FETCH_ASSOC);
      foreach($records as $record) {
        $url = array( 'have'=> $$record["id"]+8,
                        'fun' => $record["id"],
                        'enjoy' => $record["id"]+2);
        echo "<a class='index-tag' href= \"gallery.php?" . http_build_query($url) . "\">" . $record["tag_name"] . "</a>";
      }
      ?>
    </div>

</body>
</html>
