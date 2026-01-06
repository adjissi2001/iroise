<header style="
    background:#CAE4DB;
    color: white;
    padding: 15px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
">
    <h1 style="margin: 0; color : black;">Iroise - Administration</h1>
    <nav>
        <a href="{{ route('dashboard') }}" style="color:black; text-decoration:none; margin-right:20px;">Tableau de bord</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" style="background:none; border:none; color:black; cursor:pointer; font:inherit; text-decoration:none;">Se déconnecter</button>
        </form>
    </nav>
</header>
