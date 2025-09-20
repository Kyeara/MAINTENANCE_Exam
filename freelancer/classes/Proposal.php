<?php  
class Proposal extends Database {
    public function createProposal($user_id, $description, $image, $min_price, $max_price, $category_id, $subcategory_id) {
        $sql = "INSERT INTO Proposals (user_id, description, image, min_price, max_price, category_id, subcategory_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        return $this->executeNonQuery($sql, [$user_id, $description, $image, $min_price, $max_price, $category_id, $subcategory_id]);
    }

    public function getProposals($id = null) {
        if ($id) {
            $sql = "SELECT p.*, u.*, c.category_name, s.subcategory_name
                    FROM Proposals p 
                    JOIN fiverr_clone_users u ON p.user_id = u.user_id
                    LEFT JOIN categories c ON p.category_id = c.category_id
                    LEFT JOIN subcategories s ON p.subcategory_id = s.subcategory_id
                    WHERE p.Proposal_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT p.*, u.*, c.category_name, s.subcategory_name,
                p.date_added AS proposals_date_added
                FROM Proposals p 
                JOIN fiverr_clone_users u ON p.user_id = u.user_id
                LEFT JOIN categories c ON p.category_id = c.category_id
                LEFT JOIN subcategories s ON p.subcategory_id = s.subcategory_id
                ORDER BY p.date_added DESC";
        return $this->executeQuery($sql);
    }

    public function getProposalsByUserID($user_id) {
        $sql = "SELECT p.*, u.*, c.category_name, s.subcategory_name,
                p.date_added AS proposals_date_added
                FROM Proposals p 
                JOIN fiverr_clone_users u ON p.user_id = u.user_id
                LEFT JOIN categories c ON p.category_id = c.category_id
                LEFT JOIN subcategories s ON p.subcategory_id = s.subcategory_id
                WHERE p.user_id = ?
                ORDER BY p.date_added DESC";
        return $this->executeQuery($sql, [$user_id]);
    }

    public function updateProposal($description, $min_price, $max_price, $proposal_id, $image="", $category_id=null, $subcategory_id=null) {
        if (!empty($image) && $category_id !== null && $subcategory_id !== null) {
            $sql = "UPDATE Proposals SET description = ?, image = ?, min_price = ?, max_price = ?, category_id = ?, subcategory_id = ? WHERE Proposal_id = ?";
            return $this->executeNonQuery($sql, [$description, $image, $min_price, $max_price, $category_id, $subcategory_id, $proposal_id]);
        }
        else if (!empty($image)) {
            $sql = "UPDATE Proposals SET description = ?, image = ?, min_price = ?, max_price = ? WHERE Proposal_id = ?";
            return $this->executeNonQuery($sql, [$description, $image, $min_price, $max_price, $proposal_id]);
        }
        else if ($category_id !== null && $subcategory_id !== null) {
            $sql = "UPDATE Proposals SET description = ?, min_price = ?, max_price = ?, category_id = ?, subcategory_id = ? WHERE Proposal_id = ?";
            return $this->executeNonQuery($sql, [$description, $min_price, $max_price, $category_id, $subcategory_id, $proposal_id]);
        }
        else {
            $sql = "UPDATE Proposals SET description = ?, min_price = ?, max_price = ? WHERE Proposal_id = ?";
            return $this->executeNonQuery($sql, [$description, $min_price, $max_price, $proposal_id]);  
        }
    }

    public function addViewCount($proposal_id) {
        $sql = "UPDATE Proposals SET view_count = view_count + 1 WHERE Proposal_id = ?";
        return $this->executeNonQuery($sql, [$proposal_id]);
    }

    public function deleteProposal($id) {
        $sql = "DELETE FROM Proposals WHERE Proposal_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }
}
?>