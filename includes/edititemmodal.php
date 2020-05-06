  <div id="editmodal-window" class="modal">
    <form class="modal-content animate" action="bucketlist.php" method="post" enctype="multipart/form-data">
      <div class="container">
        <h1 id="modal-header"> Edit a list item</h1>
        <input style="display: none;" type="text" name="id">

        <label for="title"><strong>List item title</strong></label>
        <input type="text" placeholder="Enter list item title" name="title" required>

        <label for="description"><strong>Description</strong></label>
        <input type="text" placeholder="Description" name="description">

        <label for="date-completed"><strong>Date Completed</strong></label>
        <input type="date" placeholder="Date Completed" name="date-completed"></br>

        <label for="avatar"><strong>Upload a picture:</strong></label>
        <input type="file" id="avatar" name="avatar" accept="image/png, image/jpeg"></br>

        <label for="public-list"><strong>Make list public</strong></label>
        <input type="checkbox" name="public" id="make-public" value="1"></br>

        <label for="item-completed"><strong>Item is completed</strong></label>
        <input type="checkbox" name="completed" id="completed" value="1"></br>

        <button type="submit" name="edititem" id="edit-list">Save changes</button>
      </div>
    </form>
  </div>
