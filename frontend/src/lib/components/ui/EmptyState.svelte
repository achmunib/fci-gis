<script lang="ts">
	let { title = 'Data Kosong', description = 'Belum ada data yang tersedia.', icon = 'inbox' } = $props<{
		title?: string;
		description?: string;
		icon?: string;
	}>();
</script>

<div class="empty-state">
	<div class="empty-icon-wrapper">
		<span class="material-icons-outlined empty-icon">{icon}</span>
	</div>
	<h3 class="empty-title">{title}</h3>
	<p class="empty-desc">{description}</p>
	<slot></slot>
</div>

<style>
	.empty-state {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		text-align: center;
		padding: var(--space-2xl) var(--space-md);
	}
	.empty-icon-wrapper {
		background: var(--color-bg-alt);
		width: 80px;
		height: 80px;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		margin-bottom: var(--space-lg);
	}
	.empty-icon {
		font-size: 3rem;
		color: var(--color-text-muted);
	}
	.empty-title {
		font-size: var(--font-size-lg);
		font-weight: 600;
		color: var(--color-text);
		margin-bottom: var(--space-xs);
	}
	.empty-desc {
		color: var(--color-text-secondary);
		max-width: 400px;
		margin-bottom: var(--space-lg);
	}
</style>
