<script lang="ts">
	let { data } = $props<{
		data: Array<{ nama_opd: string; total: number }>
	}>();

	let canvas = $state<HTMLCanvasElement | undefined>();
	let chart: any;

	$effect(() => {
		if (data && data.length > 0 && canvas && typeof window !== 'undefined') {
			initChart();
		}
	});

	async function initChart() {
		if (chart) {
			chart.destroy();
		}

		// Dynamic import to prevent SSR issues with Chart.js
		const ChartModule = await import('chart.js/auto');
		const Chart = ChartModule.default;

		const labels = data.map(d => d.nama_opd);
		const values = data.map(d => d.total);

		chart = new Chart(canvas, {
			type: 'bar',
			data: {
				labels,
				datasets: [{
					label: 'Jumlah Tanah Usaha',
					data: values,
					backgroundColor: 'rgba(59, 130, 246, 0.5)',
					borderColor: 'rgb(59, 130, 246)',
					borderWidth: 1,
					borderRadius: 4
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				plugins: {
					legend: { display: false },
					title: {
						display: true,
						text: 'Distribusi Tanah Usaha per OPD',
						font: { size: 16, weight: 'bold' }
					}
				},
				scales: {
					y: { beginAtZero: true, ticks: { stepSize: 1 } },
					x: {
						ticks: {
							autoSkip: false,
							maxRotation: 45,
							minRotation: 45
						}
					}
				}
			}
		});
	}
</script>

<div class="card">
	<div class="card-body">
		<div class="chart-container" style="position: relative; height: 400px; width: 100%;">
			{#if data && data.length === 0}
				<div style="display:flex; justify-content:center; align-items:center; height:100%; color:var(--color-text-muted);">
					Tidak ada data chart
				</div>
			{:else}
				<canvas bind:this={canvas}></canvas>
			{/if}
		</div>
	</div>
</div>
