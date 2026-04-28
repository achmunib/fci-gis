import { apiGet } from './api';

export interface DashboardStats {
	total_aset: number;
	total_luas: number;
	total_opd_tanah_usaha: number;
	drilldown_bpka: number;
	chart_data: Array<{
		nama_opd: string;
		total: number;
	}>;
}

export function getDashboardStats(): Promise<DashboardStats> {
	return apiGet<DashboardStats>('/dashboard/stats');
}
