<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Bank_model extends CI_Model
{
    /**
     * This function is used to get the bank listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function bankListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.bankId, BaseTbl.name, BaseTbl.address, BaseTbl.email, BaseTbl.contact, BaseTbl.swiftcode, BaseTbl.ibancode, BaseTbl.createdDtm');
        $this->db->from('tbl_banks as BaseTbl');
        //$this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        //$this->db->join('tbl_roles as Role', 'Role.roleId = User.roleId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.address  LIKE '%".$searchText."%'
                            OR  BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.contact  LIKE '%".$searchText."%'
                            OR  BaseTbl.swiftcode  LIKE '%".$searchText."%'
                            OR  BaseTbl.ibancode  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        //$this->db->where('Role.roleId !=', 1);
        $query = $this->db->get();
        
        return $query->num_rows();
    }
    
    /**
     * This function is used to get the bank listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function bankListing($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.bankId, BaseTbl.name, BaseTbl.address, BaseTbl.email, BaseTbl.contact, BaseTbl.swiftcode, BaseTbl.ibancode, BaseTbl.createdDtm');
        $this->db->from('tbl_banks as BaseTbl');
        //$this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        //$this->db->join('tbl_roles as Role', 'Role.roleId = User.roleId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.address  LIKE '%".$searchText."%'
                            OR  BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.contact  LIKE '%".$searchText."%'
                            OR  BaseTbl.swiftcode  LIKE '%".$searchText."%'
                            OR  BaseTbl.ibancode  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        //$this->db->where('Role.roleId !=', 1);
        $this->db->order_by('BaseTbl.bankId', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
    
    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $bankId : This is bank id
     * @return {mixed} $result : This is searched result
     */
    function checkEmailExists($email, $bankId = 0)
    {
        $this->db->select("email");
        $this->db->from("tbl_banks");
        $this->db->where("email", $email);   
        $this->db->where("isDeleted", 0);
        if($bankId != 0){
            $this->db->where("bankId !=", $bankId);
        }
        $query = $this->db->get();

        return $query->result();
    }
    
    
    /**
     * This function is used to add new bank to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewbank($bankInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_banks', $bankInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get bank information by id
     * @param number $bankId : This is bank id
     * @return array $result : This is bank information
     */
    function getbankInfo($bankId)
    {
        $this->db->select('bankId, name, address, email, contact, swiftcode, ibancode');
        $this->db->from('tbl_banks');
        $this->db->where('isDeleted', 0);
		//$this->db->where('roleId !=', 1);
        $this->db->where('bankId', $bankId);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    
    /**
     * This function is used to update the bank information
     * @param array $bankInfo : This is banks updated information
     * @param number $bankId : This is bank id
     */
    function editbank($bankInfo, $bankId)
    {
        $this->db->where('bankId', $bankId);
        $this->db->update('tbl_banks', $bankInfo);
        
        return TRUE;
    }
    
    
    
    /**
     * This function is used to delete the bank information
     * @param number $bankId : This is bank id
     * @return boolean $result : TRUE / FALSE
     */
    function deletebank($bankId, $bankInfo)
    {
        $this->db->where('bankId', $bankId);
        $this->db->update('tbl_banks', $bankInfo);
        
        return $this->db->affected_rows();
    }

    
    /**
     * This function used to get bank information by id
     * @param number $bankId : This is bank id
     * @return array $result : This is bank information
     */
    function getbankInfoById($bankId)
    {
        $this->db->select('bankId, name, email, mobile, roleId');
        $this->db->from('tbl_banks');
        $this->db->where('isDeleted', 0);
        $this->db->where('bankId', $bankId);
        $query = $this->db->get();
        
        return $query->row();
    }

}

  