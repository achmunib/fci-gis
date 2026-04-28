<script lang="ts">
	import { onMount } from 'svelte';

	let isDark = $state(false);

	onMount(() => {
		isDark = document.documentElement.classList.contains('dark-mode');
	});

	function toggleTheme() {
		isDark = !isDark;
		if (isDark) {
			document.documentElement.classList.add('dark-mode');
			localStorage.setItem('theme', 'dark');
		} else {
			document.documentElement.classList.remove('dark-mode');
			localStorage.setItem('theme', 'light');
		}
	}
</script>

<button class="theme-toggle" onclick={toggleTheme} aria-label="Toggle Theme" title="Ganti Tema">
	{#if isDark}
		<span class="material-icons-outlined">light_mode</span>
	{:else}
		<span class="material-icons-outlined">dark_mode</span>
	{/if}
</button>

<style>
	.theme-toggle {
		background: none;
		border: none;
		color: var(--color-text-muted);
		cursor: pointer;
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 8px;
		border-radius: 50%;
		transition: all 0.2s;
	}
	.theme-toggle:hover {
		background: var(--color-bg-alt);
		color: var(--color-primary);
	}
</style>
