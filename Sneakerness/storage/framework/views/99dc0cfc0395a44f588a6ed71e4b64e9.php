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

            
            <li>
                <a href="<?php echo e(route('home')); ?>"
                   class="<?php echo e(request()->routeIs('home') ? 'active' : ''); ?>">
                    Home
                </a>
            </li>

            
            <li>
                <a href="<?php echo e(route('events.index')); ?>"
                   class="<?php echo e(request()->routeIs('events.*') ? 'active' : ''); ?>">
                    Events
                </a>
            </li>

            
            <li>
                <a href="<?php echo e(route('tickets.index')); ?>"
                   class="<?php echo e(request()->routeIs('tickets.*') ? 'active' : ''); ?>">
                    Tickets
                </a>
            </li>

            
            <li>
                <a href="<?php echo e(route('verkopers.index')); ?>"
                   class="<?php echo e(request()->routeIs('verkopers.*') ? 'active' : ''); ?>">
                    Verkopers
                </a>
            </li>

            
            <li>
                <a href="<?php echo e(route('stands.index')); ?>"
                   class="<?php echo e(request()->routeIs('stands.*') ? 'active' : ''); ?>">
                    Stands
                </a>
            </li>


        </ul>

        <div class="nav-right">

            <a href="#" class="login-link">
                LOGIN
            </a>

            <a href="<?php echo e(route('tickets.index')); ?>" class="btn-yellow-sm">
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
</script><?php /**PATH C:\Sneakerness--event\Sneakerness\resources\views/partials/navbar.blade.php ENDPATH**/ ?>