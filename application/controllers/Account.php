<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Account (accountController)
 * Account Class to control all account related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Account extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('account_model');
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the account
     */
    public function index()
    {
        $this->global['pageTitle'] = 'CodeInsect : Dashboard';
        
        $this->loadViews("dashboard", $this->global, NULL , NULL);
    }
    
    /**
     * This function is used to load the account list
     */
    function accountListing()
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
            
            $count = $this->account_model->accountListingCount($searchText);

			$returns = $this->paginationCompress ( "accountListing/", $count, 10 );
            
            $data['accountRecords'] = $this->account_model->accountListing($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'CodeInsect : account Listing';
            
            $this->loadViews("Account/view_list", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function addNew()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('account_model');
            //$data['roles'] = $this->account_model->getaccountRoles();
            $data['users'] = $this->account_model->getaccountUsers();
            $data['banks'] = $this->account_model->getaccountBanks();
            
            $this->global['pageTitle'] = 'CodeInsect : Add New account';

            $this->loadViews("Account/addNew", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function checkEmailExists()
    {
        $accountId = $this->input->post("accountId");
        $email = $this->input->post("email");

        if(empty($accountId)){
            $result = $this->account_model->checkEmailExists($email);
        } else {
            $result = $this->account_model->checkEmailExists($email, $accountId);
        }

        if(empty($result)){ echo("true"); }
        else { echo("false"); }
    }
    
    /**
     * This function is used to add new account to the system
     */
    function addNewAccount()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('user','User','trim|required|numeric');
            $this->form_validation->set_rules('balance','Balance','trim|required');
            $this->form_validation->set_rules('bank','Bank','trim|required|numeric');
            $this->form_validation->set_rules('identificator','Identificator','trim|required|max_length[20]');
            
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
                
                $accountInfo = array('userid'=>$userid, 'balance'=>$balance, 'bankId'=> $bankId,
                                    'identificator'=>$identificator, 'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
                
                $this->load->model('account_model');
                $result = $this->account_model->addNewaccount($accountInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New account created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'account creation failed');
                }
                
                redirect('Account/addNew');
            }
        }
    }

    
    /**
     * This function is used load account edit information
     * @param number $accountId : Optional : This is account id
     */
    function editOld($accountId = NULL)
    {
        if($this->isAdmin() == TRUE || $accountId == 1)
        {
            $this->loadThis();
        }
        else
        {
            if($accountId == null)
            {
                redirect('accountListing');
            }
            
            $data['roles'] = $this->account_model->getaccountRoles();
            $data['accountInfo'] = $this->account_model->getaccountInfo($accountId);
            
            $this->global['pageTitle'] = 'CodeInsect : Edit account';
            
            $this->loadViews("editOld", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the account information
     */
    function editaccount()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $accountId = $this->input->post('accountId');
        
            $this->form_validation->set_rules('user','User','trim|required');
            $this->form_validation->set_rules('balance','Balance','trim|required|numeric');
            $this->form_validation->set_rules('bank','Bank','trim|required|numeric');
            $this->form_validation->set_rules('identificator','Identificator','trim|required|max_length[20]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->editOld($accountId);
            }
            else
            {
                $userid = $this->security->xss_clean($this->input->post('user'));
                $balance = $this->security->xss_clean($this->input->post('balance'));
                $bankId = $this->security->xss_clean($this->input->post('bank'));
                $identificator = $this->security->xss_clean($this->input->post('identificator'));
                
                $accountInfo = array();
                
                if(empty($password))
                {
                    $accountInfo = array('email'=>$email, 'roleId'=>$roleId, 'name'=>$name,
                                    'mobile'=>$mobile, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
                }
                else
                {
                    $accountInfo = array('email'=>$email, 'password'=>getHashedPassword($password), 'roleId'=>$roleId,
                        'name'=>ucwords($name), 'mobile'=>$mobile, 'updatedBy'=>$this->vendorId, 
                        'updatedDtm'=>date('Y-m-d H:i:s'));
                }
                
                $result = $this->account_model->editaccount($accountInfo, $accountId);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'account updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'account updation failed');
                }
                
                redirect('accountListing');
            }
        }
    }


    /**
     * This function is used to delete the account using accountId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteaccount()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $accountId = $this->input->post('accountId');
            $accountInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->account_model->deleteaccount($accountId, $accountInfo);
            
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

?>