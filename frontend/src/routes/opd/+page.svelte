<script lang="ts">
	import { onMount } from 'svelte';
	import { getOpds, deleteOpd, createOpd, updateOpd } from '$lib/services/opd.service';
	import type { Opd } from '$lib/types';
	import OpdForm from '$lib/components/crud/OpdForm.svelte';
	import Skeleton from '$lib/components/ui/Skeleton.svelte';
	import EmptyState from '$lib/components/ui/EmptyState.svelte';
	import { toast } from '$lib/stores/toast';

	let opds = $state<any[]>([]);
	let loading = $state(true);
	let error = $state<string | null>(null);

	let currentPage = $state(1);
	let totalPages = $state(1);
	let searchQuery = $state('');

	// Modal State
	let showModal = $state(false);
	let modalMode = $state<'create' | 'edit'>('create');
	let editData = $state<any>(null);
	let submitting = $state(false);

	async function loadOpds(page = 1) {
		loading = true;
		error = null;
		try {
			const res = await getOpds({ page, q: searchQuery });
			opds = res.data;
			currentPage = res.current_page;
			totalPages = res.last_page;
		} catch (err: any) {
			error = err.message || 'Gagal memuat data OPD';
			toast.error(error);
		} finally {
			loading = false;
		}
	}

	onMount(() => {
		loadOpds();
	});

	function handleSearch(e: Event) {
		e.preventDefault();
		loadOpds(1);
	}

	function openCreate() {
		modalMode = 'create';
		editData = null;
		showModal = true;
	}

	function openEdit(opd: any) {
		modalMode = 'edit';
		editData = opd;
		showModal = true;
	}

	async function handleFormSubmit(formData: any) {
		submitting = true;
		try {
			if (modalMode === 'create') {
				await createOpd(formData);
				toast.success('OPD berhasil ditambahkan');
			} else if (editData) {
				await updateOpd(editData.id_opd, formData);
				toast.success('OPD berhasil diperbarui');
			}
			showModal = false;
			loadOpds(currentPage);
		} catch (err: any) {
			let errorMsg = err.message || 'Terjadi kesalahan';
			if (err.errors) {
				errorMsg += ':\n' + Object.values(err.errors).flat().join('\n');
			}
			toast.error(errorMsg);
		} finally {
			submitting = false;
		}
	}

	async function handleDelete(id: number) {
		if (confirm('Apakah Anda yakin ingin menghapus OPD ini?')) {
			try {
				await deleteOpd(id);
				toast.success('OPD berhasil dihapus');
				loadOpds(currentPage);
			} catch (err: any) {
				toast.error(err.message || 'Gagal menghapus OPD');
			}
		}
	}
</script>

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-xl);">
	<div>
		<h1 class="page-title" style="font-size: var(--font-size-2xl); font-weight: 700;">Data OPD</h1>
		<p class="page-desc" style="color: var(--color-text-muted);">Kelola data Organisasi Perangkat Daerah.</p>
	</div>
	<button class="btn btn-primary" onclick={openCreate}>
		<span class="material-icons-outlined">add</span> Tambah OPD
	</button>
</div>

<div class="card">
	<div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
		<h2 class="card-title">Daftar OPD</h2>
		<form class="search-form" style="display: flex; gap: var(--space-sm);" onsubmit={handleSearch}>
			<input type="text" class="form-input" placeholder="Cari nama OPD, Sub OPD, UPT..." bind:value={searchQuery} />
			<button type="submit" class="btn btn-secondary">Cari</button>
		</form>
	</div>
	<div class="card-body">
		{#if loading}
			<div style="display: flex; flex-direction: column; gap: var(--space-md); padding: var(--space-md);">
				<Skeleton height="40px" />
				<Skeleton height="40px" />
				<Skeleton height="40px" />
				<Skeleton height="40px" />
			</div>
		{:else if error}
			<div style="padding: var(--space-xl); text-align: center; color: var(--color-danger);">{error}</div>
		{:else if opds.length === 0}
			<EmptyState 
				title="Tidak ada OPD" 
				description="Belum ada data Organisasi Perangkat Daerah yang ditambahkan."
				icon="assured_workload"
			/>
		{:else}
			<div style="overflow-x: auto;">
				<table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
					<thead>
						<tr style="border-bottom: 1px solid var(--color-border);">
							<th style="padding: var(--space-md);">Nama OPD</th>
							<th style="padding: var(--space-md);">Sub OPD</th>
							<th style="padding: var(--space-md);">UPT</th>
							<th style="padding: var(--space-md); text-align: center;">Jumlah Aset</th>
							<th style="padding: var(--space-md);">Aksi</th>
						</tr>
					</thead>
					<tbody>
						{#each opds as opd (opd.id_opd)}
							<tr style="border-bottom: 1px solid var(--color-border); transition: background 0.2s;">
								<td style="padding: var(--space-md); font-weight: 500;">{opd.nama_opd}</td>
								<td style="padding: var(--space-md);">{opd.sub_opd || '-'}</td>
								<td style="padding: var(--space-md);">{opd.upt || '-'}</td>
								<td style="padding: var(--space-md); text-align: center;">
									<span style="background: var(--color-bg-alt); padding: 4px 10px; border-radius: 20px; font-weight: bold;">
										{opd.jumlah_aset || 0}
									</span>
								</td>
								<td style="padding: var(--space-md);">
									<div style="display: flex; gap: var(--space-sm);">
										<button onclick={() => openEdit(opd)} class="btn-icon" title="Edit" style="color: var(--color-primary); background: none; border: none; cursor: pointer;">
											<span class="material-icons-outlined">edit</span>
										</button>
										<button onclick={() => handleDelete(opd.id_opd)} class="btn-icon" title="Hapus" style="color: var(--color-danger); background: none; border: none; cursor: pointer;">
											<span class="material-icons-outlined">delete</span>
										</button>
									</div>
								</td>
							</tr>
						{/each}
					</tbody>
				</table>
			</div>

			<!-- Pagination -->
			{#if totalPages > 1}
				<div class="pagination" style="display: flex; justify-content: space-between; align-items: center; margin-top: var(--space-lg);">
					<span style="color: var(--color-text-muted); font-size: var(--font-size-sm);">Halaman {currentPage} dari {totalPages}</span>
					<div style="display: flex; gap: var(--space-sm);">
						<button 
							class="btn btn-secondary" 
							disabled={currentPage === 1}
							onclick={() => loadOpds(currentPage - 1)}
						>
							<span class="material-icons-outlined">chevron_left</span>
						</button>
						<button 
							class="btn btn-secondary" 
							disabled={currentPage === totalPages}
							onclick={() => loadOpds(currentPage + 1)}
						>
							<span class="material-icons-outlined">chevron_right</span>
						</button>
					</div>
				</div>
			{/if}
		{/if}
	</div>
</div>

<!-- Modal -->
{#if showModal}
	<div class="modal-backdrop">
		<div class="modal-content card">
			<div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
				<h2 class="card-title">{modalMode === 'create' ? 'Tambah OPD' : 'Edit OPD'}</h2>
				<button class="btn-icon" onclick={() => showModal = false} style="background: none; border: none; cursor: pointer;">
					<span class="material-icons-outlined">close</span>
				</button>
			</div>
			<div class="card-body">
				<OpdForm mode={modalMode} initialData={editData} onsubmit={handleFormSubmit} loading={submitting} oncancel={() => showModal = false} />
			</div>
		</div>
	</div>
{/if}

<style>
	.modal-backdrop {
		position: fixed;
		top: 0; left: 0; right: 0; bottom: 0;
		background: rgba(0,0,0,0.5);
		display: flex;
		align-items: center;
		justify-content: center;
		z-index: 1000;
	}
	.modal-content {
		width: 100%;
		max-width: 500px;
		margin: var(--space-lg);
		background: var(--color-bg);
		box-shadow: 0 10px 25px rgba(0,0,0,0.2);
	}
</style>
