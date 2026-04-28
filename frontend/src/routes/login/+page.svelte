<script lang="ts">
	import { login } from '$lib/services/auth.service';
	import { goto } from '$app/navigation';
	import { page } from '$app/state';

	let email = $state('');
	let password = $state('');
	let loading = $state(false);
	let errorMsg = $state('');

	async function handleLogin(e: Event) {
		e.preventDefault();
		loading = true;
		errorMsg = '';

		try {
			await login(email, password);
			// Redirect to dashboard or where they came from
			goto('/dashboard');
		} catch (err: any) {
			errorMsg = err.message || 'Login gagal. Silakan coba lagi.';
		} finally {
			loading = false;
		}
	}
</script>

<h1 class="page-title">Masuk ke SIMANTA</h1>

<div class="login-container">
	<div class="login-card card">
		<div class="card-body">
			<div class="login-logo">
				<span class="material-icons-outlined">public</span>
				<span>SIMANTA</span>
			</div>

			<form class="login-form" onsubmit={handleLogin}>
				{#if errorMsg}
					<div class="form-error" style="text-align: center; margin-bottom: var(--space-sm); padding: var(--space-sm); background: var(--color-danger-light); border-radius: var(--radius-sm); color: var(--color-danger);">
						{errorMsg}
					</div>
				{/if}

				<div class="form-group">
					<label class="form-label" for="email">Email</label>
					<input id="email" type="email" class="form-input" placeholder="email@example.com" bind:value={email} required />
				</div>

				<div class="form-group">
					<label class="form-label" for="password">Password</label>
					<input id="password" type="password" class="form-input" placeholder="••••••••" bind:value={password} required />
				</div>

				<button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;" disabled={loading}>
					{#if loading}
						<span class="material-icons-outlined" style="font-size: 1rem; animation: spin 1s linear infinite;">refresh</span>
						Memproses...
					{:else}
						Masuk
					{/if}
				</button>
			</form>
		</div>
	</div>
</div>

<style>
	.page-title {
		position: absolute;
		width: 1px;
		height: 1px;
		overflow: hidden;
		clip: rect(0, 0, 0, 0);
	}
	.login-container {
		display: flex;
		align-items: center;
		justify-content: center;
		min-height: 80vh;
	}
	.login-card {
		width: 100%;
		max-width: 400px;
	}
	.login-logo {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: var(--space-sm);
		font-size: var(--font-size-2xl);
		font-weight: 700;
		color: var(--color-primary);
		margin-bottom: var(--space-xl);
	}
	.login-logo .material-icons-outlined {
		font-size: 2rem;
	}
	.login-form {
		display: flex;
		flex-direction: column;
		gap: var(--space-md);
	}
</style>
