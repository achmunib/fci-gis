<script lang="ts">
	import { onMount, onDestroy, setContext } from 'svelte';
	import type { Map } from 'leaflet';
	
	let { children } = $props();

	let mapElement: HTMLElement;
	let map: Map | undefined;
	let L: any;
	
	let mapReady = $state(false);

	onMount(async () => {
		// Dynamic import for Leaflet to prevent SSR issues (window is not defined)
		L = (await import('leaflet')).default;
		await import('leaflet/dist/leaflet.css');

		map = L.map(mapElement).setView([-7.633, 112.900], 13);
		
		const layer_osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; OpenStreetMap contributors'
		});
		
		const layer_satelit = L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
			attribution: '&copy; Esri',
			maxZoom: 18,
		});

		layer_osm.addTo(map);

		// Basic layer control for base maps
		L.control.layers({
			"OpenStreetMap": layer_osm,
			"Satelit": layer_satelit
		}).addTo(map);

		// Expose map instance and Leaflet library to child components
		setContext('leaflet-map', () => map);
		setContext('leaflet', () => L);
		
		mapReady = true;
	});

	onDestroy(() => {
		if (map) {
			map.remove();
		}
	});
</script>

<div class="map-wrapper">
	<div bind:this={mapElement} class="map-container"></div>
	{#if mapReady}
		{@render children?.()}
	{/if}
</div>

<style>
	.map-wrapper {
		position: relative;
		width: 100%;
		height: 100%;
		min-height: 500px;
		display: flex;
		flex-direction: column;
		flex: 1;
		border-radius: var(--radius-md);
		overflow: hidden;
		border: 1px solid var(--color-border);
		z-index: 1;
	}
	.map-container {
		width: 100%;
		height: 100%;
		flex: 1;
		z-index: 1;
	}
</style>
