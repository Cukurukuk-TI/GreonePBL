<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Promo - Sistem Manajemen')</title>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    {{-- Custom Styles --}}
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .promo-card {
            transition: all 0.3s ease;
        }
        
        .promo-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .promo-badge {
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
        }
        
        .promo-active {
            background: linear-gradient(45deg, #11998e 0%, #38ef7d 100%);
        }
        
        .promo-inactive {
            background: linear-gradient(45deg, #fc466b 0%, #3f5efb 100%);
        }
        
        .promo-expired {
            background: linear-gradient(45deg, #fdbb2d 0%, #22c1c3 100%);
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    {{-- Header --}}
    <header class="bg-white shadow-sm border-b">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-tags text-2xl text-blue-600"></i>
                    <h1 class="text-xl font-bold text-gray-800">Manajemen Promo</h1>
                </div>
                
                <nav class="flex items-center space-x-4">
                    <a href="{{ route('admin.promos.index') }}" 
                       class="text-gray-600 hover:text-blue-600 transition-colors duration-200">
                        <i class="fas fa-list mr-1"></i>
                        Daftar Promo
                    </a>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="text-gray-600 hover:text-blue-600 transition-colors duration-200">
                        <i class="fas fa-home mr-1"></i>
                        Dashboard
                    </a>
                </nav>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t mt-12">
        <div class="container mx-auto px-4 py-6">
            <div class="text-center text-gray-600">
                <p>&copy; {{ date('Y') }} Sistem Manajemen Promo. All rights reserved.</p>
            </div>
        </div>
    </footer>

    {{-- Scripts --}}
    <script>
        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-auto-hide');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);

        // Confirm delete
        function confirmDelete(message = 'Yakin ingin menghapus data ini?') {
            return confirm(message);
        }

        // Format currency input
        function formatCurrency(input) {
            let value = input.value.replace(/[^\d]/g, '');
            input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Date validation
        document.addEventListener('DOMContentLoaded', function() {
            const tanggalMulai = document.getElementById('tanggal_mulai');
            const tanggalSelesai = document.getElementById('tanggal_selesai');
            
            if (tanggalMulai && tanggalSelesai) {
                tanggalMulai.addEventListener('change', function() {
                    tanggalSelesai.min = this.value;
                    if (tanggalSelesai.value && tanggalSelesai.value < this.value) {
                        tanggalSelesai.value = this.value;
                    }
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>