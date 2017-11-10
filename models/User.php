<?php
class user extends Model{
    public function getUser() {        
        return $this->db->query("SELECT * FROM `user`")->fetch();
    }
}
