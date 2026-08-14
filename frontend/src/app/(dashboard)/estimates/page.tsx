"use client";

import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { useTranslation } from "@/providers/language-provider";
import { Layers, Plus } from "lucide-react";

export default function EstimatesPage() {
  const { t, formatCurrency, formatDate } = useTranslation();

  const { data: estimatesData = [] } = useQuery({
    queryKey: ["estimates"],
    queryFn: async () => {
      const response = await apiClient.get("/estimates");
      return response.data;
    },
  });

  const defaultEstimates = [
    { id: "EST-2026-001", client: "Acme Technology Solutions", date: "2026-07-28", amount: 68000, status: "Sent" },
    { id: "EST-2026-002", client: "Siam Digital Innovations", date: "2026-07-26", amount: 145000, status: "Accepted" },
  ];

  const estimates = estimatesData.length > 0 ? estimatesData : defaultEstimates;

  return (
    <div className="space-y-8 animate-fadeIn">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-black tracking-tight text-white flex items-center gap-2">
            <Layers className="h-6 w-6 text-teal-400" />
            {t("estimate.title")}
          </h2>
          <p className="text-xs text-slate-400 mt-1">
            {t("estimate.subtitle")}
          </p>
        </div>
        <button className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold text-xs shadow-lg shadow-teal-500/25 transition-all cursor-pointer">
          <Plus className="h-4 w-4" /> {t("estimate.addNew")}
        </button>
      </div>

      <div className="bg-slate-900/60 border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
        <table className="w-full text-left text-xs">
          <thead className="bg-slate-950/80 text-slate-400 uppercase tracking-wider font-bold border-b border-slate-800">
            <tr>
              <th className="p-4">{t("estimate.number")}</th>
              <th className="p-4">{t("estimate.client")}</th>
              <th className="p-4">{t("common.date")}</th>
              <th className="p-4">{t("invoice.amount")}</th>
              <th className="p-4 text-right">{t("common.status")}</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-800/60 text-slate-200">
            {estimates.map((est: any) => (
              <tr key={est.id} className="hover:bg-slate-800/40 transition-colors">
                <td className="p-4 font-mono font-bold text-teal-400">{est.number ? `EST-2026-${est.number}` : est.id}</td>
                <td className="p-4 font-bold text-white">{est.client || est.client_name || "Enterprise Client"}</td>
                <td className="p-4 text-slate-400">{formatDate(est.date || est.datecreated || "2026-07-28")}</td>
                <td className="p-4 font-bold text-teal-400">{formatCurrency(est.amount || est.total || 68000)}</td>
                <td className="p-4 text-right">
                  <span className={`px-2.5 py-0.5 rounded-full text-[10px] font-bold ${
                    est.status === "Accepted" || est.status === 2 ? "bg-emerald-500/10 text-emerald-400 border border-emerald-500/20" : "bg-teal-500/10 text-teal-400 border border-teal-500/20"
                  }`}>
                    {est.status === "Accepted" || est.status === 2 ? t("estimate.status.accepted") : t("estimate.status.sent")}
                  </span>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
