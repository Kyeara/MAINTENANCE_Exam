<?php  
/**
 * Class for handling Category and Subcategory operations.
 * Inherits CRUD methods from the Database class.
 */
class Category extends Database {
    
    /**
     * Creates a new category.
     * @param string $category_name The category name.
     * @param string $description The category description.
     * @return bool True on success, false on failure.
     */
    public function createCategory($category_name, $description = '') {
        $sql = "INSERT INTO categories (category_name, description) VALUES (?, ?)";
        try {
            $this->executeNonQuery($sql, [$category_name, $description]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Creates a new subcategory.
     * @param int $category_id The parent category ID.
     * @param string $subcategory_name The subcategory name.
     * @param string $description The subcategory description.
     * @return bool True on success, false on failure.
     */
    public function createSubcategory($category_id, $subcategory_name, $description = '') {
        $sql = "INSERT INTO subcategories (category_id, subcategory_name, description) VALUES (?, ?, ?)";
        try {
            $this->executeNonQuery($sql, [$category_id, $subcategory_name, $description]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Retrieves all categories with their subcategories.
     * @return array
     */
    public function getCategoriesWithSubcategories() {
        $sql = "SELECT c.*, s.subcategory_id, s.subcategory_name, s.description as subcategory_description
                FROM categories c
                LEFT JOIN subcategories s ON c.category_id = s.category_id
                ORDER BY c.category_name, s.subcategory_name";
        return $this->executeQuery($sql);
    }

    /**
     * Retrieves all categories.
     * @return array
     */
    public function getCategories() {
        $sql = "SELECT * FROM categories ORDER BY category_name";
        return $this->executeQuery($sql);
    }

    /**
     * Retrieves subcategories for a specific category.
     * @param int $category_id The category ID.
     * @return array
     */
    public function getSubcategoriesByCategory($category_id) {
        $sql = "SELECT * FROM subcategories WHERE category_id = ? ORDER BY subcategory_name";
        return $this->executeQuery($sql, [$category_id]);
    }

    /**
     * Updates a category.
     * @param int $category_id The category ID.
     * @param string $category_name The new category name.
     * @param string $description The new description.
     * @return bool True on success, false on failure.
     */
    public function updateCategory($category_id, $category_name, $description = '') {
        $sql = "UPDATE categories SET category_name = ?, description = ? WHERE category_id = ?";
        try {
            $this->executeNonQuery($sql, [$category_name, $description, $category_id]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Updates a subcategory.
     * @param int $subcategory_id The subcategory ID.
     * @param string $subcategory_name The new subcategory name.
     * @param string $description The new description.
     * @return bool True on success, false on failure.
     */
    public function updateSubcategory($subcategory_id, $subcategory_name, $description = '') {
        $sql = "UPDATE subcategories SET subcategory_name = ?, description = ? WHERE subcategory_id = ?";
        try {
            $this->executeNonQuery($sql, [$subcategory_name, $description, $subcategory_id]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Deletes a category and all its subcategories.
     * @param int $category_id The category ID.
     * @return bool True on success, false on failure.
     */
    public function deleteCategory($category_id) {
        $sql = "DELETE FROM categories WHERE category_id = ?";
        try {
            $this->executeNonQuery($sql, [$category_id]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Deletes a subcategory.
     * @param int $subcategory_id The subcategory ID.
     * @return bool True on success, false on failure.
     */
    public function deleteSubcategory($subcategory_id) {
        $sql = "DELETE FROM subcategories WHERE subcategory_id = ?";
        try {
            $this->executeNonQuery($sql, [$subcategory_id]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Gets a category by ID.
     * @param int $category_id The category ID.
     * @return array|null
     */
    public function getCategoryById($category_id) {
        $sql = "SELECT * FROM categories WHERE category_id = ?";
        return $this->executeQuerySingle($sql, [$category_id]);
    }

    /**
     * Gets a subcategory by ID.
     * @param int $subcategory_id The subcategory ID.
     * @return array|null
     */
    public function getSubcategoryById($subcategory_id) {
        $sql = "SELECT * FROM subcategories WHERE subcategory_id = ?";
        return $this->executeQuerySingle($sql, [$subcategory_id]);
    }
}
?>
