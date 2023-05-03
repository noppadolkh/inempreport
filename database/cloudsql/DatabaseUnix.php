<?php

namespace Database\Cloudsql;

use PDO;
use PDOException;
use RuntimeException;
use TypeError;

class DatabaseUnix
{
    public static function initUnixDatabaseConnection(): PDO
    {
        try {
            // Note: Saving credentials in environment variables is convenient, but not
            // secure - consider a more secure solution such as
            // Cloud Secret Manager (https://cloud.google.com/secret-manager) to help
            // keep secrets safe.
            $username = getenv('DB_USERNAME'); // e.g. 'your_db_user'
            $password = getenv('DB_PASSWORD'); // e.g. 'your_db_password'
            $dbName = getenv('DB_DATABASE'); // e.g. 'your_db_name'
            $instanceUnixSocket = getenv('DB_SOCKET'); // e.g. '/cloudsql/project:region:instance'

            echo("username : ".$username."<br>");
            echo("password : ".$password."<br>");
            echo("database : ".$dbName."<br>");
            echo("instanceUnixSocket : ".$instanceUnixSocket."<br>");

            // Connect using UNIX sockets
            $dsn = sprintf(
                'mysql:dbname=%s;unix_socket=%s',
                $dbName,
                $instanceUnixSocket
            );

            // Connect to the database.
            $conn = new PDO(
                $dsn,
                $username,
                $password,
                # ...
            );
        } catch (TypeError $e) {
            throw new RuntimeException(
                sprintf(
                    'Invalid or missing configuration! Make sure you have set ' .
                        '$username, $password, $dbName, ' .
                        'and $instanceUnixSocket (for UNIX socket mode). ' .
                        'The PHP error was %s',
                    $e->getMessage()
                ),
                (int) $e->getCode(),
                $e
            );
        } catch (PDOException $e) {
            throw new RuntimeException(
                sprintf(
                    'Could not connect to the Cloud SQL Database. Check that ' .
                        'your username and password are correct, that the Cloud SQL ' .
                        'proxy is running, and that the database exists and is ready ' .
                        'for use. For more assistance, refer to %s. The PDO error was %s',
                    'https://cloud.google.com/sql/docs/mysql/connect-external-app',
                    $e->getMessage()
                ),
                (int) $e->getCode(),
                $e
            );
        }

        return $conn;
    }
}
