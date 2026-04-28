<script lang="ts">
	/* eslint-disable svelte/no-navigation-without-resolve */
	import { page } from '$app/state';
	import { onMount, onDestroy } from 'svelte';
	import { getAsset, deleteAsset } from '$lib/services/asset.service';
	import type { Outlet, User } from '$lib/types';
	import { goto } from '$app/navigation';
	import { base } from '$app/paths';
	import { toast } from '$lib/stores/toast';
	import { getUser } from '$lib/services/auth.service';

	let id = $derived(Number(page.params.id));
	let asset = $state<Outlet | null>(null);
	let currentUser = $state<User | null>(null);
	let loading = $state(true);
	let error = $state<string | null>(null);

	let mapElement = $state<HTMLElement>();
	let map: import('leaflet').Map | undefined;

	let canEdit = $derived(currentUser && asset && currentUser.id === asset.creator_id);

	onMount(async () => {
		try {
			const [fetchedAsset, fetchedUser] = await Promise.all([
				getAsset(id),
				getUser()
			]);
			asset = fetchedAsset;
			currentUser = fetchedUser;
			if (asset) {
				// Wait for DOM to update
				setTimeout(() => initMap(), 100);
			}
		} catch (err: unknown) {
			error = err instanceof Error ? err.message : 'Gagal memuat detail aset';
			toast.error(error);
		} finally {
			loading = false;
		}
	});

	onDestroy(() => {
		if (map) map.remove();
	});

	async function initMap() {
		if (!asset || !mapElement || typeof window === 'undefined') return;
		
		const L = (await import('leaflet')).default;
		await import('leaflet/dist/leaflet.css');
		
		const lat = parseFloat(asset.latitude as string) || -7.633;
		const lng = parseFloat(asset.longitude as string) || 112.900;

		map = L.map(mapElement).setView([lat, lng], 16);
		L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
			attribution: '&copy; Esri',
			maxZoom: 18
		}).addTo(map);

		if (asset.polygon) {
			try {
				const latlngs = JSON.parse(asset.polygon);
				const polygon = L.polygon(latlngs, { color: '#eab308', weight: 2, fillOpacity: 0.5 }).addTo(map);
				map.fitBounds(polygon.getBounds(), { padding: [20, 20] });
			} catch {
				console.error('Invalid polygon JSON');
			}
		}
	}

	async function handleDelete() {
		if (confirm('Yakin ingin menghapus aset ini? Tindakan ini tidak dapat dibatalkan.')) {
			try {
				await deleteAsset(id);
				toast.success('Aset berhasil dihapus');
				goto(`${base}/assets`);
			} catch (err: unknown) {
				toast.error(err instanceof Error ? err.message : 'Gagal menghapus aset');
			}
		}
	}
</script>

{#if loading}
	<div style="padding: var(--space-xl); text-align: center; color: var(--color-text-muted);">Memuat detail aset...</div>
{:else if error}
	<div style="padding: var(--space-xl); text-align: center; color: var(--color-danger);">{error}</div>
{:else if asset}
	<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-xl);">
		<div>
			<a href="{base}/assets" style="color: var(--color-primary); text-decoration: none; display: flex; align-items: center; gap: 5px; margin-bottom: 10px; font-size: 14px;">
				<span class="material-icons-outlined" style="font-size: 16px;">arrow_back</span> Kembali
			</a>
			<h1 class="page-title" style="font-size: var(--font-size-2xl); font-weight: 700;">{asset.name}</h1>
			<p class="page-desc" style="color: var(--color-text-muted);">ID Pemda: {asset.id_pemda || '-'}</p>
		</div>
		<div style="display: flex; gap: var(--space-sm);">
			{#if canEdit}
				<a href="{base}/assets/{id}/edit" class="btn btn-primary">
					<span class="material-icons-outlined">edit</span> Edit Aset
				</a>
				<button class="btn btn-danger" onclick={handleDelete}>
					<span class="material-icons-outlined">delete</span> Hapus
				</button>
			{/if}
		</div>
	</div>

	<div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-lg); align-items: start;">
		<div style="display: flex; flex-direction: column; gap: var(--space-lg);">
			<!-- Informasi Dasar -->
			<div class="card">
				<div class="card-header"><h2 class="card-title">Informasi Dasar</h2></div>
				<div class="card-body">
					<table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
						<tbody>
							{#each [
								['OPD', asset.nama_opd || '-'],
								['Kode Barang', asset.kode_barang || '-'],
								['Register', asset.register || '-'],
								['Luas', (asset.luas || '-') + ' m²'],
								['Tahun Pengadaan', asset.tahun_pengadaan || '-'],
								['Penggunaan', asset.penggunaan || '-'],
								['Harga', asset.harga ? 'Rp ' + Number(asset.harga).toLocaleString('id-ID') : '-'],
								['Alamat', asset.address || '-'],
								['Keterangan', asset.keterangan || '-']
							] as [label, val] (label)}
								<tr style="border-bottom: 1px solid var(--color-border);">
									<td style="padding: 12px 0; color: var(--color-text-secondary); width: 140px;">{label}</td>
									<td style="padding: 12px 0; font-weight: 500;">{val}</td>
								</tr>
							{/each}
						</tbody>
					</table>
				</div>
			</div>

			<!-- Sertifikat -->
			<div class="card">
				<div class="card-header"><h2 class="card-title">Sertifikat & Dokumen</h2></div>
				<div class="card-body">
					<table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
						<tbody>
							<tr style="border-bottom: 1px solid var(--color-border);">
								<td style="padding: 12px 0; color: var(--color-text-secondary); width: 140px;">Nomor Sertifikat</td>
								<td style="padding: 12px 0; font-weight: 500;">{asset.nomor_sertifikat || '-'}</td>
							</tr>
							<tr style="border-bottom: 1px solid var(--color-border);">
								<td style="padding: 12px 0; color: var(--color-text-secondary);">Tanggal</td>
								<td style="padding: 12px 0; font-weight: 500;">{asset.tanggal_sertifikat || '-'}</td>
							</tr>
							<tr style="border-bottom: 1px solid var(--color-border);">
								<td style="padding: 12px 0; color: var(--color-text-secondary);">Hak</td>
								<td style="padding: 12px 0; font-weight: 500;">{asset.hak || '-'}</td>
							</tr>
						</tbody>
					</table>
					{#if asset.file_path}
						<div style="margin-top: var(--space-md);">
							<a href="/storage/{asset.file_path}" target="_blank" class="btn btn-secondary" style="width: 100%; justify-content: center;">
								<span class="material-icons-outlined">picture_as_pdf</span> Lihat / Download PDF
							</a>
						</div>
					{/if}
				</div>
			</div>
		</div>

		<div style="display: flex; flex-direction: column; gap: var(--space-lg);">
			<!-- Peta -->
			<div class="card">
				<div class="card-header"><h2 class="card-title">Lokasi Peta</h2></div>
				<div class="card-body p-0">
					<div bind:this={mapElement} style="height: 350px; width: 100%; background: var(--color-bg-alt); border-radius: 0 0 var(--radius-md) var(--radius-md); z-index: 1;"></div>
				</div>
			</div>

			<!-- Foto -->
			<div class="card">
				<div class="card-header"><h2 class="card-title">Foto Aset</h2></div>
				<div class="card-body" style="text-align: center;">
					{#if asset.file_photo}
						<img src="/storage/{asset.file_photo}" alt="Foto {asset.name}" style="max-width: 100%; max-height: 400px; border-radius: var(--radius-sm); object-fit: contain;" />
					{:else}
						<div style="padding: var(--space-xl); color: var(--color-text-muted); background: var(--color-bg-alt); border-radius: var(--radius-sm);">
							<span class="material-icons-outlined" style="font-size: 3rem; margin-bottom: 10px;">image_not_supported</span>
							<p>Tidak ada foto yang diunggah.</p>
						</div>
					{/if}
				</div>
			</div>
		</div>
	</div>
{/if}

<style>
	.btn-danger {
		background-color: var(--color-danger);
		color: white;
		border: 1px solid var(--color-danger);
	}
	.btn-danger:hover {
		background-color: #dc2626;
	}
	.p-0 { padding: 0; }
</style>
