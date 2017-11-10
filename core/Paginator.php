<?php

class Paginator
{
    private $current;
    private $amount;
    private $count;

    /**
     * Paginator constructor.
     * 
     * @param $current
     * @param $amount
     */
    public function __construct($current, $amount)
    {
        $this->current = $current;
        $this->amount = $amount;
    }

    /**
     * Avoid someone cloning the class.
     */
    private function __clone()
    {
    }

    /**
     * Return data paginated.
     * 
     * @param $data
     * @return mixed
     */
    public function getData($data)
    {
        $pagararq = array_chunk($data, $this->amount);
        $this->count = count($pagararq);
        return $pagararq[$this->current - 1];
    }

    /**
     * Returns paginator count.
     * 
     * @return null
     */
    public function getCount()
    {
        if ($this->count !== null) {
            return $this->count;
        } else {
            return null;
        }
    }
    
}