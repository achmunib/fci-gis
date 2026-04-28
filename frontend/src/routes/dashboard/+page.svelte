<script lang="ts">
	import { onMount } from 'svelte';
	import { MapContainer, LayerControl, SearchControl } from '$lib/components/map';
	import StatsCards from '$lib/components/dashboard/StatsCards.svelte';
	import BarChart from '$lib/components/dashboard/BarChart.svelte';
	import { getAssetsGeoJSON } from '$lib/services/asset.service';
	import { getDashboardStats } from '$lib/services/dashboard.service';
	import type { GeoJSONCollection } from '$lib/types';
	import type { DashboardStats } from '$lib/services/dashboard.service';

	let activeTab = $state<'map' | 'stats' | 'download'>('map');
	
	let geojson = $state<GeoJSONCollection | null>(null);
	let stats = $state<DashboardStats | null>(null);
	
	let loading = $state(true);
	let error = $state<string | null>(null);

	onMount(async () => {
		try {
			const [geoRes, statsRes] = await Promise.all([
				getAssetsGeoJSON(),
				getDashboardStats()
			]);
			geojson = geoRes.data;
			stats = statsRes;
		} catch (err: any) {
			error = err.message || 'Gagal memuat data dashboard';
		} finally {
			loading = false;
		}
	});
</script>

<div class="dashboard-header">
	<h1 class="page-title">Dashboard</h1>
	<p class="page-desc">Peta sebaran dan statistik aset tanah Pemerintah Daerah.</p>
</div>

<!-- Tabs Navigation -->
<div class="tabs">
	<button class="tab-btn" class:active={activeTab === 'map'} onclick={() => activeTab = 'map'}>
		<span class="material-icons-outlined">map</span> Peta Sebaran
	</button>
	<button class="tab-btn" class:active={activeTab === 'stats'} onclick={() => activeTab = 'stats'}>
		<span class="material-icons-outlined">analytics</span> Data Statistik
	</button>
	<button class="tab-btn" class:active={activeTab === 'download'} onclick={() => activeTab = 'download'}>
		<span class="material-icons-outlined">download</span> Unduh Laporan
	</button>
</div>

{#if loading}
	<div class="loading-state">
		<span class="material-icons-outlined spinner">refresh</span>
		<span>Memuat data dashboard...</span>
	</div>
{:else if error}
	<div class="error-state">
		<span class="material-icons-outlined">error_outline</span>
		<span>{error}</span>
	</div>
{:else}
	<!-- Tab 1: Peta -->
	{#if activeTab === 'map'}
		<div class="card map-card">
			<div class="card-body p-0">
				<MapContainer>
					<LayerControl {geojson} />
					<SearchControl {geojson} />
				</MapContainer>
			</div>
		</div>
	{/if}

	<!-- Tab 2: Statistik -->
	{#if activeTab === 'stats' && stats}
		<StatsCards {stats} />
		<BarChart data={stats.chart_data} />
	{/if}

	<!-- Tab 3: Download -->
	{#if activeTab === 'download'}
		<div class="card">
			<div class="card-body" style="text-align: center; padding: var(--space-2xl);">
				<span class="material-icons-outlined" style="font-size: 4rem; color: var(--color-primary); margin-bottom: 1rem;">picture_as_pdf</span>
				<h2>Laporan Aset Tanah</h2>
				<p style="color: var(--color-text-muted); margin-bottom: 1.5rem;">Unduh data keseluruhan aset tanah beserta detail OPD dalam format PDF/Excel.</p>
				<button class="btn btn-primary"><span class="material-icons-outlined">download</span> Download PDF</button>
				<button class="btn btn-secondary" style="margin-left: 10px;"><span class="material-icons-outlined">download</span> Download Excel</button>
			</div>
		</div>
	{/if}
{/if}

<style>
	.dashboard-header {
		margin-bottom: var(--space-xl);
	}
	.page-title {
		font-size: var(--font-size-2xl);
		font-weight: 700;
		color: var(--color-text);
		margin-bottom: var(--space-xs);
	}
	.page-desc {
		color: var(--color-text-muted);
	}
	
	.tabs {
		display: flex;
		gap: var(--space-sm);
		margin-bottom: var(--space-xl);
		border-bottom: 1px solid var(--color-border);
		padding-bottom: 1px;
	}
	.tab-btn {
		background: none;
		border: none;
		padding: var(--space-sm) var(--space-lg);
		font-size: var(--font-size-md);
		font-weight: 500;
		color: var(--color-text-muted);
		cursor: pointer;
		display: flex;
		align-items: center;
		gap: 8px;
		border-bottom: 2px solid transparent;
		transition: all 0.2s;
	}
	.tab-btn:hover {
		color: var(--color-text);
		background: var(--color-bg-alt);
	}
	.tab-btn.active {
		color: var(--color-primary);
		border-bottom-color: var(--color-primary);
	}

	.map-card {
		height: calc(100vh - 250px);
		display: flex;
		flex-direction: column;
	}
	.p-0 {
		padding: 0 !important;
		display: flex;
		flex-direction: column;
		height: 100%;
	}
	.loading-state, .error-state {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		height: 100%;
		min-height: 400px;
		color: var(--color-text-muted);
		gap: var(--space-md);
	}
	.error-state {
		color: var(--color-danger);
	}
	.spinner {
		animation: spin 1s linear infinite;
		font-size: 2rem;
	}
	@keyframes spin {
		100% { transform: rotate(360deg); }
	}
</style>
