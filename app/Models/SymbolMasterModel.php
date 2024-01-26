<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class SymbolMasterModel extends Model
{
    protected $table = TBL_MASTER_SYMBOL;
    protected $allowedFields = [
        'sym_id',
        'sym_name',
        'created_at' ,
        'updated_at',
    ];

    public function __construct() {
        parent::__construct();
        
        $db = \Config\Database::connect();
    }

    public function getSymIdByName($data){
       
        $builder = $this->db->table($this->table);
        $builder->select('*');
        $builder->where('sym_name', $data);
        $query = $builder->get();
        if(!empty($query)){
            return $query->getResult();
        } else {
            return false;
        }
    }
}