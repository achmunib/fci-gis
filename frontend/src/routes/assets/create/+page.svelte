<script lang="ts">
	import AssetForm from '$lib/components/crud/AssetForm.svelte';
	import { createAsset } from '$lib/services/asset.service';
	import { goto } from '$app/navigation';
	import { toast } from '$lib/stores/toast';

	let loading = $state(false);
	let error = $state<string | null>(null);

	async function handleSubmit(formData: FormData) {
		loading = true;
		error = null;
		try {
			await createAsset(formData);
			toast.success('Aset berhasil ditambahkan');
			goto('/assets');
		} catch (err: any) {
			error = err.message || 'Terjadi kesalahan saat menyimpan aset';
			if (err.errors) {
				const msgs = Object.values(err.errors).flat().join('\n');
				error += ':\n' + msgs;
			}
			toast.error(error);
		} finally {
			loading = false;
		}
	}
</script>

<div class="page-header" style="margin-bottom: var(--space-xl);">
	<h1 class="page-title" style="font-size: var(--font-size-2xl); font-weight: 700;">Tambah Aset Tanah</h1>
	<p class="page-desc" style="color: var(--color-text-muted);">Isi formulir di bawah ini untuk menambahkan data aset baru.</p>
</div>

<AssetForm mode="create" onsubmit={handleSubmit} {loading} />
