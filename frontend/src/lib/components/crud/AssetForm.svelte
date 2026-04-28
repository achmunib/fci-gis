<script lang="ts">
	import { onMount, onDestroy } from 'svelte';
	import { getAllOpds } from '$lib/services/opd.service';
	import type { Opd } from '$lib/types';

	let { mode = 'create', initialData = null, onsubmit, loading = false } = $props<{
		mode?: 'create' | 'edit';
		initialData?: any;
		onsubmit: (formData: FormData) => void;
		loading?: boolean;
	}>();

	// Form State
	let formData = $state<Record<string, any>>({
		id_pemda: '',
		name: '',
		kode_barang: '',
		register: '',
		luas: '',
		tahun_pengadaan: '',
		penggunaan: '',
		harga: '',
		address: '',
		keterangan: '',
		nomor_sertifikat: '',
		tanggal_sertifikat: '',
		hak: '',
		id_opd: '',
		latitude: '',
		longitude: '',
		polygon: ''
	});

	$effect(() => {
		if (initialData) {
			Object.keys(formData).forEach(key => {
				if (initialData[key] !== undefined) {
					formData[key] = initialData[key] || '';
				}
			});
		}
	});

	let file_path = $state<FileList | null>(null);
	let file_photo = $state<FileList | null>(null);

	let opds = $state<Opd[]>([]);
	let opdLoading = $state(true);

	// Map State
	let mapElement: HTMLElement;
	let map: any;
	let L: any;
	let drawnItems: any;
	let drawControl: any;

	onMount(async () => {
		try {
			opds = await getAllOpds();
		} catch (err) {
			console.error('Failed to load OPDs', err);
		} finally {
			opdLoading = false;
		}

		// Init Map
		if (typeof window !== 'undefined') {
			L = (await import('leaflet')).default;
			await import('leaflet/dist/leaflet.css');
			
			// Load leaflet-draw
			await import('leaflet-draw');
			await import('leaflet-draw/dist/leaflet.draw.css');

			const lat = parseFloat(formData.latitude) || -7.633;
			const lng = parseFloat(formData.longitude) || 112.900;

			map = L.map(mapElement).setView([lat, lng], 14);

			L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
				attribution: '&copy; Esri'
			}).addTo(map);

			drawnItems = new L.FeatureGroup();
			map.addLayer(drawnItems);

			if (formData.polygon) {
				try {
					const latlngs = JSON.parse(formData.polygon);
					L.polygon(latlngs, { color: 'red' }).addTo(drawnItems);
				} catch (e) {
					console.error('Invalid initial polygon JSON');
				}
			}

			drawControl = new L.Control.Draw({
				draw: {
					polyline: true,
					rectangle: true,
					circle: true,
					marker: true,
					circlemarker: true
				},
				edit: {
					featureGroup: drawnItems
				}
			});

			map.addControl(drawControl);

			map.on('draw:created', function (e: any) {
				const layer = e.layer;
				const latLng = layer.getLatLngs ? layer.getLatLngs() : [layer.getLatLng()];
				formData.polygon = JSON.stringify(latLng);
				drawnItems.addLayer(layer);
			});

			map.on('draw:edited', function (e: any) {
				const layers = e.layers;
				layers.eachLayer(function (layer: any) {
					const latLng = layer.getLatLngs ? layer.getLatLngs() : [layer.getLatLng()];
					formData.polygon = JSON.stringify(latLng);
				});
			});

			map.on('draw:deleted', function () {
				formData.polygon = '';
			});
		}
	});

	onDestroy(() => {
		if (map) map.remove();
	});

	function handleSubmit(e: Event) {
		e.preventDefault();
		const data = new FormData();
		
		Object.entries(formData).forEach(([key, value]) => {
			if (value !== null && value !== undefined && value !== '') {
				data.append(key, String(value));
			}
		});

		if (file_path && file_path.length > 0) {
			data.append('file_path', file_path[0]);
		}
		if (file_photo && file_photo.length > 0) {
			data.append('file_photo', file_photo[0]);
		}

		onsubmit(data);
	}
</script>

<form class="asset-form" onsubmit={handleSubmit}>
	<div class="card">
		<div class="card-header">
			<h2 class="card-title">Informasi Dasar</h2>
		</div>
		<div class="card-body">
			<div class="form-grid">
				<div class="form-group">
					<label for="name" class="form-label">Nama Aset <span class="text-danger">*</span></label>
					<input id="name" type="text" class="form-input" bind:value={formData.name} required />
				</div>
				<div class="form-group">
					<label for="id_pemda" class="form-label">ID Pemda</label>
					<input id="id_pemda" type="text" class="form-input" bind:value={formData.id_pemda} />
				</div>
				<div class="form-group">
					<label for="kode_barang" class="form-label">Kode Barang</label>
					<input id="kode_barang" type="text" class="form-input" bind:value={formData.kode_barang} />
				</div>
				<div class="form-group">
					<label for="id_opd" class="form-label">OPD</label>
					<select id="id_opd" class="form-input" bind:value={formData.id_opd}>
						<option value="">-- Pilih OPD --</option>
						{#each opds as opd}
							<option value={opd.id_opd}>{opd.nama_opd}</option>
						{/each}
					</select>
				</div>
				<div class="form-group">
					<label for="luas" class="form-label">Luas (m²)</label>
					<input id="luas" type="number" class="form-input" bind:value={formData.luas} />
				</div>
				<div class="form-group">
					<label for="harga" class="form-label">Harga (Rp)</label>
					<input id="harga" type="number" class="form-input" bind:value={formData.harga} />
				</div>
				<div class="form-group" style="grid-column: 1 / -1;">
					<label for="address" class="form-label">Alamat</label>
					<textarea id="address" class="form-input" rows="3" bind:value={formData.address}></textarea>
				</div>
			</div>
		</div>
	</div>

	<div class="card mt-lg">
		<div class="card-header">
			<h2 class="card-title">Dokumen Sertifikat</h2>
		</div>
		<div class="card-body">
			<div class="form-grid">
				<div class="form-group">
					<label for="nomor_sertifikat" class="form-label">Nomor Sertifikat</label>
					<input id="nomor_sertifikat" type="text" class="form-input" bind:value={formData.nomor_sertifikat} />
				</div>
				<div class="form-group">
					<label for="tanggal_sertifikat" class="form-label">Tanggal Sertifikat</label>
					<input id="tanggal_sertifikat" type="date" class="form-input" bind:value={formData.tanggal_sertifikat} />
				</div>
				<div class="form-group">
					<label for="hak" class="form-label">Hak</label>
					<input id="hak" type="text" class="form-input" bind:value={formData.hak} />
				</div>
				<div class="form-group">
					<label for="file_path" class="form-label">Upload Sertifikat (PDF)</label>
					<input id="file_path" type="file" accept=".pdf" class="form-input" bind:files={file_path} />
				</div>
				<div class="form-group">
					<label for="file_photo" class="form-label">Upload Foto (JPG/PNG)</label>
					<input id="file_photo" type="file" accept="image/*" class="form-input" bind:files={file_photo} />
				</div>
			</div>
		</div>
	</div>

	<div class="card mt-lg">
		<div class="card-header">
			<h2 class="card-title">Lokasi & Polygon (Peta)</h2>
		</div>
		<div class="card-body p-0">
			<div bind:this={mapElement} class="form-map" style="height: 400px; width: 100%;"></div>
		</div>
		<div class="card-body">
			<div class="form-group" style="margin-top: var(--space-md);">
				<label for="polygon" class="form-label">GeoJSON Polygon (Auto-filled by Map)</label>
				<textarea id="polygon" class="form-input" rows="3" bind:value={formData.polygon}></textarea>
			</div>
		</div>
	</div>

	<div class="form-actions mt-xl">
		<button type="submit" class="btn btn-primary" disabled={loading}>
			{#if loading}
				<span class="material-icons-outlined" style="animation: spin 1s linear infinite;">refresh</span>
				Menyimpan...
			{:else}
				{mode === 'create' ? 'Simpan Aset' : 'Perbarui Aset'}
			{/if}
		</button>
		<a href="/assets" class="btn btn-secondary">Batal</a>
	</div>
</form>

<style>
	.form-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
		gap: var(--space-md);
	}
	.mt-lg { margin-top: var(--space-lg); }
	.mt-xl { margin-top: var(--space-xl); }
	.text-danger { color: var(--color-danger); }
	.form-actions {
		display: flex;
		gap: var(--space-md);
		justify-content: flex-end;
	}
	.p-0 { padding: 0; }
</style>
