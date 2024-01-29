<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">
    <title>Sistema de Eventos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
    <!-- Styles -->
    @livewireStyles

</head>

<body>


    <main id="main">

        <!-- partial:index.partial.html -->
        <div class="sidebar">
            <div class="logo-details">
                <i class=''></i>
                <div class="logo_name"></div>
                <i class='bi bi-justify' id="btn"></i>
            </div>
            <ul class="nav-list">
                <li>
                    <a href="/calendario">
                        <i class='bi bi-calendar-event-fill'></i>
                        <span class="links_name">Calendario</span>
                    </a>
                    <span class="tooltip">Calendario</span>
                </li>
                
                <li class="profile">
                    <div class="profile-details">
                        <img src="https://drive.google.com/uc?export=view&id=1ETZYgPpWbbBtpJnhi42_IR3vOwSOpR4z" alt="profileImg">
                        <div class="name_job">
                            <div class="name">{{ Auth::user()->name }}</div>
                        </div>
                    </div>
                    <i class='bi bi-box-arrow-left cursor-pointer' id="log_out"></i>
                </li>

            </ul>
        </div>
        <section class="home-section">
            <slot>
                {{ $slot }}
            </slot>
        </section>

    

    </main>


    @livewireScripts
    @stack('scripts')

    <script>
        // Agrega un script de JavaScript para manejar el clic en el ícono de cierre de sesión
        document.getElementById('log_out').addEventListener('click', function() {
            // Realiza la acción de cierre de sesión aquí
            fetch('/logout', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Asegúrate de incluir el token CSRF
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url; // Redirige a la nueva ubicación después del cierre de sesión
                }
            });
        });

        // Cambia el cursor al pasar sobre el ícono
        document.getElementById('log_out').addEventListener('mouseover', function() {
            this.style.cursor = 'pointer';
        });
    </script>

    <script>
        let sidebar = document.querySelector(".sidebar");
        let closeBtn = document.querySelector("#btn");
        //let searchBtn = document.querySelector(".bx-search");
        closeBtn.addEventListener("click", () => {
            sidebar.classList.toggle("open");
            menuBtnChange(); //calling the function(optional)
        });
        /*searchBtn.addEventListener("click", () => {
            // Sidebar open when you click on the search iocn
            sidebar.classList.toggle("open");
            menuBtnChange(); //calling the function(optional)
        });*/
        // following are the code to change sidebar button(optional)
        function menuBtnChange() {
            if (sidebar.classList.contains("open")) {
                closeBtn.classList.replace("bx-menu", "bx-menu-alt-right"); //replacing the iocns class
            } else {
                closeBtn.classList.replace("bx-menu-alt-right", "bx-menu"); //replacing the iocns class
            }
        }
    </script>

</body>
</html>
