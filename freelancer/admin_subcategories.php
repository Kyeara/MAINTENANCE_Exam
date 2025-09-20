<?php
require_once 'classloader.php';

if (!$userObj->isFiverrAdministrator()) {
    header('Location: index.php');
    exit();
}

$message = '';
$messageType = '';

if ($_POST) {
    if (isset($_POST['create_subcategory'])) {
        $category_id = $_POST['category_id'];
        $subcategory_name = trim($_POST['subcategory_name']);
        $description = trim($_POST['description']);
        
        if (!empty($subcategory_name) && !empty($category_id)) {
            if ($categoryObj->createSubcategory($category_id, $subcategory_name, $description)) {
                $message = 'Subcategory created successfully!';
                $messageType = 'success';
            } else {
                $message = 'Failed to create subcategory. Please try again.';
                $messageType = 'danger';
            }
        } else {
            $message = 'Subcategory name and category are required.';
            $messageType = 'danger';
        }
    }
    
    if (isset($_POST['update_subcategory'])) {
        $subcategory_id = $_POST['subcategory_id'];
        $subcategory_name = trim($_POST['subcategory_name']);
        $description = trim($_POST['description']);
        
        if (!empty($subcategory_name)) {
            if ($categoryObj->updateSubcategory($subcategory_id, $subcategory_name, $description)) {
                $message = 'Subcategory updated successfully!';
                $messageType = 'success';
            } else {
                $message = 'Failed to update subcategory. Please try again.';
                $messageType = 'danger';
            }
        } else {
            $message = 'Subcategory name is required.';
            $messageType = 'danger';
        }
    }
    
    if (isset($_POST['delete_subcategory'])) {
        $subcategory_id = $_POST['subcategory_id'];
        
        if ($categoryObj->deleteSubcategory($subcategory_id)) {
            $message = 'Subcategory deleted successfully!';
            $messageType = 'success';
        } else {
            $message = 'Failed to delete subcategory. Please try again.';
            $messageType = 'danger';
        }
    }
}

$categories = $categoryObj->getCategories();
$subcategories = $categoryObj->getCategoriesWithSubcategories();
$groupedSubcategories = [];
foreach ($subcategories as $item) {
    if ($item['subcategory_id']) {
        if (!isset($groupedSubcategories[$item['category_id']])) {
            $groupedSubcategories[$item['category_id']] = [
                'category_name' => $item['category_name'],
                'subcategories' => []
            ];
        }
        $groupedSubcategories[$item['category_id']]['subcategories'][] = $item;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subcategories - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h2>Manage Subcategories</h2>
                
                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Create New Subcategory</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Category *</label>
                                        <select class="form-select" id="category_id" name="category_id" required>
                                            <option value="">Select a category</option>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?php echo $category['category_id']; ?>">
                                                    <?php echo htmlspecialchars($category['category_name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="subcategory_name" class="form-label">Subcategory Name *</label>
                                        <input type="text" class="form-control" id="subcategory_name" name="subcategory_name" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <input type="text" class="form-control" id="description" name="description">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" name="create_subcategory" class="btn btn-primary">
                                Create Subcategory
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5>Existing Subcategories</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($groupedSubcategories)): ?>
                            <p class="text-muted">No subcategories found.</p>
                        <?php else: ?>
                            <?php foreach ($groupedSubcategories as $categoryId => $categoryData): ?>
                                <h6 class="text-primary"><?php echo htmlspecialchars($categoryData['category_name']); ?></h6>
                                <div class="table-responsive mb-4">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Subcategory Name</th>
                                                <th>Description</th>
                                                <th>Date Added</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($categoryData['subcategories'] as $subcategory): ?>
                                                <tr>
                                                    <td><?php echo $subcategory['subcategory_id']; ?></td>
                                                    <td><?php echo htmlspecialchars($subcategory['subcategory_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($subcategory['subcategory_description']); ?></td>
                                                    <td><?php echo date('M d, Y', strtotime($subcategory['date_added'])); ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $subcategory['subcategory_id']; ?>">
                                                            Edit
                                                        </button>
                                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this subcategory?');">
                                                            <input type="hidden" name="subcategory_id" value="<?php echo $subcategory['subcategory_id']; ?>">
                                                            <button type="submit" name="delete_subcategory" class="btn btn-sm btn-danger">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                
                                                <div class="modal fade" id="editModal<?php echo $subcategory['subcategory_id']; ?>" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Edit Subcategory</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form method="POST">
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="subcategory_id" value="<?php echo $subcategory['subcategory_id']; ?>">
                                                                    <div class="mb-3">
                                                                        <label for="edit_subcategory_name_<?php echo $subcategory['subcategory_id']; ?>" class="form-label">Subcategory Name *</label>
                                                                        <input type="text" class="form-control" id="edit_subcategory_name_<?php echo $subcategory['subcategory_id']; ?>" 
                                                                               name="subcategory_name" value="<?php echo htmlspecialchars($subcategory['subcategory_name']); ?>" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="edit_description_<?php echo $subcategory['subcategory_id']; ?>" class="form-label">Description</label>
                                                                        <input type="text" class="form-control" id="edit_description_<?php echo $subcategory['subcategory_id']; ?>" 
                                                                               name="description" value="<?php echo htmlspecialchars($subcategory['subcategory_description']); ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" name="update_subcategory" class="btn btn-primary">Update Subcategory</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>