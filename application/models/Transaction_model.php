<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class transaction_model extends CI_Model
{
    /**
     * This function is used to get the transaction listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function transactionListingCount($searchText = '', $id)
    {
        $this->db->select('BaseTbl.transactionId, BaseTbl.description, BaseTbl.srcaccountId, BaseTbl.destaccountId, BaseTbl.value, BaseTbl.ttypeid, BaseTbl.state, BaseTbl.createdDtm');
        $this->db->from('tbl_transactions as BaseTbl');
        //$this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        //$this->db->join('tbl_roles as Role', 'Role.roleId = User.roleId','left');
        if($id!="ADMIN"){
            $this->db->where('BaseTbl.srcaccountId',$id);
        }
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.description  LIKE '%".$searchText."%'
                            OR  BaseTbl.srcaccountId  LIKE '%".$searchText."%'
                            OR  BaseTbl.destaccountId  LIKE '%".$searchText."%'
                            OR  BaseTbl.value  LIKE '%".$searchText."%'
                            OR  BaseTbl.ttypeid  LIKE '%".$searchText."%'
                            OR  BaseTbl.state  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        //$this->db->where('Role.roleId !=', 1);
        $query = $this->db->get();
        
        return $query->num_rows();
    }
    
    /**
     * This function is used to get the transaction listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function transactionListing($searchText = '', $page, $segment, $id)
    {
        $this->db->select('BaseTbl.transactionId, BaseTbl.description, BaseTbl.srcaccountId, BaseTbl.destaccountId, BaseTbl.value, BaseTbl.ttypeid, BaseTbl.state, BaseTbl.createdDtm');
        $this->db->from('tbl_transactions as BaseTbl');
        //$this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        //$this->db->join('tbl_roles as Role', 'Role.roleId = User.roleId','left');
        if($id!="ADMIN"){
            $this->db->where('BaseTbl.srcaccountId',$id);
        }
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.description  LIKE '%".$searchText."%'
                            OR  BaseTbl.srcaccountId  LIKE '%".$searchText."%'
                            OR  BaseTbl.destaccountId  LIKE '%".$searchText."%'
                            OR  BaseTbl.value  LIKE '%".$searchText."%'
                            OR  BaseTbl.ttypeid  LIKE '%".$searchText."%'
                            OR  BaseTbl.state  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        //$this->db->where('Role.roleId !=', 1);
        $this->db->order_by('BaseTbl.transactionId', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
    
     /**
     * This function is used to get the account roles information
     * @return array $result : This is result of the query
     */
    function gettransactionBanks()
    {
        $this->db->select('bankId, name');
        $this->db->from('tbl_banks');
        $query = $this->db->get();
        
        return $query->result();
    }
    
     /**
     * This function is used to get the account roles information
     * @return array $result : This is result of the query
     */
    function gettransactionAccounts($userId)
    {
        $this->db->select('accountId, userId, bankId');
        $this->db->from('tbl_accounts');
        $this->db->where("userId", $userId);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    
    /**
     * This function is used to check whether destaccountId is already exist or not
     * @param {string} $destaccountId : This is destaccountId id
     * @param {number} $transactionId : This is transaction id
     * @return {mixed} $result : This is searched result
     */
    function checkdestaccountIdExists($accountId, $transactionId = 0)
    {
        $this->db->select("accountId");
        $this->db->from("tbl_accounts");
        $this->db->where("accountId", $destaccountId);   
        $this->db->where("isDeleted", 0);
        /**
         * if($transactionId != 0){
         *             $this->db->where("transactionId !=", $transactionId);
         *         }
         */
        $query = $this->db->get();

        return $query->result();
    }
    
    
    /**
     * This function is used to add new transaction to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewtransaction($transactionInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_transactions', $transactionInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get transaction information by id
     * @param number $transactionId : This is transaction id
     * @return array $result : This is transaction information
     */
    function gettransactionInfo($transactionId)
    {
        $this->db->select('transactionId, description, srcaccountId, destaccountId, value, ttypeid, state');
        $this->db->from('tbl_transactions');
        $this->db->where('isDeleted', 0);
		//$this->db->where('roleId !=', 1);
        $this->db->where('transactionId', $transactionId);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    
    /**
     * This function is used to update the transaction information
     * @param array $transactionInfo : This is transactions updated information
     * @param number $transactionId : This is transaction id
     */
    function edittransaction($transactionInfo, $transactionId)
    {
        $this->db->where('transactionId', $transactionId);
        $this->db->update('tbl_transactions', $transactionInfo);
        
        return TRUE;
    }
    
    
    
    /**
     * This function is used to delete the transaction information
     * @param number $transactionId : This is transaction id
     * @return boolean $result : TRUE / FALSE
     */
    function deletetransaction($transactionId, $transactionInfo)
    {
        $this->db->where('transactionId', $transactionId);
        $this->db->update('tbl_transactions', $transactionInfo);
        
        return $this->db->affected_rows();
    }

    
    /**
     * This function used to get transaction information by id
     * @param number $transactionId : This is transaction id
     * @return array $result : This is transaction information
     */
    function gettransactionInfoById($transactionId)
    {
        $this->db->select('transactionId, description, destaccountId, mobile, roleId');
        $this->db->from('tbl_transactions');
        $this->db->where('isDeleted', 0);
        $this->db->where('transactionId', $transactionId);
        $query = $this->db->get();
        
        return $query->row();
    }

}

  