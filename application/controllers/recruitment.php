<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Recruitment extends CI_Controller
{

    public $msg = array("validation_error" => "");

    public function __construct()
    {

        parent::__construct();
        checkUserSession(true, '', current_url());
        $this->load->model("recruitment_model");
        $this->load->model("users_model");
        $this->load->model("mail_model");

    }

    public function index()
    {
        if ($this->phpsession->get("ad_user_level") == 4)
            redirect(site_url("recruitment/recieved"));
        checkAccess($this->phpsession->get("ad_user_level"), 4);

        if ($this->phpsession->get("ad_user_level") == 3)
            $recruitments = $this->recruitment_model->getRecruitDetails(array("u.UploadedBy" => $this->phpsession->get("ad_user_id")));
        else
            $recruitments = $this->recruitment_model->getRecruitDetails();

        $data = array(
            "view_file" => "recruitment/newreqruit_list",
            "title" => "New Authorization Forms",
            "current_menu" => "recruitment",
            "recruitments" => $recruitments,
            "error" => $this->msg);
        $this->front_template->load_template($data);
    }


    public function add()
    {
        checkAccess($this->phpsession->get("ad_user_level"), 5);

        $data = array(
            "view_file" => "recruitment/newreqruit_add",
            "title" => "Upload New Authorization Form",
            "current_menu" => "recruitment",
            "distributionlist" => $this->users_model->getUserArray("DesignationId in(3,4,7,8)"),
            "error" => $this->msg);
        $this->front_template->load_template($data);
    }

    public function view($formid)
    {
        checkAccess($this->phpsession->get("ad_user_level"), 6);
        if ($formid == "")
            redirect(site_url("recruitment"));


        $recruitobj = null;
        $recruitdata = null;
        $recruitobj = $this->recruitment_model->getRecruitDetails(array("u.FormId" => $formid));

        if ($recruitobj)
            $recruitdata = $recruitobj->result();
        if (!$recruitdata)
            redirect("recruitment");
        $data = array(
            "view_file" => "recruitment/newreqruit_view",
            "title" => "New Authorization Form",
            "current_menu" => "recruitment",
            "recruit" => $recruitdata[0],
            "messages" => $this->recruitment_model->getMessages($formid),
            "error" => $this->msg);
        $this->front_template->load_template($data);
    }

    public function save()
    {
        checkAccess($this->phpsession->get("ad_user_level"), 5);
        if ($this->input->post("auform_add") != "")
        {
            if ($this->form_validation->run('auform_upload') == true)
            {
                $file_name = "";
                $file_config['upload_path'] = './' . PLACE_RECRUIT_DOC . '/';
                $file_config['allowed_types'] = FILE_TYPE_AUTHFORM;

                $this->load->library('upload', $file_config);
                if (!$this->upload->do_upload("auform_file"))
                {
                    $this->msg["validation_error"] = $this->upload->display_errors();
                    $this->add();
                    return;

                } else
                {
                    $dataUpload = $this->upload->data();
                    $file_name = $dataUpload["raw_name"] . $dataUpload["file_ext"];
                    $ins_array = array(
                        "RecruitName" => $this->input->post("recname"),
                        "RecruitEmail" => $this->input->post("recemail"),
                        "FormLocation" => $file_name,
                        "UploadedBy" => $this->phpsession->get("ad_user_id"),
                        "UploadedOn" => date("Y-m-d H:i:s"),
                        "Status" => "1");
                    $insrtid = $this->recruitment_model->insert($ins_array);

                    if ($insrtid)
                    {
                        //NotesId, FormId, UserId, Notes, NotesOn
                        $ins_msg_array = array(
                            "FormId" => $insrtid,
                            "UserId" => $this->phpsession->get("ad_user_id"),
                            "Notes" => $this->input->post("recmessage"),
                            "FormStatus" => 1,
                            "NotesOn" => date("Y-m-d H:i:s"));
                        $insrtmsgid = $this->recruitment_model->insertmessage($ins_msg_array);
                        $distribution_list = $this->input->post("recdslist");

                        $executive = array();
                        $excutive_list = $this->users_model->getUserArray("DesignationId in(8)", 0, 1);
                        foreach ($excutive_list as $ex_list)
                            $executive = $ex_list;
                        $distribution_list_array = array();
                        $distribution_obj = array();
                        $cc = "";

                        if (count($distribution_list) > 0)
                        {
                            foreach ($distribution_list as $ds)
                            {
                                $ins_access_array = array(
                                    "FormId" => $insrtid,
                                    "UserId" => $ds,
                                    );

                                $assign_access = $this->recruitment_model->assignAccess($ins_access_array);
                            }

                            $distribution_obj = $this->users_model->getUserArray("UserId in(" . implode(",", $distribution_list) .
                                ")");
                            if (count($distribution_obj) > 0)
                            {
                                foreach ($distribution_obj as $ds_obj)
                                {
                                    if ($ds_obj['EmailId'] != $executive['EmailId'])
                                        array_push($distribution_list_array, $ds_obj['EmailId']);
                                }

                                $cc = implode(",", $distribution_list_array);
                            }
                        }
                        
                        $to = $executive['EmailId'];
                        $sub = "GKM - New Recruit Added";
                        $from_email = $this->phpsession->get("ad_user_email");
                        $from_name = $this->phpsession->get("ad_fullname");


                        $signature = $this->phpsession->get("ad_fullname");
                        if ($this->phpsession->get("ad_user_signature") != "")
                            $signature = $this->phpsession->get("ad_user_signature");

                        $data["signature"] = $signature;
                        $data["name"] = "";
                        $data["title"] = $sub;
                        $data["recruitname"] = $this->input->post("recname");
                        $data["recruitemail"] = $this->input->post("recemail");
                        $data["comments"] = $this->input->post("recmessage");
                        $data["view_file"] = "newrecruit";

                        $cont = $this->front_template->load_email_template($data);

                        $attach_file = './' . PLACE_RECRUIT_DOC . '/' . $file_name;
                        $attachement = array("0" => $attach_file);

                        $this->mail_model->sendMail($from_email, $from_name, $to, $sub, $cont, $cc, "", $attachement);

                    }
                    $this->phpsession->flashsave("succ_msg", "<p>Form Uploaded successfully</p>");
                    redirect("recruitment");
                }
            } else
            {
                $this->msg["validation_error"] = validation_errors();
                $this->add();
            }
        } else
            redirect("recruitment");
    }

    public function addmessage()
    {
        checkAccess($this->phpsession->get("ad_user_level"), 6);
        if ($this->input->post("auformmsg_add") != "" && $this->input->post("auformid") != "")
        {
            $recruitobj = null;
            $recruitdata = null;
            $recruitobj = $this->recruitment_model->getRecruitDetails(array("u.FormId" => $this->input->post("auformid")));
    
            if ($recruitobj)
                $recruitdata = $recruitobj->result();
            if (!$recruitdata)
                redirect("recruitment");
    
            if ($this->form_validation->run('auform_message') == true)
            {
                $ins_msg_array = array(
                    "FormId" => $this->input->post("auformid"),
                    "UserId" => $this->phpsession->get("ad_user_id"),
                    "Notes" => $this->input->post("recmessage"),
                    "FormStatus" => $this->input->post("auformstatus"),
                    "NotesOn" => date("Y-m-d H:i:s"));

                $insrtmsgid = $this->recruitment_model->insertmessage($ins_msg_array);
                
                $sub = "GKM - New Message Added";
                $from_email = $this->phpsession->get("ad_user_email");
                $from_name = $this->phpsession->get("ad_fullname");                
                
                $distribution_list = $this->recruitment_model->getDistributionlist($this->input->post("auformid"));
                $to = "";
                $distribution_list_array = array("0"=>$this->phpsession->get("ad_user_email"));
                
                $uploadedbyifnoObj = $this->users_model->getUserInfo($recruitdata[0]->UploadedBy);
                if($uploadedbyifnoObj)
                    array_push($distribution_list_array, $uploadedbyifnoObj->EmailId);
                
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
                $data["recruitdetails"] = $recruitdata[0];
                $data["messages"] = $this->recruitment_model->getMessages($this->input->post("auformid"));
                $data["view_file"] = "newmessage";

                $cont = $this->front_template->load_email_template($data);
                $file_name = $recruitdata[0]->FormLocation;
                $attach_file = './' . PLACE_RECRUIT_DOC . '/' . $file_name;
                $attachement = array("0" => $attach_file);
                
                $file_name_offer = $recruitdata[0]->OfferLetterLocation;
                $file_name_salary = $recruitdata[0]->SalaryBreakupLocation;
                if($recruitdata[0]->Status>=5)
                {
                    $attach_file_offer = './' . PLACE_OFFER_LETTER . '/' . $file_name_offer; 
                    $attachement["1"] = $attach_file_offer;   
                }
                if($recruitdata[0]->Status>=6)
                {
                    $attach_file_salary = './' . PLACE_SALARY_BREAKUP . '/' . $file_name_salary; 
                    $attachement["2"] = $attach_file_salary;   
                }
                

                $this->mail_model->sendMail($from_email, $from_name, $to, $sub, $cont, $cc, "", $attachement);


                $this->phpsession->flashsave("succ_msg", "<p>successfully added</p>");
                redirect(site_url("recruitment/view/" . $this->input->post("auformid")));
            } else
            {
                $this->msg["validation_error"] = validation_errors();
                $this->view($this->input->post("auformid"));
            }
        } else
            redirect("recruitment");
    }

    public function download($type, $formid, $recfileid="")
    {
        if ($formid == "")
            redirect(site_url("recruitment"));

        if ($type == "form")
        {
            checkAccess($this->phpsession->get("ad_user_level"), 6);
        } else
            if ($type == "offer")
            {
                checkAccess($this->phpsession->get("ad_user_level"), 11);
            } else
                if ($type == "salary")
                {
                    checkAccess($this->phpsession->get("ad_user_level"), 13);
                }
              else
                if ($type == "payroll")
                {
                    checkAccess($this->phpsession->get("ad_user_level"), 16);
                }

        $recruitobj = null;
        $recruitdata = null;
        $recruitobj = $this->recruitment_model->getRecruitDetails(array("u.FormId" => $formid));

        if ($recruitobj)
            $recruitdata = $recruitobj->result();

        if (!$recruitdata)
        {
            $this->phpsession->flashsave("msg", "Invalid Download Link");
            redirect(site_url("recruitment"));
        }

        $recruitdetails = $recruitdata[0];

        if ($type == "form")
        {
            $file_path = './' . PLACE_RECRUIT_DOC . '/' . $recruitdetails->FormLocation;
            $file_location = $recruitdetails->FormLocation;
        } else
            if ($type == "offer")
            {
                $file_path = './' . PLACE_OFFER_LETTER . '/' . $recruitdetails->OfferLetterLocation;
                $file_location = $recruitdetails->OfferLetterLocation;
            } else
                if ($type == "salary")
                {
                    $file_path = './' . PLACE_SALARY_BREAKUP . '/' . $recruitdetails->SalaryBreakupLocation;
                    $file_location = $recruitdetails->SalaryBreakupLocation;
                } else
                    if ($type == "payroll" && $recfileid!="")
                    {
                        $recuritfileobj = null;
                        $recuritfileobj = $this->recruitment_model->getPayrollDocs($formid, $recfileid);
                        
                        if ($recuritfileobj)
                        {
                            $file_path = './' . PLACE_PAYROLL_DOCS . '/' . $recuritfileobj[0]->FormLocation;
                            $file_location = $recuritfileobj[0]->FormLocation;    
                        }
                         
                        
                    }
            

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

    public function recieved()
    {
        checkAccess($this->phpsession->get("ad_user_level"), 7);

        $recruitments = $this->recruitment_model->getRecievedRecruitDetails($this->phpsession->get("ad_user_id"));
        $data = array(
            "view_file" => "recruitment/newreqruit_list",
            "title" => "Recieved Authorization Forms",
            "current_menu" => "recievedrecruitment",
            "recruitments" => $recruitments,
            "error" => $this->msg);
        $this->front_template->load_template($data);
    }

    public function forward($formid)
    {
        checkAccess($this->phpsession->get("ad_user_level"), 8);
        if ($formid == "")
            redirect(site_url("recruitment"));


        $recruitobj = null;
        $recruitdata = null;
        $recruitobj = $this->recruitment_model->getRecruitDetails(array("u.FormId" => $formid));
        
        if ($recruitobj)
            $recruitdata = $recruitobj->result();
        if (!$recruitdata || $recruitdata[0]->Status != "1")
            redirect("recruitment");
        
        $haveaccess = array();    
        $haveaccessArray = $this->recruitment_model->getDistributionlist($formid);
        if($haveaccessArray && count($haveaccessArray)>0)
        {
            foreach($haveaccessArray as $ha)
            {
                array_push($haveaccess,$ha->UserId);
            }
        }
        
        $data = array(
            "view_file" => "recruitment/newreqruit_forward",
            "title" => "Forward to HR",
            "current_menu" => "recruitment",
            "recruit" => $recruitdata[0],
            "messages" => $this->recruitment_model->getMessages($formid),
            "distributionlist" => $this->users_model->getUserArray("DesignationId in(4)"),
            "haveaccess" => $haveaccess,
            "error" => $this->msg);
        $this->front_template->load_template($data);
    }

    public function forward_save()
    {
        checkAccess($this->phpsession->get("ad_user_level"), 8);
        if ($this->input->post("auform_forward") != "" && $this->input->post("auformid") != "")
        {
            if ($this->form_validation->run('auform_forward') == true)
            {
                
                $recruitobj = null;
                $recruitdata = null;
                $recruitobj = $this->recruitment_model->getRecruitDetails(array("u.FormId" => $this->input->post("auformid")));
        
                if ($recruitobj)
                    $recruitdata = $recruitobj->result();
                if (!$recruitdata)
                    redirect("recruitment");
                
                $haveaccess = array();    
                $haveaccessArray = $this->recruitment_model->getDistributionlist($this->input->post("auformid"));
                if($haveaccessArray && count($haveaccessArray)>0)
                {
                    foreach($haveaccessArray as $ha)
                    {
                        array_push($haveaccess,$ha->UserId);
                    }
                }
                    
                $update_array = array("Status" => "2");
                $insrtid = $this->recruitment_model->update($update_array, array("FormId" => $this->input->post("auformid")));

                $ins_msg_array = array(
                    "FormId" => $this->input->post("auformid"),
                    "UserId" => $this->phpsession->get("ad_user_id"),
                    "Notes" => $this->input->post("recmessage"),
                    "FormStatus" => 2,
                    "NotesOn" => date("Y-m-d H:i:s"));
                $insrtmsgid = $this->recruitment_model->insertmessage($ins_msg_array);
                
                $distribution_list = $this->input->post("recdslist");
                $distribution_list_array = array();
                $distribution_obj = array();
                $cc = "";

                if (count($distribution_list) > 0)
                {
                    foreach ($distribution_list as $ds)
                    {
                        if(!in_array($ds,$haveaccess))
                        {
                            $ins_access_array = array(
                                "FormId" => $this->input->post("auformid"),
                                "UserId" => $ds,
                                );
    
                            $assign_access = $this->recruitment_model->assignAccess($ins_access_array);
                        }
                    }

                    $distribution_obj = $this->users_model->getUserArray("UserId in(" . implode(",", $distribution_list) .
                        ")");
                    if (count($distribution_obj) > 0)
                    {
                        foreach ($distribution_obj as $ds_obj)
                        {
                            if ($ds_obj['EmailId'] != $executive['EmailId'])
                                array_push($distribution_list_array, $ds_obj['EmailId']);
                        }

                        $cc = implode(",", $distribution_list_array);
                    }
                }
                
                $to="";
                $hrname="";
                $to = $cc;
                
                $sub = "GKM - New Recruit";
                $from_email = $this->phpsession->get("ad_user_email");
                $from_name = $this->phpsession->get("ad_fullname");


                $signature = $this->phpsession->get("ad_fullname");
                if ($this->phpsession->get("ad_user_signature") != "")
                    $signature = $this->phpsession->get("ad_user_signature");

                $data["signature"] = $signature;
                $data["name"] = "";
                $data["title"] = $sub;
                $data["recruitdetails"] = $recruitdata[0];
                $data["messages"] = $this->recruitment_model->getMessages($this->input->post("auformid"));
                $data["view_file"] = "newmessage";

                $cont = $this->front_template->load_email_template($data);
                $file_name = $recruitdata[0]->FormLocation;
                $attach_file = './' . PLACE_RECRUIT_DOC . '/' . $file_name;
                $attachement = array("0" => $attach_file);

                $this->mail_model->sendMail($from_email, $from_name, $to, $sub, $cont, $cc, "", $attachement);
                
                
                $this->phpsession->flashsave("succ_msg", "<p>Forwarded Successfully</p>");
                redirect(site_url("recruitment/view/" . $this->input->post("auformid")));
            } else
            {
                $this->msg["validation_error"] = validation_errors();
                $this->forward($this->input->post("auformid"));
            }
        } else
            redirect("recruitment");
    }

    public function uploadoffer($formid)
    {
        checkAccess($this->phpsession->get("ad_user_level"), 9);
        if ($formid == "")
            redirect(site_url("recruitment/recieved"));


        $recruitobj = null;
        $recruitdata = null;
        $recruitobj = $this->recruitment_model->getRecruitDetails(array("u.FormId" => $formid));

        if ($recruitobj)
            $recruitdata = $recruitobj->result();
        if (!$recruitdata || !in_array($recruitdata[0]->Status,array("2","4")))
            redirect("recruitment/recieved");
        $sendto="";
        $distribution_obj = $this->users_model->getUserInfo($recruitdata[0]->UploadedBy);
        if($distribution_obj)
        {
            $sendto = $distribution_obj->FirstName." ".$distribution_obj->LastName." (".$distribution_obj->EmailId.")";
        }
        $data = array(
            "view_file" => "recruitment/newreqruit_uploadoffer",
            "title" => "Upload Offer Letter",
            "current_menu" => "recievedrecruitment",
            "recruit" => $recruitdata[0],
            "messages" => $this->recruitment_model->getMessages($formid),
            "sendto" => $sendto,
            "distributionlist" => $this->users_model->getUserArray("DesignationId in(4)"),
            "error" => $this->msg);
        $this->front_template->load_template($data);
    }

    public function uploadoffer_save()
    {
        checkAccess($this->phpsession->get("ad_user_level"), 9);
        if ($this->input->post("upload_offer") != "" && $this->input->post("auformid") != "")
        {
            $recruitobj = null;
            $recruitdata = null;
            $recruitobj = $this->recruitment_model->getRecruitDetails(array("u.FormId" => $this->input->post("auformid")));
    
            if ($recruitobj)
                $recruitdata = $recruitobj->result();
            if (!$recruitdata)
                redirect("recruitment");
                    
            if ($this->form_validation->run('upload_offer') == true)
            {
                $file_name = "";
                $file_config['upload_path'] = './' . PLACE_OFFER_LETTER . '/';
                $file_config['allowed_types'] = FILE_TYPE_OFFERLETTER;

                $this->load->library('upload', $file_config);
                if (!$this->upload->do_upload("auform_file"))
                {
                    $this->msg["validation_error"] = $this->upload->display_errors();
                    $this->uploadoffer($this->input->post("auformid"));
                    return;

                } 
                else
                {
                    $dataUpload = $this->upload->data();
                    $file_name = $dataUpload["raw_name"] . $dataUpload["file_ext"];
                    $update_array = array(
                        "OfferLetterLocation" => $file_name,
                        "OfferLetterUploadedBy" => $this->phpsession->get("ad_user_id"),
                        "OfferLetterUploadedOn" => date("Y-m-d H:i:s"),
                        "Status" => "3");
                    $updateid = $this->recruitment_model->update($update_array, array("FormId" => $this->input->post("auformid")));

                    if ($updateid)
                    {
                        //NotesId, FormId, UserId, Notes, NotesOn
                        $ins_msg_array = array(
                            "FormId" => $this->input->post("auformid"),
                            "UserId" => $this->phpsession->get("ad_user_id"),
                            "Notes" => $this->input->post("recmessage"),
                            "FormStatus" => 3,
                            "NotesOn" => date("Y-m-d H:i:s"));
                        $insrtmsgid = $this->recruitment_model->insertmessage($ins_msg_array);
                    }
                    
                    $distribution_obj = $this->users_model->getUserInfo($recruitdata[0]->UploadedBy);
                    $to="";
                    $toname="";
                    if($distribution_obj)
                    {
                        $to = $distribution_obj->EmailId;
                        $toname = $distribution_obj->FirstName." ".$distribution_obj->LastName;
                    }
                    $sub = "GKM - Offer Letter Prepared";
                    $from_email = $this->phpsession->get("ad_user_email");
                    $from_name = $this->phpsession->get("ad_fullname");
    
    
                    $signature = $this->phpsession->get("ad_fullname");
                    if ($this->phpsession->get("ad_user_signature") != "")
                        $signature = $this->phpsession->get("ad_user_signature");
    
                    $data["signature"] = $signature;
                    $data["name"] = $toname;
                    $data["title"] = $sub;
                    $data["recruitdetails"] = $recruitdata[0];
                    $data["messages"] = $this->recruitment_model->getMessages($this->input->post("auformid"));
                    $data["view_file"] = "newmessage";
    
                    $cont = $this->front_template->load_email_template($data);
                    $file_name_rec = $recruitdata[0]->FormLocation;
                    $attach_file = './' . PLACE_RECRUIT_DOC . '/' . $file_name_rec;
                    $attach_file_offer = './' . PLACE_OFFER_LETTER . '/' . $file_name;
                    $attachement = array("0" => $attach_file, "1" => $attach_file_offer);
    
                    $this->mail_model->sendMail($from_email, $from_name, $to, $sub, $cont, $cc, "", $attachement);
                    
                    
                    $this->phpsession->flashsave("succ_msg", "<p>Offer Letter Uploaded successfully</p>");
                    redirect("recruitment/recieved");
                }
            } else
            {
                $this->msg["validation_error"] = validation_errors();
                $this->uploadoffer($this->input->post("auformid"));
            }
        } else
            redirect("recruitment/recieved");
    }

    public function changeoffer($type, $formid)
    {

        if ($formid == "")
            redirect(site_url("recruitment/recieved"));


        $recruitobj = null;
        $recruitdata = null;
        $recruitobj = $this->recruitment_model->getRecruitDetails(array("u.FormId" => $formid));

        if ($recruitobj)
            $recruitdata = $recruitobj->result();
        if (!$recruitdata)
            redirect("recruitment");


        if ($type == "accept" && $recruitdata[0]->Status == "3")
        {
            checkAccess($this->phpsession->get("ad_user_level"), 10);
            $changest = "5";
            $message = "Approved By Branch Admin";
        } else
            if ($type == "reject" && $recruitdata[0]->Status == "3")
            {
                checkAccess($this->phpsession->get("ad_user_level"), 10);
                $changest = "4";
                $message = "Rejected By Branch Admin";
            } else
                if ($type == "reqconfirm" && $recruitdata[0]->Status == "7")
                {
                    checkAccess($this->phpsession->get("ad_user_level"), 14);
                    $changest = "8";
                    $message = "Confirmed By Recuiter";
                } else
                {
                    redirect("recruitment");
                }
                
        $update_array = array("Status" => $changest);

        $insrtid = $this->recruitment_model->update($update_array, array("FormId" => $formid));

        $ins_msg_array = array(
            "FormId" => $formid,
            "UserId" => $this->phpsession->get("ad_user_id"),
            "Notes" => $message,
            "FormStatus" => $changest,
            "NotesOn" => date("Y-m-d H:i:s"));
        $insrtmsgid = $this->recruitment_model->insertmessage($ins_msg_array);
        
        if($type=="accept" || $type=="reject")
        {
            $distribution_obj = $this->users_model->getUserInfo($recruitdata[0]->OfferLetterUploadedBy);
            $to="";
            $toname="";
            if($distribution_obj)
            {
                $to = $distribution_obj->EmailId;
                $toname = $distribution_obj->FirstName." ".$distribution_obj->LastName;
            }
            $sub = "GKM - Offer Letter ".$message;
            $from_email = $this->phpsession->get("ad_user_email");
            $from_name = $this->phpsession->get("ad_fullname");
    
    
            $signature = $this->phpsession->get("ad_fullname");
            if ($this->phpsession->get("ad_user_signature") != "")
                $signature = $this->phpsession->get("ad_user_signature");
    
            $data["signature"] = $signature;
            $data["name"] = $toname;
            $data["title"] = $sub;
            $data["recruitdetails"] = $recruitdata[0];
            $data["messages"] = $this->recruitment_model->getMessages($formid);
            $data["view_file"] = "newmessage";
    
            $cont = $this->front_template->load_email_template($data);
            $file_name_rec = $recruitdata[0]->FormLocation;
            $attach_file = './' . PLACE_RECRUIT_DOC . '/' . $file_name_rec;
            $file_name = $recruitdata[0]->OfferLetterLocation;
            $attach_file_offer = './' . PLACE_OFFER_LETTER . '/' . $file_name;
            $attachement = array("0" => $attach_file, "1" => $attach_file_offer);
    
            $this->mail_model->sendMail($from_email, $from_name, $to, $sub, $cont, $cc, "", $attachement);
        }
        
        
        
        $this->phpsession->flashsave("succ_msg", "<p>Updated successfully</p>");
        if($type == "reject")
            redirect(site_url("recruitment/view/" . $formid));
        else
            redirect("recruitment");
    }

    public function salarybreakup($formid)
    {
        checkAccess($this->phpsession->get("ad_user_level"), 12);
        if ($formid == "")
            redirect(site_url("recruitment/recieved"));


        $recruitobj = null;
        $recruitdata = null;
        $recruitobj = $this->recruitment_model->getRecruitDetails(array("u.FormId" => $formid));

        if ($recruitobj)
            $recruitdata = $recruitobj->result();
        if (!$recruitdata || $recruitdata[0]->Status != "5")
            redirect("recruitment");

        $data = array(
            "view_file" => "recruitment/salarybreakup",
            "title" => "Upload Salary Breakup",
            "current_menu" => "recievedrecruitment",
            "recruit" => $recruitdata[0],
            "messages" => $this->recruitment_model->getMessages($formid),
            "distributionlist" => $this->users_model->getUserArray("DesignationId in(4)"),
            "error" => $this->msg);
        $this->front_template->load_template($data);
    }

    public function uploadsalary_save()
    {
        checkAccess($this->phpsession->get("ad_user_level"), 12);
        if ($this->input->post("upload_salary") != "" && $this->input->post("auformid") != "")
        {
            $recruitobj = null;
            $recruitdata = null;
            $recruitobj = $this->recruitment_model->getRecruitDetails(array("u.FormId" => $this->input->post("auformid")));
    
            if ($recruitobj)
                $recruitdata = $recruitobj->result();
            if (!$recruitdata)
                redirect("recruitment");

            if ($this->form_validation->run('upload_salary') == true)
            {
                $file_name = "";
                $file_config['upload_path'] = './' . PLACE_SALARY_BREAKUP . '/';
                $file_config['allowed_types'] = FILE_TYPE_SALARYBREAKUP;

                $this->load->library('upload', $file_config);
                if (!$this->upload->do_upload("auform_file"))
                {
                    $this->msg["validation_error"] = $this->upload->display_errors();
                    $this->salarybreakup($this->input->post("auformid"));
                    return;

                } else
                { //FormId, RecruitName, RecruitEmail, FormLocation, OfferLetterLocation, SalaryBreakupLocation, UploadedBy, UploadedOn, AssignedTo, AssignedOn, OfferLetterUploadedBy, OfferLetterUploadedOn, Status
                    $data = $this->upload->data();
                    $file_name = $data["raw_name"] . $data["file_ext"];
                    $update_array = array(
                                    "SalaryBreakupLocation" => $file_name, 
                                    "Status" => "7",
                                    "SalaryBreakupCreatedBy" => $this->phpsession->get("ad_user_id"),
                                    "SalaryBreakupCreatedOn" => date("Y-m-d H:i:s"),
                                    );
                    $updateid = $this->recruitment_model->update($update_array, array("FormId" => $this->input->post("auformid")));

                    if ($updateid)
                    {
                        //NotesId, FormId, UserId, Notes, NotesOn
                        $ins_msg_array = array(
                            "FormId" => $this->input->post("auformid"),
                            "UserId" => $this->phpsession->get("ad_user_id"),
                            "Notes" => $this->input->post("recmessage"),
                            "FormStatus" => 7,
                            "NotesOn" => date("Y-m-d H:i:s"));
                        $insrtmsgid = $this->recruitment_model->insertmessage($ins_msg_array);
                    }
                    
                    $distribution_obj = $this->users_model->getUserInfo($recruitdata[0]->UploadedBy);
                    $to="";
                    $toname="";
                    $cc = "";
                    if($distribution_obj)
                    {
                        $to = $distribution_obj->EmailId;
                        $toname = $distribution_obj->FirstName." ".$distribution_obj->LastName;
                    }
                    $sub = "GKM - Salary Breakup Uploaded";
                    $from_email = $this->phpsession->get("ad_user_email");
                    $from_name = $this->phpsession->get("ad_fullname");
    
    
                    $signature = $this->phpsession->get("ad_fullname");
                    if ($this->phpsession->get("ad_user_signature") != "")
                        $signature = $this->phpsession->get("ad_user_signature");
    
                    $data["signature"] = $signature;
                    $data["name"] = $toname;
                    $data["title"] = $sub;
                    $data["recruitdetails"] = $recruitdata[0];
                    $data["messages"] = $this->recruitment_model->getMessages($this->input->post("auformid"));
                    $data["view_file"] = "newmessage";
    
                    $cont = $this->front_template->load_email_template($data);
                    $file_name_rec = $recruitdata[0]->FormLocation;
                    $attach_file = './' . PLACE_RECRUIT_DOC . '/' . $file_name_rec;
                    $file_name_offer = $recruitdata[0]->OfferLetterLocation;
                    $attach_file_offer = './' . PLACE_OFFER_LETTER . '/' . $file_name_offer;
                    $attach_file_salary = './' . PLACE_SALARY_BREAKUP . '/' . $file_name;
                    
                    $attachement = array("0" => $attach_file, "1" => $attach_file_offer, "2" => $attach_file_salary);
    
                    $this->mail_model->sendMail($from_email, $from_name, $to, $sub, $cont, $cc, "", $attachement);
                    $this->mail_model->clearMail();
                    
                    // Send Offer Letter to Recruit.
                    $to_o = $recruitdata[0]->RecruitEmail;
                    $sub_o = "GKM - Offer Letter ";
                    $attachement_o = array();
                    $data_o["signature"] = $signature;
                    $data_o["name"] = $recruitdata[0]->RecruitName;
                    $data_o["title"] = $sub_o;
                    $data_o["view_file"] = "confirmrecruit";
        
                    $cont_o = $this->front_template->load_email_template($data_o);
                    $attachement_o["0"] = $attach_file_offer;   
                    $attachement_o["1"] = $attach_file_salary;   
                    $this->mail_model->sendMail($from_email, $from_name, $to_o, $sub_o, $cont_o, $cc, "", $attachement_o);
                    
                    $this->phpsession->flashsave("succ_msg", "<p>Salary Breakup Uploaded successfully</p>");
                    redirect("recruitment/recieved");
                }
            } else
            {
                
                $this->msg["validation_error"] = validation_errors();
                $this->salarybreakup($this->input->post("auformid"));
            }
        } else
            redirect("recruitment/recieved");
    }

    public function payrolldocs($formid)
    {
        checkAccess($this->phpsession->get("ad_user_level"), 15);
        if ($formid == "")
            redirect(site_url("recruitment/recieved"));


        $recruitobj = null;
        $recruitdata = null;
        $recruitobj = $this->recruitment_model->getRecruitDetails(array("u.FormId" => $formid));

        if ($recruitobj)
            $recruitdata = $recruitobj->result();
        if (!$recruitdata || $recruitdata[0]->Status != "8")
            redirect("recruitment");

        $data = array(
            "view_file" => "recruitment/upload_payrolldocs",
            "title" => "Upload Documents For Payroll",
            "current_menu" => "recievedrecruitment",
            "recruit" => $recruitdata[0],
            "messages" => $this->recruitment_model->getMessages($formid),
            "payrolldocslist" => $this->recruitment_model->getPayrollDocs($formid),
            "error" => $this->msg);
        $this->front_template->load_template($data);
    }

    public function payrolldocs_save()
    {
        checkAccess($this->phpsession->get("ad_user_level"), 15);
        if ($this->input->post("payrolldocs_save") != "" && $this->input->post("auformid") != "")
        {
            
            $payrolldocs = $this->recruitment_model->getPayrollDocsList(array("IsActive" => 1));
            $payrolldocslist = $this->recruitment_model->getPayrollDocsList(array("IsActive" => 1));


            $file_config['upload_path'] = './' . PLACE_PAYROLL_DOCS . '/';
            $file_config['allowed_types'] = FILE_TYPE_RECRUITPAYDOCS;
            $errmsg = "";
            $this->load->library('upload', $file_config);
            
            
            foreach ($payrolldocs as $pd)
            {
                $this->upload->initialize($file_config);
                $fileformname = "auform_file_" . $pd->RecruitFileId;
                $file_name = "";
                
                if(isset($_FILES[$fileformname]) && $_FILES[$fileformname]['name']!="" && !in_array($pd->RecruitFileId,$payrolldocslist))
                {
                    
                    if (!$this->upload->do_upload($fileformname))
                    {
                        $errmsg .= $this->upload->display_errors("<p>", "(" . $pd->RecruitFileName . ")</p>");
                    } 
                    else
                    { 
                        $dataUpload = $this->upload->data();
                        $file_name = $dataUpload["raw_name"] . $dataUpload["file_ext"];
                        $ins_array = array(
                            "FormId" => $this->input->post("auformid"), 
                            "RecruitFileId" => $pd->RecruitFileId,  
                            "FormLocation" => $file_name,
                            "UploadedBy" => $this->phpsession->get("ad_user_id"),
                            "UploadedOn" => date("Y-m-d H:i:s"),
                            "IsActive" => "1");
                        $insrtid = $this->recruitment_model->insertdocs($ins_array);
                    }
                       
                }   
                
            }
            
            if($errmsg!="")
            $this->phpsession->flashsave("error_msg", $errmsg);
            
            redirect("recruitment/payrolldocs/".$this->input->post("auformid"));
            
        } else
            redirect("recruitment/recieved");
    }
    
    public function removepaydoc($formid,$recfileid)
    {
        checkAccess($this->phpsession->get("ad_user_level"), 15);
        if ($formid == "" || $recfileid=="")
            redirect(site_url("recruitment/recieved"));
        
        $removedoc=$this->recruitment_model->removedocs($formid, $recfileid);
        $this->phpsession->flashsave("succ_msg", "Deleted Successfully");
        redirect("recruitment/payrolldocs/".$formid);
    }
}
