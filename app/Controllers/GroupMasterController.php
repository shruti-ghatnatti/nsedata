<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\UploaderModel;
use App\Models\SymbolMasterModel;

class Home extends BaseController
{
    public function index()
    {

        echo view('commons/header');
        echo view('pages/uploadCSV');
        echo view('commons/footer');
    }

    public function uploadCSVSubmit(){
        $input = $this->validate([
            'file' => 'uploaded[file]|max_size[file,2048]|ext_in[file,csv],'
        ]);
        if (!$input) {
            $data['validation'] = $this->validator;
            return redirect()->route('/');
        }else{
            if($file = $this->request->getFile('file')) {
            if ($file->isValid() && ! $file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move('../public_html/csvfile', $newName);
                $file = fopen("../public_html/csvfile/".$newName,"r");
                $i = 0;
                $flag = true;
                $numberOfFields = 15;
                $csvArr = array();
                $symbolArr = array();
                $j = 0;
                while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                    echo '<pre>';
                    $num = count($filedata);
                    //execute this for the first time to Skip Header
                    if($flag === true){ 
                        $flag = false;
                    } else {
                        if($i > 0 && $num == $numberOfFields){
                            if($filedata[1] === " EQ"){
                                // Step 1. For Master Symbol:
                                $symbolArr[$j]['sym_name'] = $filedata[0];

                                //Step 2: All Symbol Data:
                                $csvArr[$j]['sym_name'] = $filedata[0];
                                $csvArr[$j]['series_id'] = 1;
                                $csvArr[$j]['historical_date'] = $filedata[2];
                                $csvArr[$j]['prev_close'] = $filedata[3];
                                $csvArr[$j]['open_price'] = $filedata[4];
                                $csvArr[$j]['high_price'] = $filedata[5];
                                $csvArr[$j]['low_price'] = $filedata[6];
                                $csvArr[$j]['close_price'] = $filedata[7];
                                $csvArr[$j]['avg_price'] = $filedata[8];
                                $csvArr[$j]['ttl_trd_qty'] = $filedata[9];
                                $csvArr[$j]['turnover_lacs'] = $filedata[10];
                                $csvArr[$j]['no_of_trades'] = $filedata[11];
                                $csvArr[$j]['deliv_qty'] = $filedata[12];
                                $csvArr[$j]['deliv_per'] = $filedata[13];
                                // add if any columns
                            } else {
                                //skip
                            }
                            $j++;
                        }
                    }
                    $i++;
                }
                fclose($file);
                $count = 0;
                // Step 3: Insert SYMBOL into Master Table - master_symbol
                foreach($symbolArr as $data){
                    $symbolModel = new SymbolMasterModel();
                    $findRecord = $symbolModel->where('sym_name', $data['sym_name'])->countAllResults();
                    if($findRecord == 0){
                        if($symbolModel->insert($data)){
                            // $count++;
                        }
                    }
                }
                // Step 4: Insert SYMBOL Data
                
                foreach($csvArr as $data){
                    //Step a: Fetch the Symbol Id:
                    $symData = $symbolModel->getSymIdByName($data['sym_name']);
                    // Step b: Unset the Sym Name read from the file
                    unset($data['sym_name']);
                    //Step c: Add the Id to the Sym_data Array
                    $data['sym_id'] = $symData[0]->sym_id;
                    // Step d: Insert the Symbol Data
                    $uploaderModel = new UploaderModel();
                    $findRecord = $uploaderModel->where(['sym_id' => $data['sym_id'], 'historical_date' => $data['historical_date']])->countAllResults();
                    if($findRecord == 0){
                        if($uploaderModel->insert($data)){
                            $count++;
                        }
                    }
                }
                session()->setFlashdata('message', $count.' rows successfully added.');
                session()->setFlashdata('alert-class', 'alert-success');
            }
            else{
                session()->setFlashdata('message', 'CSV file coud not be imported.');
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            }else{
                session()->setFlashdata('message', 'CSV file coud not be imported.');
                session()->setFlashdata('alert-class', 'alert-danger');
            }
        }
        return redirect()->route('/');         
    }

}
