<h1 class="page-title">Masuk ke SIMANTA</h1>

<div class="login-container">
	<div class="login-card card">
		<div class="card-body">
			<div class="login-logo">
				<span class="material-icons-outlined">public</span>
				<span>SIMANTA</span>
			</div>

			<form class="login-form">
				<div class="form-group">
					<label class="form-label" for="email">Email</label>
					<input id="email" type="email" class="form-input" placeholder="email@example.com" />
				</div>

				<div class="form-group">
					<label class="form-label" for="password">Password</label>
					<input id="password" type="password" class="form-input" placeholder="••••••••" />
				</div>

				<button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
					Masuk
				</button>
			</form>
		</div>
	</div>
</div>

<style>
	.page-title {
		position: absolute;
		width: 1px;
		height: 1px;
		overflow: hidden;
		clip: rect(0, 0, 0, 0);
	}
	.login-container {
		display: flex;
		align-items: center;
		justify-content: center;
		min-height: 80vh;
	}
	.login-card {
		width: 100%;
		max-width: 400px;
	}
	.login-logo {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: var(--space-sm);
		font-size: var(--font-size-2xl);
		font-weight: 700;
		color: var(--color-primary);
		margin-bottom: var(--space-xl);
	}
	.login-logo .material-icons-outlined {
		font-size: 2rem;
	}
	.login-form {
		display: flex;
		flex-direction: column;
		gap: var(--space-md);
	}
</style>
