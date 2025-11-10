<div class="space-y-3">
    <a href="{{ url('/auth/google') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 border border-black rounded-md text-sm font-medium text-black hover:bg-black hover:text-white focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
            <path d="M12 2a10 10 0 1 0 7.07 17.07l-2.82-2.82A6 6 0 1 1 18 12h-6V7h11a10 10 0 0 0-11-5z" />
        </svg>
        <span>Continue with Google</span>
    </a>

    <a href="{{ url('/auth/facebook') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 border border-black rounded-md text-sm font-medium text-black hover:bg-black hover:text-white focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
            <path d="M13 3h4a1 1 0 0 1 1 1v4h-3a2 2 0 0 0-2 2v3h5l-1 4h-4v7h-4v-7H6v-4h3V9a6 6 0 0 1 6-6z"/>
        </svg>
        <span>Continue with Facebook</span>
    </a>
</div>

