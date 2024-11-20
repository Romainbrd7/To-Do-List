<?php

use PHPUnit\Framework\TestCase;


class LoginTasksTest extends TestCase
{
    protected $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo->exec("CREATE TABLE users (
            id INTEGER PRIMARY KEY,
            email TEXT NOT NULL,
            mot_de_passe TEXT NOT NULL,
            nom TEXT,
            prenom TEXT
        )");

        // Insère un utilisateur test
        $mot_de_passe_hash = password_hash('password123', PASSWORD_BCRYPT);
        $this->pdo->exec("INSERT INTO users (email, mot_de_passe, nom, prenom)
                          VALUES ('test@example.com', '$mot_de_passe_hash', 'Test', 'User')");
    }

    public function testSuccessfulLogin()
    {
        // Simuler les variables de session
        $_SESSION = [];

        $redirect = loginUser('test@example.com', 'password123', $this->pdo);

        // Vérifie la redirection attendue
        $this->assertEquals('dashboard.php', $redirect);

        // Vérifie que les variables de session sont définies correctement
        $this->assertArrayHasKey('user_id', $_SESSION);
        $this->assertEquals('Test', $_SESSION['nom']);
        $this->assertEquals('User', $_SESSION['prenom']);
    }

    public function testFailedLogin()
    {
        // Simuler les variables de session
        $_SESSION = [];

        $redirect = loginUser('test@example.com', 'wrongpassword', $this->pdo);

        // Vérifie le message d'erreur attendu
        $this->assertEquals('Identifiants incorrects', $redirect);

        // Vérifie que les variables de session ne sont pas définies
        $this->assertArrayNotHasKey('user_id', $_SESSION);
    }
}
