@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-400 rounded-lg shadow" role="alert">
        <div class="flex">
            <div>
                <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v2H9zm0 4V12h2v3H9z"/></svg>
            </div>
            <div>
                <p class="font-bold">Berhasil!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-400 rounded-lg shadow" role="alert">
        <div class="flex">
            <div>
                <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 5v6h2V5H9zm0 8v2h2v-2H9z"/></svg>
            </div>
            <div>
                <p class="font-bold">Oops! Terjadi Kesalahan</p>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        </div>
    </div>
@endif

@if (session('warning'))
    <div class="mb-4 p-4 bg-yellow-100 text-yellow-700 border border-yellow-400 rounded-lg shadow" role="alert">
        <div class="flex">
            <div>
                <svg class="fill-current h-6 w-6 text-yellow-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 5v6h2V5H9zm0 8v2h2v-2H9z"/></svg>
            </div>
            <div>
                <p class="font-bold">Peringatan</p>
                <p class="text-sm">{{ session('warning') }}</p>
            </div>
        </div>
    </div>
@endif

@if (session('info'))
    <div class="mb-4 p-4 bg-blue-100 text-blue-700 border border-blue-400 rounded-lg shadow" role="alert">
        <div class="flex">
            <div>
                 <svg class="fill-current h-6 w-6 text-blue-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 9V5h2v4H9zm0 6v-2h2v2H9z"/></svg>
            </div>
            <div>
                <p class="font-bold">Informasi</p>
                <p class="text-sm">{{ session('info') }}</p>
            </div>
        </div>
    </div>
@endif

{{-- Menampilkan error validasi (jika ada) --}}
@if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-400 rounded-lg shadow" role="alert">
        <div class="flex">
             <div>
                <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 5v6h2V5H9zm0 8v2h2v-2H9z"/></svg>
            </div>
            <div>
                <p class="font-bold">Oops! Ada beberapa masalah dengan input Anda:</p>
                <ul class="mt-1 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif