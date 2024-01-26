<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class GroupMasterModel extends Model
{
    protected $table = TBL_MASTER_GROUP;
    protected $allowedFields = [
        'group_id',
        'group_name',
        'created_at' ,
        'updated_at',
    ];

    public function __construct() {
        parent::__construct();
        
        $db = \Config\Database::connect();
    }
}