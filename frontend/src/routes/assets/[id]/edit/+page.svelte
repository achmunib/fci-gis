<script lang="ts">
	import { page } from '$app/state';
	import { onMount } from 'svelte';
	import AssetForm from '$lib/components/crud/AssetForm.svelte';
	import { getAsset, updateAsset } from '$lib/services/asset.service';
	import { goto } from '$app/navigation';
	import type { Outlet } from '$lib/types';
	import { toast } from '$lib/stores/toast';

	let id = $derived(Number(page.params.id));
	let asset = $state<Outlet | null>(null);
	let loading = $state(true);
	let saving = $state(false);

	onMount(async () => {
		try {
			asset = await getAsset(id);
		} catch (err) {
			toast.error('Gagal memuat data aset');
			goto('/assets');
		} finally {
			loading = false;
		}
	});

	async function handleSubmit(formData: FormData) {
		saving = true;
		try {
			await updateAsset(id, formData);
			toast.success('Aset berhasil diperbarui');
			goto(`/assets/${id}`);
		} catch (err: any) {
			let errorMsg = err.message || 'Gagal memperbarui aset';
			if (err.errors) {
				const msgs = Object.values(err.errors).flat().join('\n');
				errorMsg += ':\n' + msgs;
			}
			toast.error(errorMsg);
		} finally {
			saving = false;
		}
	}
</script>

{#if loading}
	<div style="padding: var(--space-xl); text-align: center; color: var(--color-text-muted);">Memuat data aset...</div>
{:else if asset}
	<div class="page-header" style="margin-bottom: var(--space-xl);">
		<a href="/assets/{id}" style="color: var(--color-primary); text-decoration: none; display: flex; align-items: center; gap: 5px; margin-bottom: 10px; font-size: 14px;">
			<span class="material-icons-outlined" style="font-size: 16px;">arrow_back</span> Kembali ke Detail
		</a>
		<h1 class="page-title" style="font-size: var(--font-size-2xl); font-weight: 700;">Edit Aset: {asset.name}</h1>
	</div>

	<AssetForm mode="edit" initialData={asset} onsubmit={handleSubmit} loading={saving} />
{/if}
