<script lang="ts">
	/* eslint-disable svelte/no-navigation-without-resolve */
	import '../app.css';
	import { page } from '$app/state';
	import { goto } from '$app/navigation';
	import { base } from '$app/paths';
	import { fade } from 'svelte/transition';
	import ThemeToggle from '$lib/components/ui/ThemeToggle.svelte';
	import ToastContainer from '$lib/components/ui/ToastContainer.svelte';
	import { hasToken, logout } from '$lib/services/auth.service';

	let { children } = $props();

	let sidebarOpen = $state(true);
	let currentPath = $derived(page.url.pathname);

	const navItems = [
		{ href: '/dashboard', icon: 'dashboard', label: 'Dashboard' },
		{ href: '/assets', icon: 'landscape', label: 'Aset Tanah' },
		{ href: '/opd', icon: 'assured_workload', label: 'Data OPD' }
	];

	function isActive(href: string): boolean {
		return currentPath === href || currentPath.startsWith(href + '/');
	}

	$effect(() => {
		if (!hasToken() && currentPath !== '/login') {
			goto(`${base}/login`);
		} else if (hasToken() && currentPath === '/login') {
			goto(`${base}/dashboard`);
		}
	});

	async function handleLogout() {
		await logout();
		goto(`${base}/login`);
	}
</script>

<ToastContainer />

<div class="app-layout" class:sidebar-collapsed={!sidebarOpen}>
	<!-- Sidebar -->
	<aside class="sidebar">
		<div class="sidebar-header">
			<div class="sidebar-brand">
				<span class="material-icons-outlined brand-icon">public</span>
				{#if sidebarOpen}
					<div class="brand-text">
						<span class="brand-name">SIMANTA</span>
						<span class="brand-desc">Manajemen Aset Tanah</span>
					</div>
				{/if}
			</div>
			<button class="sidebar-toggle btn-icon" onclick={() => (sidebarOpen = !sidebarOpen)}>
				<span class="material-icons-outlined">
					{sidebarOpen ? 'chevron_left' : 'chevron_right'}
				</span>
			</button>
		</div>

		<nav class="sidebar-nav">
			{#each navItems as item (item.href)}
				<a
					href={`${base}${item.href}`}
					class="nav-item"
					class:active={isActive(item.href)}
					title={item.label}
				>
					<span class="material-icons-outlined nav-icon">{item.icon}</span>
					{#if sidebarOpen}
						<span class="nav-label">{item.label}</span>
					{/if}
				</a>
			{/each}
		</nav>

		<div class="sidebar-footer">
			<button class="nav-item btn-icon" title="Keluar" onclick={handleLogout} style="width: 100%; border: none; background: transparent; text-align: left; cursor: pointer;">
				<span class="material-icons-outlined nav-icon">logout</span>
				{#if sidebarOpen}
					<span class="nav-label">Keluar</span>
				{/if}
			</button>
		</div>
	</aside>

	<!-- Main Content -->
	<main class="main-content">
		<header class="top-header">
			<div class="header-actions">
				<ThemeToggle />
			</div>
		</header>
		
		{#key currentPath}
			<div in:fade={{ duration: 200, delay: 200 }} out:fade={{ duration: 200 }}>
				{@render children()}
			</div>
		{/key}
	</main>
</div>

<style>
	.app-layout {
		display: flex;
		min-height: 100vh;
	}

	/* --- Sidebar --- */
	.sidebar {
		width: var(--sidebar-width);
		background: var(--color-bg-sidebar);
		display: flex;
		flex-direction: column;
		transition: width var(--transition-slow);
		position: fixed;
		top: 0;
		left: 0;
		bottom: 0;
		z-index: 100;
		overflow: hidden;
	}

	:global(.sidebar-collapsed) .sidebar {
		width: var(--sidebar-collapsed-width);
	}

	.sidebar-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: var(--space-lg);
		border-bottom: 1px solid rgba(255, 255, 255, 0.08);
		min-height: var(--header-height);
	}

	.sidebar-brand {
		display: flex;
		align-items: center;
		gap: var(--space-sm);
		overflow: hidden;
	}

	.brand-icon {
		color: var(--color-accent);
		font-size: 1.75rem;
		flex-shrink: 0;
	}

	.brand-text {
		display: flex;
		flex-direction: column;
		white-space: nowrap;
	}

	.brand-name {
		color: #fff;
		font-size: var(--font-size-lg);
		font-weight: 700;
		letter-spacing: 0.5px;
	}

	.brand-desc {
		color: var(--color-text-muted);
		font-size: var(--font-size-xs);
	}

	.sidebar-toggle {
		color: var(--color-text-sidebar);
		background: none;
		border: none;
		cursor: pointer;
		border-radius: var(--radius-md);
		padding: 0.25rem;
		transition: background-color var(--transition-fast);
		flex-shrink: 0;
	}
	.sidebar-toggle:hover {
		background: var(--color-bg-sidebar-hover);
	}

	/* --- Navigation --- */
	.sidebar-nav {
		flex: 1;
		padding: var(--space-md);
		display: flex;
		flex-direction: column;
		gap: 2px;
	}

	.nav-item {
		display: flex;
		align-items: center;
		gap: var(--space-sm);
		padding: 0.625rem 0.75rem;
		color: var(--color-text-sidebar);
		border-radius: var(--radius-md);
		transition:
			background-color var(--transition-fast),
			color var(--transition-fast);
		text-decoration: none;
		white-space: nowrap;
	}

	.nav-item:hover {
		background: var(--color-bg-sidebar-hover);
		color: var(--color-text-sidebar-active);
	}

	.nav-item.active {
		background: var(--color-primary);
		color: var(--color-text-sidebar-active);
	}

	.nav-icon {
		font-size: 1.25rem;
		flex-shrink: 0;
	}

	.nav-label {
		font-size: var(--font-size-sm);
		font-weight: 500;
	}

	.sidebar-footer {
		padding: var(--space-md);
		border-top: 1px solid rgba(255, 255, 255, 0.08);
	}

	/* --- Main Content --- */
	.main-content {
		flex: 1;
		margin-left: var(--sidebar-width);
		padding: var(--space-xl);
		min-height: 100vh;
		transition: margin-left var(--transition-slow);
	}
	
	.top-header {
		display: flex;
		justify-content: flex-end;
		margin-bottom: var(--space-md);
	}
	
	.header-actions {
		display: flex;
		align-items: center;
		gap: var(--space-sm);
	}

	:global(.sidebar-collapsed) .main-content {
		margin-left: var(--sidebar-collapsed-width);
	}

	/* --- Responsive --- */
	@media (max-width: 768px) {
		.sidebar {
			width: var(--sidebar-collapsed-width);
		}
		.main-content {
			margin-left: var(--sidebar-collapsed-width);
		}
	}
</style>
