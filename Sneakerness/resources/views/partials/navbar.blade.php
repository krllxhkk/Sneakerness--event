<header>
    <div class="header-container">

        <div class="logo">
            SNEAKERNESS<sup>®</sup>
        </div>

        <div class="hamburger" id="hamburgerBtn" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <ul class="nav-links" id="navLinks">

            {{-- HOME --}}
            <li>
                <a href="{{ route('home') }}"
                   class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    Home
                </a>
            </li>

            {{-- EVENTS --}}
            <li>
                <a href="{{ route('events.index') }}"
                   class="{{ request()->routeIs('events.*') ? 'active' : '' }}">
                    Events
                </a>
            </li>

            {{-- TICKETS --}}
            <li>
                <a href="{{ route('tickets.index') }}"
                   class="{{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                    Tickets
                </a>
            </li>

            {{-- VERKOPERS --}}
            <li>
                <a href="{{ route('verkopers.index') }}"
                   class="{{ request()->routeIs('verkopers.*') ? 'active' : '' }}">
                    Verkopers
                </a>
            </li>

            {{-- STANDS --}}
            <li>
                <a href="{{ route('stands.index') }}"
                   class="{{ request()->routeIs('stands.*') ? 'active' : '' }}">
                    Stands
                </a>
            </li>


        </ul>

        <div class="nav-right">

            <a href="#" class="login-link">
                LOGIN
            </a>

            <a href="{{ route('tickets.index') }}" class="btn-yellow-sm">
                TICKET KOPEN
            </a>

        </div>

    </div>
</header>

<script>
    function toggleMenu() {
        const navLinks = document.getElementById('navLinks');
        navLinks.classList.toggle('active');
    }
</script>