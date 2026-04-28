<script lang="ts">
	import { toast } from '$lib/stores/toast';
	import { fly, fade } from 'svelte/transition';
	
	let toasts = $state<any[]>([]);
	
	toast.subscribe((value) => {
		toasts = value;
	});

	const icons = {
		success: 'check_circle',
		error: 'error',
		warning: 'warning',
		info: 'info'
	};
</script>

<div class="toast-container">
	{#each toasts as t (t.id)}
		<div 
			class="toast {t.type}" 
			transition:fly={{ y: 20, duration: 300 }}
		>
			<span class="material-icons-outlined toast-icon">{icons[t.type]}</span>
			<span class="toast-message">{t.message}</span>
			<button class="toast-close" onclick={() => toast.remove(t.id)}>
				<span class="material-icons-outlined">close</span>
			</button>
		</div>
	{/each}
</div>

<style>
	.toast-container {
		position: fixed;
		bottom: var(--space-xl);
		right: var(--space-xl);
		display: flex;
		flex-direction: column;
		gap: var(--space-sm);
		z-index: 9999;
		pointer-events: none;
	}
	.toast {
		pointer-events: auto;
		display: flex;
		align-items: center;
		gap: var(--space-md);
		padding: var(--space-sm) var(--space-md);
		background: var(--color-bg);
		color: var(--color-text);
		border-radius: var(--radius-md);
		box-shadow: 0 10px 25px rgba(0,0,0,0.2);
		min-width: 300px;
		max-width: 400px;
		border-left: 4px solid;
	}
	
	.toast.success { border-left-color: #10b981; }
	.toast.error { border-left-color: #ef4444; }
	.toast.warning { border-left-color: #f59e0b; }
	.toast.info { border-left-color: #3b82f6; }

	.toast.success .toast-icon { color: #10b981; }
	.toast.error .toast-icon { color: #ef4444; }
	.toast.warning .toast-icon { color: #f59e0b; }
	.toast.info .toast-icon { color: #3b82f6; }

	.toast-message {
		flex: 1;
		font-size: var(--font-size-sm);
		font-weight: 500;
	}
	.toast-close {
		background: none;
		border: none;
		color: var(--color-text-muted);
		cursor: pointer;
		display: flex;
		align-items: center;
		padding: 4px;
		border-radius: 50%;
		transition: background 0.2s;
	}
	.toast-close:hover {
		background: var(--color-bg-alt);
		color: var(--color-text);
	}
</style>
