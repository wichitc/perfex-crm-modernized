"use client";

import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { MOCK_STAFF_OUTSOURCING } from "@/lib/mock-data";
import { useTranslation } from "@/providers/language-provider";
import { Briefcase, Plus } from "lucide-react";

export default function StaffOutsourcingPage() {
  const { t } = useTranslation();

  const { data: staff } = useQuery({
    queryKey: ["staff-outsourcing"],
    queryFn: async () => {
      try {
        const response = await apiClient.get("/staff-outsourcing");
        return response.data;
      } catch {
        return MOCK_STAFF_OUTSOURCING;
      }
    },
    initialData: MOCK_STAFF_OUTSOURCING,
  });

  return (
    <div className="space-y-8 animate-fadeIn">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-black tracking-tight text-white flex items-center gap-2">
            <Briefcase className="h-6 w-6 text-amber-400" />
            {t("outsourcing.title")}
          </h2>
          <p className="text-xs text-slate-400 mt-1">
            {t("outsourcing.subtitle")}
          </p>
        </div>
        <button className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-lg shadow-amber-500/25 transition-all cursor-pointer">
          <Plus className="h-4 w-4" /> {t("outsourcing.bookResource")}
        </button>
      </div>

      <div className="bg-slate-900/60 border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
        <table className="w-full text-left text-xs">
          <thead className="bg-slate-950/80 text-slate-400 uppercase tracking-wider font-bold border-b border-slate-800">
            <tr>
              <th className="p-4">{t("outsourcing.contractorName")}</th>
              <th className="p-4">{t("outsourcing.role")}</th>
              <th className="p-4">{t("outsourcing.hourlyRate")}</th>
              <th className="p-4">Allocation</th>
              <th className="p-4">{t("outsourcing.clientProject")}</th>
              <th className="p-4 text-right">{t("common.status")}</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-800/60 text-slate-200">
            {staff.map((s: any) => (
              <tr key={s.id} className="hover:bg-slate-800/40 transition-colors">
                <td className="p-4 font-bold text-white">{s.name}</td>
                <td className="p-4 text-slate-300">{s.role}</td>
                <td className="p-4 font-bold text-amber-400">{s.rate}</td>
                <td className="p-4 font-mono text-cyan-400">{s.allocation}</td>
                <td className="p-4 text-slate-400">{s.project}</td>
                <td className="p-4 text-right">
                  <span className="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                    {s.status}
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
