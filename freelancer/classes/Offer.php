<?php  
class Offer extends Database {
    public function createOffer($user_id, $description, $proposal_id) {
        if ($this->hasExistingOffer($user_id, $proposal_id)) {
            return ['success' => false, 'message' => 'You have already submitted an offer for this proposal. Only one offer per proposal is allowed.'];
        }
        
        $sql = "INSERT INTO offers (user_id, description, proposal_id) VALUES (?, ?, ?)";
        try {
            $this->executeNonQuery($sql, [$user_id, $description, $proposal_id]);
            return ['success' => true, 'message' => 'Offer submitted successfully!'];
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Failed to submit offer. Please try again.'];
        }
    }

    public function hasExistingOffer($user_id, $proposal_id) {
        $sql = "SELECT COUNT(*) as count FROM offers WHERE user_id = ? AND proposal_id = ?";
        $result = $this->executeQuerySingle($sql, [$user_id, $proposal_id]);
        return $result['count'] > 0;
    }

    public function getOffers($offer_id = null) {
        if ($id) {
            $sql = "SELECT * FROM offers WHERE offer_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT * FROM offers JOIN fiverr_clone_users ON 
                offers.user_id = fiverr_clone_users.user_id 
                ORDER BY offers.date_added DESC";
        return $this->executeQuery($sql);
    }

    public function getOffersByProposalID($proposal_id) {
        $sql = "SELECT 
                    offers.*, fiverr_clone_users.*, 
                    offers.date_added AS offer_date_added 
                FROM Offers 
                JOIN fiverr_clone_users ON 
                    offers.user_id = fiverr_clone_users.user_id
                WHERE proposal_id = ? 
                ORDER BY Offers.date_added DESC";
        return $this->executeQuery($sql, [$proposal_id]);
    }

    public function updateOffer($description, $offer_id) {
        $sql = "UPDATE Offers SET description = ? WHERE Offer_id = ?";
        return $this->executeNonQuery($sql, [$description, $offer_id]);
    }
    
    public function deleteOffer($id) {
        $sql = "DELETE FROM Offers WHERE Offer_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }
}
?>