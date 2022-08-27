<?php
include_once 'config/db.php';
class User
{
    private $connection;

    public function __construct()
    {

        $this->connection = new PDO(DRIVER . ':host='. SERVER . ';dbname=' . DB, USERNAME, PASSWORD);
    }

    public function create(array $data): void
    {
        try {
            $statement = $this->connection->prepare("INSERT INTO `users` (`id`, `email`, `first_name`, `last_name`, `age`, `date_created`) VALUES (NULL, :email, :first_name, :last_name, :age, :date)");

            $dt = new DateTime();
            $data['date'] = $dt->format('Y-m-d H:i:s');

            $statement->execute($data);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function update(array $data): void
    {
        try {
            $statement = $this->connection->prepare("UPDATE `users` SET `email`=:email,`first_name`=:first_name,`last_name`=:last_name,`age`=:age WHERE `id` = :id");
            $statement->execute($data);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function delete(int $id): void
    {
        try {
            $statement = $this->connection->prepare("DELETE FROM `users` WHERE `id` = :id");
            $statement->bindValue('id', $id);
            $statement->execute();
            header('Location: /users-pdo/');
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function list(): array
    {
        try {
            $statement = $this->connection->prepare("SELECT * FROM `users`");
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
