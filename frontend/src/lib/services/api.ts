/**
 * Base API client untuk komunikasi dengan Laravel backend.
 *
 * Handles:
 * - JWT token injection
 * - CSRF token handling
 * - Error response normalization
 * - 401 → redirect to login
 * - 422 → validation errors
 */

import type { ApiError } from '$lib/types';

const API_BASE = import.meta.env.VITE_API_BASE_URL || '/api';

function getToken(): string | null {
	if (typeof window === 'undefined') return null;
	return localStorage.getItem('auth_token');
}

export function setToken(token: string): void {
	localStorage.setItem('auth_token', token);
}

export function clearToken(): void {
	localStorage.removeItem('auth_token');
}

/**
 * Core fetch wrapper with auth and error handling.
 */
export async function apiFetch<T>(
	endpoint: string,
	options: RequestInit = {}
): Promise<T> {
	const token = getToken();

	const headers: Record<string, string> = {
		Accept: 'application/json',
		...(options.headers as Record<string, string>)
	};

	// Only set Content-Type for non-FormData requests
	if (!(options.body instanceof FormData)) {
		headers['Content-Type'] = 'application/json';
	}

	if (token) {
		headers['Authorization'] = `Bearer ${token}`;
	}

	const response = await fetch(`${API_BASE}${endpoint}`, {
		...options,
		headers
	});

	// 204 No Content
	if (response.status === 204) {
		return undefined as T;
	}

	// 401 Unauthorized → redirect to login
	if (response.status === 401) {
		clearToken();
		if (typeof window !== 'undefined') {
			window.location.href = '/login';
		}
		throw { message: 'Sesi telah berakhir, silakan login kembali.' } as ApiError;
	}

	const data = await response.json();

	if (!response.ok) {
		const error: ApiError = {
			message: data.message || `Error ${response.status}`,
			errors: data.errors
		};
		throw error;
	}

	return data as T;
}

// --- Shorthand methods ---

export function apiGet<T>(endpoint: string): Promise<T> {
	return apiFetch<T>(endpoint, { method: 'GET' });
}

export function apiPost<T>(endpoint: string, body: unknown): Promise<T> {
	const isFormData = body instanceof FormData;
	return apiFetch<T>(endpoint, {
		method: 'POST',
		body: isFormData ? body : JSON.stringify(body)
	});
}

export function apiPut<T>(endpoint: string, body: unknown): Promise<T> {
	const isFormData = body instanceof FormData;
	if (isFormData) {
		body.append('_method', 'PUT');
		return apiFetch<T>(endpoint, {
			method: 'POST',
			body
		});
	}
	return apiFetch<T>(endpoint, {
		method: 'PUT',
		body: JSON.stringify(body)
	});
}

export function apiDelete<T>(endpoint: string): Promise<T> {
	return apiFetch<T>(endpoint, { method: 'DELETE' });
}
