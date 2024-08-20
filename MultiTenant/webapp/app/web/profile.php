<?php

class jwtToken
{
    public function __construct($_id, $_token, $_name, $_endpoint) {
	  $this->tokenid = $_id;
          $this->jwtToken = $_token;
          $this->friendlyName = $_name;
          $this->apiEndpoint = $_endpoint;
    }

    public $tokenid;
    public $jwtToken;
    public $friendlyName;
    public $apiEndpoint;
}

class UserProfile
{
	public function __construct() {
          $this->userid = -1;
          $this->username = "";
	  $jwtToken = [];
        }
	
	public function Set($userid, $username) {
          $this->userid = $userid;
          $this->username = $username;
	}

	public function AddToken($token) {
		array_push($this->jwtToken, $token);
	}

	public function Dump() {
		echo "Dumping local variables";
		echo "UserId: ".$this->userid;
		echo "Username: ".$this->username;
	}

	public $userid;
	public $username;
	public $jwtToken = [];
}

?>
