<script lang="ts">
	let { width = '100%', height = '1rem', borderRadius = 'var(--radius-sm)', className = '' } = $props<{
		width?: string;
		height?: string;
		borderRadius?: string;
		className?: string;
	}>();
</script>

<div class="skeleton {className}" style="width: {width}; height: {height}; border-radius: {borderRadius};"></div>

<style>
	.skeleton {
		background-color: var(--color-bg-alt);
		position: relative;
		overflow: hidden;
	}
	.skeleton::after {
		content: '';
		position: absolute;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		transform: translateX(-100%);
		background-image: linear-gradient(
			90deg,
			rgba(255, 255, 255, 0) 0,
			rgba(255, 255, 255, 0.2) 20%,
			rgba(255, 255, 255, 0.5) 60%,
			rgba(255, 255, 255, 0)
		);
		animation: shimmer 2s infinite;
	}

	:global(body.dark-mode) .skeleton::after {
		background-image: linear-gradient(
			90deg,
			rgba(255, 255, 255, 0) 0,
			rgba(255, 255, 255, 0.05) 20%,
			rgba(255, 255, 255, 0.1) 60%,
			rgba(255, 255, 255, 0)
		);
	}

	@keyframes shimmer {
		100% { transform: translateX(100%); }
	}
</style>
