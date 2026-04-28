<script lang="ts">
	let { mode = 'create', initialData = null, onsubmit, loading = false, oncancel } = $props<{
		mode?: 'create' | 'edit';
		initialData?: any;
		onsubmit: (data: any) => void;
		loading?: boolean;
		oncancel: () => void;
	}>();

	let formData = $state<Record<string, any>>({
		nama_opd: '',
		sub_opd: '',
		upt: ''
	});

	$effect(() => {
		if (initialData) {
			formData.nama_opd = initialData.nama_opd || '';
			formData.sub_opd = initialData.sub_opd || '';
			formData.upt = initialData.upt || '';
		} else {
			formData.nama_opd = '';
			formData.sub_opd = '';
			formData.upt = '';
		}
	});

	function handleSubmit(e: Event) {
		e.preventDefault();
		onsubmit(formData);
	}
</script>

<form class="opd-form" onsubmit={handleSubmit}>
	<div class="form-group" style="margin-bottom: var(--space-md);">
		<label for="nama_opd" class="form-label">Nama OPD <span class="text-danger">*</span></label>
		<input id="nama_opd" type="text" class="form-input" bind:value={formData.nama_opd} required />
	</div>
	
	<div class="form-group" style="margin-bottom: var(--space-md);">
		<label for="sub_opd" class="form-label">Sub OPD</label>
		<input id="sub_opd" type="text" class="form-input" bind:value={formData.sub_opd} />
	</div>
	
	<div class="form-group" style="margin-bottom: var(--space-lg);">
		<label for="upt" class="form-label">UPT</label>
		<input id="upt" type="text" class="form-input" bind:value={formData.upt} />
	</div>
	
	<div class="form-actions" style="display: flex; justify-content: flex-end; gap: var(--space-sm);">
		<button type="button" class="btn btn-secondary" onclick={oncancel} disabled={loading}>Batal</button>
		<button type="submit" class="btn btn-primary" disabled={loading}>
			{#if loading}
				<span class="material-icons-outlined" style="animation: spin 1s linear infinite;">refresh</span>
				Menyimpan...
			{:else}
				{mode === 'create' ? 'Simpan' : 'Perbarui'}
			{/if}
		</button>
	</div>
</form>

<style>
	.text-danger { color: var(--color-danger); }
</style>
