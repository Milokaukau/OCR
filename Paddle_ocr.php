<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paddle_ocr extends CI_Controller {
    private $python_path = "C:/Users/yuanf/anaconda3/python.exe";
    private $test_script_path = "c:/xampp/htdocs/python_files/test.py";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url')); 
        
    }

    public function index()
    {
        $this->load->view("/paddle_ocr/upload");
        
    }

    public function to_api()
    {
        $file_path = 'c:/xampp/htdocs/CodeIgniter3/uploads/clear_bm.png';
        $data = json_encode(['file_path' => $file_path]);

        $ch = curl_init('http://localhost:5000/ocr');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        echo $response;
    }

    public function do_upload()
    {
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'pdf|jpg|png';
        $config['max_size']             = 300;
        $config['max_width']            = 2048;
        $config['max_height']           = 2048;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('userfile'))
        {  
            $uploaded_file_path = realpath('./uploads/'.$this->upload->data('file_name'));
            $this -> test_run_ocr($uploaded_file_path);    
        }
        else
        {
            $error = array('error' => $this->upload->display_errors());
            echo "<pre>";
            print_r($error);
            echo "</pre>";
            exit;
        }
    }

    public function test_run_ocr($uploaded_file_path)
    {
        // Run script
        ob_start();
        $output = shell_exec($this->python_path." ".$this->test_script_path." ".$uploaded_file_path);
        ob_get_clean();

        // Print out output
        echo "<pre>";
        print_r($output);
        echo "</pre>";

        // Delete the uploaded file
        echo "<br/>"."deleting ".$this->upload->data('file_name')."<br/>";
        unlink(realpath('./uploads/'.$this->upload->data('file_name')));
        exit;
    }


    //try exec .py
    //try call already made api from outside
    //try make own api 

    
    // echo "<pre>";
    // echo `whoami`;
    // echo json_encode(exec("C:/Users/yuanf/anaconda3/python.exe --version"));
    // echo $output;
    // echo "</pre>";
    // exit;

     
}
