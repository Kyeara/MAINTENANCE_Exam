<?php  
class Category extends Database {
    
    public function createCategory($category_name, $description = '') {
        $sql = "INSERT INTO categories (category_name, description) VALUES (?, ?)";
        try {
            $this->executeNonQuery($sql, [$category_name, $description]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function createSubcategory($category_id, $subcategory_name, $description = '') {
        $sql = "INSERT INTO subcategories (category_id, subcategory_name, description) VALUES (?, ?, ?)";
        try {
            $this->executeNonQuery($sql, [$category_id, $subcategory_name, $description]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function getCategoriesWithSubcategories() {
        $sql = "SELECT c.*, s.subcategory_id, s.subcategory_name, s.description as subcategory_description
                FROM categories c
                LEFT JOIN subcategories s ON c.category_id = s.category_id
                ORDER BY c.category_name, s.subcategory_name";
        return $this->executeQuery($sql);
    }

    public function getCategories() {
        $sql = "SELECT * FROM categories ORDER BY category_name";
        return $this->executeQuery($sql);
    }

    public function getSubcategoriesByCategory($category_id) {
        $sql = "SELECT * FROM subcategories WHERE category_id = ? ORDER BY subcategory_name";
        return $this->executeQuery($sql, [$category_id]);
    }

    public function updateCategory($category_id, $category_name, $description = '') {
        $sql = "UPDATE categories SET category_name = ?, description = ? WHERE category_id = ?";
        try {
            $this->executeNonQuery($sql, [$category_name, $description, $category_id]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function updateSubcategory($subcategory_id, $subcategory_name, $description = '') {
        $sql = "UPDATE subcategories SET subcategory_name = ?, description = ? WHERE subcategory_id = ?";
        try {
            $this->executeNonQuery($sql, [$subcategory_name, $description, $subcategory_id]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function deleteCategory($category_id) {
        $sql = "DELETE FROM categories WHERE category_id = ?";
        try {
            $this->executeNonQuery($sql, [$category_id]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function deleteSubcategory($subcategory_id) {
        $sql = "DELETE FROM subcategories WHERE subcategory_id = ?";
        try {
            $this->executeNonQuery($sql, [$subcategory_id]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function getCategoryById($category_id) {
        $sql = "SELECT * FROM categories WHERE category_id = ?";
        return $this->executeQuerySingle($sql, [$category_id]);
    }

    public function getSubcategoryById($subcategory_id) {
        $sql = "SELECT * FROM subcategories WHERE subcategory_id = ?";
        return $this->executeQuerySingle($sql, [$subcategory_id]);
    }
}
?>