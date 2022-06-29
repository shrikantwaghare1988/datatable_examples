<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Emi_calculator extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Employee_model','em');
        $this->load->model('Core_model','cm');
    }
    public function test()
    {
        $amount = $_REQUEST['loan_amount'];
        $interest_rate = $_REQUEST['interest_rate'];
        $months = $_REQUEST['tenure_months'];
        $daterange = $_REQUEST['daterange'];     
        
        $emi = $this->PMT($interest_rate,$months,$amount);
        $balance = $amount;
        $payment_date = $daterange; 

        $data = array();
        $html = "";
        for($i=1;$i<=$months*12;$i++)
        {
            $row = array();
            $interest = (($interest_rate/100)*$balance)/12;
            $principal = $emi - $interest;
            $balance = $balance - $principal;
            $payment_date = date('Y-m-d',strtotime("+1 month",strtotime($payment_date)));

            $row['sr_no'] = $i;
            $row['payment_date'] = $this->showDate($payment_date);
            $row['emi'] = $this->showValue($emi);
            $row['interest'] = $this->showValue($interest);
            $row['principal'] = $this->showValue($principal);
            $row['balance'] = $this->showValue($balance);

            $data[] = $row;            
            $html .= "<tr>";
            $html .= "<td>".$i."</td>";
            $html .= "<td>".$this->showDate($payment_date)."</td>";
            $html .= "<td>".$this->showValue($emi)."</td>";
            $html .= "<td>".$this->showValue($interest)."</td>";
            $html .= "<td>".$this->showValue($principal)."</td>";
            $html .= "<td>".$this->showValue($balance)."</td>";
            $html .= "</tr>";
            
        }

        //echo $html;
        $final_data = json_encode($data);
        echo $final_data;
        //echo json_last_error_msg();die;
       
        //print_r($data);
    }
    function emi_of_cal($amount,$month,$percentage){
        $rateof = ($percentage/1200);
        $x = pow((1+$rateof),$month);
        
        $emi = (($amount*$rateof)*($x/($x-1)));
        $t_amnt = ($emi*$month);
        
        return [ "total" => $t_amnt, "emi" => $emi];
    }
    function PMT($interest,$period,$loan_amount){
        $interest = (float)$interest;
        $period = (float)$period;
        $loan_amount = (float)$loan_amount;
        $period = $period * 12;
        $interest = $interest / 1200;
        $amount = $interest * -$loan_amount * pow((1+$interest),$period) / (1 - pow((1+$interest), $period));
        return $amount;
      }
      function showValue($value){
        //return number_format($value,2);
        return round($value);
        //return ceil($value);
      }
      
      function showDate($date){
        return date('jS F, Y',strtotime($date));
      }
}