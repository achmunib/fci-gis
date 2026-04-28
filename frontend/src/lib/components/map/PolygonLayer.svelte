<script lang="ts">
	import { getContext, onMount, onDestroy } from 'svelte';
	import type { Map } from 'leaflet';
	import type { GeoJSONCollection } from '$lib/types';
	
	let { geojson } = $props<{ geojson: GeoJSONCollection | null }>();

	const getMap = getContext<() => Map>('leaflet-map');
	const getL = getContext<() => any>('leaflet');
	
	let map: Map;
	let L: any;
	let layerGroup: any;
	
	onMount(() => {
		map = getMap();
		L = getL();
		layerGroup = L.layerGroup().addTo(map);
	});
	
	onDestroy(() => {
		if (layerGroup && map) {
			map.removeLayer(layerGroup);
		}
	});
	
	// Update layer when geojson prop changes
	$effect(() => {
		if (!layerGroup || !L) return;
		
		layerGroup.clearLayers();
		
		if (geojson && geojson.features && geojson.features.length > 0) {
			const gLayer = L.geoJSON(geojson, {
				style: function (feature: any) {
					return {
						color: feature.properties.color || '#eab308',
						weight: 2,
						opacity: 1,
						fillOpacity: 0.5
					};
				},
				onEachFeature: function (feature: any, layer: any) {
					const p = feature.properties;
					const photoUrl = p.photo ? `/storage/${p.photo}` : null;
					
					let popupContent = `
						<div class="map-popup" style="font-family: var(--font-family);">
							<h4 style="margin:0 0 5px 0;font-size:14px;color:var(--color-primary);">${p.nama}</h4>
							<table style="width:100%;font-size:12px;margin-bottom:10px;border-collapse:collapse;">
								<tr><td style="color:var(--color-text-secondary);padding-right:10px;padding-bottom:4px;">OPD</td><td style="padding-bottom:4px;"><b>${p.opd}</b></td></tr>
								<tr><td style="color:var(--color-text-secondary);padding-right:10px;padding-bottom:4px;">Jenis</td><td style="padding-bottom:4px;"><b>${p.jenis || '-'}</b></td></tr>
								<tr><td style="color:var(--color-text-secondary);padding-right:10px;">Luas</td><td><b>${p.luas ? p.luas + ' m²' : '-'}</b></td></tr>
							</table>
					`;
					
					if (photoUrl) {
						popupContent += `<img src="${photoUrl}" style="width:100%;border-radius:4px;margin-bottom:10px;max-height:150px;object-fit:cover;" alt="Foto Aset" onerror="this.style.display='none'">`;
					}
					
					popupContent += `
							<a href="/assets/${p.id}" style="display:block;text-align:center;background:var(--color-primary);color:white;padding:6px;border-radius:4px;text-decoration:none;font-size:12px;font-weight:500;">Lihat Detail</a>
						</div>
					`;
					
					layer.bindPopup(popupContent, { minWidth: 220 });
				}
			});
			
			gLayer.addTo(layerGroup);
			
			// Auto-zoom to fit all polygons
			map.fitBounds(gLayer.getBounds(), { padding: [20, 20], maxZoom: 16 });
		}
	});
</script>
