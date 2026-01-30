<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>POS Coffee Shop</title>
    
    {{-- Tailwind & Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Font Inter --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; -webkit-tap-highlight-color: transparent; }
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 h-screen flex flex-col overflow-hidden">

    {{-- 1. HEADER (Fixed) --}}
    <div class="bg-white shadow-sm z-30 px-4 py-3 flex-none">
        <div class="flex justify-between items-center mb-3">
            <div>
                <h1 class="text-lg font-bold text-gray-900 leading-none">Coffee POS ☕</h1>
                <p class="text-xs text-gray-500 mt-1">Shift Pagi • Budi</p>
            </div>
            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                👤
            </div>
        </div>
        
        {{-- Search Bar --}}
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">🔍</span>
            <input type="text" id="searchInput" onkeyup="filterMenu()" placeholder="Cari menu..." 
                class="w-full bg-gray-100 border-none rounded-xl pl-9 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-brown-500 outline-none">
        </div>
    </div>

    {{-- 2. KATEGORI (Horizontal Scroll) --}}
    <div class="bg-white border-b border-gray-100 flex-none pb-2">
        <div class="flex overflow-x-auto whitespace-nowrap hide-scroll px-4 space-x-2">
            <button onclick="filterCategory('all')" class="category-btn active px-4 py-2 rounded-full text-xs font-bold bg-gray-900 text-white shadow-md transition-all">
                Semua
            </button>
            @foreach($categories as $category)
            <button onclick="filterCategory('{{ $category->slug }}')" class="category-btn px-4 py-2 rounded-full text-xs font-medium bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all" data-category="{{ $category->slug }}">
                {{ $category->name }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- 3. GRID PRODUK (Scrollable Area) --}}
    <main class="flex-grow overflow-y-auto p-4 pb-24 hide-scroll" id="product-container">
        <div class="grid grid-cols-2 gap-4">
            @foreach($products as $product)
            <div class="product-card bg-white p-3 rounded-2xl shadow-sm border border-gray-100 flex flex-col h-full active:scale-95 transition-transform duration-100"
                 data-name="{{ strtolower($product->name) }}"
                 data-category="{{ $product->category->slug ?? 'all' }}">
                
                {{-- Gambar --}}
                <div class="h-28 w-full bg-gray-100 rounded-xl mb-3 overflow-hidden relative">
                     {{-- Gunakan dummy image jika null --}}
                    <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://placehold.co/300x300?text='.urlencode($product->name) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-cover">
                    
                    {{-- Stok Badge --}}
                    <span class="absolute top-2 right-2 bg-black/60 text-white text-[10px] px-2 py-0.5 rounded-full backdrop-blur-md">
                        {{ $product->stock }}
                    </span>
                </div>
                
                {{-- Info --}}
                <div class="flex-grow">
                    <h3 class="font-bold text-gray-800 text-sm leading-tight mb-1">{{ $product->name }}</h3>
                    <p class="text-[10px] text-gray-400 line-clamp-2">{{ $product->description }}</p>
                </div>
                
                {{-- Harga & Add Button --}}
                <div class="flex justify-between items-end mt-3">
                    <span class="font-bold text-gray-900 text-sm">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                    <button onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})" 
                        class="w-8 h-8 rounded-full bg-gray-900 text-white flex items-center justify-center shadow-lg hover:bg-gray-700 active:bg-black transition">
                        +
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        
        {{-- State Kosong (Hidden by default) --}}
        <div id="empty-state" class="hidden flex-col items-center justify-center h-full text-gray-400 mt-10">
            <span class="text-4xl mb-2">☕</span>
            <p class="text-sm">Menu tidak ditemukan</p>
        </div>
    </main>

    {{-- 4. FLOATING CART BAR --}}
    <div onclick="openCheckoutModal()" id="cart-bar" class="fixed bottom-5 left-4 right-4 bg-gray-900 text-white rounded-2xl p-4 shadow-2xl z-40 flex justify-between items-center cursor-pointer transform translate-y-24 transition-transform duration-300">
        <div class="flex items-center space-x-3">
            <div class="relative">
                <div class="bg-white/20 w-10 h-10 rounded-full flex items-center justify-center text-lg">🛒</div>
                <span id="cart-qty" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-gray-900">0</span>
            </div>
            <div class="flex flex-col">
                <span class="text-[10px] text-gray-300 uppercase tracking-wider">Total</span>
                <span id="cart-total" class="font-bold text-lg leading-none">Rp 0</span>
            </div>
        </div>
        <div class="flex items-center space-x-2 bg-white/10 px-3 py-1.5 rounded-lg">
            <span class="font-semibold text-xs">Checkout</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </div>
    </div>

    {{-- 5. MODAL CHECKOUT (Hidden by default) --}}
    <div id="checkoutModal" class="fixed inset-0 z-50 hidden">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeCheckoutModal()"></div>
        
        {{-- Modal Content (Slide Up) --}}
        <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-3xl p-5 h-[85vh] flex flex-col shadow-2xl transform transition-transform duration-300">
            
            {{-- Handle Bar --}}
            <div class="w-12 h-1.5 bg-gray-300 rounded-full mx-auto mb-4"></div>

            <div class="flex justify-between items-center mb-4 border-b pb-4">
                <h2 class="text-lg font-bold">Detail Pesanan</h2>
                <button onclick="closeCheckoutModal()" class="text-sm text-red-500 font-semibold">Tutup</button>
            </div>

            {{-- List Item Keranjang --}}
            <div id="cart-items-container" class="flex-grow overflow-y-auto hide-scroll space-y-4 pr-2">
                </div>

            {{-- Summary & Pay --}}
            <div class="mt-4 border-t pt-4 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Subtotal</span>
                    <span id="modal-subtotal" class="font-semibold">Rp 0</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Pajak (0%)</span>
                    <span>Rp 0</span>
                </div>
                <div class="flex justify-between text-lg font-bold text-gray-900">
                    <span>Total Bayar</span>
                    <span id="modal-total">Rp 0</span>
                </div>
                
                {{-- Payment Method --}}
                <div class="grid grid-cols-2 gap-3 mt-2">
                    <button class="py-2.5 border rounded-lg text-sm font-semibold text-center hover:bg-gray-50 focus:ring-2 ring-gray-900">💵 Tunai</button>
                    <button class="py-2.5 border rounded-lg text-sm font-semibold text-center hover:bg-gray-50 focus:ring-2 ring-gray-900">📱 QRIS</button>
                </div>

                <button onclick="processPayment()" class="w-full bg-green-600 text-white font-bold py-3.5 rounded-xl shadow-lg active:scale-95 transition mt-2">
                    Bayar Sekarang
                </button>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT LOGIC --}}
    <script>
        // --- DATA ---
        let cart = [];
        
        // --- UTILS ---
        const formatRupiah = (num) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);

        // --- CORE FUNCTIONS ---
        function addToCart(id, name, price) {
            const existing = cart.find(item => item.id === id);
            if (existing) {
                existing.qty++;
            } else {
                cart.push({ id, name, price, qty: 1 });
            }
            
            // Haptic feedback
            if (navigator.vibrate) navigator.vibrate(50);
            
            updateUI();
        }

        function updateCartItem(id, change) {
            const item = cart.find(i => i.id === id);
            if (item) {
                item.qty += change;
                if (item.qty <= 0) {
                    cart = cart.filter(i => i.id !== id);
                }
            }
            updateUI();
            renderModalItems(); // Update tampilan modal juga
        }

        function updateUI() {
            const totalQty = cart.reduce((sum, i) => sum + i.qty, 0);
            const totalPrice = cart.reduce((sum, i) => sum + (i.price * i.qty), 0);
            
            // Update Floating Bar
            document.getElementById('cart-qty').innerText = totalQty;
            document.getElementById('cart-total').innerText = formatRupiah(totalPrice);
            document.getElementById('modal-subtotal').innerText = formatRupiah(totalPrice);
            document.getElementById('modal-total').innerText = formatRupiah(totalPrice);

            // Show/Hide Floating Bar
            const bar = document.getElementById('cart-bar');
            if (totalQty > 0) {
                bar.classList.remove('translate-y-24');
            } else {
                bar.classList.add('translate-y-24');
                closeCheckoutModal(); // Tutup modal jika keranjang kosong
            }
        }

        // --- FILTERING ---
        function filterMenu() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.product-card');
            let found = false;

            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                if (name.includes(search)) {
                    card.style.display = 'flex';
                    found = true;
                } else {
                    card.style.display = 'none';
                }
            });

            document.getElementById('empty-state').style.display = found ? 'none' : 'flex';
        }

        function filterCategory(slug) {
            // Update tombol active
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.classList.remove('bg-gray-900', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-600');
            });
            event.target.classList.remove('bg-gray-100', 'text-gray-600');
            event.target.classList.add('bg-gray-900', 'text-white');

            // Filter produk
            const cards = document.querySelectorAll('.product-card');
            cards.forEach(card => {
                if (slug === 'all' || card.getAttribute('data-category') === slug) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // --- MODAL LOGIC ---
        function openCheckoutModal() {
            if (cart.length === 0) return;
            renderModalItems();
            document.getElementById('checkoutModal').classList.remove('hidden');
        }

        function closeCheckoutModal() {
            document.getElementById('checkoutModal').classList.add('hidden');
        }

        function renderModalItems() {
            const container = document.getElementById('cart-items-container');
            container.innerHTML = '';

            cart.forEach(item => {
                container.innerHTML += `
                    <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl">
                        <div>
                            <h4 class="font-bold text-gray-800">${item.name}</h4>
                            <p class="text-xs text-gray-500">${formatRupiah(item.price)}</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button onclick="updateCartItem(${item.id}, -1)" class="w-7 h-7 bg-white border rounded-full text-gray-600 font-bold">-</button>
                            <span class="font-bold text-sm w-4 text-center">${item.qty}</span>
                            <button onclick="updateCartItem(${item.id}, 1)" class="w-7 h-7 bg-gray-900 text-white rounded-full font-bold">+</button>
                        </div>
                    </div>
                `;
            });
        }

        function processPayment() {
            // Logika simpan ke database akan ada di tahap selanjutnya
            alert('Fitur Bayar (Simpan ke Database) akan kita buat setelah ini!');
            console.log("Data Transaksi:", cart);
        }
    </script>
</body>
</html>

