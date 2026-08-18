<x-layouts::auth :title="__('Iniciar sesión')">
    <div class="fixed inset-0 z-50 flex w-full h-screen bg-zinc-950 overflow-hidden">

        <!-- Lado Izquierdo: Imagen de Santa Cruz y Branding (Ocupa el 60% o más del ancho) -->
        <div class="relative hidden lg:flex lg:w-7/12 flex-col justify-between p-12 text-white overflow-hidden bg-zinc-900">
            <!-- Imagen de fondo -->
            <img src="{{ asset('img/SANTA CRUZ DE LA  SIERRA.png') }}" alt="Santa Cruz de la Sierra" class="absolute inset-0 size-full object-cover">

            <!-- Capa oscura degradada para legibilidad -->
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/90 via-zinc-950/40 to-zinc-950/30"></div>

            <!-- Parte Superior: Logo / Marca dinámicos de Ajustes -->
            <div class="relative z-10 flex items-center gap-3">
                @php
                    $ajuste = \App\Models\Ajuste::first();
                    $logoUrl = $ajuste && $ajuste->logo ? asset('storage/' . $ajuste->logo) : null;
                    $appName = $ajuste ? $ajuste->nombre : 'MINIMARKET';
                @endphp
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $appName }}" class="h-10 w-auto object-contain">
                @else
                    <div class="flex aspect-square size-10 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-md">
                        <flux:icon.shopping-cart class="size-6" />
                    </div>
                @endif
                <span class="text-xl font-bold tracking-wider uppercase text-white drop-shadow-md">{{ $appName }}</span>
            </div>

            <!-- Parte Inferior: Ubicación y Frase -->
            <div class="relative z-10 flex flex-col gap-2">
                <div class="flex items-center gap-2 text-emerald-400 font-medium text-sm drop-shadow">
                    <flux:icon.map-pin class="size-4 text-emerald-400" />
                    <span>Santa Cruz de la Sierra, Bolivia 🇧🇴</span>
                </div>
                <p class="text-zinc-300 text-sm drop-shadow">
                    Abasteciendo tu hogar, todos los días.
                </p>
            </div>
        </div>

        <!-- Lado Derecho: Formulario de Inicio de Sesión (Ocupa el resto del ancho) -->
        <div class="w-full lg:w-5/12 flex flex-col items-center justify-center p-6 lg:p-12 bg-zinc-950 text-zinc-100 h-full overflow-y-auto">
            <div class="w-full max-w-sm flex flex-col gap-6">

                <!-- Encabezado del Formulario -->
                <div class="flex flex-col items-center text-center gap-3">
                    <div class="flex aspect-square size-12 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shadow-inner">
                        <flux:icon.shopping-cart class="size-6" />
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-zinc-100">Iniciar sesión</h1>
                        <p class="text-sm text-zinc-400 mt-1">Sistema de Gestión para Minimarket</p>
                    </div>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="text-center text-emerald-400 text-sm" :status="session('status')" />

                <!-- Formulario -->
                <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
                    @csrf

                    <!-- Email Address -->
                    <flux:input
                        name="email"
                        :label="__('Correo electrónico')"
                        :value="old('email')"
                        type="email"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="ejemplo@minimarket.com"
                    />

                    <!-- Password -->
                    <div class="relative">
                        <flux:input
                            name="password"
                            :label="__('Contraseña')"
                            type="password"
                            required
                            autocomplete="current-password"
                            :placeholder="__('Ingresa tu contraseña')"
                            viewable
                        />

                        @if (Route::has('password.request'))
                            <flux:link class="absolute top-0 text-xs end-0 text-emerald-400 hover:text-emerald-300" :href="route('password.request')" wire:navigate>
                                {{ __('¿Olvidaste tu contraseña?') }}
                            </flux:link>
                        @endif
                    </div>

                    <!-- Remember Me -->
                    <flux:checkbox name="remember" :label="__('Recordarme')" :checked="old('remember')" />

                    <div class="flex items-center justify-end mt-2">
                        <flux:button variant="primary" type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-medium py-2.5 rounded-lg transition-all shadow-lg shadow-emerald-950/50" data-test="login-button">
                            {{ __('Iniciar sesión') }}
                        </flux:button>
                    </div>
                </form>

                <!-- Pie de página -->
                <div class="text-center text-xs text-zinc-500 mt-4">
                    <span>Seguro, confiable y siempre contigo.</span>
                </div>
            </div>
        </div>

    </div>
</x-layouts::auth>
