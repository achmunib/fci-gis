/**
 * Auth Service — JWT authentication dengan Laravel API
 */

import { apiFetch, setToken, clearToken } from './api';
import type { User, LoginResponse } from '$lib/types';

/**
 * Login dengan email & password.
 * Simpan JWT token ke localStorage.
 */
export async function login(email: string, password: string): Promise<User> {
	const response = await apiFetch<LoginResponse>('/auth/login', {
		method: 'POST',
		body: JSON.stringify({ email, password })
	});

	setToken(response.access_token);
	return response.user;
}

/**
 * Logout — hapus token dari client & invalidate di server.
 */
export async function logout(): Promise<void> {
	try {
		await apiFetch<void>('/auth/logout', { method: 'POST' });
	} catch {
		// Even if server call fails, clear local token
	} finally {
		clearToken();
	}
}

/**
 * Get current authenticated user.
 * Returns null if not authenticated.
 */
export async function getUser(): Promise<User | null> {
	try {
		return await apiFetch<User>('/auth/user', { method: 'GET' });
	} catch {
		return null;
	}
}

/**
 * Check if user has a stored token.
 * NOTE: Does not validate token — use getUser() for that.
 */
export function hasToken(): boolean {
	if (typeof window === 'undefined') return false;
	return !!localStorage.getItem('auth_token');
}
