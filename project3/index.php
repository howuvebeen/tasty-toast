<?php
include("includes/init.php");
?>
<?php
const MAX_FILE_SIZE = 1000000;
// Upload an image
if (isset($_POST["upload_image"])) {
    $upload_info = $_FILES["image_file"];
    $upload_src = filter_input(INPUT_POST, 'image_src', FILTER_SANITIZE_STRING);

    if ($upload_info['error'] == 0) {
        $upload_name = basename($upload_info["name"]);
        $upload_ext = strtolower(pathinfo($upload_name, PATHINFO_EXTENSION) );
        $upload_name = basename($upload_info["name"], ".".$upload_ext);
        $sql = "INSERT INTO images (image_name, image_ext, image_src) VALUES (:image_name, :image_ext, :image_src)";
        $params = array(
            ':image_name' => $upload_name,
            ':image_ext' => $upload_ext,
            ':image_src' => $upload_src,
    );
    $result = exec_sql_query($db, $sql, $params);
    if ($result) {
        $id = $db->lastInsertId("id");
        if (move_uploaded_file($upload_info["tmp_name"], IMAGE_UPLOADS_PATH . "$id.$upload_ext")){
        }
    }
    }
}
?>
<?php
// Delete an image
if (isset($_POST["delete_image"])) {
    $image_name = $_POST['image_delete'];

    $sql = "DELETE FROM images_tags WHERE image_id = :image_id";
    $params = array(':image_id' => $image_id);
    $result = exec_sql_query($db, $sql, $params)->fetchAll();

    $sql = "SELECT * FROM images WHERE image_name = :image_name";
    $params = array(':image_name' => $image_name);
    $result = exec_sql_query($db, $sql, $params)->fetchAll();
    $image_id = $result[0]['id'];

    $sql = "DELETE FROM images WHERE id = :id";
    $params = array(':id' => $image_id);
    exec_sql_query($db, $sql, $params)->fetchAll();

    if($result){
      $result = $result[0];
      unlink(IMAGE_UPLOADS_PATH. $result['id'] . "." . $result['image_ext']);
    }

}

?>
<?php
// Delete a tag
if (isset($_POST["delete_tag_forever"])) {
    $tag_name = $_POST['tag_delete_forever'];

    $sql = "SELECT * FROM tags WHERE tag_name = :tag_name";
    $params = array(':tag_name' => $tag_name);
    $result = exec_sql_query($db, $sql, $params)->fetchAll();
    $tag_id = $result[0]['id'];

    $sql = "DELETE FROM images_tags WHERE tag_id = :tag_id";
    $params = array(':tag_id' => $tag_id);
    $result = exec_sql_query($db, $sql, $params)->fetchAll();

    $sql = "DELETE FROM tags WHERE id = :id";
    $params = array(':id' => $tag_id);
    exec_sql_query($db, $sql, $params)->fetchAll();
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

<body class="all">

<header>
  <div class="row1 main-title">
    <p>Tasty Toast</p>
  </div>
</header>

<main>
  <div class="row2 body-intro">
    <h2>Find the recipe for the ideal toast!</h2>
    <p>Tired of waking up in the morning and having eating toast same as yesterday? Explore a variety of toast toppings combinations. Try them out and find the perfect match for you!</p>
  </div>

  <div class="row2 body-gallery">
    <h2>Easy Toast Options</h2>
    <div class="gallery-images">
      <?php
      // Display all images
      $records = exec_sql_query($db, "SELECT * FROM images")->fetchAll(PDO::FETCH_ASSOC);
      // Citation provided in init.sql
      foreach($records as $record) {
        $url = array( 'stay'=> $$record["id"]+3,
                      'calm' => $record["id"],
                      'and' => $record["id"]+9,
                      'eat' => $record["id"]+1);
        echo "<a href= \"single.php?" . http_build_query($url) . "\"><img class='index-image' alt='" . $record["image_name"] . "' src= \"" . IMAGE_UPLOADS_PATH . $record["id"] . "." . $record["image_ext"] . "\">";
      }
      ?>
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
  </div>

  <div class="row3 body-upload">
    <h2>Upload a topping</h2>

    <form id="uploadFile" action="index.php" method="post" enctype="multipart/form-data">
        <!-- MAX_FILE_SIZE must precede the file input field -->
        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>" />

        <div class="form-input">
        <label for="image_file">Choose an Image:</label>
        <input id="image_file" type="file" name="image_file" required>
        </div>

        <div class="form-input">
        <label for="image_src">Image Source:</label>
        <textarea id="image_src" name="image_src" cols="40" rows="5" required></textarea>
        </div>

        <div class="form-input">
        <span></span>
        <button name="upload_image" type="submit">Upload</button>
        </div>
    </form>


  </div>

  <div class="row3 body-delete">
    <h2>Delete a topping & toast</h2>

    <form id="deleteFile" action="index.php" method="POST" enctype="multipart/form-data">
      <div class="form-input">
          <label for="image_delete">Delete an Image:</label>
          <select id="image_delete" name="image_delete">
            <?php
            $records = exec_sql_query($db, "SELECT * FROM images")->fetchAll(PDO::FETCH_ASSOC);
            foreach($records as $record) {
              echo "<option value= \"" . htmlspecialchars($record["image_name"]) . "\">" . htmlspecialchars(basename($record["image_name"])). "." . $record["image_ext"] .
              "</option>";
            }
            ?>
          </select>
          <span></span>
          <button name="delete_image" type="submit">Delete</button>
        </div>
    </form>
    <form id="deleteTagforever" action="index.php" method="POST" enctype="multipart/form-data">
      <div class="form-input">
          <label for="tag_delete_forever">Delete a Tag:</label>
          <select id="tag_delete_forever" name="tag_delete_forever">
            <?php
            $records = exec_sql_query($db, "SELECT * FROM tags")->fetchAll(PDO::FETCH_ASSOC);
            foreach($records as $record) {
              echo "<option value= \"" . htmlspecialchars($record["tag_name"]) . "\">" . htmlspecialchars(basename($record["tag_name"])).
              "</option>";
            }
            ?>
          </select>
          <span></span>
          <button name="delete_tag_forever" type="submit">Delete</button>
        </div>
    </form>

  </div>

</main>

</body>

</html>
