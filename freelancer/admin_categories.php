<?php
require_once 'classloader.php';

if (!$userObj->isFiverrAdministrator()) {
    header('Location: index.php');
    exit();
}

$message = '';
$messageType = '';

if ($_POST) {
    if (isset($_POST['create_category'])) {
        $category_name = trim($_POST['category_name']);
        $description = trim($_POST['description']);
        
        if (!empty($category_name)) {
            if ($categoryObj->createCategory($category_name, $description)) {
                $message = 'Category created successfully!';
                $messageType = 'success';
            } else {
                $message = 'Failed to create category. Please try again.';
                $messageType = 'danger';
            }
        } else {
            $message = 'Category name is required.';
            $messageType = 'danger';
        }
    }
    
    if (isset($_POST['update_category'])) {
        $category_id = $_POST['category_id'];
        $category_name = trim($_POST['category_name']);
        $description = trim($_POST['description']);
        
        if (!empty($category_name)) {
            if ($categoryObj->updateCategory($category_id, $category_name, $description)) {
                $message = 'Category updated successfully!';
                $messageType = 'success';
            } else {
                $message = 'Failed to update category. Please try again.';
                $messageType = 'danger';
            }
        } else {
            $message = 'Category name is required.';
            $messageType = 'danger';
        }
    }
    
    if (isset($_POST['delete_category'])) {
        $category_id = $_POST['category_id'];
        
        if ($categoryObj->deleteCategory($category_id)) {
            $message = 'Category deleted successfully!';
            $messageType = 'success';
        } else {
            $message = 'Failed to delete category. Please try again.';
            $messageType = 'danger';
        }
    }
}

$categories = $categoryObj->getCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h2>Manage Categories</h2>
                
                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Create New Category</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category_name" class="form-label">Category Name *</label>
                                        <input type="text" class="form-control" id="category_name" name="category_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <input type="text" class="form-control" id="description" name="description">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" name="create_category" class="btn btn-primary">
                                Create Category
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5>Existing Categories</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($categories)): ?>
                            <p class="text-muted">No categories found.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Category Name</th>
                                            <th>Description</th>
                                            <th>Date Added</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($categories as $category): ?>
                                            <tr>
                                                <td><?php echo $category['category_id']; ?></td>
                                                <td><?php echo htmlspecialchars($category['category_name']); ?></td>
                                                <td><?php echo htmlspecialchars($category['description']); ?></td>
                                                <td><?php echo date('M d, Y', strtotime($category['date_added'])); ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $category['category_id']; ?>">
                                                        Edit
                                                    </button>
                                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this category? This will also delete all its subcategories.');">
                                                        <input type="hidden" name="category_id" value="<?php echo $category['category_id']; ?>">
                                                        <button type="submit" name="delete_category" class="btn btn-sm btn-danger">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            
                                            <div class="modal fade" id="editModal<?php echo $category['category_id']; ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Category</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form method="POST">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="category_id" value="<?php echo $category['category_id']; ?>">
                                                                <div class="mb-3">
                                                                    <label for="edit_category_name_<?php echo $category['category_id']; ?>" class="form-label">Category Name *</label>
                                                                    <input type="text" class="form-control" id="edit_category_name_<?php echo $category['category_id']; ?>" 
                                                                           name="category_name" value="<?php echo htmlspecialchars($category['category_name']); ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="edit_description_<?php echo $category['category_id']; ?>" class="form-label">Description</label>
                                                                    <input type="text" class="form-control" id="edit_description_<?php echo $category['category_id']; ?>" 
                                                                           name="description" value="<?php echo htmlspecialchars($category['description']); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" name="update_category" class="btn btn-primary">Update Category</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>