<?php
$host = "localhost";
$dbname = "mydatabase";
$user = "myuser";
$password = "mypassword";

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// إضافة سجل
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $name = $_POST['name'];
    try {
        $stmt = $conn->prepare("INSERT INTO mytable (name) VALUES (:name)");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        echo "Record added successfully!";
    } catch (PDOException $e) {
        echo "Error adding record: " . $e->getMessage();
    }
}

// تعديل سجل
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    try {
        $stmt = $conn->prepare("UPDATE mytable SET name = :name WHERE id = :id");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        echo "Record updated successfully!";
    } catch (PDOException $e) {
        echo "Error updating record: " . $e->getMessage();
    }
}

// حذف سجل
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $id = $_POST['id'];
    try {
        $stmt = $conn->prepare("DELETE FROM mytable WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        echo "Record deleted successfully!";
    } catch (PDOException $e) {
        echo "Error deleting record: " . $e->getMessage();
    }
}

// استرجاع السجلات
try {
    $stmt = $conn->query("SELECT * FROM mytable");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching records: " . $e->getMessage();
}
?>

<!-- نموذج لإضافة سجل -->
<form method="POST">
    <input type="hidden" name="action" value="add">
    <input type="text" name="name" placeholder="Enter name" required>
    <button type="submit">Add</button>
</form>

<!-- نموذج لتعديل سجل -->
<form method="POST">
    <input type="hidden" name="action" value="edit">
    <input type="number" name="id" placeholder="Enter ID to edit" required>
    <input type="text" name="name" placeholder="Enter new name" required>
    <button type="submit">Edit</button>
</form>

<!-- نموذج لحذف سجل -->
<form method="POST">
    <input type="hidden" name="action" value="delete">
    <input type="number" name="id" placeholder="Enter ID to delete" required>
    <button type="submit">Delete</button>
</form>

<!-- عرض السجلات -->
<h2>Records:</h2>
<ul>
    <?php foreach ($rows as $row): ?>
        <li><?php echo htmlspecialchars($row['id']) . ': ' . htmlspecialchars($row['name']); ?></li>
    <?php endforeach; ?>
</ul>
