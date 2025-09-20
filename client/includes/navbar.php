<?php
require_once 'classloader.php';
$categories = $categoryObj->getCategoriesWithSubcategories();
$groupedCategories = [];
foreach ($categories as $item) {
    if (!isset($groupedCategories[$item['category_id']])) {
        $groupedCategories[$item['category_id']] = [
            'category_name' => $item['category_name'],
            'subcategories' => []
        ];
    }
    if ($item['subcategory_id']) {
        $groupedCategories[$item['category_id']]['subcategories'][] = [
            'subcategory_id' => $item['subcategory_id'],
            'subcategory_name' => $item['subcategory_name']
        ];
    }
}
?>

<nav class="navbar navbar-expand-lg navbar-dark p-4" style="background-color: #023E8A;">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Client Panel</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>
        
        <!-- Categories Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Categories
          </a>
          <div class="dropdown-menu" aria-labelledby="categoriesDropdown">
            <?php foreach ($groupedCategories as $category): ?>
              <div class="dropdown-submenu">
                <a class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><?php echo htmlspecialchars($category['category_name']); ?></a>
                <div class="dropdown-menu">
                  <?php foreach ($category['subcategories'] as $subcategory): ?>
                    <a class="dropdown-item" href="index.php?category=<?php echo $subcategory['subcategory_id']; ?>">
                      <?php echo htmlspecialchars($subcategory['subcategory_name']); ?>
                    </a>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </li>
      
      <li class="nav-item">
        <a class="nav-link" href="project_offers_submitted.php">Project Offers Submitted </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="profile.php">Profile</a>
      </li>
      <?php if ($userObj->isFiverrAdministrator()): ?>
      <li class="nav-item">
        <a class="nav-link" href="admin_categories.php">Manage Categories</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin_subcategories.php">Manage Subcategories</a>
      </li>
      <?php endif; ?>
      <li class="nav-item">
        <a class="nav-link" href="core/handleForms.php?logoutUserBtn=1">Logout</a>
      </li>
    </ul>
  </div>
</nav>

<style>
.dropdown-submenu {
  position: relative;
}

.dropdown-submenu .dropdown-menu {
  top: 0;
  left: 100%;
  margin-top: -1px;
}

.dropdown-submenu:hover .dropdown-menu {
  display: block;
}
</style>

