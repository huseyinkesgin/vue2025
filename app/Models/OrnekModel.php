namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasKod;

class OrnekModel extends Model
{
    use HasKod;

    // Tablo adını belirtiyoruz
    protected $table = 'ornek_tablo';

    // Tarih sütunlarının adlarını belirtiyoruz
    const CREATED_AT = 'eklenme_tarihi';
    const UPDATED_AT = 'duzenlenme_tarihi';
}
