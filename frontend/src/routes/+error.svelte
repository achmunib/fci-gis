<script lang="ts">
	import { page } from '$app/stores';
</script>

<svelte:head>
	<title>Halaman Tidak Ditemukan</title>
</svelte:head>

<div class="error-container">
	<span class="material-icons-outlined error-icon">explore_off</span>
	<h1 class="error-code">404</h1>
	<h2 class="error-title">Halaman Tidak Ditemukan</h2>
	<p class="error-desc">Maaf, halaman <code style="background:var(--color-bg-alt);padding:2px 6px;border-radius:4px;">{$page.url.pathname}</code> tidak dapat ditemukan atau telah dipindahkan.</p>
	<div class="error-actions">
		<button class="btn btn-primary" onclick={() => window.history.back()}>
			<span class="material-icons-outlined">arrow_back</span> Kembali
		</button>
		<a href="/" class="btn btn-secondary">
			<span class="material-icons-outlined">home</span> Ke Beranda
		</a>
	</div>
</div>

<style>
	.error-container {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		text-align: center;
		min-height: calc(100vh - 200px);
		padding: var(--space-2xl) var(--space-md);
	}
	.error-icon {
		font-size: 5rem;
		color: var(--color-danger);
		margin-bottom: var(--space-md);
		opacity: 0.8;
	}
	.error-code {
		font-size: 6rem;
		font-weight: 800;
		color: var(--color-text);
		line-height: 1;
		margin: 0;
	}
	.error-title {
		font-size: var(--font-size-2xl);
		font-weight: 600;
		margin-top: var(--space-md);
		margin-bottom: var(--space-sm);
	}
	.error-desc {
		color: var(--color-text-secondary);
		max-width: 500px;
		margin-bottom: var(--space-xl);
		line-height: 1.6;
	}
	.error-actions {
		display: flex;
		gap: var(--space-md);
	}
</style>
