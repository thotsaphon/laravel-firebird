<?php namespace Firebird;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Database\Connectors\Connector;
use Illuminate\Database\Connectors\ConnectorInterface;

class FirebirdConnector extends Connector implements ConnectorInterface {

  /**
   * Establish a database connection.
   *
   * @param  array  $config
   * @return \PDO
   *
   * @throws \InvalidArgumentException
   */
  public function connect(array $config)
  {
    $options = $this->getOptions($config);

    $path = $config['database'];

    $charset = $config['charset'];
    
    $host = $config['host'];
    if ( empty($host))
    {
      throw new InvalidArgumentException("Host not given, required.");
    }

    return $this->createConnection("firebird:dbname={$host}:{$path};charset={$charset}", $config, $options);
  }

    /**
     * Create a new PDO connection instance.
     *
     * @param  string  $dsn
     * @param  string  $username
     * @param  string  $password
     * @param  array  $options
     * @return \PDO
     */
    protected function createPdoConnection($dsn, $username, $password, $options)
    {
        if (class_exists(PDOConnection::class) && ! $this->isPersistentConnection($options)) {
            return new PDOConnection($dsn, $username, $password, $options);
        }

        return new PDO($dsn, $username, $password, $options);
    }
}
