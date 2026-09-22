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
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><a href="{{ route('events.index') }}">Events</a></li>
            <li><a href="{{ url('/#tickets') }}">Tickets</a></li>
            <li><a href="{{ url('/#verkopers') }}">Verkopers</a></li>
            <li><a href="{{ route('stands.index') }}" class="active">Stands</a></li>
            <li><a href="{{ url('/#contact') }}">Contact</a></li>
        </ul>

        <div class="nav-right">
            <a href="#" class="login-link">LOGIN</a>
            <a href="{{ url('/#tickets') }}" class="btn-yellow-sm">
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