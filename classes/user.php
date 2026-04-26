<?php
class User {
    protected $name;
    protected $role;
    public function __construct($name, $role) {
        $this->name = $name;
        $this->role = $role;
    }
    public function getRole() {
        return $this->role;
    }
}
class Admin extends User {
    public function canManageProducts() {
        return true;
    }
}
?>