<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : bank (bankController)
 * bank Class to control all bank related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Bank extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('bank_model');
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the bank
     */
    public function index()
    {
        $this->global['pageTitle'] = 'CodeInsect : Dashboard';
        
        $this->loadViews("dashboard", $this->global, NULL , NULL);
    }
    
    /**
     * This function is used to load the bank list
     */
    function bankListing()
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
            
            $count = $this->bank_model->bankListingCount($searchText);

			$returns = $this->paginationCompress ( "bankListing/", $count, 10 );
            
            $data['bankRecords'] = $this->bank_model->bankListing($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'CodeInsect : bank Listing';
            
            $this->loadViews("bank/view_list", $this->global, $data, NULL);
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
            $this->load->model('bank_model');
            //$data['roles'] = $this->bank_model->getbankRoles();
            
            $this->global['pageTitle'] = 'CodeInsect : Add New bank';

            $this->loadViews("bank/addNew", $this->global, NULL, NULL);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function checkEmailExists()
    {
        $bankId = $this->input->post("bankId");
        $email = $this->input->post("email");

        if(empty($bankId)){
            $result = $this->bank_model->checkEmailExists($email);
        } else {
            $result = $this->bank_model->checkEmailExists($email, $bankId);
        }

        if(empty($result)){ echo("true"); }
        else { echo("false"); }
    }
    
    /**
     * This function is used to add new bank to the system
     */
    function addNewbank()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('bname','Bank Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('address','Address','trim|required|max_length[128]');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('contact','Contact Name','trim|required|max_length[20]');
            $this->form_validation->set_rules('swiftcode','Swift code','trim|required|max_length[20]');
            $this->form_validation->set_rules('ibancode','IBAN code','trim|required|max_length[20]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->addNew();
            }
            else
            {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('bname'))));
                $address = $this->security->xss_clean($this->input->post('address'));
                $email = $this->security->xss_clean($this->input->post('email'));
                $contact = $this->security->xss_clean($this->input->post('contact'));
                $swiftcode = $this->security->xss_clean($this->input->post('swiftcode'));
                $ibancode = $this->security->xss_clean($this->input->post('ibancode'));
                
                $bankInfo = array('email'=>$email, 'address'=>$address, 'contact'=>$contact, 'name'=> $name, 'swiftcode'=>$swiftcode,
                                    'ibancode'=>$ibancode, 'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
                
                $this->load->model('bank_model');
                $result = $this->bank_model->addNewbank($bankInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New bank created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'bank creation failed');
                }
                
                redirect('Bank/addNew');
            }
        }
    }

    
    /**
     * This function is used load bank edit information
     * @param number $bankId : Optional : This is bank id
     */
    function editOld($bankId = NULL)
    {
        /*if( $bankId == 0)
        {
            $this->loadThis();
        }
        else
        {*/
            if($bankId == null)
            {
                redirect('bankListing');
            }
            
            //$data['roles'] = $this->bank_model->getbankRoles();
            $data['bankInfo'] = $this->bank_model->getbankInfo($bankId);
            
            $this->global['pageTitle'] = 'System : Edit bank';
            
            $this->loadViews("Bank/editOld", $this->global, $data, NULL);
        //}
    }
    
    
    /**
     * This function is used to edit the bank information
     */
    function editbank()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $bankId = $this->input->post('bankId');
            
            $this->form_validation->set_rules('bname','Bank Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('address','Address','trim|required|max_length[128]');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('contact','Contact Name','trim|required|max_length[20]');
            $this->form_validation->set_rules('swiftcode','Swift code','trim|required|max_length[20]');
            $this->form_validation->set_rules('ibancode','IBAN code','trim|required|max_length[20]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->editOld($bankId);
            }
            else
            {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('bname'))));
                $address = $this->security->xss_clean($this->input->post('address'));
                $email = $this->security->xss_clean($this->input->post('email'));
                $contact = $this->security->xss_clean($this->input->post('contact'));
                $swiftcode = $this->security->xss_clean($this->input->post('swiftcode'));
                $ibancode = $this->security->xss_clean($this->input->post('ibancode'));
                
                $bankInfo = array('email'=>$email, 'address'=>$address, 'contact'=>$contact, 'name'=> $name, 'swiftcode'=>$swiftcode,
                                    'ibancode'=>$ibancode, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
                
                
                $result = $this->bank_model->editbank($bankInfo, $bankId);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'bank updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'bank updation failed');
                }
                
                redirect('Bank/bankListing');
            }
        }
    }


    /**
     * This function is used to delete the bank using bankId
     * @return boolean $result : TRUE / FALSE
     */
    function deletebank()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $bankId = $this->input->post('bankId');
            $bankInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->bank_model->deletebank($bankId, $bankInfo);
            
            if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
            else { echo(json_encode(array('status'=>FALSE))); }
        }
    }
    
    /**
     * This function is used to load the change password screen
     */
    function loadChangePass()
    {
        $this->global['pageTitle'] = 'CodeInsect : Change Password';
        
        $this->loadViews("changePassword", $this->global, NULL, NULL);
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