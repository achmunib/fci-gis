<h1 class="page-title">Dashboard</h1>
<p class="page-subtitle">Halaman ini akan berisi peta interaktif dan statistik aset tanah.</p>

<div class="placeholder-grid">
	<div class="card placeholder-card map-placeholder">
		<div class="card-header">Peta Aset Tanah</div>
		<div class="card-body">
			<div class="skeleton" style="height: 400px; width: 100%;"></div>
		</div>
	</div>

	<div class="stats-row">
		{#each ['Total Aset', 'Total OPD', 'Total Luas (m²)'] as label}
			<div class="card stat-card">
				<div class="card-body">
					<span class="stat-label">{label}</span>
					<div class="skeleton" style="height: 2rem; width: 60%; margin-top: 0.5rem;"></div>
				</div>
			</div>
		{/each}
	</div>
</div>

<style>
	.page-title {
		font-size: var(--font-size-2xl);
		font-weight: 700;
		color: var(--color-text);
		margin-bottom: var(--space-xs);
	}
	.page-subtitle {
		color: var(--color-text-secondary);
		font-size: var(--font-size-sm);
		margin-bottom: var(--space-xl);
	}
	.placeholder-grid {
		display: flex;
		flex-direction: column;
		gap: var(--space-lg);
	}
	.stats-row {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
		gap: var(--space-lg);
	}
	.stat-card .card-body {
		padding: var(--space-lg);
	}
	.stat-label {
		font-size: var(--font-size-sm);
		color: var(--color-text-secondary);
		font-weight: 500;
	}
</style>
