<?php
/**
 * =============================================================================
 * CONEXIÓN A BASE DE DATOS - core/BaseDatos.php
 * =============================================================================
 * Implementa el patrón Singleton para garantizar que en toda la aplicación
 * se use siempre una única instancia de la conexión PDO.
 *
 * ¿Por qué Singleton?
 *  - Evita abrir múltiples conexiones a la BD en la misma petición.
 *  - Centraliza la configuración de acceso en un único lugar.
 *
 * ⚠️ IMPORTANTE: Edita las constantes HOST, USER, PASS y DB con los datos
 *    reales de tu servidor antes de desplegar en producción.
 */
class BaseDatos {

    /** @var PDO|null Instancia única de la conexión PDO */
    private static ?PDO $instancia = null;

    // ── Datos de conexión ─────────────────────────────────────────────────────
    private const HOST = '134.0.14.185';        // Dirección IP del servidor MySQL
    private const USER = 'asignaciones';        // Usuario de la base de datos
    private const PASS = 'aplicacion$2026dAw';  // Contraseña del usuario
    private const DB   = 'asignaciones';        // Nombre de la base de datos

    /**
     * Devuelve la instancia PDO (la crea si no existe todavía).
     *
     * Configuración de PDO usada:
     *  - ERRMODE_EXCEPTION: lanza excepciones ante cualquier error SQL,
     *    lo que permite capturarlos con try/catch en los controladores.
     *  - FETCH_ASSOC: los resultados se devuelven como arrays asociativos
     *    (clave = nombre de columna), más cómodo que arrays numéricos.
     *  - EMULATE_PREPARES = false: usa prepared statements reales del servidor,
     *    lo que mejora la seguridad frente a inyección SQL.
     *
     * @return PDO La instancia compartida de conexión a la base de datos.
     */
    public static function conexion(): PDO {
        if (self::$instancia === null) {
            // Construimos el DSN (Data Source Name) con charset utf8mb4
            // para soporte completo de Unicode (incluidos emojis y caracteres especiales)
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
