<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.login') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>

<style>
    body {
        background-color: #000 !important;
    }

    .login-container {
        min-height: 100vh;
        background-color: #000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }

    .login-card {
        background-color: #1a1a1a;
        border: 2px solid #D4B68A;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(212, 182, 138, 0.3);
        overflow: hidden;
        max-width: 450px;
        width: 100%;
    }

    .login-header {
        background-color: #D4B68A;
        color: #000;
        padding: 2.5rem 2rem;
        text-align: center;
    }

    .login-header h2 {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
    }

    .login-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.8;
        font-size: 0.95rem;
    }

    .login-body {
        padding: 2.5rem 2rem;
    }

    .form-floating > .form-control {
        background-color: #2a2a2a;
        border: 2px solid #D4B68A;
        border-radius: 10px;
        padding: 1rem 0.75rem;
        color: #f5f5dc;
    }

    .form-floating > .form-control:focus {
        border-color: #D4B68A;
        box-shadow: 0 0 0 0.2rem rgba(212, 182, 138, 0.25);
        background-color: #2a2a2a;
        color: #f5f5dc;
    }

    .form-floating > label {
        color: #D4B68A;
    }

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        color: #D4B68A;
    }

    .btn-login {
        background-color: #D4B68A;
        border: none;
        border-radius: 10px;
        padding: 0.875rem;
        font-weight: 600;
        font-size: 1.05rem;
        color: #000;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-login:hover {
        background-color: #c9a770;
        color: #000;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(212, 182, 138, 0.3);
    }

    .btn-google {
        border: 2px solid #D4B68A;
        border-radius: 10px;
        padding: 0.875rem;
        font-weight: 600;
        transition: all 0.2s;
        background: #2a2a2a;
        color: #D4B68A;
    }

    .btn-google:hover {
        border-color: #D4B68A;
        background: #D4B68A;
        color: #000;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(212, 182, 138, 0.2);
    }

    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 1.5rem 0;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #D4B68A;
    }

    .divider span {
        padding: 0 1rem;
        color: #D4B68A;
        font-size: 0.9rem;
    }

    .form-check-input {
        background-color: #2a2a2a;
        border-color: #D4B68A;
    }

    .form-check-input:checked {
        background-color: #D4B68A;
        border-color: #D4B68A;
    }

    .form-check-label {
        color: #f5f5dc;
    }

    .login-footer {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #D4B68A;
        text-align: center;
    }

    .login-footer a {
        color: #D4B68A;
        text-decoration: none;
        font-weight: 600;
    }

    .login-footer a:hover {
        color: #c9a770;
        text-decoration: underline;
    }

    .login-footer p {
        color: #f5f5dc;
    }

    .alert {
        border-radius: 10px;
    }
</style>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2>Bienvenido</h2>
                <p>Inicia sesión en tu cuenta</p>
            </div>

            <div class="login-body">

                <?php if (session('error') !== null) : ?>
                    <div class="alert alert-danger" role="alert"><?= esc(session('error')) ?></div>
                <?php elseif (session('errors') !== null) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php if (is_array(session('errors'))) : ?>
                            <?php foreach (session('errors') as $error) : ?>
                                <?= esc($error) ?>
                                <br>
                            <?php endforeach ?>
                        <?php else : ?>
                            <?= esc(session('errors')) ?>
                        <?php endif ?>
                    </div>
                <?php endif ?>

                <?php if (session('message') !== null) : ?>
                    <div class="alert alert-success" role="alert"><?= esc(session('message')) ?></div>
                <?php endif ?>

                <?php if (session('success') !== null) : ?>
                    <div class="alert alert-success" role="alert"><?= esc(session('success')) ?></div>
                <?php endif ?>

                <!-- Botón de Google OAuth (solo si está configurado) -->
                <?php if (getenv('GOOGLE_CLIENT_ID') && getenv('GOOGLE_CLIENT_ID') !== 'TU_CLIENT_ID_AQUI.apps.googleusercontent.com') : ?>
                    <div class="d-grid mb-3">
                        <a href="<?= site_url('oauth/google') ?>" class="btn btn-google btn-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-google me-2" viewBox="0 0 16 16">
                                <path d="M15.545 6.558a9.4 9.4 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.7 7.7 0 0 1 5.352 2.082l-2.284 2.284A4.35 4.35 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.8 4.8 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.7 3.7 0 0 0 1.599-2.431H8v-3.08z"/>
                            </svg>
                            Continuar con Google
                        </a>
                    </div>

                    <div class="divider">
                        <span>o continúa con email</span>
                    </div>
                <?php endif ?>

                <form action="<?= url_to('login') ?>" method="post">
                    <?= csrf_field() ?>

                    <!-- Email -->
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="floatingEmailInput" name="email" inputmode="email" autocomplete="email" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>" required>
                        <label for="floatingEmailInput"><?= lang('Auth.email') ?></label>
                    </div>

                    <!-- Password -->
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="floatingPasswordInput" name="password" inputmode="text" autocomplete="current-password" placeholder="<?= lang('Auth.password') ?>" required>
                        <label for="floatingPasswordInput"><?= lang('Auth.password') ?></label>
                    </div>

                    <!-- Remember me -->
                    <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" name="remember" class="form-check-input" <?php if (old('remember')): ?> checked<?php endif ?>>
                                <?= lang('Auth.rememberMe') ?>
                            </label>
                        </div>
                    <?php endif; ?>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-login btn-lg">Iniciar Sesión</button>
                    </div>

                    <div class="login-footer">
                        <?php if (setting('Auth.allowMagicLinkLogins')) : ?>
                            <p class="mb-2"><?= lang('Auth.forgotPassword') ?> <a href="<?= url_to('magic-link') ?>"><?= lang('Auth.useMagicLink') ?></a></p>
                        <?php endif ?>
                    </div>

                </form>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>
