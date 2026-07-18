<div class="smart-sidebar-footer">
<form method="POST" action="{{ route('logout') }}">
            @csrf

        <button type="submit" class="smart-sidebar-logout">
            Logout
        </button>
    </form>
</div>