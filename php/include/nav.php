<nav>
    <div class="nav-container">
        <!-- Logo / Brand -->
        <input type="checkbox" class="nav-toggle-checkbox" id="navToggleCheckbox"/>
        <div class="nav-logo-container">
            <a href="index.php" class="nav-logo">Reiseberichte</a>
            <!-- Toggle Button (für Mobile) -->
            <label for="navToggleCheckbox" class="nav-toggle" id="navToggle">            
                <span></span>
                <span></span>
                <span></span>
            </label>
        </div>
        <!-- Navigation Links -->
        <div class="nav-links" id="navLinks">
            <a href="report_form.php">Bericht erstellen</a>
            <a href="profile.php?side=nav" id="profil-nav">Profil</a>
            <a href="profile.php?side=konto" id="profil-konto">Profil</a>
            <?php if (isset($_SESSION['user'])): ?>
                <a href="logout.php">Abmelden</a>
            <?php else: ?>
                <a href="login.php">Anmelden</a>
            <?php endif; ?>
        </div>
    </div>
</nav>