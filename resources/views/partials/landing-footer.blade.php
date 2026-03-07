<footer class="bg-slate-900 text-white pt-16 pb-8 relative overflow-hidden">

    {{-- top glow line --}}
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-sky-500 to-transparent"></div>

    {{-- subtle background glow --}}
    <div class="absolute -top-32 -right-32 w-96 h-96 bg-sky-500/10 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative">

        {{-- main footer --}}
        <div class="flex flex-col md:flex-row items-start justify-between gap-12 pb-12">

            {{-- Brand --}}
            <div class="max-w-xs">

                <img src="{{ asset('images/exdrivewhite-logo.png') }}"
                    alt="Extech Studio"
                    class="h-12 w-auto transition-all duration-300 hover:scale-105">

                <p class="mt-4 text-sm text-slate-400 leading-relaxed font-medium">
                    专为现代出行企业打造的智能化叫车与调度管理系统。我们致力于通过技术手段，
                    让每一次出行派单都更高效、更精准。
                </p>

            </div>

            {{-- Links --}}
            <div class="grid grid-cols-2 gap-x-16 gap-y-8">

                {{-- Quick Links --}}
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                        快速链接
                    </h4>

                    <ul class="mt-4 space-y-3 text-sm text-slate-300 font-bold">

                        <li>
                            <a href="/#intro"
                                class="hover:text-white transition-colors relative group">
                                系统介绍
                                <span class="absolute left-0 -bottom-1 w-0 h-[2px] bg-sky-500 transition-all group-hover:w-full"></span>
                            </a>
                        </li>

                        <li>
                            <a href="/#scenes"
                                class="hover:text-white transition-colors relative group">
                                适用场景
                                <span class="absolute left-0 -bottom-1 w-0 h-[2px] bg-sky-500 transition-all group-hover:w-full"></span>
                            </a>
                        </li>

                        <li>
                            <a href="/#features"
                                class="hover:text-white transition-colors relative group">
                                功能特色
                                <span class="absolute left-0 -bottom-1 w-0 h-[2px] bg-sky-500 transition-all group-hover:w-full"></span>
                            </a>
                        </li>

                        <li>
                            <a href="/#roles"
                                class="hover:text-white transition-colors relative group">
                                角色说明
                                <span class="absolute left-0 -bottom-1 w-0 h-[2px] bg-sky-500 transition-all group-hover:w-full"></span>
                            </a>
                        </li>

                    </ul>
                </div>

                {{-- Legal --}}
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                        法律合规
                    </h4>

                    <ul class="mt-4 space-y-3 text-sm text-slate-300 font-bold">

                        <li>
                            <a href="{{ route('terms') }}"
                                class="hover:text-white transition-colors relative group">
                                Terms & Conditions
                                <span class="absolute left-0 -bottom-1 w-0 h-[2px] bg-sky-500 transition-all group-hover:w-full"></span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('privacy') }}"
                                class="hover:text-white transition-colors relative group">
                                Privacy Policy
                                <span class="absolute left-0 -bottom-1 w-0 h-[2px] bg-sky-500 transition-all group-hover:w-full"></span>
                            </a>
                        </li>

                    </ul>
                </div>

            </div>

        </div>

        {{-- bottom --}}
        <div class="pt-8 border-t border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4">

            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                © {{ date('Y') }} Extech Studio. All rights reserved.
            </div>

            <div class="flex items-center space-x-4">

                <div class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></div>

                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                    System Status: Online
                </span>

            </div>

        </div>

    </div>

</footer>