/**
 * TypeScript types untuk aplikasi SIMANTA
 * Sesuai skema database Laravel (Outlet, Opd)
 */

// --- Outlet (Aset Tanah) ---
export interface Outlet {
	id: number;
	id_pemda: string | null;
	nama: string;
	keterangan: string | null;
	id_opd: number;
	jenis: string | null;
	luas: number | null;
	alamat: string | null;
	no_sertifikat: string | null;
	no_sertifikat_2: string | null;
	no_sertifikat_3: string | null;
	tgl_sertifikat: string | null;
	photo: string | null;
	photo_2: string | null;
	photo_3: string | null;
	photo_4: string | null;
	sertif_pdf: string | null;
	latitude: string | null;
	longitude: string | null;
	polygon: string | null;
	opd?: Opd;
	created_at?: string;
	updated_at?: string;
}

// --- OPD ---
export interface Opd {
	id_opd: number;
	nama_opd: string;
	sub_opd: string | null;
	upt: string | null;
	outlets_count?: number;
}

// --- GeoJSON ---
export interface GeoJSONFeature {
	type: 'Feature';
	geometry: {
		type: 'Polygon';
		coordinates: number[][][];
	};
	properties: {
		id: number;
		nama: string;
		opd: string;
		jenis: string | null;
		luas: number | null;
		alamat: string | null;
		photo: string | null;
		color: string;
	};
}

export interface GeoJSONCollection {
	type: 'FeatureCollection';
	features: GeoJSONFeature[];
}

// --- API Response ---
export interface PaginatedResponse<T> {
	current_page: number;
	data: T[];
	last_page: number;
	per_page: number;
	total: number;
	from: number | null;
	to: number | null;
}

export interface ApiError {
	message: string;
	errors?: Record<string, string[]>;
}

// --- Auth ---
export interface User {
	id: number;
	name: string;
	email: string;
}

export interface LoginResponse {
	access_token: string;
	token_type: string;
	user: User;
}

// --- Asset type color mapping ---
export const ASSET_COLORS: Record<string, string> = {
	'Tanah Usaha': 'var(--color-tanah-usaha)',
	'Tanah Bangunan': 'var(--color-tanah-bangunan)',
	'Tanah Untuk Jalan': 'var(--color-tanah-jalan)',
	'Tanah Makam': 'var(--color-tanah-makam)',
	'Tanah Kosong': 'var(--color-tanah-kosong)',
	'Tanah Lapangan': 'var(--color-tanah-lapangan)'
};

export const ASSET_HEX_COLORS: Record<string, string> = {
	'Tanah Usaha': '#ef4444',
	'Tanah Bangunan': '#3b82f6',
	'Tanah Untuk Jalan': '#22c55e',
	'Tanah Makam': '#22c55e',
	'Tanah Kosong': '#1e293b',
	'Tanah Lapangan': '#f97316'
};

export function getAssetColor(jenis: string | null): string {
	if (!jenis) return '#eab308';
	return ASSET_HEX_COLORS[jenis] ?? '#eab308';
}
