<script lang="ts">
	import { getContext, onMount, onDestroy } from 'svelte';
	import type { Map } from 'leaflet';
	import type { GeoJSONCollection } from '$lib/types';
	
	let { geojson } = $props<{ geojson: GeoJSONCollection | null }>();

	const getMap = getContext<() => Map>('leaflet-map');
	const getL = getContext<() => any>('leaflet');
	
	let map: Map;
	let L: any;
	
	let layerControl: any;
	let currentMode = $state<'opd' | 'nama'>('opd');
	
	let overlayMapsOPD: Record<string, any> = {};
	let overlayMapsName: Record<string, any> = {};
	let allLayers: any[] = [];
	
	let isInitialized = false;

	onMount(() => {
		map = getMap();
		L = getL();
		isInitialized = true;
	});
	
	onDestroy(() => {
		if (layerControl && map) {
			map.removeControl(layerControl);
		}
		clearAllLayers();
	});

	function clearAllLayers() {
		allLayers.forEach(layer => map.removeLayer(layer));
		allLayers = [];
		overlayMapsOPD = {};
		overlayMapsName = {};
	}

	function createPopupContent(p: any) {
		const photoUrl = p.photo ? `/storage/${p.photo}` : null;
		let content = `
			<div class="map-popup" style="font-family: var(--font-family);">
				<h4 style="margin:0 0 5px 0;font-size:14px;color:var(--color-primary);">${p.nama}</h4>
				<table style="width:100%;font-size:12px;margin-bottom:10px;border-collapse:collapse;">
					<tr><td style="color:var(--color-text-secondary);padding-right:10px;padding-bottom:4px;">OPD</td><td style="padding-bottom:4px;"><b>${p.opd}</b></td></tr>
					<tr><td style="color:var(--color-text-secondary);padding-right:10px;padding-bottom:4px;">Jenis</td><td style="padding-bottom:4px;"><b>${p.jenis || '-'}</b></td></tr>
					<tr><td style="color:var(--color-text-secondary);padding-right:10px;">Luas</td><td><b>${p.luas ? p.luas + ' m²' : '-'}</b></td></tr>
				</table>
		`;
		if (photoUrl) {
			content += `<img src="${photoUrl}" style="width:100%;border-radius:4px;margin-bottom:10px;max-height:150px;object-fit:cover;" alt="Foto Aset" onerror="this.style.display='none'">`;
		}
		content += `<a href="/assets/${p.id}" style="display:block;text-align:center;background:var(--color-primary);color:white;padding:6px;border-radius:4px;text-decoration:none;font-size:12px;font-weight:500;">Lihat Detail</a></div>`;
		return content;
	}

	function buildLayers() {
		if (!L || !map || !geojson || !geojson.features) return;
		
		clearAllLayers();

		// Create layer groups for each OPD and Name
		geojson.features.forEach((feature: any) => {
			const p = feature.properties;
			const opdName = p.opd || 'Unknown OPD';
			const outletName = p.nama || 'Unknown Name';

			if (!overlayMapsOPD[opdName]) {
				overlayMapsOPD[opdName] = L.layerGroup();
			}
			if (!overlayMapsName[outletName]) {
				overlayMapsName[outletName] = L.layerGroup();
			}

			const polygon = L.geoJSON(feature, {
				style: {
					color: p.color || '#eab308',
					weight: 2,
					opacity: 1,
					fillOpacity: 0.5,
					transition: 'all 0.2s ease'
				},
				onEachFeature: (f: any, layer: any) => {
					layer.on({
						mouseover: (e: any) => {
							const target = e.target;
							target.setStyle({
								weight: 4,
								fillOpacity: 0.8
							});
							if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
								target.bringToFront();
							}
						},
						mouseout: (e: any) => {
							polygon.resetStyle(e.target);
						}
					});
				}
			});
			
			polygon.bindPopup(createPopupContent(p), { minWidth: 220 });
			
			// Attach feature property for search plugin
			polygon.eachLayer((layer: any) => {
				layer.feature = feature;
			});

			overlayMapsOPD[opdName].addLayer(polygon);
			overlayMapsName[outletName].addLayer(polygon);
			allLayers.push(polygon);
		});
		
		// Add OPD layers by default
		Object.values(overlayMapsOPD).forEach(layer => map.addLayer(layer as any));
		
		updateControl();
	}

	function updateControl() {
		if (layerControl) {
			map.removeControl(layerControl);
		}

		const overlays = currentMode === 'opd' ? overlayMapsOPD : overlayMapsName;
		
		// Ensure current overlays are on map, others are off
		if (currentMode === 'opd') {
			Object.values(overlayMapsName).forEach(layer => map.removeLayer(layer as any));
			Object.values(overlayMapsOPD).forEach(layer => map.addLayer(layer as any));
		} else {
			Object.values(overlayMapsOPD).forEach(layer => map.removeLayer(layer as any));
			Object.values(overlayMapsName).forEach(layer => map.addLayer(layer as any));
		}

		layerControl = L.control.layers(undefined, overlays, { collapsed: false }).addTo(map);
		
		// Inject Select All & Mode Toggle into Leaflet Control DOM
		const container = layerControl.getContainer();
		if (container) {
			const form = container.querySelector('.leaflet-control-layers-list');
			if (form) {
				const headerDiv = L.DomUtil.create('div', 'layer-control-header');
				headerDiv.style.padding = '5px 10px';
				headerDiv.style.borderBottom = '1px solid #ccc';
				headerDiv.style.marginBottom = '5px';
				
				// Mode Toggle
				const select = document.createElement('select');
				select.style.width = '100%';
				select.style.marginBottom = '8px';
				select.style.padding = '4px';
				select.innerHTML = `
					<option value="opd" ${currentMode === 'opd' ? 'selected' : ''}>Grup: OPD</option>
					<option value="nama" ${currentMode === 'nama' ? 'selected' : ''}>Grup: Nama Aset</option>
				`;
				select.onchange = (e: any) => {
					currentMode = e.target.value;
					updateControl();
				};
				
				// Select All
				const selectAllLabel = document.createElement('label');
				selectAllLabel.style.display = 'flex';
				selectAllLabel.style.alignItems = 'center';
				selectAllLabel.style.gap = '5px';
				selectAllLabel.style.fontWeight = 'bold';
				selectAllLabel.style.cursor = 'pointer';
				
				const selectAllCheckbox = document.createElement('input');
				selectAllCheckbox.type = 'checkbox';
				selectAllCheckbox.checked = true;
				selectAllCheckbox.onchange = (e: any) => {
					const checked = e.target.checked;
					const currentLayers = currentMode === 'opd' ? overlayMapsOPD : overlayMapsName;
					Object.values(currentLayers).forEach(layer => {
						if (checked) map.addLayer(layer as any);
						else map.removeLayer(layer as any);
					});
					// Trigger control update visually
					updateControl();
				};
				
				selectAllLabel.appendChild(selectAllCheckbox);
				selectAllLabel.appendChild(document.createTextNode('Pilih Semua'));
				
				headerDiv.appendChild(select);
				headerDiv.appendChild(selectAllLabel);
				
				form.insertBefore(headerDiv, form.firstChild);
			}
		}
	}

	$effect(() => {
		if (isInitialized && geojson) {
			buildLayers();
		}
	});
</script>

<!-- LayerControl intercept the GeoJSON and creates Leaflet Controls, so no DOM needed -->
