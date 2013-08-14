<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payment extends CI_Controller
{

    public $msg = array("validation_error" => "");

    public function __construct()
    {

        parent::__construct();
        checkUserSession(true, '', current_url());
        $this->load->model("payment_model");
        $this->load->model("users_model");
        $this->load->model("mail_model");

    }

    public function index()
    {
       
        checkAccess($this->phpsession->get("ad_user_level"), 17);

        if ($this->phpsession->get("ad_user_level") == 3)
            $paymentrequests = $this->payment_model->getPaymentRequestDetails(array("p.RequestedBy" => $this->phpsession->get("ad_user_id")));
        else if ($this->phpsession->get("ad_user_level") == 8)
            $paymentrequests = $this->payment_model->getPaymentRequestDetails("p.Status in(2, 3, 4, 5, 6)");
        else if ($this->phpsession->get("ad_user_level") == 5)
            $paymentrequests = $this->payment_model->getPaymentRequestDetails("p.Status in(2, 3, 4, 5, 6)");
        else
            $paymentrequests = $this->payment_model->getPaymentRequestDetails();

        $data = array(
            "view_file" => "payment/payment_requestlist",
            "title" => "Payment Requests",
            "current_menu" => "payment",
            "paymentrequests" => $paymentrequests,
            "error" => $this->msg);
        $this->front_template->load_template($data);
    }


    public function request()
    {
        checkAccess($this->phpsession->get("ad_user_level"), 18);
        $branchlist = $this->users_model->getUserBranch($this->phpsession->get("ad_user_id"));
        
        if(count($branchlist)<=0)
        {
            $this->phpsession->flashsave("error_msg", "<p>You haven't associated with any branch</p>");
            redirect(site_url("payment"));
        }
        
        $data = array(
            "view_file" => "payment/payment_request",
            "title" => "New Payment Request",
            "current_menu" => "payment",
            "distributionlist" => $this->users_model->getUserArray("DesignationId in(7)"),
            "branchlist" => $branchlist,
            "error" => $this->msg);
        $this->front_template->load_template($data);
    }

    public function view($requestid)
    {
        checkAccess($this->phpsession->get("ad_user_level"), 19);
        if ($requestid == "")
            redirect(site_url("payment"));
        

        $paymentobj = null;
        $paymentdata = null;
        $paymentobj = $this->payment_model->getPaymentRequestDetails(array("p.PaymentRequestId" => $requestid));
        
        if ($paymentobj)
            $paymentdata = $paymentobj->result();
        if (!$paymentdata)
            redirect("recruitment");
        
        $data = array(
            "view_file" => "payment/paymentrequest_view",
            "title" => "View Payment Request",
            "current_menu" => "payment",
            "paymentdetails" => $paymentdata[0],
            "messages" => $this->payment_model->getNotes($requestid),
            "paymentdocs" => $this->payment_model->getPaymentDocs($requestid),
            "error" => $this->msg);
        $this->front_template->load_template($data);
    }

    public function save()
    {
        checkAccess($this->phpsession->get("ad_user_level"), 18);
        if ($this->input->post("payment_request") != "")
        {
            $validation_rule='payment_request';
            if($this->input->post("paymenttransfer")=="C")
            {
               $validation_rule='payment_request_check';
            }
            else if($this->input->post("paymenttransfer")=="F")
            {
                $validation_rule='payment_request_bank';
            }  
              
            if ($this->form_validation->run($validation_rule) == true)
            {
                $dataUpload = array();
                $file_exist = array();
                $payment_doc = $_FILES;
                $file_config['upload_path'] = './' . PLACE_PAYMENT_DOCS . '/';
                $file_config['allowed_types'] = FILE_TYPE_PAYMENTDOCS;
                
                $errmsg = "";
                $this->load->library('upload');
            
                for ($tinc=0;$tinc<count($payment_doc); $tinc++)
                {
                    if($tinc==0)
                    $fileformnames = "supportdoc_file";
                    else
                    $fileformnames = "supportdoc_file_".($tinc-1);
                    
                    $this->upload->initialize($file_config);
                    
                    $file_name = "";
                    
                    if(isset($payment_doc[$fileformnames]['name']) && $payment_doc[$fileformnames]['name']!="")
                    {
                        if (!$this->upload->do_upload($fileformnames))
                        {
                            $errmsg .= $this->upload->display_errors("<p>", "(" . $payment_doc[$fileformnames]['name'] . ")</p>");
                        }
                        
                        $dataUpload[$tinc] = $this->upload->data();
                        $file_exist[$tinc] = './' . PLACE_PAYMENT_DOCS . '/' . $dataUpload[$tinc]["raw_name"] . $dataUpload[$tinc]["file_ext"];
                    }   
                }
                
                if($errmsg!="")
                {
                    for ($tinc=0;$tinc<count($payment_doc); $tinc++)
                    {
                        if(file_exists($file_exist[$tinc]))
                            unlink($file_exist[$tinc]);
                    }
                    
                    $this->msg["validation_error"]=$errmsg;
                    $this->request();    
                }
                else
                {
                    $payeename="";
                    $accountnumber="";
                    $ifsccode="";
                    $bankdetails="";
                    $paymenttype = "O";
                    $entryOnTracker = "";
                    if($this->input->post("paymenttransfer")=="C" || $this->input->post("paymenttransfer")=="F")
                    {
                        $payeename = $this->input->post("payeename");
                        $accountnumber = $this->input->post("accountnumber");
                        $ifsccode = $this->input->post("ifsccode");
                        $bankdetails = $this->input->post("bankdetails");
                    }
                    if($this->input->post("paymenttransfer")=="C")
                    {
                        $paymenttype = "F";
                    } 
                    
                    
                    
                    $ins_array = array(
                        "PaymentRequestFor" => $this->input->post("reqfor"),
                        "BillAmount" => $this->input->post("billamount"),
                        "PaymentTransferType" => $this->input->post("paymenttransfer"),
                        "DueDate" => $this->input->post("duedate"),
                        "BillAmountAfterDueDate" => $this->input->post("billamount_duedate"),
                        "PayeeName" => $payeename,
                        "PaymentType" => $paymenttype,
                        "AccountNumber" => $accountnumber,
                        "IFSCCode" => $ifsccode,
                        "BankBranchDetail" => $bankdetails,
                        "TDSApplicable" => $this->input->post("tdsdetail"),
                        "ServiceTaxIncluded" => $this->input->post("servtax"),
                        "EntryOnTracker" => $entryOnTracker,
                        "RequestedBranchId" => $this->input->post("branchid"),
                        "RequestedBy" => $this->phpsession->get("ad_user_id"),
                        "RequestedOn" => date("Y-m-d H:i:s"),
                        "Status" => "1");
                        
                    $insrtid = $this->payment_model->insert($ins_array);
                    if($insrtid)
                    {
                        $attachFileArray = array();
                        for ($tinc=0;$tinc<count($payment_doc); $tinc++)
                        {
                            if($tinc==0)
                            $fileformnames = "supportdoc_file";
                            else
                            $fileformnames = "supportdoc_file_".($tinc-1);
                            
                            if(isset($payment_doc[$fileformnames]['name']) && $payment_doc[$fileformnames]['name']!="")
                            {
                                $file_name = $dataUpload[$tinc]["raw_name"] . $dataUpload[$tinc]["file_ext"];
                                $attachFileArray[] = './' . PLACE_PAYMENT_DOCS . '/' . $file_name;
                                $ins_array = array(
                                    "PaymentRequestId" => $insrtid, 
                                    "FileName" => $file_name,  
                                    "FileLocation" => $file_name,
                                    "UploadedBy" => $this->phpsession->get("ad_user_id"),
                                    "UploadedOn" => date("Y-m-d H:i:s"),
                                    "IsActive" => "1");
                                $insrtiddoc = $this->payment_model->insertdocs($ins_array);
                            }
                        }
                        
                        $ins_msg_array = array(
                            "PaymentRequestId" => $insrtid,
                            "UserId" => $this->phpsession->get("ad_user_id"),
                            "Notes" => $this->input->post("payment_notes"),
                            "RequestStatus" => 1,
                            "NotesOn" => date("Y-m-d H:i:s"));
                        $insrtmsgid = $this->payment_model->insertmessage($ins_msg_array);
                        
                        
                        
                        $sub = "GKM - New Payment Request";
                        $from_email = $this->phpsession->get("ad_user_email");
                        $from_name = $this->phpsession->get("ad_fullname");                
                        
                        $distribution_list = $this->payment_model->getPaymentDistributionlist();
                        $to = "";
                        $distribution_list_array = array("0"=>$this->phpsession->get("ad_user_email"));
                        
                        if (count($distribution_list))
                        {
                            foreach ($distribution_list as $ds_obj)
                            {
                                if(!in_array($ds_obj->EmailId,$distribution_list_array) && $ds_obj->EmailId!=$from_email)
                                array_push($distribution_list_array, $ds_obj->EmailId);
                            }
                            $to = implode(",", $distribution_list_array);
                        }
                       
                        $signature = $this->phpsession->get("ad_fullname");
                        if ($this->phpsession->get("ad_user_signature") != "")
                            $signature = $this->phpsession->get("ad_user_signature");
                        
                        $paymentobj = $this->payment_model->getPaymentRequestDetails(array("p.PaymentRequestId" => $insrtid));
                        $paymentdata = $paymentobj->result();
                        
                        $data["signature"] = $signature;
                        $data["name"] = "";
                        $data["title"] = $sub;
                        $data["paymentdetails"] = $paymentdata[0];
                        $data["messages"] = $this->payment_model->getNotes($insrtid);
                        $data["view_file"] = "newmessagepayment";
        
                        $cont = $this->front_template->load_email_template($data);
                        $attachement = $attachFileArray;
        
                        $this->mail_model->sendMail($from_email, $from_name, $to, $sub, $cont, $cc, "", $attachement);
                        
                    
                    }
                    $this->phpsession->flashsave("succ_msg", "<p>successfully added</p>");
                    redirect(site_url("payment"));
                }
            } 
            else
            {
                $this->msg["validation_error"] = validation_errors();
                $this->request();
            }
        } else
            redirect("payment");
    }

    public function addmessage()
    {
        checkAccess($this->phpsession->get("ad_user_level"), 19);
        
        if (($this->input->post("paymentmsg_add") != "" || 
                $this->input->post("changepayment_status") != "" || 
                $this->input->post("forward_acc") != "" || 
                $this->input->post("voucher_status") != "" || 
                $this->input->post("payment_status_change") != "" ||
                $this->input->post("rerequest_status") != "")
            && $this->input->post("papymentid") != "")
        {
            $paymentobj = null;
            $paymentdata = null;
            $paymentobj = $this->payment_model->getPaymentRequestDetails(array("p.PaymentRequestId" => $this->input->post("papymentid")));
            $attachement = array();
            $distribution_list = $this->payment_model->getPaymentDistributionlist();
            
            if ($paymentobj)
                $paymentdata = $paymentobj->result();
            if (!$paymentdata)
                redirect("payment");
                
            
            
            $validation_function = "payment_message";
            if($this->input->post("changepayment_status") != "")
            {
                $validation_function = "changepayment_status";
                if ($this->input->post("paystatus") == "3")
                {
                    $distribution_list = $this->payment_model->getPaymentDistributionlist("8,7,5");
                }
            }
            else if($this->input->post("forward_acc") != "")
            {
                $validation_function = "forward_acc";
                $distribution_list = $this->payment_model->getPaymentDistributionlist("8,7,5");    
            }
            else if($this->input->post("voucher_status") != "")
            {
                $validation_function = "voucher_status";
                $distribution_list = $this->payment_model->getPaymentDistributionlist("8,7,5");   
            }
            else if($this->input->post("payment_status_change") != "")
            {
                $validation_function = "payment_status_change";
                $distribution_list = $this->payment_model->getPaymentDistributionlist("8,7,5");
            }
            else if($this->input->post("rerequest_status") != "")
            {
                $validation_function = "rerequest_status";
            }
            else if($this->input->post("paymentmsg_add") != "")
            {
                if($this->input->post("paymentstatus")=="2")
                $distribution_list = $this->payment_model->getPaymentDistributionlist("8,7");
                if($this->input->post("paymentstatus")=="3" || $this->input->post("paymentstatus")=="4" || $this->input->post("paymentstatus")=="5" || $this->input->post("paymentstatus")=="6")
                $distribution_list = $this->payment_model->getPaymentDistributionlist("8,7,5");
                
            }
                       
            
            if ($this->form_validation->run($validation_function) == true)
            {
                $message = $this->input->post("recmessage");
                $mail_sibject = "New Payment Request";
                
                $paymentstatus = $this->input->post("paymentstatus");
                if($this->input->post("changepayment_status") != "")
                {
                    $paymentstatus = $this->input->post("paystatus");
                    $update_array = array("Status" => $this->input->post("paystatus"));
                    $updateid = $this->payment_model->update($update_array, array("PaymentRequestId" => $this->input->post("papymentid")));
                    
                    if ($this->input->post("paystatus") == "3")
                    {
                        $message = "Approved & Forwarded to Chitra and Accounts <br /><br />".$message;
                    } else
                        if ($this->input->post("paystatus") == "0")
                        {
                            $message = "Rejected <br /><br />". $message;
                        }
                }
                else if($this->input->post("forward_acc") != "" && $this->input->post("forwardstatus")==2)
                {
                    $paymentstatus = 4;
                    $update_array = array("Status" => 4);
                    $updateid = $this->payment_model->update($update_array, array("PaymentRequestId" => $this->input->post("papymentid")));
                    $message = "Forwarded to Accounts <br /><br />".$message;
                }
                else if($this->input->post("payment_status_change") != "" && $this->input->post("paymade_status")==2)
                {
                    
                    $file_name = "";
                    $file_config['upload_path'] = './' . PAYMENT_RECIEPT_DOCS . '/';
                    $file_config['allowed_types'] = FILE_TYPE_RECIEPTDOCS;
                    $this->load->library('upload', $file_config);
                    
                    if (!$this->upload->do_upload("payment_suc_file"))
                    {
                        $this->msg["validation_error"] = $this->upload->display_errors();
                        $this->view($this->input->post("papymentid"));
                        return;
    
                    } else
                    {
                        $dataUpload = $this->upload->data();
                        $file_name = $dataUpload["raw_name"] . $dataUpload["file_ext"];
                        $attachement[] = './' . PAYMENT_RECIEPT_DOCS . '/'.$file_name;
                        $paymentstatus = 5;
                        $update_array = array("Status" => 5, "PaymentFileLocation"=>$file_name, "PaymentFileUploadedBy"=>$this->phpsession->get("ad_user_id"), "PaymentFileUploadedOn" => date("Y-m-d H:i:s"));
                        $updateid = $this->payment_model->update($update_array, array("PaymentRequestId" => $this->input->post("papymentid")));
                        $message = "Payment Made <br /><br />".$message;
                    }
                    
                }
                else if($this->input->post("rerequest_status") != "" && $this->input->post("requeststatus")==2)
                {
                    $payment_doc = $_FILES;
                    $file_config['upload_path'] = './' . PLACE_PAYMENT_DOCS . '/';
                    $file_config['allowed_types'] = FILE_TYPE_PAYMENTDOCS;
                    
                    $distribution_list = $this->payment_model->getPaymentDistributionlist();
                    $errmsg = "";
                    $this->load->library('upload');
                
                    for ($tinc=0;$tinc<count($payment_doc); $tinc++)
                    {
                        if($tinc==0)
                        $fileformnames = "supportdoc_file";
                        else
                        $fileformnames = "supportdoc_file_".($tinc-1);
                        
                        $this->upload->initialize($file_config);
                        
                        $file_name = "";
                        
                        if(isset($payment_doc[$fileformnames]['name']) && $payment_doc[$fileformnames]['name']!="")
                        {
                            if (!$this->upload->do_upload($fileformnames))
                            {
                                $errmsg .= $this->upload->display_errors("<p>", "(" . $payment_doc[$fileformnames]['name'] . ")</p>");
                            }
                            
                            $dataUpload[$tinc] = $this->upload->data();
                            $file_exist[$tinc] = './' . PLACE_PAYMENT_DOCS . '/' . $dataUpload[$tinc]["raw_name"] . $dataUpload[$tinc]["file_ext"];
                        }   
                    }
                    
                    if($errmsg!="")
                    {
                        for ($tinc=0;$tinc<count($payment_doc); $tinc++)
                        {
                            if(file_exists($file_exist[$tinc]))
                                unlink($file_exist[$tinc]);
                        }
                        
                        $this->msg["validation_error"]=$errmsg;
                        $this->view($this->input->post("papymentid"));
                        return;   
                    }
                    else
                    {
                        for ($tinc=0;$tinc<count($payment_doc); $tinc++)
                        {
                            if($tinc==0)
                            $fileformnames = "supportdoc_file";
                            else
                            $fileformnames = "supportdoc_file_".($tinc-1);
                            
                            if(isset($payment_doc[$fileformnames]['name']) && $payment_doc[$fileformnames]['name']!="")
                            {
                                $file_name = $dataUpload[$tinc]["raw_name"] . $dataUpload[$tinc]["file_ext"];
                                $attachement[] = './' . PLACE_PAYMENT_DOCS . '/' . $file_name;
                                $ins_array = array(
                                    "PaymentRequestId" => $this->input->post("papymentid"), 
                                    "FileName" => $file_name,  
                                    "FileLocation" => $file_name,
                                    "UploadedBy" => $this->phpsession->get("ad_user_id"),
                                    "UploadedOn" => date("Y-m-d H:i:s"),
                                    "IsActive" => "1");
                                $insrtiddoc = $this->payment_model->insertdocs($ins_array);
                            }
                        }
                    }
                    
                    $paymentstatus = 1;
                    $update_array = array("Status" => 1);
                    $updateid = $this->payment_model->update($update_array, array("PaymentRequestId" => $this->input->post("papymentid")));
                    $message = "Re-Requested <br /><br />".$message;
                }
                
                $ins_msg_array = array(
                    "PaymentRequestId" => $this->input->post("papymentid"),
                    "UserId" => $this->phpsession->get("ad_user_id"),
                    "Notes" => $message,
                    "RequestStatus" => $paymentstatus,
                    "NotesOn" => date("Y-m-d H:i:s"));

                $insrtmsgid = $this->payment_model->insertmessage($ins_msg_array);
                
                
                $sub = "GKM - Regarding Payment Request";
                $from_email = $this->phpsession->get("ad_user_email");
                $from_name = $this->phpsession->get("ad_fullname");                
                
                $to = "";
                $distribution_list_array = array("0"=>$this->phpsession->get("ad_user_email"));
                if($paymentdata[0]->EmailId != $this->phpsession->get("ad_user_email"))
                $distribution_list_array = array("1"=>$paymentdata[0]->EmailId);
                
                if (count($distribution_list))
                {
                    foreach ($distribution_list as $ds_obj)
                    {
                        if(!in_array($ds_obj->EmailId,$distribution_list_array) && $ds_obj->EmailId!=$from_email)
                        array_push($distribution_list_array, $ds_obj->EmailId);
                    }
                    $to = implode(",", $distribution_list_array);
                }
               
                $signature = $this->phpsession->get("ad_fullname");
                if ($this->phpsession->get("ad_user_signature") != "")
                    $signature = $this->phpsession->get("ad_user_signature");
                    
                $data["signature"] = $signature;
                $data["name"] = "";
                $data["title"] = $sub;
                $data["paymentdetails"] = $paymentdata[0];
                $data["messages"] = $this->payment_model->getNotes($this->input->post("papymentid"));
                $data["view_file"] = "newmessagepayment";

                $cont = $this->front_template->load_email_template($data);
                

                $this->mail_model->sendMail($from_email, $from_name, $to, $sub, $cont, $cc, "", $attachement);
                

                $this->phpsession->flashsave("succ_msg", "<p>successfully added</p>");
                redirect(site_url("payment/view/" . $this->input->post("papymentid")));
            } else
            {
                $this->msg["validation_error"] = validation_errors();
                $this->view($this->input->post("papymentid"));
            }
        } else
            redirect("payment");
    }

    public function download($requestid, $reqfileid)
    {
        if ($requestid == "" || $reqfileid=="")
            redirect(site_url("payment"));

        checkAccess($this->phpsession->get("ad_user_level"), 19);
        
        $paymentobj = null;
        $paymentdata = null;
        $paymentobj = $this->payment_model->getPaymentDocs($requestid, $reqfileid);

        if ($paymentobj)
            $paymentdata = $paymentobj[0];

        if (!$paymentdata)
        {
            $this->phpsession->flashsave("error_msg", "Invalid Download Link");
            redirect(site_url("payment"));
        }
        $file_path = './' . PLACE_PAYMENT_DOCS . '/' . $paymentdata->FileLocation;
        $file_location = $paymentdata->FileLocation;
        
        if (file_exists($file_path))
        {
            $dpath = file_get_contents($file_path);
            force_download($file_location, $dpath);
        } else
        {
            $this->phpsession->flashsave("msg", "Invalid Download Link");
            redirect(site_url("recruitment"));
        }
    }
    
    public function downloadreciept($requestid)
    {
        checkAccess($this->phpsession->get("ad_user_level"), 19);
        if ($requestid == "")
            redirect(site_url("payment"));
        

        $paymentobj = null;
        $paymentdata = null;
        $paymentobj = $this->payment_model->getPaymentRequestDetails(array("p.PaymentRequestId" => $requestid));
        
        if ($paymentobj)
            $paymentdata = $paymentobj->result();
        if (!$paymentdata)
            redirect(site_url("payment"));
            
        $recieptloc = "";
        
        $file_path = './' . PAYMENT_RECIEPT_DOCS . '/' . $paymentdata[0]->PaymentFileLocation;
        $file_location = $paymentdata[0]->PaymentFileLocation;
            
        
        if (file_exists($file_path))
        {
            $dpath = file_get_contents($file_path);
            force_download($file_location, $dpath);
        } else
        {
            $this->phpsession->flashsave("msg", "Invalid Download Link");
            redirect(site_url("payment"));
        }    
    }
}