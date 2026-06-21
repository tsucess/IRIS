<x-app-layout>
    <link rel="stylesheet" href="main-id.css">
    <main class="bg-[#181818] gap-1 row flex flex-row items-center justify-center font-segoe leading-normal"
        style="height: 92vh">
        <!-- ID CARD -->
        {{-- <div --}}
        {{-- class="flex flex-col gap-[10px] z-20 relative w-[500px] h-[300px] p-[15px] rounded-[10px] overflow-hidden bg-gradient-to-b from-[#7f6065] to-[#638e8d] group"> --}}
        <div
            class="col-12 col-md-6 flex flex-col gap-[10px] z-20 relative w-[500px] h-[300px] p-[15px] rounded-[10px] overflow-hidden bg-gradient-to-b from-[#ffffff] to-[#0077b6] group">
            <!-- TOP PART -->
            <div class="relative flex justify-evenly gap-[10px]">
                <div>
                    <img class="w-[50px] " src="{{ asset('../images/logo.png') }}" />
                </div>
                <div>
                    <p class="text-[19px] font-medium">
                        Ilisan Remo
                    </p>
                    <p class="text-[14px] mt-[-5px]">
                        Identity card
                    </p>
                </div>
                <div>
                    <p class="text-[19px] font-medium">
                        AGAKANOWO
                    </p>
                </div>
                <div class="flex">
                    <img class="w-[60px] h-[60px]" src={{ url('images/id/ogunstatemap.png') }} />
                    <p class="text-[19px] font-bold text-[#e3c5a0] ml-[-10px] mt-[10px]">
                        OGUN
                    </p>
                </div>
                <p class="absolute top-[52px] left-[233px] text-dark opacity-20 text-[19px] font-medium">
                    iris
                </p>
            </div>
            <!-- BOTTOM PART -->
            <div class="relative flex flex-1">
                <!-- ID IMAGE -->
                <div class="relative z-20 flex items-end overflow-hidden mt-2 rounded-[10px]"
                    style="width 4rem !important; height:12rem; background: #e5e7eb; overflow:hidden">
                    <img class="" src="{{ $user->photo ? asset('uploads/' . $user->photo) : asset('images/avatar.png') }}" style="width 100%; height:100%" />
                </div>
                <!-- ID DATA -->
                <div class="relative z-20 flex flex-col flex-1 ml-[8px]">

                    <div>
                        <span class="block text-[9px]">
                            Full Name
                        </span>
                        <p class="font-medium font-rubik">
                            {{ $user->firstname }} {{ $user->lastname }}
                        </p>
                    </div>
                    <div>
                        <span class="block text-[9px]">
                            Sex
                        </span>
                        <p class="font-medium font-rubik text-[14px]">
                            {{ $resident ? ucwords($resident->gender ?? 'N/A') : 'N/A' }}
                        </p>
                    </div>
                    <div class="flex justify-between gap-[15px] w-full mt-[12px]">
                        <div>
                            <span class="block text-[9px]">
                                State
                            </span>
                            <p class="font-medium text-[14px]">
                                {{ $resident ? ucwords($resident->state ?? 'N/A') : 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <span class="block text-[9px]">
                                Nationality
                            </span>
                            <p class="font-medium text-[14px]">
                                {{ $resident ? ucwords($resident->country ?? 'N/A') : 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <span class="block text-[9px]">
                                Date of birth
                            </span>
                            <p class="font-medium text-[14px]">
                                {{ $resident ? ($resident->date_of_birth ?? 'N/A') : 'N/A' }}
                            </p>
                            <span class="invisible block text-[9px]">
                                {{ $user->id_number }}
                            </span>
                            <span class="block text-[9px]">
                                Signature
                            </span>
                            <p class="text-[12px] font-medium font-cedarville mt-[5px]">
                                {{ $user->firstname }} {{ $user->lastname }}
                            </p>
                        </div>
                        <div>
                            <span class="block text-[9px]">
                                ID serial number
                            </span>
                            <p class="font-medium text-[11px]">
                                {{ $user->id_number }}
                            </p>
                            <span class="invisible block text-[9px]">
                                {{ $user->id_number }}
                            </span>
                            <span class="block text-[9px]">
                                INDIGENE
                            </span>
                            <p class="font-medium text-[14px]">
                                {{ $resident && $resident->indigene ? 'YES' : 'NO' }}
                            </p>
                        </div>
                    </div>
                </div>
                <!-- ABSOLUTE ELEMENT - CIRCLE WITH IMAGE -->
                <div
                    class="absolute z-10 w-[120px] h-[120px] rounded-full bg-white opacity-10 left-[77px] top-[-4px] overflow-hidden">
                    <img class="h-[50px] w-[40px] mt-[20px] ml-[52px]" src={{ url('images/id/cg.png') }} />
                    <p class="text-[#d4af3a] text-[15px] font-[900] mt-[35px] ml-[35px]">
                        ILISAN
                    </p>
                    <p class="text-[#d4af3a] text-[15px] font-[900] mt-[-39px] ml-[50px]">
                        REMO
                    </p>
                    <p class="text-[#d4af3a] text-[15px] font-[900] mt-[-39px] ml-[65px]">
                        ILISAN
                    </p>
                    <p class="text-[#d4af3a] text-[15px] font-[900] mt-[-39px] ml-[80px]">
                        REMO
                    </p>
                </div>
                <!-- COUNTRY CODE ABSOLUTE -->
                <svg width="400" height="100" class="absolute top-[7px] left-[212px] font-rubik">
                    <text fill="white" fill-opacity="0.1" font-size="80" x="200" y="70" text-anchor="middle"
                        stroke="#e3c5a0" stroke-opacity="0.1">
                        NG
                    </text>
                </svg>
                <!-- ABSOLUTE ELEMENT - PROFILE IMAGE -->
                <div class="absolute w-[80px] h-[55px] rounded-[100px] bg-white top-[143px] left-[182px] opacity-20 ">
                    <img src="{{ $user->photo ? asset('uploads/' . $user->photo) : asset('images/avatar.png') }}"
                        class="w-[47px] h-[52px] blur-[1.9px] ml-[18px] mt-[3px]" />
                </div>
            </div>
            <!-- ABSOLUTE ELEMENTS - RIGHT LINES -->
            <div
                class="absolute z-10 opacity-50 w-[220px] h-[300px] left-[290px]
                top-[100px] bg-transparent border-[0.2px] border-[#aa485c]
                border-b-0 border-r-0 rounded-tl-[120%]">
            </div>
            <div
                class="absolute z-10 opacity-30 w-[220px] h-[300px] left-[290px]
                top-[133px] bg-transparent border-[0.2px] border-[#aa485c]
                border-b-0 border-r-0 rounded-tl-[120%]">
            </div>
            <div
                class="absolute z-10 opacity-25 w-[220px] h-[300px] left-[290px]
                top-[164px] bg-transparent border-[0.2px] border-[#aa485c]
                border-b-0 border-r-0 rounded-tl-[120%]">
            </div>
            <div
                class="absolute z-10 opacity-20 w-[220px] h-[300px] left-[290px]
                top-[195px] bg-transparent border-[0.2px] border-[#aa485c]
                border-b-0 border-r-0 rounded-tl-[120%]">
            </div>
            <!-- ABSOLUTE ELEMENTS - LEFT LINES -->
            <div
                class="absolute z-10 opacity-50 w-[350px] h-[450px] left-[-126px]
                top-[-47px] bg-transparent border-[0.2px] border-[#aa485c] rounded-[150%]
                transform rotate-[65deg]">
            </div>
            <div
                class="absolute z-10 opacity-30 w-[350px] h-[450px] left-[-147px]
                top-[-39px] bg-transparent border-[0.2px] border-[#aa485c] rounded-[150%]
                transform rotate-[65deg]">
            </div>
            <div
                class="absolute z-10 opacity-25 w-[350px] h-[450px] left-[-168px]
                top-[-31px] bg-transparent border-[0.2px] border-[#aa485c] rounded-[150%]
                transform rotate-[65deg]">
            </div>
            <div
                class="absolute z-10 opacity-20 w-[350px] h-[450px] left-[-189px]
                top-[-24px] bg-transparent border-[0.2px] border-[#aa485c] rounded-[150%]
                transform rotate-[65deg]">
            </div>
            <!-- WRAPPER HOVER EFFECT -->
            <div
                class="absolute top-0 left-0 w-0 h-full bg-white/20 bg-opacity-40
                transition-all group-hover:w-[120%] group-hover:bg-opacity-0
                group-hover:transition-all duration-[650ms] group-hover:duration-[650ms]
                ease-in-out">
            </div>
        </div>


        {{-- BACK VIEW  --}}
        <div 
            class="col-12 col-md-6 relative flex flex-col gap-[10px] z-20 relative w-[500px] h-[300px] p-[15px] rounded-[10px] overflow-hidden bg-gradient-to-b from-[#ffffff] to-[#0077b6] group">
            <!-- TOP PART -->
            <div class="relative flex justify-evenly gap-[15px]">
                <div>
                    <span class="invisible block text-[9px]">
                        {{ $user->id_number }}
                    </span>
                    <p class="" style="font-size: 0.8rem">
                        This card may be used by the authorized signatory only, subject to conditions notified to the
                        card holder form time to time,
                        if found, please contact IDA, ilisan Development Association, Ilisan Ikenne Local Government
                        Ogun State.
                    </p>
                </div>

            </div>
            <!-- BOTTOM PART -->
            <div class="relative flex flex-1">

                <!-- ID DATA -->
                <div class="relative z-20 flex flex-col flex-1 ml-[8px]">
                    <div style="font-size: 0.8rem">
                        <span class="invisible block text-[9px]">
                            {{ $user->id_number }}
                        </span>
                        <p class="font-medium text-end mt-3">
                            Contact: +2348123456789
                        </p>
                        <p class="font-medium text-end">
                            Email: info@iris.com
                        </p>

                    </div>
                </div>
            </div>
            <!-- ABSOLUTE ELEMENT - CIRCLE WITH IMAGE -->
            <div
                class="absolute z-10 w-[120px] h-[120px] rounded-full bg-white opacity-10 left-[77px] top-[-4px] overflow-hidden">
                <p class="text-[#d4af3a] text-[15px] font-[900] mt-[35px] ml-[35px]">
                    ILISAN
                </p>

            </div>
            <!-- COUNTRY CODE ABSOLUTE -->
            <svg width="400" height="100" class="absolute top-[7px] left-[212px] font-rubik">
                <text fill="white" fill-opacity="0.1" font-size="80" x="200" y="70" text-anchor="middle"
                    stroke="#e3c5a0" stroke-opacity="0.1">
                    NG
                </text>
            </svg>
            <!-- ABSOLUTE ELEMENT - PROFILE IMAGE -->
            <div class="absolute w-[80px] h-[55px] rounded-[100px] bg-white top-[143px] left-[182px] opacity-20 ">
                <img src="{{ $user->photo ? asset('uploads/' . $user->photo) : asset('images/avatar.png') }}"
                    class="w-[47px] h-[52px] blur-[1.9px] ml-[18px] mt-[3px]" />
            </div>
            <!-- Barcode ELEMENT - PROFILE IMAGE -->
            <div class="absolute  top-[173px] left-[0px] ">
                <div class="w-[47px] h-[52px] ml-[18px] mt-[3px]">
                    {{ $qrCode }}
                </div>
            </div>
        </div>


        <!-- WRAPPER HOVER EFFECT -->
        <div
            class="absolute top-0 left-0 w-0 h-full bg-white/20 bg-opacity-40
                transition-all group-hover:w-[120%] group-hover:bg-opacity-0
                group-hover:transition-all duration-[650ms] group-hover:duration-[650ms]
                ease-in-out">
        </div>
        </div>
    </main>

    @push('scripts')
        <script>
            function printIDCard() {
                var content = document.getElementById('idCard').outerHTML;
                var win = window.open('', '', 'width=400,height=600');
                win.document.write(`
                    <html>
                        <head>
                            <title>ID Card</title>
                            <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
                            <style>
                                body { display:flex; justify-content:center; align-items:center; height:100vh; background:#f8f9fa; }
                                ${document.querySelector('style').innerHTML}
                            </style>
                        </head>
                        <body>${content}</body>
                    </html>
                `);
                win.document.close();
                win.print();
            }
        </script>
    @endpush
</x-app-layout>
