<?php

require_once '../settings/db_class.php';

/**
 * 
 */
class Customer extends db_connection
{
    private $customer_id;
    private $name;
    private $email;
    private $password;
    private $role;
    private $date_created;
    private $phone_number;
    private $country;
    private $city;
    private $image;

    public function __construct($customer_id = null)
    {
        parent::db_connect();
        if ($customer_id) {
            $this->customer_id = $customer_id;
            $this->loadCustomer();
        }
    }

    private function loadCustomer($customer_id = null)
    {
        if ($customer_id) {
            $this->customer_id = $customer_id;
        }
        if (!$this->customer_id) {
            return false;
        }
        $stmt = $this->db->prepare("SELECT * FROM customer WHERE customer_id = ?");
        $stmt->bind_param("i", $this->customer_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result) {
            $this->name = $result['customer_name'];
            $this->email = $result['customer_email'];
            $this->role = $result['user_role'];
            $this->date_created = isset($result['date_created']) ? $result['date_created'] : null;
            $this->phone_number = $result['customer_contact'];
            $this->country = $result['customer_country'];
            $this->city = $result['customer_city'];
            $this->image = $result['customer_image'];
        }
    }

    public function createUser($name, $email, $password, $phone_number, $role)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO customer (customer_name, customer_email, customer_pass, customer_contact, user_role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $name, $email, $hashed_password, $phone_number, $role);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM customer WHERE customer_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function add_customer($args)
    {
        $name = $args['customer_name'];
        $email = $args['customer_email'];
        $password = $args['customer_pass'];
        $role = isset($args['user_role']) ? $args['user_role'] : 2;
        $phone_number = $args['customer_contact'];
        $country = $args['customer_country'];
        $city = $args['customer_city'];
        $image = isset($args['customer_image']) ? $args['customer_image'] : null;

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("insert into customer (customer_name, customer_email, customer_pass, customer_country, customer_city, customer_image, user_role) values (?,?,?,?,?,?,?,?)");
        
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function edit_customer($args)
    {
        $customer_id = $args['customer_id'];
        $name = $args['customer_name'];
        $email = $args['customer_email'];
        $country = $args[customer_country];
        $city = $args['customer_city'];
        $contact = $args['customer_contact'];

        $stmt = $this->db->prepare("update customer set customer_name = ?, customer_email = ?, customer_country = ?, customer_city = ?, customer_contact = ? where customer_id = ?");
        $stmt->bind_param("sssssi", $name, $email, $country, $city, $contact, $customer_id);
    }

    public function delete_customer($args)
    {
        $stmt = $this->db->prepare("delete from customer where customer_id = ?");
        $stmt->bind_param("i", $customer_id);
        return $stmt->execute();
    }

    public function get_all_customers()
    {
        $stmt = $this->db->prepare("select * from customer order by date_created desc");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function getCustomerId() {return $this->customer_id; }
    public function getCustomerName() {return $this->customer_name; }
    public function getCustomerEmail() {return $this->customer_email; }
    public function getCustomerCountry() {return $this->customer_country; }
    public function getCustomerCity() {return $this->customer_city; }
    public function getCustomerContact() {return $this->customer_contact; }
    public function getCustomerImage() {return $this->customer_image; }
    public function getUserRole() {return $this->user_role; }
    public function getDateCreated() {return $this->date_created; }

}

?>