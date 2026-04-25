<script lang="ts">
	// Redirect root to dashboard
	import { onMount } from 'svelte';
	import { goto } from '$app/navigation';

	onMount(() => {
		goto('/dashboard');
	});
</script>

<div class="loading-redirect">
	<span class="material-icons-outlined spin">public</span>
	<p>Memuat SIMANTA...</p>
</div>

<style>
	.loading-redirect {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		height: 60vh;
		gap: var(--space-md);
		color: var(--color-text-secondary);
	}
	.spin {
		font-size: 3rem;
		color: var(--color-primary);
		animation: spin 2s linear infinite;
	}
	@keyframes spin {
		from { transform: rotate(0deg); }
		to { transform: rotate(360deg); }
	}
</style>
