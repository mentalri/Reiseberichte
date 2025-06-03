<?php

namespace php\model;

use PDO;
use PDOException;
use php\model\exceptions\InternalErrorException;
use php\model\exceptions\MissingEntryException;

abstract class DatabaseHandler
{
    protected const DB_PATH = "/db/reiseberichte.db";
    private PDO $db;
    /**
     * @throws InternalErrorException
     */
    protected function getConnection(): PDO
    {
        if (isset($this->db)) {
            return $this->db;
        }
        global $abs_path;
        $dbFile = $abs_path . self::DB_PATH;
        $dsn = 'sqlite:' . $dbFile;
        $user = 'root';
        $pw = null;

        try {
            $this->db = new PDO($dsn, $user, $pw);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->exec('PRAGMA foreign_keys = ON;');
            $this->create($this->db); // Always check for missing tables
            return $this->db;
        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine());
            error_log($e->getTraceAsString());
            throw new InternalErrorException();
        }
    }
    
    /**
     * @throws InternalErrorException
     */
    abstract protected function create(PDO $db): void;

    /**
     * @throws InternalErrorException
     */
    protected function createTable(PDO $db, string $name, string $sql): bool
    {
        $db->exec($sql);
        $sql = "SELECT count(*) as count FROM $name";
        $command = $db->prepare($sql);
        $command->execute();
        return $command->fetchAll()[0]['count'] === 0;
    }
    /**
     * @throws InternalErrorException
     */
    protected function insert($db, $sql, $params = []): int
    {
        if (!isset($db)) {
            $db = $this->getConnection();
        }
        try {
            $command = $db->prepare($sql);
            $command->execute($params);
            return (int)$db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Database insert error: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine());
            error_log($e->getTraceAsString());
            throw new InternalErrorException();
        }
    }

    /**
     * @throws InternalErrorException
     */
    protected function select($db, $sql, $params = []): array
    {
        try {
            $command = $db->prepare($sql);
            $command->execute($params);
            return $command->fetchAll();
        } catch (PDOException $e) {
            throw new InternalErrorException($e->getMessage());
        }
    }

    /**
     * @throws InternalErrorException
     * @throws MissingEntryException
     */
    protected function update($db, $sql, $params = []): void
    {
        if (!isset($db)) {
            $db = $this->getConnection();
        }
        try {
            $db->beginTransaction();
            $command = $db->prepare($sql);
            $command->execute($params);
            $db->commit();
            if($command->rowCount() === 0) {
                throw new MissingEntryException("No rows affected by update operation.");
            }
        } catch (PDOException) {
            $db->rollBack();
            throw new InternalErrorException();
        }
    }

    /**
     * @throws InternalErrorException
     * @throws MissingEntryException
     */
    protected function delete($db, $sql, $params = []): void
    {
        if (!isset($db)) {
            $db = $this->getConnection();
        }
        try {
            $db->beginTransaction();
            $command = $db->prepare($sql);
            $command->execute($params);
            $db->commit();
            if($command->rowCount() === 0) {
                throw new MissingEntryException("No rows affected by delete operation.");
            }
        } catch (PDOException) {
            $db->rollBack();
            throw new InternalErrorException();
        }
    }
}