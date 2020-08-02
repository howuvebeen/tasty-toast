<?php
include('includes/init.php');
?>

<?php
// Add a new tag that belongs to the image
if (isset($_POST["add_new_tag"])) {
  $tag_name = strtolower(filter_input(INPUT_POST, 'tag_new', FILTER_SANITIZE_STRING));

  $sql = "SELECT * FROM tags WHERE tag_name = :tag_name";
  $params = array(':tag_name' => $tag_name);
  $result = exec_sql_query($db, $sql, $params)->fetchAll();

  if (empty($result)) {
      $sql = "INSERT INTO tags (tag_name) VALUES (:tag_name)";
      $params = array(':tag_name' => $tag_name);
      $result = exec_sql_query($db, $sql, $params);

      $image_id = $_GET['calm'];
      $tag_id = $db->lastInsertId("id");
      $sql = "INSERT INTO images_tags (image_id, tag_id) VALUES (:image_id, :tag_id)";
      $params = array(':image_id' => $image_id, ':tag_id' => $tag_id);

      $result = exec_sql_query($db, $sql, $params);
  }
}
?>
<?php
// Add an existing tag that belongs to the image
if (isset($_POST["add_exist_tag"])) {
  $image_id = $_GET['calm'];
  $tag_name = $_POST['tag_exist'];

  $sql = "SELECT * FROM tags WHERE tag_name = :tag_name";
  $params = array(':tag_name' => $tag_name);
  $result = exec_sql_query($db, $sql, $params)->fetchAll();
  $tag_id = $result[0]['id'];

  $sql = "SELECT * FROM images_tags WHERE image_id =:image_id AND tag_id = :tag_id";
  $params = array(':image_id' => $image_id, ':tag_id' => $tag_id);
  $result = exec_sql_query($db, $sql, $params)->fetchAll();

  if (empty($result)) {
      $sql = "INSERT INTO images_tags (image_id, tag_id) VALUES (:image_id, :tag_id)";
      $params = array(':image_id' => $image_id, ':tag_id' => $tag_id);
      $result = exec_sql_query($db, $sql, $params);
  }
}
?>
<?php
// Delete a tag that belongs to the image
$image_id = $_GET['calm'];

$sql = "SELECT * FROM images WHERE id = :id";
$params = array(':id' => $image_id);
$records = exec_sql_query($db, $sql, $params)->fetchAll();

if (isset($_POST["delete_tag"])) {
  $image_id = $_GET['calm'];
  $tag_name = $_POST['tag_delete'];

  $sql = "SELECT * FROM tags WHERE tag_name = :tag_name";
  $params = array(':tag_name' => $tag_name);
  $result = exec_sql_query($db, $sql, $params)->fetchAll();
  $tag_id = $result[0]['id'];

  $sql = "DELETE FROM images_tags WHERE image_id = :image_id AND tag_id = :tag_id";
  $params = array(':image_id' => $image_id, ':tag_id' => $tag_id);
  $result = exec_sql_query($db, $sql, $params)->fetchAll();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Tasty Toast</title>
  <link rel="stylesheet" type="text/css" href="styles/theme.css" media="all"/>
</head>

<body class="single">

<main>
    <h2>A Topping</h2>
    <div class="gallery-images">
      <div class="single-left">
        <p class="back"><a href=index.php>< Back</a></p>
      </div>
      <div class="single-middle">
        <div class="display-image">
          <?php
            // Display a clicked image and its information
            $image_id = $_GET['calm'];

            $sql = "SELECT * FROM images WHERE id = :id";
            $params = array(':id' => $image_id);
            $records = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
            // Citation provided in init.sql
            foreach($records as $record){
              echo "<h2 class='image_name'>" . ucfirst($record["image_name"]) . "</h2>";
              echo "<img class='single-image' alt='" . $record["image_name"] . "' src= \"" . IMAGE_UPLOADS_PATH . $record["id"] . "." . $record["image_ext"] . "\">";
              echo "<p class='image_src'> Source: " ."<a href=\"". $record["image_src"]. "\">Link</a> </p>";
            }
          ?>
        </div>
        <div class="display-tag">
            <?php
            // Display all tags for a clicked image
            $image_id = $_GET['calm'];

            $sql = "SELECT * FROM tags INNER JOIN images_tags ON tags.id = images_tags.tag_id
            WHERE images_tags.image_id = :id";
            $params = array(':id' => $image_id);
            $records = exec_sql_query($db, $sql, $params)->fetchAll();
            foreach($records as $record) {
              $url = array( 'have'=> $$record["id"]+8,
                            'fun' => $record["id"],
                            'enjoy' => $record["id"]+2);
              echo "<a class='single-tag' href= \"gallery.php?" . http_build_query($url) . "\">" . $record["tag_name"] . "</a>";
            }
          ?>
        </div>
      </div>
    <div class="single-right">
      <form id="addNewtag" action="single.php?<?php $url = array( 'stay'=> $image_id+3,
                      'calm' => $image_id,
                      'and' => $image_id+9,
                      'eat' => $image_id+1); echo http_build_query($url) ?>" method="POST" enctype="multipart/form-data">
        <div class="form-input">
            <label for="tag_new">Add a new tag:</label>
            <input id="tag_new" type="text" name="tag_new" maxlength="20" required>
            <span></span>
            <button name="add_new_tag" type="submit">Add</button>
        </div>
      </form>
      <form id="addExisttag" action="single.php?<?php $url = array( 'stay'=> $image_id+3,
                      'calm' => $image_id,
                      'and' => $image_id+9,
                      'eat' => $image_id+1); echo http_build_query($url) ?>" method="POST" enctype="multipart/form-data">
      <div class="form-input">
          <label for="tag_exist">Add an existing tag:</label>
          <select id="tag_exist" name="tag_exist">
          <?php
          $sql = "SELECT tag_name FROM tags EXCEPT SELECT DISTINCT tag_name FROM tags
          INNER JOIN images_tags ON tags.id = images_tags.tag_id
          WHERE images_tags.image_id = :image_id";
          $params = array(':image_id' => $image_id);
          $records = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
          foreach($records as $record) {
              echo "<option value= \"" . htmlspecialchars($record["tag_name"]) . "\">" . htmlspecialchars($record["tag_name"]).
              "</option>";
          }
          ?>
          </select>
          <span></span>
          <button name="add_exist_tag" type="submit">Add</button>
      </div>
      </form>
      <form id="deleteTag" action="single.php?<?php $url = array( 'stay'=> $image_id+3,
                      'calm' => $image_id,
                      'and' => $image_id+9,
                      'eat' => $image_id+1); echo http_build_query($url) ?>" method="POST" enctype="multipart/form-data">
      <div class="form-input">
          <label for="tag_delete">Delete a tag:</label>
          <select id="tag_delete" name="tag_delete">
          <?php
          $sql = "SELECT * FROM tags INNER JOIN images_tags ON tags.id = images_tags.tag_id
          WHERE images_tags.image_id = :image_id";
          $params = array(':image_id' => $image_id);
          $records = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
          foreach($records as $record) {
              echo "<option value= \"" . htmlspecialchars($record["tag_name"]) . "\">" . htmlspecialchars($record["tag_name"]).
              "</option>";
          }
          ?>
          </select>
          <span></span>
          <button name="delete_tag" type="submit">Delete</button>
      </div>
      </form>
    </div>
  </div>
</main>

</body>
</html>
