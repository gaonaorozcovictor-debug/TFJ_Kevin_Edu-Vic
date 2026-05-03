<?php
/**
 * Conexión a la base de datos mediante PDO.
 * Edita las constantes de abajo con los datos reales del servidor.
 */
class BaseDatos {

    private static ?PDO $instancia = null;

    private const HOST = '13.185';
    private const USER = '';
    private const PASS = 'aplicacion$';
    private const DB   = '';

    /** Retorna siempre la misma instancia PDO (Singleton). */
    public static function conexion(): PDO {
        if (self::$instancia === null) {
            $dsn = 'mysql:host=' . self::HOST . ';dbname=' . self::DB . ';charset=utf8mb4';
            self::$instancia = new PDO($dsn, self::USER, self::PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }
        return self::$instancia;
    }
}
