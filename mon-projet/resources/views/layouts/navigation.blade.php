<nav x-data="{ open: false, profileOpen: false }" style="background:linear-gradient(135deg,#1e3a5f 0%,#2563eb 100%); box-shadow:0 2px 12px rgba(15,23,42,0.18); position:sticky; top:0; z-index:50;">
    <div style="margin:0 auto; padding:0 1rem;">
        <div style="display:flex; align-items:center; justify-content:space-between; height:64px;">

            {{-- ═══ LEFT: Logo + Nav Links ═══ --}}
            <div style="display:flex; align-items:center; gap:2rem;">
                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" style="display:flex; align-items:center; gap:0.6rem; text-decoration:none;">
                    <img src="{{ asset('images/ias-dessin-logo-couleur.png') }}" alt="IAS" style="height:40px; width:40px; border-radius:50%; object-fit:cover; border:2px solid rgba(255,255,255,0.3);">
                    <span style="font-weight:800; font-size:1.1rem; color:#fff; letter-spacing:-0.3px; display:none;" class="sm-show-name">IAS</span>
                </a>

                @php
                    $roleProfil = optional(auth()->user()->profil)->role;
                @endphp

                {{-- Desktop Nav Links --}}
                <div style="display:none; align-items:center; gap:0.25rem;" class="sm-show-nav">

                    @if(auth()->user()->is_admin)
                        <a href="{{ route('beneficiaire.index') }}" style="padding:0.45rem 0.85rem; border-radius:8px; font-size:0.88rem; font-weight:600; text-decoration:none; {{ request()->routeIs('beneficiaire.*') ? 'background:rgba(255,255,255,0.2); color:#fff;' : 'color:rgba(255,255,255,0.75);' }}" onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.color='#fff'" onmouseout="this.style.background='{{ request()->routeIs('beneficiaire.*') ? 'rgba(255,255,255,0.2)' : 'transparent' }}';this.style.color='{{ request()->routeIs('beneficiaire.*') ? '#fff' : 'rgba(255,255,255,0.75)' }}';">
                            Bénéficiaires
                        </a>
                        <a href="{{ route('admin.missions.index') }}" style="padding:0.45rem 0.85rem; border-radius:8px; font-size:0.88rem; font-weight:600; text-decoration:none; {{ request()->routeIs('admin.missions.*') ? 'background:rgba(255,255,255,0.2); color:#fff;' : 'color:rgba(255,255,255,0.75);' }}" onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.color='#fff'" onmouseout="this.style.background='{{ request()->routeIs('admin.missions.*') ? 'rgba(255,255,255,0.2)' : 'transparent' }}';this.style.color='{{ request()->routeIs('admin.missions.*') ? '#fff' : 'rgba(255,255,255,0.75)' }}';">
                            Missions
                        </a>
                    <a href="{{ route('admin.categories.index') }}" style="padding:0.45rem 0.85rem; border-radius:8px; font-size:0.88rem; font-weight:600; text-decoration:none; {{ request()->routeIs('admin.categories.*') ? 'background:rgba(255,255,255,0.2); color:#fff;' : 'color:rgba(255,255,255,0.75);' }}" onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.color='#fff'" onmouseout="this.style.background='{{ request()->routeIs('admin.categories.*') ? 'rgba(255,255,255,0.2)' : 'transparent' }}';this.style.color='{{ request()->routeIs('admin.categories.*') ? '#fff' : 'rgba(255,255,255,0.75)' }}';">
                            Catégories
                        </a>
                        <a href="{{ route('user.index') }}" style="padding:0.45rem 0.85rem; border-radius:8px; font-size:0.88rem; font-weight:600; text-decoration:none; {{ request()->routeIs('user.*') ? 'background:rgba(255,255,255,0.2); color:#fff;' : 'color:rgba(255,255,255,0.75);' }}" onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.color='#fff'" onmouseout="this.style.background='{{ request()->routeIs('user.*') ? 'rgba(255,255,255,0.2)' : 'transparent' }}';this.style.color='{{ request()->routeIs('user.*') ? '#fff' : 'rgba(255,255,255,0.75)' }}';">
                            Utilisateurs
                        </a>
                        <a href="{{ route('agenda.index') }}" style="padding:0.45rem 0.85rem; border-radius:8px; font-size:0.88rem; font-weight:600; text-decoration:none; {{ request()->routeIs('agenda.*') ? 'background:rgba(255,255,255,0.2); color:#fff;' : 'color:rgba(255,255,255,0.75);' }}" onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.color='#fff'" onmouseout="this.style.background='{{ request()->routeIs('agenda.*') ? 'rgba(255,255,255,0.2)' : 'transparent' }}';this.style.color='{{ request()->routeIs('agenda.*') ? '#fff' : 'rgba(255,255,255,0.75)' }}';">
                            Agenda
                        </a>
                        <a href="{{ route('compte-rendu.index') }}" style="padding:0.45rem 0.85rem; border-radius:8px; font-size:0.88rem; font-weight:600; text-decoration:none; {{ request()->routeIs('compte-rendu.*') ? 'background:rgba(255,255,255,0.2); color:#fff;' : 'color:rgba(255,255,255,0.75);' }}" onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.color='#fff'" onmouseout="this.style.background='{{ request()->routeIs('compte-rendu.*') ? 'rgba(255,255,255,0.2)' : 'transparent' }}';this.style.color='{{ request()->routeIs('compte-rendu.*') ? '#fff' : 'rgba(255,255,255,0.75)' }}';">
                            Compte rendu
                        </a>

                    @elseif($roleProfil === 'referent')
                        <a href="{{ route('admin.missions.index') }}" style="padding:0.45rem 0.85rem; border-radius:8px; font-size:0.88rem; font-weight:600; text-decoration:none; {{ request()->routeIs('admin.missions.*') ? 'background:rgba(255,255,255,0.2); color:#fff;' : 'color:rgba(255,255,255,0.75);' }}" onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.color='#fff'" onmouseout="this.style.background='{{ request()->routeIs('admin.missions.*') ? 'rgba(255,255,255,0.2)' : 'transparent' }}';this.style.color='{{ request()->routeIs('admin.missions.*') ? '#fff' : 'rgba(255,255,255,0.75)' }}';">
                            Missions
                        </a>
                        <a href="{{ route('beneficiaire.index') }}" style="padding:0.45rem 0.85rem; border-radius:8px; font-size:0.88rem; font-weight:600; text-decoration:none; {{ request()->routeIs('beneficiaire.*') ? 'background:rgba(255,255,255,0.2); color:#fff;' : 'color:rgba(255,255,255,0.75);' }}" onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.color='#fff'" onmouseout="this.style.background='{{ request()->routeIs('beneficiaire.*') ? 'rgba(255,255,255,0.2)' : 'transparent' }}';this.style.color='{{ request()->routeIs('beneficiaire.*') ? '#fff' : 'rgba(255,255,255,0.75)' }}';">
                            Bénéficiaires
                        </a>
                        <a href="{{ route('user.index') }}" style="padding:0.45rem 0.85rem; border-radius:8px; font-size:0.88rem; font-weight:600; text-decoration:none; {{ request()->routeIs('user.*') ? 'background:rgba(255,255,255,0.2); color:#fff;' : 'color:rgba(255,255,255,0.75);' }}" onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.color='#fff'" onmouseout="this.style.background='{{ request()->routeIs('user.*') ? 'rgba(255,255,255,0.2)' : 'transparent' }}';this.style.color='{{ request()->routeIs('user.*') ? '#fff' : 'rgba(255,255,255,0.75)' }}';">
                            Utilisateurs
                        </a>
                        <a href="{{ route('agenda.index') }}" style="padding:0.45rem 0.85rem; border-radius:8px; font-size:0.88rem; font-weight:600; text-decoration:none; {{ request()->routeIs('agenda.*') ? 'background:rgba(255,255,255,0.2); color:#fff;' : 'color:rgba(255,255,255,0.75);' }}" onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.color='#fff'" onmouseout="this.style.background='{{ request()->routeIs('agenda.*') ? 'rgba(255,255,255,0.2)' : 'transparent' }}';this.style.color='{{ request()->routeIs('agenda.*') ? '#fff' : 'rgba(255,255,255,0.75)' }}';">
                            Agenda
                        </a>
                        <a href="{{ route('compte-rendu.index') }}" style="padding:0.45rem 0.85rem; border-radius:8px; font-size:0.88rem; font-weight:600; text-decoration:none; {{ request()->routeIs('compte-rendu.*') ? 'background:rgba(255,255,255,0.2); color:#fff;' : 'color:rgba(255,255,255,0.75);' }}" onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.color='#fff'" onmouseout="this.style.background='{{ request()->routeIs('compte-rendu.*') ? 'rgba(255,255,255,0.2)' : 'transparent' }}';this.style.color='{{ request()->routeIs('compte-rendu.*') ? '#fff' : 'rgba(255,255,255,0.75)' }}';">
                            Compte rendu
                        </a>

                    @else
                        <a href="{{ route('beneficiaire.index') }}" style="padding:0.45rem 0.85rem; border-radius:8px; font-size:0.88rem; font-weight:600; text-decoration:none; {{ request()->routeIs('beneficiaire.*') ? 'background:rgba(255,255,255,0.2); color:#fff;' : 'color:rgba(255,255,255,0.75);' }}" onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.color='#fff'" onmouseout="this.style.background='{{ request()->routeIs('beneficiaire.*') ? 'rgba(255,255,255,0.2)' : 'transparent' }}';this.style.color='{{ request()->routeIs('beneficiaire.*') ? '#fff' : 'rgba(255,255,255,0.75)' }}';">
                            Bénéficiaires
                        </a>
                        <a href="{{ route('agenda.index') }}" style="padding:0.45rem 0.85rem; border-radius:8px; font-size:0.88rem; font-weight:600; text-decoration:none; {{ request()->routeIs('agenda.*') ? 'background:rgba(255,255,255,0.2); color:#fff;' : 'color:rgba(255,255,255,0.75);' }}" onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.color='#fff'" onmouseout="this.style.background='{{ request()->routeIs('agenda.*') ? 'rgba(255,255,255,0.2)' : 'transparent' }}';this.style.color='{{ request()->routeIs('agenda.*') ? '#fff' : 'rgba(255,255,255,0.75)' }}';">
                            Agenda
                        </a>
                        <a href="{{ route('compte-rendu.index') }}" style="padding:0.45rem 0.85rem; border-radius:8px; font-size:0.88rem; font-weight:600; text-decoration:none; {{ request()->routeIs('compte-rendu.*') ? 'background:rgba(255,255,255,0.2); color:#fff;' : 'color:rgba(255,255,255,0.75);' }}" onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.color='#fff'" onmouseout="this.style.background='{{ request()->routeIs('compte-rendu.*') ? 'rgba(255,255,255,0.2)' : 'transparent' }}';this.style.color='{{ request()->routeIs('compte-rendu.*') ? '#fff' : 'rgba(255,255,255,0.75)' }}';">
                            Compte rendu
                        </a>
                    @endif
                </div>
            </div>

            {{-- ═══ RIGHT: Profile Dropdown (desktop) ═══ --}}
            <div style="display:none; align-items:center; position:relative;" class="sm-show-nav">
                <button @click="profileOpen = !profileOpen" style="display:flex; align-items:center; gap:0.5rem; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.15); border-radius:10px; padding:0.4rem 0.65rem; cursor:pointer; transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.18)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                    <div style="width:32px; height:32px; border-radius:50%; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center;">
                        <svg style="width:18px; height:18px; color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <svg style="width:14px; height:14px; color:rgba(255,255,255,0.6); transition:transform 0.2s;" :style="profileOpen ? 'transform:rotate(180deg)' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                {{-- Dropdown menu --}}
                <div x-show="profileOpen" @click.outside="profileOpen = false" x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 transform -translate-y-1" x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     style="position:absolute; right:0; top:52px; background:#fff; border-radius:10px; box-shadow:0 8px 24px rgba(15,23,42,0.15); min-width:200px; overflow:hidden; border:1px solid rgba(15,23,42,0.06); z-index:99;">
                    <a href="{{ route('profile.edit') }}" style="display:flex; align-items:center; gap:0.6rem; padding:0.75rem 1rem; text-decoration:none; color:#374151; font-size:0.9rem; font-weight:500; transition:background 0.15s;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                        <svg style="width:18px; height:18px; color:#6b7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Mon profil
                    </a>
                
                    <div style="height:1px; background:#f3f4f6; margin:0 0.75rem;"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" style="display:flex; align-items:center; gap:0.6rem; padding:0.75rem 1rem; width:100%; border:none; background:transparent; cursor:pointer; color:#dc2626; font-size:0.9rem; font-weight:500; text-align:left; transition:background 0.15s;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'">
                            <svg style="width:18px; height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>

            {{-- ═══ Hamburger (mobile) ═══ --}}
            <div style="display:flex; align-items:center;" class="sm-hide-burger">
                <button @click="open = !open" style="background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.15); border-radius:8px; padding:0.45rem; cursor:pointer; display:flex; align-items:center; justify-content:center;">
                    <svg x-show="!open" style="width:22px; height:22px; color:#fff;" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="open" x-cloak style="width:22px; height:22px; color:#fff;" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ═══ MOBILE MENU ═══ --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak style="background:rgba(15,23,42,0.95); backdrop-filter:blur(12px); border-top:1px solid rgba(255,255,255,0.08);">
        <div style="padding:0.75rem 1rem;">

            @php $roleProfil = optional(auth()->user()->profil)->role; @endphp

            @if(auth()->user()->is_admin)
                <a href="{{ route('beneficiaire.index') }}" style="display:block; padding:0.65rem 1rem; border-radius:8px; text-decoration:none; font-size:0.92rem; font-weight:600; margin-bottom:2px; {{ request()->routeIs('beneficiaire.*') ? 'background:rgba(255,255,255,0.12); color:#fff;' : 'color:rgba(255,255,255,0.7);' }}">Bénéficiaires</a>
                <a href="{{ route('admin.missions.index') }}" style="display:block; padding:0.65rem 1rem; border-radius:8px; text-decoration:none; font-size:0.92rem; font-weight:600; margin-bottom:2px; {{ request()->routeIs('admin.missions.*') ? 'background:rgba(255,255,255,0.12); color:#fff;' : 'color:rgba(255,255,255,0.7);' }}">Missions</a>
                <a href="{{ route('admin.categories.index') }}" style="display:block; padding:0.65rem 1rem; border-radius:8px; text-decoration:none; font-size:0.92rem; font-weight:600; margin-bottom:2px; {{ request()->routeIs('admin.categories.*') ? 'background:rgba(255,255,255,0.12); color:#fff;' : 'color:rgba(255,255,255,0.7);' }}">Catégories</a> 
                <a href="{{ route('user.index') }}" style="display:block; padding:0.65rem 1rem; border-radius:8px; text-decoration:none; font-size:0.92rem; font-weight:600; margin-bottom:2px; {{ request()->routeIs('user.*') ? 'background:rgba(255,255,255,0.12); color:#fff;' : 'color:rgba(255,255,255,0.7);' }}">Utilisateurs</a>
                <a href="{{ route('agenda.index') }}" style="display:block; padding:0.65rem 1rem; border-radius:8px; text-decoration:none; font-size:0.92rem; font-weight:600; margin-bottom:2px; {{ request()->routeIs('agenda.*') ? 'background:rgba(255,255,255,0.12); color:#fff;' : 'color:rgba(255,255,255,0.7);' }}">Agenda</a>
                <a href="{{ route('register') }}" style="display:block; padding:0.65rem 1rem; border-radius:8px; text-decoration:none; font-size:0.92rem; font-weight:600; margin-bottom:2px; {{ request()->routeIs('register') ? 'background:rgba(255,255,255,0.12); color:#fff;' : 'color:rgba(255,255,255,0.7);' }}">Ajouter un utilisateur</a>
                <a href="{{ route('compte-rendu.index') }}" style="display:block; padding:0.65rem 1rem; border-radius:8px; text-decoration:none; font-size:0.92rem; font-weight:600; margin-bottom:2px; {{ request()->routeIs('compte-rendu.*') ? 'background:rgba(255,255,255,0.12); color:#fff;' : 'color:rgba(255,255,255,0.7);' }}">Compte rendu</a>

            @elseif($roleProfil === 'referent')
                <a href="{{ route('admin.beneficiaires') }}" style="display:block; padding:0.65rem 1rem; border-radius:8px; text-decoration:none; font-size:0.92rem; font-weight:600; margin-bottom:2px; {{ request()->routeIs('admin.beneficiaires') ? 'background:rgba(255,255,255,0.12); color:#fff;' : 'color:rgba(255,255,255,0.7);' }}">Liste Bénéficiaires</a>
                <a href="{{ route('user.index') }}" style="display:block; padding:0.65rem 1rem; border-radius:8px; text-decoration:none; font-size:0.92rem; font-weight:600; margin-bottom:2px; {{ request()->routeIs('user.*') ? 'background:rgba(255,255,255,0.12); color:#fff;' : 'color:rgba(255,255,255,0.7);' }}">Utilisateurs</a>
                <a href="{{ route('agenda.index') }}" style="display:block; padding:0.65rem 1rem; border-radius:8px; text-decoration:none; font-size:0.92rem; font-weight:600; margin-bottom:2px; {{ request()->routeIs('agenda.*') ? 'background:rgba(255,255,255,0.12); color:#fff;' : 'color:rgba(255,255,255,0.7);' }}">Agenda</a>
                <a href="{{ route('compte-rendu.index') }}" style="display:block; padding:0.65rem 1rem; border-radius:8px; text-decoration:none; font-size:0.92rem; font-weight:600; margin-bottom:2px; {{ request()->routeIs('compte-rendu.*') ? 'background:rgba(255,255,255,0.12); color:#fff;' : 'color:rgba(255,255,255,0.7);' }}">Compte rendu</a>

            @else
                <a href="{{ route('beneficiaire.index') }}" style="display:block; padding:0.65rem 1rem; border-radius:8px; text-decoration:none; font-size:0.92rem; font-weight:600; margin-bottom:2px; {{ request()->routeIs('beneficiaire.*') ? 'background:rgba(255,255,255,0.12); color:#fff;' : 'color:rgba(255,255,255,0.7);' }}">Bénéficiaires</a>
                <a href="{{ route('agenda.index') }}" style="display:block; padding:0.65rem 1rem; border-radius:8px; text-decoration:none; font-size:0.92rem; font-weight:600; margin-bottom:2px; {{ request()->routeIs('agenda.*') ? 'background:rgba(255,255,255,0.12); color:#fff;' : 'color:rgba(255,255,255,0.7);' }}">Agenda</a>
                <a href="{{ route('compte-rendu.index') }}" style="display:block; padding:0.65rem 1rem; border-radius:8px; text-decoration:none; font-size:0.92rem; font-weight:600; margin-bottom:2px; {{ request()->routeIs('compte-rendu.*') ? 'background:rgba(255,255,255,0.12); color:#fff;' : 'color:rgba(255,255,255,0.7);' }}">Compte rendu</a>
            @endif
        </div>

        {{-- Mobile user section --}}
        <div style="border-top:1px solid rgba(255,255,255,0.08); padding:0.75rem 1rem;">
            <div style="display:flex; align-items:center; gap:0.6rem; padding:0.5rem 1rem; margin-bottom:0.5rem;">
                <div style="width:32px; height:32px; border-radius:50%; background:rgba(255,255,255,0.15); display:flex; align-items:center; justify-content:center;">
                    <svg style="width:16px; height:16px; color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                    <div style="font-size:0.9rem; font-weight:700; color:#fff;">{{ Auth::user()->display_name }}</div>
                    <div style="font-size:0.78rem; color:rgba(255,255,255,0.5);">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" style="display:block; padding:0.6rem 1rem; border-radius:8px; text-decoration:none; font-size:0.9rem; font-weight:500; color:rgba(255,255,255,0.7); margin-bottom:2px;">Mon profil</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="display:block; width:100%; text-align:left; padding:0.6rem 1rem; border-radius:8px; border:none; background:transparent; cursor:pointer; font-size:0.9rem; font-weight:500; color:#f87171;">Déconnexion</button>
            </form>
        </div>
    </div>
</nav>

{{-- CSS responsive: show/hide elements at sm breakpoint --}}
<style>
    .sm-show-nav { display: none !important; }
    .sm-show-name { display: none !important; }
    .sm-hide-burger { display: flex !important; }
    @media (min-width: 640px) {
        .sm-show-nav { display: flex !important; }
        .sm-show-name { display: inline !important; }
        .sm-hide-burger { display: none !important; }
    }
</style>
