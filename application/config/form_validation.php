<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
$addEmp=array(
                array(
                    'field' => 'bfname',
                    'label' => 'First Name',
                    'rules' => 'required'),
                array(
                    'field' => 'blname',
                    'label' => 'Last Name',
                    'rules' => 'required'),
                array(
                    'field' => 'bemail',
                    'label' => 'Email',
                    'rules' => 'required|valid_email'),
                array(
                    'field' => 'bpassword',
                    'label' => 'Password',
                    'rules' => 'required'),
                array(
                    'field' => 'brepassword',
                    'label' => 'Retype-Password',
                    'rules' => 'required'),
                array(
                    'field' => 'bcontact',
                    'label' => 'Contact Number',
                    'rules' => 'required|min_length[10]'),
                array(
                    'field' => 'baddress',
                    'label' => 'Adress',
                    'rules' => 'required'),
                array(
                    'field' => 'bgender',
                    'label' => 'Gender',
                    'rules' => 'required')
            );
$add_gkmEmp=$addEmp;
array_push($add_gkmEmp,array(
                    'field' => 'bdesignation',
                    'label' => 'Designation',
                    'rules' => 'required'));

$editEmp=array(
                array(
                    'field' => 'bfname',
                    'label' => 'First Name',
                    'rules' => 'required'),
                array(
                    'field' => 'blname',
                    'label' => 'Last Name',
                    'rules' => 'required'),
                array(
                    'field' => 'bemail',
                    'label' => 'Email',
                    'rules' => 'required|valid_email'),
                array(
                    'field' => 'bcontact',
                    'label' => 'Contact Number',
                    'rules' => 'required|min_length[10]'),
                array(
                    'field' => 'baddress',
                    'label' => 'Adress',
                    'rules' => 'required'),
                array(
                    'field' => 'bgender',
                    'label' => 'Gender',
                    'rules' => 'required')
            );

$edit_gkmEmp=$editEmp;
array_push($edit_gkmEmp,array(
                    'field' => 'bdesignation',
                    'label' => 'Designation',
                    'rules' => 'required'));
$payment_request_default = array(
        array(
                    'field' => 'branchid',
                    'label' => 'Branch',
                    'rules' => 'required'),
                array(
                    'field' => 'reqfor',
                    'label' => 'Request For',
                    'rules' => 'required'),
                array(
                    'field' => 'billamount',
                    'label' => 'Bill Amount',
                    'rules' => 'required|numeric'),
                array(
                    'field' => 'duedate',
                    'label' => 'Due Date',
                    'rules' => 'required|'),
                array(
                    'field' => 'billamount_duedate',
                    'label' => 'Bill Amount After Due Date',
                    'rules' => 'required|numeric'),
                array(
                    'field' => 'paymenttransfer',
                    'label' => 'Payment Type',
                    'rules' => 'required'),
                array(
                    'field' => 'tdsdetail',
                    'label' => 'TDS Appicable',
                    'rules' => 'required'),
                array(
                    'field' => 'servtax',
                    'label' => 'Service Taxt Included',
                    'rules' => 'required'),
                array(
                    'field' => 'payment_notes',
                    'label' => 'Notes',
                    'rules' => 'required')
            );
$payment_request_check = array_merge($payment_request_default,array(
                            array(
                                'field' => 'payeename',
                                'label' => 'Payee Name',
                                'rules' => 'required')
                            ));
$payment_request_bank = array_merge($payment_request_default,array(
                            array(
                                'field' => 'payeename',
                                'label' => 'Payee Name',
                                'rules' => 'required'),
                            array(
                                'field' => 'accountnumber',
                                'label' => 'Account Number',
                                'rules' => 'required'),
                            array(
                                'field' => 'ifsccode',
                                'label' => 'IFSC Code',
                                'rules' => 'required'),
                            array(
                                'field' => 'bankdetails',
                                'label' => 'Bank Details',
                                'rules' => 'required')
                        ));

$config = array("edit_branch" => 
            array(
                array(
                    'field' => 'bname',
                    'label' => 'Branch Name',
                    'rules' => 'required'),
                array(
                    'field' => 'blocation',
                    'label' => 'Branch Location',
                    'rules' => 'required'),
                array(
                    'field' => 'baddress',
                    'label' => 'Branch Address',
                    'rules' => 'required'),
                array(
                    'field' => 'badmin',
                    'label' => 'Branch Admin',
                    'rules' => 'required'),
                array(
                    'field' => 'bemployees',
                    'label' => 'Branch Employees',
                    'rules' => 'required')
            ),
            "add_employee" => $addEmp,
            "add_gkm_employee" =>$add_gkmEmp,
            "edit_employee" => $editEmp,
            "edit_gkm_employee" =>$edit_gkmEmp,
            "change_password" =>
            array(
                array(
                    'field' => 'oldpass',
                    'label' => 'Old Password',
                    'rules' => 'required'),
                array(
                    'field' => 'newpass',
                    'label' => 'New Password',
                    'rules' => 'required'),
                array(
                    'field' => 'renewpass',
                    'label' => 'Re-type New Password',
                    'rules' => 'required')
            ),
            "auform_upload" =>
            array(
                array(
                    'field' => 'recname',
                    'label' => 'Recruit Name',
                    'rules' => 'required'),
                array(
                    'field' => 'recemail',
                    'label' => 'Recruit Email',
                    'rules' => 'required|valid_email'),
                array(
                    'field' => 'recmessage',
                    'label' => 'Comments',
                    'rules' => 'required'),
                array(
                    'field' => 'recdslist',
                    'label' => 'Distribution List',
                    'rules' => 'required')
            ),
            "auform_message" =>
            array(
                array(
                    'field' => 'recmessage',
                    'label' => 'Comments',
                    'rules' => 'required')
            ),
            "auform_forward" =>
            array(
                array(
                    'field' => 'recdslist',
                    'label' => 'Forward To',
                    'rules' => 'required'),
                array(
                    'field' => 'recmessage',
                    'label' => 'Comments',
                    'rules' => 'required')
            ),
            "upload_offer" =>
            array(
                array(
                    'field' => 'recmessage',
                    'label' => 'Comments',
                    'rules' => 'required')
            ),
            "upload_salary" =>
            array(
                array(
                    'field' => 'recmessage',
                    'label' => 'Comments',
                    'rules' => 'required')
            ),
            "payrolldocs_save"=>
            array(
                array(
                    'field' => 'recmessage',
                    'label' => 'Comments',
                    'rules' => 'not-required')
            ),
            "payment_request"=>$payment_request_default,
            "payment_request_bank"=>$payment_request_bank,
            "payment_request_check"=>$payment_request_check,
            "payment_message" =>
            array(
                array(
                    'field' => 'recmessage',
                    'label' => 'Comments',
                    'rules' => 'required')
            ),
            "changepayment_status" =>
            array(
                array(
                    'field' => 'paystatus',
                    'label' => 'Payment Status',
                    'rules' => 'required'),
                array(
                    'field' => 'recmessage',
                    'label' => 'Comments',
                    'rules' => 'required')
            ),
            "forward_acc" =>
            array(
                array(
                    'field' => 'forwardstatus',
                    'label' => 'Forward Status',
                    'rules' => 'required'),
                array(
                    'field' => 'recmessage',
                    'label' => 'Comments',
                    'rules' => 'required')
            ),
            "voucher_status" =>
            array(
                array(
                    'field' => 'voucherstatus',
                    'label' => 'Voucher Status',
                    'rules' => 'required'),
                array(
                    'field' => 'recmessage',
                    'label' => 'Comments',
                    'rules' => 'required')
            ),
            "payment_status_change" =>
            array(
                array(
                    'field' => 'paymade_status',
                    'label' => 'Payment Status',
                    'rules' => 'required'),
                array(
                    'field' => 'recmessage',
                    'label' => 'Comments',
                    'rules' => 'required')
            ),
            "rerequest_status" =>
            array(
                array(
                    'field' => 'requeststatus',
                    'label' => 'Rerequest Status',
                    'rules' => 'required'),
                array(
                    'field' => 'recmessage',
                    'label' => 'Comments',
                    'rules' => 'required')
            ),
            "news_add" =>
            array(
                array(
                    'field' => 'newstext',
                    'label' => 'News',
                    'rules' => 'required')
            )
        );

?>