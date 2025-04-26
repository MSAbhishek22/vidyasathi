<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$page_title = "Categories";
include 'includes/header.php';

// Get all categories with course counts
$sql = "SELECT c.id, c.name, c.description, c.image_path, COUNT(co.id) as course_count 
        FROM categories c
        LEFT JOIN courses co ON c.id = co.category_id
        GROUP BY c.id
        ORDER BY c.name ASC";
$result = $conn->query($sql);
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="mb-3">Course Categories</h1>
            <p class="lead">Browse our wide range of educational content by category</p>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($category = $result->fetch_assoc()): ?>
                <div class="col">
                    <div class="card h-100">
                        <?php if (!empty($category['image_path'])): ?>
                            <img src="<?php echo htmlspecialchars($category['image_path']); ?>" class="card-img-top"
                                alt="<?php echo htmlspecialchars($category['name']); ?>">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                style="height: 160px;">
                                <i class="fas fa-folder fa-4x text-muted"></i>
                            </div>
                        <?php endif; ?>

                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($category['name']); ?></h5>
                            <p class="card-text">
                                <?php echo htmlspecialchars($category['description'] ?? 'No description available'); ?></p>
                            <p class="text-muted"><i class="fas fa-book me-1"></i> <?php echo $category['course_count']; ?>
                                courses</p>
                        </div>

                        <div class="card-footer bg-transparent">
                            <a href="search.php?category=<?php echo $category['id']; ?>" class="btn btn-primary w-100">Browse
                                Courses</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">
                    <p class="mb-0">No categories found. Please check back later.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
include 'includes/footer.php';
?>