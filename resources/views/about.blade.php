@extends('layouts.app')

@section('content')
<div class="bg-emerald-900 py-16 sm:py-24 relative overflow-hidden">
    <!-- Decorative pattern -->
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10">
        <h1 class="text-4xl font-extrabold text-white sm:text-5xl lg:text-6xl tracking-tight mb-4">Tentang Kami</h1>
        <p class="mt-4 text-xl text-emerald-100 max-w-2xl mx-auto">Mengenal lebih dekat perjalanan Panti Asuhan Vita Dulcedo dalam merajut asa dan masa depan anak bangsa.</p>
    </div>
</div>

<div class="bg-white py-16 sm:py-24">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mx-auto text-slate-600 leading-relaxed space-y-6 text-lg">
            
            <div class="text-center mb-12 border-b border-slate-100 pb-10">
                <img src="{{ asset('assets/logoVitaDulcedo.png') }}" alt="Logo Vita Dulcedo" class="h-24 w-auto mx-auto mb-6 object-contain">
                <h2 class="font-bold text-slate-900 text-2xl">PANTI ASUHAN VITA DULCEDO</h2>
                <p class="text-slate-500 mt-2">Jl. Flamboyan Indah blok KM 10-11 Harapan Indah Bekasi</p>
            </div>

            <p>Panti Asuhan Vita Dulcedo adalah panti asuhan putri yang dikelola oleh suster-suster Kongregasi KYM (Kasih Yesus & Maria Bunda Pertolongan Baik) di bawah naungan Yayasan Vita Dulcedo. Yayasan Vita Dulcedo (Hidup Yang Bersinar) adalah karya sosial yang berdiri pada tanggal 11 Mei 2007 dan berpusat di Pematangsiantar – Sumatera Utara. Yayasan ini memiliki beberapa unit pelayanan, yakni Anak Jalanan, pendampingan kelompok tani, Panti asuhan, dan Panti Jompo. Panti asuhan tersebar di tiga tempat yakni, Pematangsiantar, Surabaya, dan Bekasi – Harapan Indah.</p>

            <p>Panti Asuhan Vita Dulcedo cabang Bekasi didirikan pada tanggal 06 Agustus 2014 bertepatan pada ulang tahun ke-20 Komunitas Susteran KYM Rosalia Bekasi. Vita Dulcedo berada di Jl. Flamboyan blok KM 10-11 Harapan Indah Bekasi. Saat ini Panti Asuhan Vita Dulcedo masih satu atap dengan komunitas Susteran KYM. Rencana kedepan panti asuhan ini akan menempati rumah sendiri yang tidak jauh dari alamat yang sekarang.</p>

            <p>Dalam perjalanan sejak tahun 2014 – 2019, jumlah anak asuh saat ini sudah mencapai 17 orang. Mulai dari TK, SD, dan SMP. Kegiatan anak-anak meliputi: Pendidikan formal dan non formal, pelatihan kemandirian, pengembangan bakat sesuai dengan potensi yang dimiliki (olah vocal, les musik, dsb), olah rohani (latihan doa, meditasi, ibadat rosario, rekoleksi, dan Legio Maria), pembinaan karakter, olah raga, nonton sekali seminggu, hingga rekreasi terpimpin sebulan sekali.</p>

            <p>Selain kegiatan rutin di atas kami juga sudah mulai memberikan bekal kepada anak panti dengan berbagai keterampilan seperti; les olah vocal, les musik (gitar, organ, pianika), kursus menari, kursus menjahit, dan kursus salon.</p>

            <p class="font-medium text-emerald-700 bg-emerald-50 p-6 rounded-xl border-l-4 border-emerald-500">Pertumbuhan dan perkembangan panti asuhan ini sangat bergantung pada kebaikan hati para donatur. Untuk menjaga kelangsungan dinamika kegiatan ini masih sangat membutuhkan uluran tangan para donatur.</p>

            <div class="my-12 p-8 sm:p-10 bg-slate-50 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-400 to-blue-500"></div>
                <h2 class="text-3xl font-bold text-slate-900 mb-6">Kerjasama dengan Misi Domestik KAJ</h2>
                <p>Visi, misi Vita Dulcedo berlandaskan pada pendidikan formal dan iman, membangun perkembangan karakter yang jujur, mandiri, dan berakhlak mulia. Spiritualitas Kongregasi KYM yang menaungi Yayasan Vita Dulcedo ditanamkan dalam metode pembinaan yang dikembangkan, yakni membantu pendidikan anak-anak yang berkekurangan. Oleh karena itu, sangat penting membangun strategi yang kontekstual terhadap anak-anak sehingga capaian yang menjadikan anak-anak menjadi manusia beriman, mandiri, berkarakter, trampil dan beraklak mulia terwujud dengan baik.</p>
                
                <p class="mt-4">Atas dasar pembinaan ini, maka Kongregasi KYM melalui Yayasan Vita Dulcedo membuka jalan untuk bekerjasama dengan Keuskupan Agung Jakarta dalam mendidik anak-anak yang membutuhkan pendidikan sampai ke bagian Timur Indonesia. Hal ini dipandang sangat baik dan sejalan dengan Visi Misi Vita Dulcedo. Oleh Pastor Paroki St. Albertus Harapan Indah di mana Vita Dulcedo cabang Bekasi berdomisili, diwujudkanlah kerjasama yang baik antara Yayasan Vita Dulcedo Cabang Bekasi dengan Keuskupan Agung Jakarta untuk membantu anak-anak yang berasal dari Bomomani, Papua.</p>
                
                <p class="mt-4">Desa Bomomani Papua merupakan salah satu bagian Misi Domestik Keuskupan Agung Jakarta. Mereka memiliki perkembangan yang sangat terbelakang. Perkembangan dan pertambahan anak-anak sangat pesat tetapi kurang mendapatkan pendidikan yang layak. Dengan pendidikan yang sangat terbatas ini, akhirnya romo Paroki St. Albertus Harapan Indah, romo Yustinus Kesaryanto, Pr menerobos perbatasan wilayah untuk mendapatkan pendidikan anak-anak di desa Bomomani Papua dengan membawa keluar daerah.</p>

                <p class="mt-4">Anak-anak inilah yang pada akhirnya ditempatkan di Panti Asuhan Vita Dulcedo Cabang Bekasi, bergabung bersama anak-anak yang sudah hadir sebelumnya. Angkatan pertama yang bergabung bersama Vita Dulcedo dengan jumlah anak 3 (tiga) orang hadir pada tahun ajaran 2015/2016. Mereka memulai pendidikan sejak TK dan bergabung bersama anak-anak di sekolah formal.</p>

                <p class="mt-4">Melihat perkembangan yang cukup baik terhadap pendidikan anak-anak tersebut, akhirnya ditetapkan kerjasama yang resmi antara Keuskupan Agung Jakarta dengan Vita Dulcedo Cabang Bekasi untuk mendidik dan membina anak-anak yang berasal dari Bomomani Papua. Oleh karena itu pada tanggal 30 Januari 2021 diadakan penandatanganan MoU dari KAJ dan Pemimpin Umum Kongregasi, bersama ketua Yayasan Vita Dulcedo. Dengan demikian Vita Dulcedo Cabang Bekasi bersama KAJ yang diatas namakan melalui Paroki St.Albertus Harapan Indah tetap melanjutkan pendampingan dan pendidikan formal terhadap anak-anak dengan mengembangkan bakat-bakat yang ada di dalam diri mereka masing-masing. Dengan harapan, anak-anak Bomomani yang bergabung bersama anak-anak lainnya mendapatkan pengalaman yang lebih luas dan terbuka terhadap perubahan-perubahan yang ada.</p>
            </div>
            
            <div class="text-center mt-16 pb-8 border-b border-slate-100">
                <h3 class="text-2xl font-bold text-slate-900 mb-6">Mari Menjadi Bagian dari Perjalanan Kami</h3>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('donasi') }}" class="inline-flex justify-center items-center px-8 py-4 border border-transparent text-base font-bold rounded-full text-white bg-emerald-600 hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all shadow-lg hover:shadow-emerald-500/30">
                        Donasi Sekarang
                        <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="{{ route('kunjungan') }}" class="inline-flex justify-center items-center px-8 py-4 border-2 border-emerald-600 text-base font-bold rounded-full text-emerald-600 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                        Jadwalkan Kunjungan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
