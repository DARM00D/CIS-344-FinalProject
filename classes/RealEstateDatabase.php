<?php
require_once __DIR__ . '/Database.php';

class RealEstateDatabase {
    private PDO $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function addUser(string $userName, string $contactInfo, string $passwordHash, string $userType): bool {
        $sql = "INSERT INTO Users (userName, contactInfo, passwordHash, userType)
                VALUES (:userName, :contactInfo, :passwordHash, :userType)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':userName'     => $userName,
            ':contactInfo'  => $contactInfo,
            ':passwordHash' => $passwordHash,
            ':userType'     => $userType
        ]);
    }

    public function getUserByUsername(string $userName) {
        $sql = "SELECT * FROM Users WHERE userName = :userName LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':userName' => $userName]);
        return $stmt->fetch();
    }

    public function getUserDetails(int $userId) {
        $user = $this->conn->prepare("SELECT * FROM Users WHERE userId = :userId");
        $user->execute([':userId' => $userId]);
        $userData = $user->fetch();

        if (!$userData) {
            return null;
        }

        $inquiries = $this->conn->prepare(
            "SELECT i.*, p.title AS propertyTitle
             FROM Inquiries i
             JOIN Properties p ON i.propertyId = p.propertyId
             WHERE i.userId = :userId
             ORDER BY i.inquiryDate DESC"
        );
        $inquiries->execute([':userId' => $userId]);
        $userData['inquiries'] = $inquiries->fetchAll();

        $favorites = $this->conn->prepare(
            "SELECT f.*, p.title AS propertyTitle, p.city, p.price, p.status
             FROM Favorites f
             JOIN Properties p ON f.propertyId = p.propertyId
             WHERE f.userId = :userId
             ORDER BY f.savedDate DESC"
        );
        $favorites->execute([':userId' => $userId]);
        $userData['favorites'] = $favorites->fetchAll();

        $transactions = $this->conn->prepare(
            "SELECT t.*, p.title AS propertyTitle
             FROM Transactions t
             JOIN Properties p ON t.propertyId = p.propertyId
             WHERE t.userId = :userId
             ORDER BY t.transactionDate DESC"
        );
        $transactions->execute([':userId' => $userId]);
        $userData['transactions'] = $transactions->fetchAll();

        return $userData;
    }

    public function addProperty(string $title, string $propertyType, string $address, string $city, float $price, string $status, int $agentId): bool {
        $sql = "INSERT INTO Properties (title, propertyType, address, city, price, status, agentId)
                VALUES (:title, :propertyType, :address, :city, :price, :status, :agentId)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':title'        => $title,
            ':propertyType' => $propertyType,
            ':address'      => $address,
            ':city'         => $city,
            ':price'        => $price,
            ':status'       => $status,
            ':agentId'      => $agentId
        ]);
    }

    public function getAllProperties(): array {
        $stmt = $this->conn->query("SELECT * FROM PropertyListingView ORDER BY propertyId DESC");
        return $stmt->fetchAll();
    }

    public function getPropertyById(int $propertyId) {
        $sql = "SELECT * FROM PropertyListingView WHERE propertyId = :propertyId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':propertyId' => $propertyId]);
        return $stmt->fetch();
    }

    public function getPropertiesByCity(string $city): array {
        $sql = "SELECT * FROM PropertyListingView WHERE city = :city ORDER BY propertyId DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':city' => $city]);
        return $stmt->fetchAll();
    }

    public function addInquiry(int $userId, int $propertyId, string $message): bool {
        $sql = "INSERT INTO Inquiries (userId, propertyId, message, inquiryDate)
                VALUES (:userId, :propertyId, :message, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':userId'     => $userId,
            ':propertyId' => $propertyId,
            ':message'    => $message
        ]);
    }

    public function addFavorite(int $userId, int $propertyId): bool {
        $sql = "INSERT INTO Favorites (userId, propertyId, savedDate)
                VALUES (:userId, :propertyId, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':userId'     => $userId,
            ':propertyId' => $propertyId
        ]);
    }

    public function removeFavorite(int $userId, int $propertyId): bool {
        $sql = "DELETE FROM Favorites WHERE userId = :userId AND propertyId = :propertyId";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':userId'     => $userId,
            ':propertyId' => $propertyId
        ]);
    }

    public function getFavoritesByUser(int $userId): array {
        $sql = "SELECT f.favoriteId, f.savedDate, p.propertyId, p.title AS propertyTitle,
                       p.city, p.price, p.status, p.agentName
                FROM Favorites f
                JOIN PropertyListingView p ON f.propertyId = p.propertyId
                WHERE f.userId = :userId
                ORDER BY f.savedDate DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetchAll();
    }

    public function isFavorite(int $userId, int $propertyId): bool {
        $sql = "SELECT COUNT(*) FROM Favorites WHERE userId = :userId AND propertyId = :propertyId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':userId' => $userId, ':propertyId' => $propertyId]);
        return (bool)$stmt->fetchColumn();
    }

    public function processTransaction(int $propertyId, int $userId, string $transactionType, float $amount): bool {
        $stmt = $this->conn->prepare("CALL ProcessTransaction(:propertyId, :userId, :transactionType, :amount)");
        return $stmt->execute([
            ':propertyId'      => $propertyId,
            ':userId'          => $userId,
            ':transactionType' => $transactionType,
            ':amount'          => $amount
        ]);
    }
}
?>