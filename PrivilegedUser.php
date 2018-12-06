<?php

require_once("User.php");
require_once("db.php");

class PrivilegedUser extends User
{
    private $roles;

    public function __construct() {
        parent::__construct();
      
    }

    // override User method
    public static function getByUsername($username) {
        $pdo = pdoConnection();

        $stmt = $pdo->prepare("SELECT * from users  WHERE name = :name");
        $stmt->execute([$username]);
        $result = $stmt->fetch();
      
    
        if (!empty($result)) {
            $privUser = new PrivilegedUser();
            $privUser->user_id = $result["user_id"];
            $privUser->username = $username;
            $privUser->email = $result["email"];
            $privUser->initRoles();
            return $privUser;
        } else {
            return false;
        }
        
    }

    // populate roles with their associated permissions
    protected function initRoles() {
        $pdo = pdoConnection();
        $this->roles = array();
        //Gets roles for logged in person
        $stmt = $pdo->prepare("SELECT t1.role_id, t2.role_name FROM user_role as t1
                JOIN roles as t2 ON t1.role_id = t2.role_id
                WHERE t1.user_id = :user_id");
        $stmt->execute([$this->user_id]);
        $roles = $stmt->fetchAll();
        
        //Gets permissions for the roles the logged in person has...
        foreach($roles as $role) {
            $this->roles[$role["role_name"]] = Role::getRolePerms($role["role_id"]);
        }
     
    }

    // check if user has a specific privilege
    public function hasPrivilege($perm) {
        foreach ($this->roles as $role) {
            if ($role->hasPerm($perm)) {
                return true;
            }
        }
        return false;
    }
}