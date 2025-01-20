<?php
// Exemple de code avec PDO pour tester les limites de Psalm

// Connexion à la base de données avec PDO
$dsn = 'mysql:host=localhost;dbname=test_db';
$username = 'root';
$password = '';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

// class PDO_wrapper extends PDO {
    
//     private PDO $conn;
//     public function __construct(string $dsn, string $username, string $password, array $options) {
//         $this->conn = new PDO($dsn, $username, $password, $options);
//     }

//     /**
//      * @psalm-taint-source input
//      */
//     public function fetch(string $query, array $params) {
//         $stmt = $this->conn->prepare($query);
//         $stmt->execute($params);
//         return $stmt->fetch();
//     }

// }

$conn = new PDO($dsn, $username, $password, $options);

// 1. Injection SQL (Exécution de requêtes sans préparation - pour tester la vulnérabilité)
function getUserInfo(PDO $conn, $userId) {
    $query = "SELECT * FROM users WHERE id = '$userId'"; // Injection possible
    $stmt = $conn->query($query); // Pas de préparation sécurisée
    return $stmt->fetch();
}

// 2. Cookies - Test de la manipulation des cookies sans validation
function setUserCookie($username) {
    setcookie("user", $username, time() + 3600, "/", "", false, true); // Cookie non sécurisé
}

// 3. Requête SQL préparée mais sans validation des données reçues
function getUserData(PDO $conn, $userId) {
    $query = "SELECT * FROM users WHERE id = :userId";
    $params = ['userId' => $userId];
    $stmt = $conn->prepare($query);
    $stmt->execute($params); // Données non validées
    return $stmt->fetch();
}

// 4. Affichage de données sans échappement
function writeUserDataToFile($userId) {
    $filename = "/var/www/data/user_$userId.txt";
    file_put_contents($filename, "User data for $userId");  // Risque de Path Traversal si $userId est manipulé
}

// Exemple d'appel des fonctions avec des données utilisateur simulées
$userId = $_GET['user_id']; // Donnée non sécurisée de l'utilisateur
$username = $_GET['username']; // Donnée non sécurisée de l'utilisateur

getUserInfo($conn, $userId); // Injection SQL potentielle
setUserCookie($username); // Cookie non sécurisé
echo getUserData($conn, $userId); // Requête préparée mais non validée
writeUserDataToFile($userId); // Affichage de données sans échappement

$token = $_SERVER['HTTP_AUTHORIZATION'];
// 5. Utilisation de données non sécurisées dans une requête SQL
$conn->query("SELECT * FROM users WHERE token = '$token'"); // Injection SQL potentielle

$headers = getallheaders();
$token_2 = $headers['Authorization'];
$conn->query("SELECT * FROM users WHERE token = '$token_2'"); // Injection SQL potentielle

$token_sanitized = filter_var($token, FILTER_SANITIZE_STRING); // Nettoyage des données
$conn->query("SELECT * FROM users WHERE token = '$token_sanitized'"); // Requête sécurisée
?>

<div>
    <p><?= $_COOKIE['user'] ?></p> <!-- Cookie non sécurisé -->
</div>

<?php
