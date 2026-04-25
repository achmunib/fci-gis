/**
 * Asset Service — CRUD operasi untuk Aset Tanah (Outlet)
 */

import { apiGet, apiPost, apiPut, apiDelete } from './api';
import type { Outlet, GeoJSONCollection, PaginatedResponse } from '$lib/types';

export interface AssetSearchParams {
	q?: string;
	opd?: number;
	jenis?: string;
	page?: number;
	per_page?: number;
}

function buildQuery(params: AssetSearchParams): string {
	const query = new URLSearchParams();
	if (params.q) query.set('q', params.q);
	if (params.opd) query.set('opd', String(params.opd));
	if (params.jenis) query.set('jenis', params.jenis);
	if (params.page) query.set('page', String(params.page));
	if (params.per_page) query.set('per_page', String(params.per_page));
	const str = query.toString();
	return str ? `?${str}` : '';
}

/**
 * List aset tanah (paginated, searchable)
 */
export function getAssets(params: AssetSearchParams = {}): Promise<PaginatedResponse<Outlet>> {
	return apiGet<PaginatedResponse<Outlet>>(`/outlets${buildQuery(params)}`);
}

/**
 * Get single asset by ID
 */
export function getAsset(id: number): Promise<Outlet> {
	return apiGet<Outlet>(`/outlets/${id}`);
}

/**
 * Get all assets as GeoJSON for map rendering
 */
export function getAssetsGeoJSON(): Promise<GeoJSONCollection> {
	return apiGet<GeoJSONCollection>('/outlets/geojson');
}

/**
 * Create new asset (supports file upload via FormData)
 */
export function createAsset(data: FormData): Promise<Outlet> {
	return apiPost<Outlet>('/outlets', data);
}

/**
 * Update asset
 */
export function updateAsset(id: number, data: Record<string, unknown>): Promise<Outlet> {
	return apiPut<Outlet>(`/outlets/${id}`, data);
}

/**
 * Delete asset
 */
export function deleteAsset(id: number): Promise<void> {
	return apiDelete<void>(`/outlets/${id}`);
}
