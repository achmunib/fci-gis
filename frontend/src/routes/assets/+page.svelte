<script lang="ts">
	import { onMount } from 'svelte';
	import { getAssets, deleteAsset } from '$lib/services/asset.service';
	import type { Outlet, PaginatedResponse } from '$lib/types';
	import { goto } from '$app/navigation';

	let assets = $state<Outlet[]>([]);
	let loading = $state(true);
	let error = $state<string | null>(null);

	// Pagination & Search
	let currentPage = $state(1);
	let totalPages = $state(1);
	let searchQuery = $state('');

	async function loadAssets(page = 1) {
		loading = true;
		error = null;
		try {
			const res = await getAssets({ page, q: searchQuery });
			assets = res.data;
			currentPage = res.current_page;
			totalPages = res.last_page;
		} catch (err: any) {
			error = err.message || 'Gagal memuat data aset';
		} finally {
			loading = false;
		}
	}

	onMount(() => {
		loadAssets();
	});

	function handleSearch(e: Event) {
		e.preventDefault();
		loadAssets(1);
	}

	async function handleDelete(id: number) {
		if (confirm('Apakah Anda yakin ingin menghapus aset ini?')) {
			try {
				await deleteAsset(id);
				loadAssets(currentPage);
			} catch (err: any) {
				alert(err.message || 'Gagal menghapus aset');
			}
		}
	}
</script>

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-xl);">
	<div>
		<h1 class="page-title" style="font-size: var(--font-size-2xl); font-weight: 700;">Data Aset Tanah</h1>
		<p class="page-desc" style="color: var(--color-text-muted);">Kelola data aset tanah (tambah, ubah, hapus).</p>
	</div>
	<a href="/assets/create" class="btn btn-primary">
		<span class="material-icons-outlined">add</span>
		Tambah Aset
	</a>
</div>

<div class="card">
	<div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
		<h2 class="card-title">Daftar Aset</h2>
		<form class="search-form" style="display: flex; gap: var(--space-sm);" onsubmit={handleSearch}>
			<input type="text" class="form-input" placeholder="Cari nama, ID, alamat..." bind:value={searchQuery} />
			<button type="submit" class="btn btn-secondary">Cari</button>
		</form>
	</div>
	<div class="card-body">
		{#if loading}
			<div style="display: flex; flex-direction: column; gap: var(--space-md); padding: var(--space-md);">
				<Skeleton height="40px" />
				<Skeleton height="40px" />
				<Skeleton height="40px" />
				<Skeleton height="40px" />
			</div>
		{:else if error}
			<div style="padding: var(--space-xl); text-align: center; color: var(--color-danger);">{error}</div>
		{:else if assets.length === 0}
			<EmptyState 
				title="Tidak ada aset" 
				description="Belum ada data aset tanah yang ditambahkan atau tidak cocok dengan pencarian Anda."
				icon="landscape"
			/>
		{:else}
			<div style="overflow-x: auto;">
				<table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
					<thead>
						<tr style="border-bottom: 1px solid var(--color-border);">
							<th style="padding: var(--space-md);">ID PEMDA</th>
							<th style="padding: var(--space-md);">Nama Aset</th>
							<th style="padding: var(--space-md);">OPD</th>
							<th style="padding: var(--space-md);">Luas (m²)</th>
							<th style="padding: var(--space-md);">Aksi</th>
						</tr>
					</thead>
					<tbody>
						{#each assets as asset (asset.id)}
							<tr style="border-bottom: 1px solid var(--color-border); transition: background 0.2s;">
								<td style="padding: var(--space-md);">{asset.id_pemda || '-'}</td>
								<td style="padding: var(--space-md); font-weight: 500;">{asset.name}</td>
								<td style="padding: var(--space-md);">{asset.nama_opd || '-'}</td>
								<td style="padding: var(--space-md);">{asset.luas || '-'}</td>
								<td style="padding: var(--space-md);">
									<div style="display: flex; gap: var(--space-sm);">
										<a href="/assets/{asset.id}" class="btn-icon" title="Detail" style="color: var(--color-primary); background: none; border: none; cursor: pointer;">
											<span class="material-icons-outlined">visibility</span>
										</a>
										<button onclick={() => handleDelete(asset.id)} class="btn-icon" title="Hapus" style="color: var(--color-danger); background: none; border: none; cursor: pointer;">
											<span class="material-icons-outlined">delete</span>
										</button>
									</div>
								</td>
							</tr>
						{/each}
					</tbody>
				</table>
			</div>

			<!-- Pagination -->
			{#if totalPages > 1}
				<div class="pagination" style="display: flex; justify-content: space-between; align-items: center; margin-top: var(--space-lg);">
					<span style="color: var(--color-text-muted); font-size: var(--font-size-sm);">Halaman {currentPage} dari {totalPages}</span>
					<div style="display: flex; gap: var(--space-sm);">
						<button 
							class="btn btn-secondary" 
							disabled={currentPage === 1}
							onclick={() => loadAssets(currentPage - 1)}
						>
							<span class="material-icons-outlined">chevron_left</span>
						</button>
						<button 
							class="btn btn-secondary" 
							disabled={currentPage === totalPages}
							onclick={() => loadAssets(currentPage + 1)}
						>
							<span class="material-icons-outlined">chevron_right</span>
						</button>
					</div>
				</div>
			{/if}
		{/if}
	</div>
</div>
