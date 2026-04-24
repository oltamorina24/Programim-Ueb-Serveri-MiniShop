<?php
class User{
    protected $name;
}

class Admin extends User{
    public function access(){
        return "Full access";
    }
}