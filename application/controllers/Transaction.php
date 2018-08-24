<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Transaction extends BaseController {
	
	public function __construct()
	{
		parent::__construct();
        $this->load->model('transaction_model');
        $this->load->model('transaction_model');
        $this->isLoggedIn(); 
	}
	
/**
     * This function used to load the first screen of the transaction
     */
    public function index()
    {
        $this->global['pageTitle'] = 'CodeInsect : Dashboard';
        
        $this->loadViews("dashboard", $this->global, NULL , NULL);
    }
    
    /**
     * This function is used to load the transaction list
     */
    function transactionListing()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {        
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $id = getidloggeduser();
            
            $count = $this->transaction_model->transactionListingCount($searchText,$id);

			$returns = $this->paginationCompress ( "transactionListing/", $count, 10 );
            
            $data['transactionRecords'] = $this->transaction_model->transactionListing($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'CodeInsect : transaction Listing';
            
            $this->loadViews("transaction/view_list", $this->global, $data, NULL);
        }
    }
    
    function getidloggeduser(){
        if($this->isAdmin() != TRUE){
               $id = "ADMIN"; 
            }else{
               $id = $this->vendorId;
            }
            return $id;
    }
    
    /**
     * This function is used to load the add new form
     */
    function addNew($string = 'TRANSFERENCIA')
    {
        if($this->isAdmin() == TRUE && $string == 'DEPOSITO')
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('transaction_model');
            //$data['roles'] = $this->transaction_model->gettransactionRoles();
            $string = $this->security->xss_clean($string);
            switch($string){
                case 'DEPOSITO':
                $this->global['transaction'] = 'DEPOSITO';
                break;
                case 'TRANSFERENCIA':
                $this->global['transaction'] = 'TRANSFERENCIA';
                break;
                case 'RETIRO': 
                $this->global['transaction'] = 'RETIRO';
                break;
            }
            
            $data['accounts'] = $this->transaction_model->gettransactionAccounts($this->vendorId);
            $data['banks'] = $this->transaction_model->gettransactionBanks();
            
            $this->global['pageTitle'] = 'CodeInsect : Add New transaction';

            $this->loadViews("transaction/addNew", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function checkEmailExists()
    {
        $transactionId = $this->input->post("transactionId");
        $email = $this->input->post("email");

        if(empty($transactionId)){
            $result = $this->transaction_model->checkEmailExists($email);
        } else {
            $result = $this->transaction_model->checkEmailExists($email, $transactionId);
        }

        if(empty($result)){ echo("true"); }
        else { echo("false"); }
    }
    
    /**
     * This function is used to add new transaction to the system
     */
    function addNewtransaction()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('bank','Bank','trim|required|numeric');
            $this->form_validation->set_rules('identificator','Identificator','trim|required|max_length[20]');
            $this->form_validation->set_rules('user','User','trim|required|numeric');
            $this->form_validation->set_rules('balance','Balance','trim|required');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->addNew();
            }
            else
            {
                $userid = $this->security->xss_clean($this->input->post('user'));
                $balance = $this->security->xss_clean($this->input->post('balance'));
                $bankId = $this->security->xss_clean($this->input->post('bank'));
                $identificator = $this->security->xss_clean($this->input->post('identificator'));
                switch($string){
                case 'DEPOSITO':
                $this->global['transaction'] = 'DEPOSITO';
                break;
                case 'TRANSFERENCIA':
                $this->global['transaction'] = 'TRANSFERENCIA';
                break;
                case 'RETIRO': 
                $this->global['transaction'] = 'RETIRO';
                break;
            }
                $transactionInfo = array('userid'=>$userid, 'balance'=>$balance, 'bankId'=> $bankId,
                                    'identificator'=>$identificator, 'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
                
                $this->load->model('transaction_model');
                $result = $this->transaction_model->addNewtransaction($transactionInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New transaction created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'transaction creation failed');
                }
                
                redirect('transaction/addNew');
            }
        }
    }

    
    /**
     * This function is used load transaction edit information
     * @param number $transactionId : Optional : This is transaction id
     */
    function editOld($transactionId = NULL)
    {
        if($this->isAdmin() == TRUE || $transactionId == 1)
        {
            $this->loadThis();
        }
        else
        {
            if($transactionId == null)
            {
                redirect('transactionListing');
            }
            
            $data['roles'] = $this->transaction_model->gettransactionRoles();
            $data['transactionInfo'] = $this->transaction_model->gettransactionInfo($transactionId);
            
            $this->global['pageTitle'] = 'CodeInsect : Edit transaction';
            
            $this->loadViews("editOld", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the transaction information
     */
    function edittransaction()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $transactionId = $this->input->post('transactionId');
        
            $this->form_validation->set_rules('user','User','trim|required');
            $this->form_validation->set_rules('balance','Balance','trim|required|numeric');
            $this->form_validation->set_rules('bank','Bank','trim|required|numeric');
            $this->form_validation->set_rules('identificator','Identificator','trim|required|max_length[20]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->editOld($transactionId);
            }
            else
            {
                $userid = $this->security->xss_clean($this->input->post('user'));
                $balance = $this->security->xss_clean($this->input->post('balance'));
                $bankId = $this->security->xss_clean($this->input->post('bank'));
                $identificator = $this->security->xss_clean($this->input->post('identificator'));
                
                $transactionInfo = array();
                
                if(empty($password))
                {
                    $transactionInfo = array('email'=>$email, 'roleId'=>$roleId, 'name'=>$name,
                                    'mobile'=>$mobile, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
                }
                else
                {
                    $transactionInfo = array('email'=>$email, 'password'=>getHashedPassword($password), 'roleId'=>$roleId,
                        'name'=>ucwords($name), 'mobile'=>$mobile, 'updatedBy'=>$this->vendorId, 
                        'updatedDtm'=>date('Y-m-d H:i:s'));
                }
                
                $result = $this->transaction_model->edittransaction($transactionInfo, $transactionId);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'transaction updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'transaction updation failed');
                }
                
                redirect('transactionListing');
            }
        }
    }


    /**
     * This function is used to delete the transaction using transactionId
     * @return boolean $result : TRUE / FALSE
     */
    function deletetransaction()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $transactionId = $this->input->post('transactionId');
            $transactionInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->transaction_model->deletetransaction($transactionId, $transactionInfo);
            
            if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
            else { echo(json_encode(array('status'=>FALSE))); }
        }
    }

    /**
     * Page not found : error 404
     */
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }

}