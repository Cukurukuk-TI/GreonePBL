<!-- <?php 
// // 
// namespace App\Http\Requests;

// use Illuminate\Foundation\Http\FormRequest;
// use App\Models\Alamat; // Import model Alamat

// class SimpanAlamatRequest extends FormRequest
// {
//     /**

//     public function authorize(): bool
//     {
//         // Jika methodnya adalah 'POST' (untuk aksi store/create),
//         // user hanya perlu login untuk bisa menambah alamat baru.
//         if ($this->isMethod('post')) {
//             return auth()->check();
//         }

//         // Jika methodnya adalah 'PUT' atau 'PATCH' (untuk aksi update),
//         // kita perlu memastikan user adalah pemilik alamat tersebut.
//         // Hal ini juga secara implisit melindungi method edit dan destroy.
//         $alamat = $this->route('alamat'); // Ambil model Alamat dari route
//         if ($alamat) {
//             return auth()->check() && $alamat->user_id == auth()->id();
//         }

//         return false;
//     }

//     /**
//      * Dapatkan aturan validasi yang berlaku untuk request.
//      *
//      * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
//      */
//     public function rules(): array
//     {
//         return [
//             'label' => 'required|in:rumah,kantor,lainnya', // 'other' lebih baik diganti 'lainnya'
//             'nama_penerima' => 'required|string|max:255',
//             'nomor_hp' => 'required|string|max:20',
//             'provinsi' => 'required|string|max:255',
//             'kota' => 'required|string|max:255',
//             'detail_alamat' => 'required|string|max:1000',
//         ];
//     }
// } -->