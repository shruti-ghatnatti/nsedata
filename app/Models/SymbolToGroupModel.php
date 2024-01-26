<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class SymbolToGroupModel extends Model
{
    protected $table = TBL_SYMBOL_TO_GROUP;
    protected $allowedFields = [
        'sym_group_id',
        'sym_id',
        'group_id',
        'created_at' ,
        'updated_at',
    ];

    public function __construct() {
        parent::__construct();
        
        $db = \Config\Database::connect();
    }
}