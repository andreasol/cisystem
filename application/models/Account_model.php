<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Account_model extends CI_Model
{
    /**
     * This function is used to get the account listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function accountListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.accountId, User.name, BaseTbl.balance,BaseTbl.identificator, BaseTbl.createdDtm, Role.role');
        $this->db->from('tbl_accounts as BaseTbl');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        $this->db->join('tbl_roles as Role', 'Role.roleId = User.roleId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.identificator  LIKE '%".$searchText."%'
                            OR  BaseTbl.balance  LIKE '%".$searchText."%'
                            OR  BaseTbl.userId  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('Role.roleId !=', 1);
        $query = $this->db->get();
        
        return $query->num_rows();
    }
    
    /**
     * This function is used to get the account listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function accountListing($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.accountId, User.name, BaseTbl.balance, BaseTbl.identificator, BaseTbl.createdDtm, Role.role');
        $this->db->from('tbl_accounts as BaseTbl');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        $this->db->join('tbl_roles as Role', 'Role.roleId = User.roleId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.identificator  LIKE '%".$searchText."%'
                            OR  BaseTbl.balance  LIKE '%".$searchText."%'
                            OR  BaseTbl.userId  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('Role.roleId !=', 1);
        $this->db->order_by('BaseTbl.accountId', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
    
    /**
     * This function is used to get the account roles information
     * @return array $result : This is result of the query
     */
    function getaccountRoles()
    {
        $this->db->select('roleId, role');
        $this->db->from('tbl_roles');
        $this->db->where('roleId !=', 1);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    /**
     * This function is used to get the account roles information
     * @return array $result : This is result of the query
     */
    function getaccountusers()
    {
        $this->db->select('userId, name');
        $this->db->from('tbl_users');
        //$this->db->where('userId !=', 1);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    /**
     * This function is used to get the account roles information
     * @return array $result : This is result of the query
     */
    function getaccountbanks()
    {
        $this->db->select('bankId, name');
        $this->db->from('tbl_banks');
        $query = $this->db->get();
        
        return $query->result();
    }
    

    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $accountId : This is account id
     * @return {mixed} $result : This is searched result
     */
    function checkEmailExists($email, $accountId = 0)
    {
        $this->db->select("email");
        $this->db->from("tbl_accounts");
        $this->db->where("email", $email);   
        $this->db->where("isDeleted", 0);
        if($accountId != 0){
            $this->db->where("accountId !=", $accountId);
        }
        $query = $this->db->get();

        return $query->result();
    }
    
    
    /**
     * This function is used to add new account to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewAccount($accountInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_accounts', $accountInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get account information by id
     * @param number $accountId : This is account id
     * @return array $result : This is account information
     */
    function getaccountInfo($accountId)
    {
        $this->db->select('accountId, userId, balance, bankId, identificator');
        $this->db->from('tbl_accounts');
        $this->db->where('isDeleted', 0);
		//$this->db->where('roleId !=', 1);
        $this->db->where('accountId', $accountId);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    
    /**
     * This function is used to update the account information
     * @param array $accountInfo : This is accounts updated information
     * @param number $accountId : This is account id
     */
    function editaccount($accountInfo, $accountId)
    {
        $this->db->where('accountId', $accountId);
        $this->db->update('tbl_accounts', $accountInfo);
        
        return TRUE;
    }
    
    
    
    /**
     * This function is used to delete the account information
     * @param number $accountId : This is account id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteaccount($accountId, $accountInfo)
    {
        $this->db->where('accountId', $accountId);
        $this->db->update('tbl_accounts', $accountInfo);
        
        return $this->db->affected_rows();
    }

    
    /**
     * This function used to get account information by id
     * @param number $accountId : This is account id
     * @return array $result : This is account information
     */
    function getaccountInfoById($accountId)
    {
        $this->db->select('accountId, name, email, mobile, roleId');
        $this->db->from('tbl_accounts');
        $this->db->where('isDeleted', 0);
        $this->db->where('accountId', $accountId);
        $query = $this->db->get();
        
        return $query->row();
    }

}

  