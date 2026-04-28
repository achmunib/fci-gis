<script lang="ts">
	import { getContext, onMount, onDestroy } from 'svelte';
	import type { Map } from 'leaflet';
	import type { GeoJSONCollection } from '$lib/types';
	
	let { geojson } = $props<{ geojson: GeoJSONCollection | null }>();

	const getMap = getContext<() => Map>('leaflet-map');
	const getL = getContext<() => any>('leaflet');
	
	let map: Map;
	let L: any;
	let searchControl: any;
	let searchLayer: any;
	
	let isInitialized = false;

	onMount(async () => {
		map = getMap();
		L = getL();
		
		// Import leaflet-search plugin
		await import('leaflet-search');
		await import('leaflet-search/dist/leaflet-search.src.css');
		
		searchLayer = L.layerGroup().addTo(map);
		isInitialized = true;
	});
	
	onDestroy(() => {
		if (searchControl && map) {
			map.removeControl(searchControl);
		}
		if (searchLayer && map) {
			map.removeLayer(searchLayer);
		}
	});

	$effect(() => {
		if (!isInitialized || !geojson || !L || !searchLayer) return;
		
		searchLayer.clearLayers();
		if (searchControl) {
			map.removeControl(searchControl);
		}

		// Add features to search layer
		geojson.features.forEach((feature: any) => {
			const polygon = L.geoJSON(feature);
			// We only need invisible polygons for search indexing, or we use the actual layer
			// leaflet-search will search through the features in this layer
			polygon.eachLayer((layer: any) => {
				layer.feature = feature;
			});
			searchLayer.addLayer(polygon);
		});

		searchControl = new L.Control.Search({
			layer: searchLayer,
			propertyName: 'nama', // our property is 'nama' not 'name' in GeoJSON
			initial: false,
			zoom: 16,
			marker: false,
			textPlaceholder: 'Cari aset tanah...'
		});

		searchControl.on('search:locationfound', function(e: any) {
			// e.layer is the matched layer
			if (e.layer._popup) {
				e.layer.openPopup();
			}
		});

		map.addControl(searchControl);
	});
</script>

<!-- SearchControl intercepts the GeoJSON and creates a search input on the map -->
