<?php

class Model
{

    /**
     * Database Connection.
     * 
     * @var PDO 
     */
    protected $db;

    /**
     * Nome da Tabela.
     * 
     * @var string 
     */
    protected $tabela;

    /**
     * Construtor da classe.
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->tabela = strtolower(get_class($this));
    }

    /**
     * Evita que alguém tente clonar a classe.
     */
    private function __clone()
    {
        
    }

    /**
     * Executa uma ação no banco de dados.
     * 
     * @param string $statement
     * @param array $values
     * @return string|PDOStatement
     */
    private function run($statement, $values = null)
    {
        try {
            $stmt = $this->db->prepare($statement);
            if (is_null($values)) {
                $values = Array();
            }
            $count = 1;
            foreach ($values as $data) {
                $stmt->bindValue($count, $data);
                $count++;
            }
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Seta os dados para condição where.
     * 
     * @param array $condicoes
     */
    private function where($condicoes)
    {
        $where = " WHERE ";
        if (!is_null($condicoes)) {
            $count = 0;
            foreach (array_keys($condicoes) as $key) {
                if ($count === 0) {
                    $where .= " {$key} = ? ";
                    $count++;
                } else {
                    $where .= " AND {$key} = ? ";
                }
            }
        }
        return $where;
    }

    /**
     * Faz seleção de linha(s) no banco de dados.
     * 
     * @param array $condicoes
     * @param string $orderby
     * @param string $wherelike
     * @param string $sqladd
     * @return string|object|mixed|null
     */
    public function select($condicoes = null, $orderby = null, $wherelike = null, $sqladd = null)
    {
        $sql = "SELECT *";
        !is_null($sqladd) ? $sql .= ", {$sqladd}" : null;
        $sql .= " FROM {$this->tabela}";
        if (!is_null($condicoes)) {
            $sql .= $this->where($condicoes);
        } elseif (is_null($wherelike) && is_null($condicoes)) {
            $sql .= " WHERE 1";
        }
        !is_null($wherelike) ? $sql .= " {$wherelike} " : null;
        !is_null($orderby) ? $sql .= " ORDER BY {$orderby} " : null;
        $stmt = $this->run($sql, $condicoes);

        if (is_string($stmt)) {
            return $stmt;
        }
        
        if ($stmt->rowCount() >= 1) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }

    /**
     * Insere valores novos no banco de dados.
     * 
     * @param array $valores
     * @return int|bool|string
     */
    public function insert($valores)
    {
        $sql = "INSERT INTO {$this->tabela}(";
        $sql2 = "";
        $count = 0;
        foreach (array_keys($valores) as $key) {
            if ($count === 0) {
                $sql .= "{$key}";
                $sql2 .= "?";
                $count++;
            } else {
                $sql .= ", {$key}";
                $sql2 .= ", ?";
            }
        }
        $sql .= ") VALUES(" . $sql2 . ")";
        $stmt = $this->run($sql, $valores);
        if (is_string($stmt)) {
            return $stmt;
        }
        if ($stmt->rowCount() > 0) {
            return (int) $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Faz o update no banco de dados.
     * 
     * @param array $valores
     * @param array $condicoes
     * @return string|bool
     */
    public function update($valores, $condicoes)
    {
        $sql = "UPDATE {$this->tabela} SET  ";
        $count = 0;
        foreach (array_keys($valores) as $key) {
            if ($count === 0) {
                $sql .= "{$key} = ?";
                $count++;
            } else {
                $sql .= ", {$key} = ?";
            }
        }
        $sql .= $this->where($condicoes);
        $dados = array_merge($valores, $condicoes);
        $stmt = $this->run($sql, $dados);
        if (is_string($stmt)) {
            return $stmt;
        }
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Faz o delete de uma linha da tabela.
     * 
     * @param int $cod
     * @return bool|string
     */
    public function delete($cod)
    {
        $sql = "DELETE FROM {$this->tabela} WHERE cod_{$this->tabela} = '{$cod}'";
        $stmt = $this->run($sql);
        if (is_string($stmt)) {
            return $stmt;
        }
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Executa um sql diferênciado.
     * 
     * @param string $sql
     * @return object|mixed|null|string
     */
    public function query($sql, $up = null)
    {
        $stmt = $this->run($sql);
        if (is_string($stmt)) {
            return $stmt;
        }
        if ($stmt->rowCount() > 1) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } elseif ($stmt->rowCount() == 1) {
            if (is_null($up)) {
                return $stmt->fetch(PDO::FETCH_OBJ);
            }
            return true;
        }
        return null;
    }

}
