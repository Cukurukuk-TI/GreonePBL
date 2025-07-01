<?php

namespace App\Http\Controllers;

use App\Models\Testimoni;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TestimoniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Untuk Admin: Menampilkan semua testimoni
            $testimonis = Testimoni::with(['user', 'produk'])->latest()->get();
            return view('testimoni.index', compact('testimonis'));
        } catch (\Exception $e) {
            Log::error('Error loading testimoni index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data testimoni.');
        }
    }

    /**
     * Show the form for creating a new resource (as a popup).
     */
    public function create($pesanan_id)
    {
        try {
            // 1. Ambil pesanan beserta relasi detail dan produknya
            $pesanan = Pesanan::with('details.produk')->findOrFail($pesanan_id);

            // 2. Otorisasi: Pastikan pesanan ini milik user yang sedang login
            if ($pesanan->user_id !== Auth::id()) {
                return response('Akses ditolak.', 403);
            }

            // 3. Validasi: Hanya pesanan 'complete' yang bisa diulas
            if ($pesanan->status !== 'complete') {
                return response('Testimoni hanya bisa diberikan untuk pesanan yang sudah selesai.', 403);
            }

            // 4. Ambil detail produk pertama dari pesanan
            $detail = $pesanan->details->first();
            if (!$detail) {
                return response('Pesanan ini tidak memiliki produk untuk diulas.', 404);
            }

            // 5. Cek apakah testimoni untuk produk ini sudah ada
            $existingTestimoni = Testimoni::where('user_id', Auth::id())
                ->where('produk_id', $detail->produk_id)
                ->exists();

            if ($existingTestimoni) {
                return response('<div class="p-8 text-center bg-white rounded-lg"><p class="text-gray-700">Anda sudah memberikan ulasan untuk produk ini.</p><button onclick="closeTestimoniModal()" class="mt-4 px-4 py-2 bg-gray-200 text-gray-700 rounded-md">Tutup</button></div>', 409);
            }

            // 6. Kirim data ke view modal
            return view('testimoni.create', compact('pesanan', 'detail'));
        } catch (\Exception $e) {
            Log::error('Error creating testimoni form: ' . $e->getMessage());
            return response('Terjadi kesalahan saat memuat form testimoni.', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'pesanan_id' => 'required|exists:pesanans,id',
                'produk_id' => 'required|exists:produks,id',
                'rating' => 'required|integer|min:1|max:5',
                'komentar' => 'required|string|max:500',
                'foto_testimoni' => 'nullable|image|max:2048',
            ]);

            $pesanan = Pesanan::findOrFail($request->pesanan_id);

            if ($pesanan->user_id !== Auth::id() || $pesanan->status !== 'complete') {
                return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk memberikan testimoni pada pesanan ini.');
            }

            // Cek apakah produk yang diulas benar-benar ada dalam pesanan tersebut
            $productInOrder = $pesanan->details()->where('produk_id', $request->produk_id)->exists();
            if (!$productInOrder) {
                return redirect()->back()->with('error', 'Produk tidak ditemukan dalam pesanan yang Anda ulas.');
            }

            $existingTestimoni = Testimoni::where('user_id', Auth::id())
                ->where('produk_id', $request->produk_id)
                ->exists();
            if ($existingTestimoni) {
                return redirect()->back()->with('error', 'Anda sudah memberikan testimoni untuk produk ini.');
            }

            DB::beginTransaction();

            $fotoPath = null;
            if ($request->hasFile('foto_testimoni')) {
                $fotoPath = $request->file('foto_testimoni')->store('testimoni_photos', 'public');
            }

            Testimoni::create([
                'user_id' => Auth::id(),
                'produk_id' => $request->produk_id,
                'rating' => $request->rating,
                'komentar' => $request->komentar,
                'foto_testimoni' => $fotoPath,
            ]);

            DB::commit();

            return redirect()->route('user.pesanan')->with('success', 'Testimoni berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing testimoni: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan testimoni.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimoni $testimoni)
    {
        try {
            // Cek apakah testimoni masih ada
            if (!$testimoni || !$testimoni->exists) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Testimoni tidak ditemukan.'
                    ], 404);
                }
                return redirect()->back()->with('error', 'Testimoni tidak ditemukan.');
            }

            // Cek autentikasi user
            $user = Auth::user();
            if (!$user) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda harus login terlebih dahulu.'
                    ], 401);
                }
                return redirect()->route('login');
            }

            // Cek otorisasi
            $isAdmin = $this->isAdmin();
            $isOwner = $user->id === $testimoni->user_id;

            // Jika bukan admin dan bukan pemilik testimoni, tolak akses
            if (!$isAdmin && !$isOwner) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki izin untuk menghapus testimoni ini.'
                    ], 403);
                }
                return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus testimoni ini.');
            }

            DB::beginTransaction();

            // Simpan path foto sebelum menghapus record
            $fotoPath = $testimoni->foto_testimoni;

            // Hapus record testimoni dari database
            $testimoni->delete();

            // Hapus foto testimoni jika ada
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }

            DB::commit();

            // Tentukan pesan berdasarkan role
            $message = $isAdmin ? 'Testimoni berhasil dihapus oleh admin.' : 'Testimoni Anda berhasil dihapus.';

            // Log aktivitas penghapusan
            Log::info('Testimoni deleted successfully', [
                'deleted_testimoni_id' => $testimoni->id,
                'deleted_by_user_id' => $user->id,
                'deleted_by_role' => $isAdmin ? 'admin' : 'user',
                'original_testimoni_user_id' => $testimoni->user_id
            ]);

            // Jika request dari AJAX, return JSON response
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            // Tentukan redirect berdasarkan role
            if ($isAdmin) {
                return redirect()->route('testimoni.index')->with('success', $message);
            } else {
                return redirect()->back()->with('success', $message);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error deleting testimoni: ' . $e->getMessage(), [
                'testimoni_id' => $testimoni->id ?? 'unknown',
                'user_id' => Auth::id(),
                'error' => $e->getTraceAsString()
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus testimoni.'
                ], 500);
            }

            return redirect()->back()->with('success', 'Testimoni Berhasil Dihapus.');
        }
    }

    /**
     * Helper method untuk mengecek apakah user adalah admin
     */
    private function isAdmin()
    {
        $user = Auth::user();
        return $user && isset($user->role) && $user->role === 'admin';
    }
}