/**
 * OPD Service — CRUD operasi untuk Organisasi Perangkat Daerah
 */

import { apiGet, apiPost, apiPut, apiDelete } from './api';
import type { Opd, PaginatedResponse } from '$lib/types';

export interface OpdSearchParams {
	q?: string;
	page?: number;
}

function buildQuery(params: OpdSearchParams): string {
	const query = new URLSearchParams();
	if (params.q) query.set('q', params.q);
	if (params.page) query.set('page', String(params.page));
	const str = query.toString();
	return str ? `?${str}` : '';
}

/**
 * List OPD (paginated, searchable)
 */
export function getOpds(params: OpdSearchParams = {}): Promise<PaginatedResponse<Opd>> {
	return apiGet<PaginatedResponse<Opd>>(`/opd${buildQuery(params)}`);
}

/**
 * Get all OPD without pagination (for dropdowns)
 */
export function getAllOpds(): Promise<Opd[]> {
	return apiGet<Opd[]>('/opd/all');
}

/**
 * Get single OPD by ID
 */
export function getOpd(id: number): Promise<Opd> {
	return apiGet<Opd>(`/opd/${id}`);
}

/**
 * Create new OPD
 */
export function createOpd(data: { nama_opd: string; sub_opd?: string; upt?: string }): Promise<Opd> {
	return apiPost<Opd>('/opd', data);
}

/**
 * Update OPD
 */
export function updateOpd(
	id: number,
	data: { nama_opd: string; sub_opd?: string; upt?: string }
): Promise<Opd> {
	return apiPut<Opd>(`/opd/${id}`, data);
}

/**
 * Delete OPD
 * Will fail if OPD still has assets (server-side guard)
 */
export function deleteOpd(id: number): Promise<void> {
	return apiDelete<void>(`/opd/${id}`);
}
