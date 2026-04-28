<script lang="ts">
	let { stats } = $props<{ stats: any }>();

	let totalAset = $derived(stats?.total_aset || 0);
	let totalLuas = $derived(stats?.total_luas || 0);
	let opdTanahUsaha = $derived(stats?.total_opd_tanah_usaha || 0);
	let bpkaCount = $derived(stats?.drilldown_bpka || 0);
</script>

<div class="stats-grid">
	<div class="card stat-card">
		<div class="card-body">
			<span class="stat-icon material-icons-outlined" style="color: #3b82f6; background: #dbeafe;">landscape</span>
			<div class="stat-info">
				<span class="stat-label">Total Data Tanah</span>
				<span class="stat-value">{totalAset}</span>
			</div>
		</div>
	</div>

	<div class="card stat-card">
		<div class="card-body">
			<span class="stat-icon material-icons-outlined" style="color: #10b981; background: #d1fae5;">business</span>
			<div class="stat-info">
				<span class="stat-label">Total Luas (m²)</span>
				<span class="stat-value">{Number(totalLuas).toLocaleString('id-ID')}</span>
			</div>
		</div>
	</div>

	<div class="card stat-card">
		<div class="card-body">
			<span class="stat-icon material-icons-outlined" style="color: #f59e0b; background: #fef3c7;">account_balance</span>
			<div class="stat-info">
				<span class="stat-label">OPD dgn Tanah Usaha</span>
				<span class="stat-value">{opdTanahUsaha}</span>
			</div>
		</div>
	</div>

	<div class="card stat-card">
		<div class="card-body">
			<span class="stat-icon material-icons-outlined" style="color: #8b5cf6; background: #ede9fe;">analytics</span>
			<div class="stat-info">
				<span class="stat-label">Total Aset BPKA</span>
				<span class="stat-value">{bpkaCount}</span>
			</div>
		</div>
	</div>
</div>

<style>
	.stats-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
		gap: var(--space-lg);
		margin-bottom: var(--space-xl);
	}
	.stat-card .card-body {
		display: flex;
		align-items: center;
		gap: var(--space-md);
		padding: var(--space-lg);
	}
	.stat-icon {
		font-size: 2rem;
		padding: var(--space-sm);
		border-radius: var(--radius-md);
	}
	.stat-info {
		display: flex;
		flex-direction: column;
	}
	.stat-label {
		font-size: var(--font-size-sm);
		color: var(--color-text-secondary);
		font-weight: 500;
	}
	.stat-value {
		font-size: 1.5rem;
		font-weight: 700;
		color: var(--color-text);
	}
</style>
