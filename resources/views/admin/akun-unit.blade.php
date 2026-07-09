<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akun Unit Admin – LurahOnline</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-white text-slate-900 antialiased">

@include('components.navbar-admin')

<main class="relative overflow-hidden pt-[126px] pb-16">

    {{-- ── Ambient background blobs ── --}}
    <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-[271px] -z-10 flex justify-between blur-[350px]">
        <div class="-ml-24 h-[280px] w-[280px] shrink-0 rounded-full bg-[#0047AB]"></div>
        <div class="-mr-24 h-[220px] w-[460px] shrink-0 rounded-full bg-[#00B4D8] opacity-60"></div>
    </div>

    <div class="mx-auto flex w-full max-w-[979px] flex-col items-center gap-[34px] px-6 sm:px-8 lg:px-4">

        @php
            $unitOptions = $unitOptions ?? ['Unit Infrastruktur', 'Unit Kebersihan', 'Unit Keamanan', 'Unit Administrasi', 'Unit Umum'];
            $akunUnit = $akunUnit ?? [
                ['email' => 'unit_infrastruktur@lurah.local', 'unit' => 'Unit Infrastruktur', 'dibuat' => '11/6/2026'],
                ['email' => 'unit_kebersihan@lurah.local', 'unit' => 'Unit Kebersihan', 'dibuat' => '11/6/2026'],
                ['email' => 'unit_keamanan@lurah.local', 'unit' => 'Unit Keamanan', 'dibuat' => '11/6/2026'],
            ];
        @endphp

        {{-- ── Page Header ── --}}
        <div class="flex w-full items-center gap-3.5">
            <div class="flex h-[53px] w-[53px] shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#0047AB] to-[#153655]">
                <svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.375 18.9062C9.1575 18.9062 2.75 20.515 2.75 23.7188V26.125H22V23.7188C22 20.515 15.5925 18.9062 12.375 18.9062ZM5.9675 23.375C7.1225 22.5775 9.91375 21.6562 12.375 21.6562C14.8363 21.6562 17.6275 22.5775 18.7825 23.375H5.9675ZM12.375 16.5C15.0287 16.5 17.1875 14.3412 17.1875 11.6875C17.1875 9.03375 15.0287 6.875 12.375 6.875C9.72125 6.875 7.5625 9.03375 7.5625 11.6875C7.5625 14.3412 9.72125 16.5 12.375 16.5ZM12.375 9.625C13.5162 9.625 14.4375 10.5463 14.4375 11.6875C14.4375 12.8288 13.5162 13.75 12.375 13.75C11.2338 13.75 10.3125 12.8288 10.3125 11.6875C10.3125 10.5463 11.2338 9.625 12.375 9.625ZM22.055 18.9888C23.65 20.1438 24.75 21.6838 24.75 23.7188V26.125H30.25V23.7188C30.25 20.9412 25.4375 19.36 22.055 18.9888ZM20.625 16.5C23.2787 16.5 25.4375 14.3412 25.4375 11.6875C25.4375 9.03375 23.2787 6.875 20.625 6.875C19.8825 6.875 19.195 7.05375 18.5625 7.35625C19.4287 8.58 19.9375 10.0788 19.9375 11.6875C19.9375 13.2962 19.4287 14.795 18.5625 16.0187C19.195 16.3212 19.8825 16.5 20.625 16.5Z" fill="white"/>
                </svg>
            </div>
            <div class="flex flex-col gap-0.5">
                <h1 class="bg-gradient-to-r from-[#0047AB] to-[#153655] bg-clip-text text-[30px] font-extrabold leading-none text-transparent">
                    Akun Unit
                </h1>
                <p class="text-[18px] font-medium text-[#464646]">Kelola akun pengguna untuk tiap unit</p>
            </div>
        </div>

        {{-- ── Tambah Akun Unit ── --}}
        <div class="w-full rounded-[30px] border-[0.5px] border-[#E5E5E5] bg-white p-[43px] shadow-[0_4px_10px_0_rgba(0,71,171,0.20)] sm:p-[43px]">
            <form id="tambah-akun-form" class="flex flex-col items-start gap-[20px]">
                <h2 class="text-[20px] font-extrabold text-[#001D45]">Tambah Akun Unit</h2>

                <div class="flex w-full flex-col gap-[10px]">
                    <label for="akun-email" class="text-[15px] font-medium text-[#464646]">Email</label>
                    <div class="flex h-[50px] w-full items-center gap-4 rounded-[20px] border-[0.5px] border-[#A19E9E] bg-[#F9F9F9] px-[26px]">
                        <svg class="shrink-0" width="20" height="16" viewBox="0 0 30 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M30 3C30 1.35 28.65 0 27 0H3C1.35 0 0 1.35 0 3V21C0 22.65 1.35 24 3 24H27C28.65 24 30 22.65 30 21V3ZM27 3L15 10.5L3 3H27ZM27 21H3V6L15 13.5L27 6V21Z" fill="#656565"/>
                        </svg>
                        <input id="akun-email" name="email" type="email" placeholder="unit@lurah.local" required
                            class="flex-1 bg-transparent border-0 outline-none text-[19px] font-medium text-[#464646] placeholder:text-[#A19E9E]">
                    </div>
                </div>

                <div class="flex w-full flex-col gap-[10px]">
                    <label for="akun-password" class="text-[15px] font-medium text-[#464646]">Password (minimal 8 karakter)</label>
                    <div class="flex h-[50px] w-full items-center gap-4 rounded-[20px] border-[0.5px] border-[#A19E9E] bg-[#F9F9F9] px-[26px]">
                        <svg class="shrink-0" width="20" height="20" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 2.6665C18.0698 2.66643 20.0591 3.46859 21.55 4.90446C23.0408 6.34032 23.917 8.29812 23.9947 10.3665L24 10.6665H25.3333C26.0061 10.6663 26.6541 10.9204 27.1474 11.3778C27.6407 11.8353 27.9429 12.4623 27.9933 13.1332L28 13.3332V26.6665C28.0002 27.3393 27.7461 27.9873 27.2887 28.4806C26.8312 28.9739 26.2042 29.276 25.5333 29.3265L25.3333 29.3332H6.66667C5.9939 29.3334 5.34591 29.0793 4.8526 28.6218C4.35929 28.1644 4.05712 27.5374 4.00667 26.8665L4 26.6665V13.3332C3.99979 12.6604 4.25388 12.0124 4.71133 11.5191C5.16878 11.0258 5.79579 10.7236 6.46667 10.6732L6.66667 10.6665H8C8 8.54477 8.84285 6.50994 10.3431 5.00965C11.8434 3.50936 13.8783 2.6665 16 2.6665ZM25.3333 13.3332H6.66667V26.6665H25.3333V13.3332ZM16 15.9998C16.5688 16 17.1226 16.1821 17.5806 16.5194C18.0386 16.8567 18.3767 17.3316 18.5456 17.8747C18.7144 18.4179 18.7052 19.0008 18.5193 19.5383C18.3333 20.0758 17.9804 20.5398 17.512 20.8625L17.3333 20.9758V22.6665C17.333 23.0063 17.2028 23.3332 16.9695 23.5803C16.7362 23.8274 16.4174 23.9761 16.0781 23.9961C15.7389 24.016 15.4048 23.9056 15.1442 23.6875C14.8836 23.4694 14.7161 23.16 14.676 22.8225L14.6667 22.6665V20.9758C14.1583 20.6823 13.761 20.2293 13.5364 19.6869C13.3118 19.1446 13.2724 18.5433 13.4243 17.9763C13.5762 17.4093 13.911 16.9083 14.3767 16.5509C14.8424 16.1936 15.413 15.9999 16 15.9998ZM16 5.33317C14.5855 5.33317 13.229 5.89507 12.2288 6.89527C11.2286 7.89546 10.6667 9.25202 10.6667 10.6665H21.3333C21.3333 9.25202 20.7714 7.89546 19.7712 6.89527C18.771 5.89507 17.4145 5.33317 16 5.33317Z" fill="#656565"/>
                        </svg>
                        <input id="akun-password" name="password" type="password" placeholder="••••••••••" minlength="8" required
                            class="flex-1 bg-transparent border-0 outline-none text-[15px] font-medium text-[#464646] placeholder:text-[#A19E9E]">
                        <button type="button" id="toggle-akun-password" class="shrink-0">
                            <svg width="24" height="24" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.75 16.5C13.75 17.2293 14.0397 17.9288 14.5555 18.4445C15.0712 18.9603 15.7707 19.25 16.5 19.25C17.2293 19.25 17.9288 18.9603 18.4445 18.4445C18.9603 17.9288 19.25 17.2293 19.25 16.5C19.25 15.7707 18.9603 15.0712 18.4445 14.5555C17.9288 14.0397 17.2293 13.75 16.5 13.75C15.7707 13.75 15.0712 14.0397 14.5555 14.5555C14.0397 15.0712 13.75 15.7707 13.75 16.5Z" stroke="#656565" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M28.875 16.5C25.575 22 21.45 24.75 16.5 24.75C11.55 24.75 7.425 22 4.125 16.5C7.425 11 11.55 8.25 16.5 8.25C21.45 8.25 25.575 11 28.875 16.5Z" stroke="#656565" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex w-full flex-col gap-[9px]">
                    <label for="akun-unit" class="text-[15px] font-medium text-[#464646]">Unit</label>
                    <div class="relative w-full">
                        <select id="akun-unit" name="unit" required
                            class="w-full appearance-none rounded-[20px] border-[0.5px] border-[#A19E9E] bg-[#F9F9F9] px-[31px] py-[13px] text-[15px] font-medium text-[#464646] outline-none">
                            <option value="" disabled selected>Pilih Unit...</option>
                            @foreach ($unitOptions as $unit)
                                <option value="{{ $unit }}">{{ $unit }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-[28px] top-1/2 -translate-y-1/2" width="20" height="20" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 12.5L15 17.5L20 12.5" stroke="#464646" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

                <button type="submit"
                    class="w-full rounded-[20px] bg-gradient-to-r from-[#0047AB] to-[#153655] py-[18px] text-[23px] font-semibold text-white shadow-[2px_2px_4px_0_rgba(0,0,0,0.25)] hover:opacity-90 transition">
                    Buat Akun
                </button>

                <p class="mx-auto max-w-[711px] text-center text-[15px] font-medium text-[#656565]">
                    Akun ini akan memiliki role unit dan hanya bisa memperbarui pengaduan yang ditugaskan ke unitnya.
                </p>
            </form>
        </div>

        {{-- ── Daftar Akun Unit ── --}}
        <div class="w-full rounded-[20px] border-[0.5px] border-[#A19E9E] bg-white shadow-[0_4px_10px_0_rgba(0,0,0,0.20)]">
            <div class="border-b-[0.5px] border-[#A19E9E] bg-[#F9F9F9] px-[21px] py-[18px]">
                <h2 id="daftar-akun-title" class="text-[20px] font-extrabold text-[#153655]">Daftar Akun Unit ({{ count($akunUnit) }})</h2>
            </div>

            <div id="daftar-akun-list" class="flex flex-col divide-y divide-[#E5E5E5] px-[21px]">
                @foreach ($akunUnit as $akun)
                    <div class="akun-row flex flex-wrap items-center justify-between gap-6 py-[18px]">
                        <div class="flex min-w-[216px] flex-col gap-[7px]">
                            <p class="akun-email text-[15px] font-semibold text-black">{{ $akun['email'] }}</p>
                            <div class="flex items-center gap-[5px]">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M2.77936 2.77942V2.78182C2.73647 3.26497 2.7058 3.74913 2.68736 4.23382C2.64096 5.40182 2.64736 6.78822 2.80096 7.81782C2.8142 7.8821 2.84687 7.94075 2.89456 7.98582L9.09776 14.189C9.17277 14.264 9.2745 14.3061 9.38056 14.3061C9.48663 14.3061 9.58835 14.264 9.66336 14.189L14.189 9.66342C14.2639 9.58841 14.3061 9.48669 14.3061 9.38062C14.3061 9.27456 14.2639 9.17283 14.189 9.09782L7.98576 2.89462C7.94068 2.84694 7.88203 2.81426 7.81776 2.80102C6.78816 2.64742 5.40176 2.64102 4.23376 2.68742C3.74906 2.70586 3.26491 2.73653 2.78176 2.77942H2.77936ZM2.07216 2.07222C1.99456 2.14582 1.68096 5.72582 2.01056 7.93622C2.04576 8.17142 2.16256 8.38422 2.33056 8.55222L8.53296 14.7554C8.75799 14.9804 9.06316 15.1068 9.38136 15.1068C9.69956 15.1068 10.0047 14.9804 10.2298 14.7554L14.7554 10.2298C14.9803 10.0048 15.1067 9.69962 15.1067 9.38142C15.1067 9.06323 14.9803 8.75806 14.7554 8.53302L8.55136 2.32822C8.3851 2.15901 8.1694 2.04696 7.93536 2.00822C5.72496 1.67942 2.14496 1.99222 2.07136 2.07062" fill="#0047AB"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.06101 6.12927C6.0228 6.16616 5.99233 6.2103 5.97137 6.2591C5.9504 6.30791 5.93937 6.36039 5.93891 6.41351C5.93844 6.46662 5.94856 6.51929 5.96868 6.56845C5.98879 6.61761 6.01849 6.66227 6.05605 6.69982C6.0936 6.73738 6.13827 6.76708 6.18742 6.78719C6.23658 6.80731 6.28925 6.81743 6.34237 6.81697C6.39548 6.8165 6.44797 6.80547 6.49677 6.78451C6.54557 6.76354 6.58971 6.73307 6.62661 6.69487C6.69947 6.61942 6.73979 6.51838 6.73888 6.41351C6.73796 6.30863 6.6959 6.2083 6.62173 6.13414C6.54757 6.05998 6.44725 6.01791 6.34237 6.017C6.23749 6.01608 6.13645 6.0564 6.06101 6.12927ZM5.49621 7.26047C5.37965 7.15027 5.28637 7.01784 5.22188 6.87098C5.15739 6.72412 5.12299 6.56582 5.12073 6.40544C5.11846 6.24507 5.14836 6.08586 5.20868 5.93724C5.26899 5.78861 5.35849 5.65359 5.47188 5.54015C5.58527 5.42671 5.72025 5.33715 5.86884 5.27676C6.01744 5.21638 6.17663 5.1864 6.33701 5.18859C6.49739 5.19078 6.6557 5.2251 6.80259 5.28953C6.94948 5.35395 7.08196 5.44717 7.19221 5.56367C7.40699 5.79063 7.52476 6.09243 7.52049 6.40488C7.51622 6.71733 7.39024 7.01579 7.16933 7.2368C6.94843 7.45781 6.65002 7.58394 6.33757 7.58836C6.02512 7.59278 5.72327 7.47514 5.49621 7.26047Z" fill="#0047AB"/>
                                </svg>
                                <span class="akun-unit-label text-[15px] font-medium text-[#0047AB]">{{ $akun['unit'] }}</span>
                            </div>
                            <p class="text-[15px] font-semibold text-[#656565]">Dibuat: {{ $akun['dibuat'] }}</p>
                        </div>

                        <div class="flex items-center gap-[10px]">
                            <div class="relative">
                                <select
                                    class="akun-unit-select w-[292px] appearance-none rounded-[10px] border-[0.5px] border-[#A19E9E] bg-[#F9F9F9] px-[22px] py-[8px] text-[14px] font-medium text-[#464646] outline-none">
                                    @foreach ($unitOptions as $unit)
                                        <option value="{{ $unit }}" @selected($unit === $akun['unit'])>{{ $unit }}</option>
                                    @endforeach
                                </select>
                                <svg class="pointer-events-none absolute right-[22px] top-1/2 -translate-y-1/2" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 10L12 14L16 10" stroke="#464646" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>

                            <button type="button" title="Reset password" aria-label="Reset password"
                                class="reset-akun-btn flex h-[30px] w-[30px] shrink-0 items-center justify-center rounded-[6px] border-[0.5px] border-[#A19E9E] bg-[#F9F9F9]">
                                <svg width="18" height="18" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.2599 17.9405C19.8774 17.9405 22 15.827 22 13.2199C22 10.6128 19.8774 8.5 17.2599 8.5C14.6423 8.5 12.522 10.6136 12.522 13.2199C12.522 14.4274 13.0725 15.3057 13.0725 15.3057L7.34084 21.0133C7.08359 21.2699 6.72358 21.9359 7.34084 22.5509L8.00236 23.2094C8.25962 23.4292 8.90614 23.7367 9.4349 23.2094L10.2074 22.4414C10.9784 23.2094 11.8605 22.7706 12.1912 22.3311C12.7417 21.5631 12.081 20.7943 12.081 20.7943L12.3015 20.5746C13.359 21.6291 14.2853 21.0141 14.616 20.5746C15.1673 19.8066 14.616 19.0378 14.616 19.0378C14.3955 18.599 13.9545 18.599 14.5058 18.05L15.1673 17.3915C15.6961 17.8303 16.7836 17.9405 17.2614 17.9405H17.2599Z" stroke="#464646" stroke-linejoin="round"/>
                                    <path d="M18.914 13.2208C18.913 13.6583 18.7383 14.0775 18.4283 14.3862C18.1183 14.6949 17.6984 14.8679 17.261 14.8671C16.8235 14.8679 16.4036 14.6949 16.0936 14.3862C15.7836 14.0775 15.6089 13.6583 15.6079 13.2208C15.6083 13.0041 15.6514 12.7896 15.7347 12.5896C15.8179 12.3895 15.9398 12.2078 16.0933 12.0549C16.2468 11.902 16.4289 11.7807 16.6293 11.6982C16.8296 11.6156 17.0443 11.5733 17.261 11.5737C17.4776 11.5733 17.6923 11.6156 17.8926 11.6982C18.093 11.7807 18.2751 11.902 18.4286 12.0549C18.5821 12.2078 18.704 12.3895 18.7873 12.5896C18.8705 12.7896 18.9136 13.0041 18.914 13.2208Z" stroke="#464646"/>
                                </svg>
                            </button>

                            <button type="button" title="Hapus akun" aria-label="Hapus akun"
                                class="hapus-akun-btn flex h-[30px] w-[30px] shrink-0 items-center justify-center rounded-[6px] bg-[#D83D3D]">
                                <svg width="18" height="18" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.0469 24.2812C9.48828 24.2812 9.01026 24.0825 8.61282 23.6851C8.21537 23.2876 8.01631 22.8093 8.01563 22.25V9.04687C7.72787 9.04687 7.48683 8.94937 7.2925 8.75437C7.09818 8.55937 7.00068 8.31833 7 8.03125C6.99933 7.74417 7.09683 7.50312 7.2925 7.30812C7.48818 7.11312 7.72922 7.01562 8.01563 7.01562H12.0781C12.0781 6.72786 12.1756 6.48682 12.3706 6.2925C12.5656 6.09818 12.8067 6.00068 13.0938 6H17.1562C17.444 6 17.6854 6.0975 17.8804 6.2925C18.0754 6.4875 18.1726 6.72854 18.1719 7.01562H22.2344C22.5221 7.01562 22.7635 7.11312 22.9585 7.30812C23.1535 7.50312 23.2507 7.74417 23.25 8.03125C23.2493 8.31833 23.1518 8.55971 22.9575 8.75539C22.7632 8.95107 22.5221 9.04823 22.2344 9.04687V22.25C22.2344 22.8086 22.0356 23.2869 21.6382 23.6851C21.2408 24.0832 20.7624 24.2819 20.2031 24.2812H10.0469ZM20.2031 9.04687H10.0469V22.25H20.2031V9.04687ZM13.8179 19.9273C14.0122 19.7323 14.1094 19.4909 14.1094 19.2031V12.0937C14.1094 11.806 14.0119 11.5649 13.8169 11.3706C13.6219 11.1763 13.3808 11.0788 13.0938 11.0781C12.8067 11.0774 12.5656 11.1749 12.3706 11.3706C12.1756 11.5663 12.0781 11.8073 12.0781 12.0937V19.2031C12.0781 19.4909 12.1756 19.7323 12.3706 19.9273C12.5656 20.1223 12.8067 20.2194 13.0938 20.2187C13.3808 20.2181 13.6222 20.1216 13.8179 19.9273ZM17.8804 19.9262C18.0747 19.7326 18.1719 19.4916 18.1719 19.2031V12.0937C18.1719 11.806 18.0744 11.5649 17.8794 11.3706C17.6844 11.1763 17.4433 11.0788 17.1562 11.0781C16.8692 11.0774 16.6281 11.1749 16.4331 11.3706C16.2381 11.5663 16.1406 11.8073 16.1406 12.0937V19.2031C16.1406 19.4909 16.2381 19.7323 16.4331 19.9273C16.6281 20.1223 16.8692 20.2194 17.1562 20.2187C17.4433 20.2181 17.6847 20.1206 17.8804 19.9262Z" fill="white"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</main>

<script>
(function () {
    var list = document.getElementById('daftar-akun-list');
    var title = document.getElementById('daftar-akun-title');
    var form = document.getElementById('tambah-akun-form');
    var unitOptions = @json($unitOptions);

    function updateCount() {
        title.textContent = 'Daftar Akun Unit (' + list.querySelectorAll('.akun-row').length + ')';
    }

    function optionsHtml(selected) {
        return unitOptions.map(function (unit) {
            return '<option value="' + unit + '"' + (unit === selected ? ' selected' : '') + '>' + unit + '</option>';
        }).join('');
    }

    function todayFormatted() {
        var now = new Date();
        return now.getDate() + '/' + (now.getMonth() + 1) + '/' + now.getFullYear();
    }

    function createRow(email, unit) {
        var row = document.createElement('div');
        row.className = 'akun-row flex flex-wrap items-center justify-between gap-6 py-[18px]';
        row.innerHTML =
            '<div class="flex min-w-[216px] flex-col gap-[7px]">' +
                '<p class="akun-email text-[15px] font-semibold text-black"></p>' +
                '<div class="flex items-center gap-[5px]">' +
                    '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M2.77936 2.77942V2.78182C2.73647 3.26497 2.7058 3.74913 2.68736 4.23382C2.64096 5.40182 2.64736 6.78822 2.80096 7.81782C2.8142 7.8821 2.84687 7.94075 2.89456 7.98582L9.09776 14.189C9.17277 14.264 9.2745 14.3061 9.38056 14.3061C9.48663 14.3061 9.58835 14.264 9.66336 14.189L14.189 9.66342C14.2639 9.58841 14.3061 9.48669 14.3061 9.38062C14.3061 9.27456 14.2639 9.17283 14.189 9.09782L7.98576 2.89462C7.94068 2.84694 7.88203 2.81426 7.81776 2.80102C6.78816 2.64742 5.40176 2.64102 4.23376 2.68742C3.74906 2.70586 3.26491 2.73653 2.78176 2.77942H2.77936ZM2.07216 2.07222C1.99456 2.14582 1.68096 5.72582 2.01056 7.93622C2.04576 8.17142 2.16256 8.38422 2.33056 8.55222L8.53296 14.7554C8.75799 14.9804 9.06316 15.1068 9.38136 15.1068C9.69956 15.1068 10.0047 14.9804 10.2298 14.7554L14.7554 10.2298C14.9803 10.0048 15.1067 9.69962 15.1067 9.38142C15.1067 9.06323 14.9803 8.75806 14.7554 8.53302L8.55136 2.32822C8.3851 2.15901 8.1694 2.04696 7.93536 2.00822C5.72496 1.67942 2.14496 1.99222 2.07136 2.07062" fill="#0047AB"/><path fill-rule="evenodd" clip-rule="evenodd" d="M6.06101 6.12927C6.0228 6.16616 5.99233 6.2103 5.97137 6.2591C5.9504 6.30791 5.93937 6.36039 5.93891 6.41351C5.93844 6.46662 5.94856 6.51929 5.96868 6.56845C5.98879 6.61761 6.01849 6.66227 6.05605 6.69982C6.0936 6.73738 6.13827 6.76708 6.18742 6.78719C6.23658 6.80731 6.28925 6.81743 6.34237 6.81697C6.39548 6.8165 6.44797 6.80547 6.49677 6.78451C6.54557 6.76354 6.58971 6.73307 6.62661 6.69487C6.69947 6.61942 6.73979 6.51838 6.73888 6.41351C6.73796 6.30863 6.6959 6.2083 6.62173 6.13414C6.54757 6.05998 6.44725 6.01791 6.34237 6.017C6.23749 6.01608 6.13645 6.0564 6.06101 6.12927ZM5.49621 7.26047C5.37965 7.15027 5.28637 7.01784 5.22188 6.87098C5.15739 6.72412 5.12299 6.56582 5.12073 6.40544C5.11846 6.24507 5.14836 6.08586 5.20868 5.93724C5.26899 5.78861 5.35849 5.65359 5.47188 5.54015C5.58527 5.42671 5.72025 5.33715 5.86884 5.27676C6.01744 5.21638 6.17663 5.1864 6.33701 5.18859C6.49739 5.19078 6.6557 5.2251 6.80259 5.28953C6.94948 5.35395 7.08196 5.44717 7.19221 5.56367C7.40699 5.79063 7.52476 6.09243 7.52049 6.40488C7.51622 6.71733 7.39024 7.01579 7.16933 7.2368C6.94843 7.45781 6.65002 7.58394 6.33757 7.58836C6.02512 7.59278 5.72327 7.47514 5.49621 7.26047Z" fill="#0047AB"/></svg>' +
                    '<span class="akun-unit-label text-[15px] font-medium text-[#0047AB]"></span>' +
                '</div>' +
                '<p class="text-[15px] font-semibold text-[#656565]">Dibuat: ' + todayFormatted() + '</p>' +
            '</div>' +
            '<div class="flex items-center gap-[10px]">' +
                '<div class="relative">' +
                    '<select class="akun-unit-select w-[292px] appearance-none rounded-[10px] border-[0.5px] border-[#A19E9E] bg-[#F9F9F9] px-[22px] py-[8px] text-[14px] font-medium text-[#464646] outline-none">' + optionsHtml(unit) + '</select>' +
                    '<svg class="pointer-events-none absolute right-[22px] top-1/2 -translate-y-1/2" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 10L12 14L16 10" stroke="#464646" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>' +
                '</div>' +
                '<button type="button" title="Reset password" aria-label="Reset password" class="reset-akun-btn flex h-[30px] w-[30px] shrink-0 items-center justify-center rounded-[6px] border-[0.5px] border-[#A19E9E] bg-[#F9F9F9]">' +
                    '<svg width="18" height="18" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17.2599 17.9405C19.8774 17.9405 22 15.827 22 13.2199C22 10.6128 19.8774 8.5 17.2599 8.5C14.6423 8.5 12.522 10.6136 12.522 13.2199C12.522 14.4274 13.0725 15.3057 13.0725 15.3057L7.34084 21.0133C7.08359 21.2699 6.72358 21.9359 7.34084 22.5509L8.00236 23.2094C8.25962 23.4292 8.90614 23.7367 9.4349 23.2094L10.2074 22.4414C10.9784 23.2094 11.8605 22.7706 12.1912 22.3311C12.7417 21.5631 12.081 20.7943 12.081 20.7943L12.3015 20.5746C13.359 21.6291 14.2853 21.0141 14.616 20.5746C15.1673 19.8066 14.616 19.0378 14.616 19.0378C14.3955 18.599 13.9545 18.599 14.5058 18.05L15.1673 17.3915C15.6961 17.8303 16.7836 17.9405 17.2614 17.9405H17.2599Z" stroke="#464646" stroke-linejoin="round"/><path d="M18.914 13.2208C18.913 13.6583 18.7383 14.0775 18.4283 14.3862C18.1183 14.6949 17.6984 14.8679 17.261 14.8671C16.8235 14.8679 16.4036 14.6949 16.0936 14.3862C15.7836 14.0775 15.6089 13.6583 15.6079 13.2208C15.6083 13.0041 15.6514 12.7896 15.7347 12.5896C15.8179 12.3895 15.9398 12.2078 16.0933 12.0549C16.2468 11.902 16.4289 11.7807 16.6293 11.6982C16.8296 11.6156 17.0443 11.5733 17.261 11.5737C17.4776 11.5733 17.6923 11.6156 17.8926 11.6982C18.093 11.7807 18.2751 11.902 18.4286 12.0549C18.5821 12.2078 18.704 12.3895 18.7873 12.5896C18.8705 12.7896 18.9136 13.0041 18.914 13.2208Z" stroke="#464646"/></svg>' +
                '</button>' +
                '<button type="button" title="Hapus akun" aria-label="Hapus akun" class="hapus-akun-btn flex h-[30px] w-[30px] shrink-0 items-center justify-center rounded-[6px] bg-[#D83D3D]">' +
                    '<svg width="18" height="18" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.0469 24.2812C9.48828 24.2812 9.01026 24.0825 8.61282 23.6851C8.21537 23.2876 8.01631 22.8093 8.01563 22.25V9.04687C7.72787 9.04687 7.48683 8.94937 7.2925 8.75437C7.09818 8.55937 7.00068 8.31833 7 8.03125C6.99933 7.74417 7.09683 7.50312 7.2925 7.30812C7.48818 7.11312 7.72922 7.01562 8.01563 7.01562H12.0781C12.0781 6.72786 12.1756 6.48682 12.3706 6.2925C12.5656 6.09818 12.8067 6.00068 13.0938 6H17.1562C17.444 6 17.6854 6.0975 17.8804 6.2925C18.0754 6.4875 18.1726 6.72854 18.1719 7.01562H22.2344C22.5221 7.01562 22.7635 7.11312 22.9585 7.30812C23.1535 7.50312 23.2507 7.74417 23.25 8.03125C23.2493 8.31833 23.1518 8.55971 22.9575 8.75539C22.7632 8.95107 22.5221 9.04823 22.2344 9.04687V22.25C22.2344 22.8086 22.0356 23.2869 21.6382 23.6851C21.2408 24.0832 20.7624 24.2819 20.2031 24.2812H10.0469ZM20.2031 9.04687H10.0469V22.25H20.2031V9.04687ZM13.8179 19.9273C14.0122 19.7323 14.1094 19.4909 14.1094 19.2031V12.0937C14.1094 11.806 14.0119 11.5649 13.8169 11.3706C13.6219 11.1763 13.3808 11.0788 13.0938 11.0781C12.8067 11.0774 12.5656 11.1749 12.3706 11.3706C12.1756 11.5663 12.0781 11.8073 12.0781 12.0937V19.2031C12.0781 19.4909 12.1756 19.7323 12.3706 19.9273C12.5656 20.1223 12.8067 20.2194 13.0938 20.2187C13.3808 20.2181 13.6222 20.1216 13.8179 19.9273ZM17.8804 19.9262C18.0747 19.7326 18.1719 19.4916 18.1719 19.2031V12.0937C18.1719 11.806 18.0744 11.5649 17.8794 11.3706C17.6844 11.1763 17.4433 11.0788 17.1562 11.0781C16.8692 11.0774 16.6281 11.1749 16.4331 11.3706C16.2381 11.5663 16.1406 11.8073 16.1406 12.0937V19.2031C16.1406 19.4909 16.2381 19.7323 16.4331 19.9273C16.6281 20.1223 16.8692 20.2194 17.1562 20.2187C17.4433 20.2181 17.6847 20.1206 17.8804 19.9262Z" fill="white"/></svg>' +
                '</button>' +
            '</div>';
        row.querySelector('.akun-email').textContent = email;
        row.querySelector('.akun-unit-label').textContent = unit;
        return row;
    }

    document.getElementById('toggle-akun-password').addEventListener('click', function () {
        var input = document.getElementById('akun-password');
        input.type = input.type === 'password' ? 'text' : 'password';
    });

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        var email = document.getElementById('akun-email').value.trim();
        var password = document.getElementById('akun-password').value;
        var unit = document.getElementById('akun-unit').value;

        if (!email || password.length < 8 || !unit) return;

        list.appendChild(createRow(email, unit));
        updateCount();
        form.reset();
    });

    list.addEventListener('click', function (event) {
        var hapusBtn = event.target.closest('.hapus-akun-btn');
        if (hapusBtn) {
            hapusBtn.closest('.akun-row').remove();
            updateCount();
            return;
        }

        var resetBtn = event.target.closest('.reset-akun-btn');
        if (resetBtn) {
            var email = resetBtn.closest('.akun-row').querySelector('.akun-email').textContent;
            alert('Password untuk ' + email + ' telah direset.');
        }
    });

    list.addEventListener('change', function (event) {
        var select = event.target.closest('.akun-unit-select');
        if (!select) return;
        select.closest('.akun-row').querySelector('.akun-unit-label').textContent = select.value;
    });
})();
</script>

@include('components.footer')

</body>
</html>
