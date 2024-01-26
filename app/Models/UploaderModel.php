<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class UploaderModel extends Model
{
    protected $table = TBL_SYMBOL_DATA;
    protected $allowedFields = [
        'sym_id',
        'series_id',
        'historical_date', 
        'prev_close' ,
        'open_price' ,
        'high_price' ,
        'low_price' ,
        'close_price', 
        'avg_price' ,
        'ttl_trd_qty', 
        'turnover_lacs', 
        'no_of_trades' ,
        'deliv_qty' ,
        'deliv_per' ,
        'created_at' ,
        'updated_at',
    ];

    public function __construct() {
        parent::__construct();
        
        $db = \Config\Database::connect();
    }
}