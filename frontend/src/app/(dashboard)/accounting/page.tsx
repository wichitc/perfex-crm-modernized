"use client";

import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { MOCK_ACCOUNTING } from "@/lib/mock-data";
import { useTranslation } from "@/providers/language-provider";
import { BookOpen, Plus, FileSpreadsheet } from "lucide-react";

export default function AccountingPage() {
  const { t, formatCurrency } = useTranslation();

  const { data: accounting } = useQuery({
    queryKey: ["accounting"],
    queryFn: async () => {
      try {
        const response = await apiClient.get("/accounting/summary");
        return response.data;
      } catch {
        return MOCK_ACCOUNTING;
      }
    },
    initialData: MOCK_ACCOUNTING,
  });

  return (
    <div className="space-y-8 animate-fadeIn">
      {/* Top Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-black tracking-tight text-white flex items-center gap-2">
            <BookOpen className="h-6 w-6 text-emerald-400" />
            {t("accounting.title")}
          </h2>
          <p className="text-xs text-slate-400 mt-1">
            {t("accounting.subtitle")}
          </p>
        </div>
        <button className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs shadow-lg shadow-emerald-500/25 transition-all cursor-pointer">
          <Plus className="h-4 w-4" /> {t("accounting.addNewEntry")}
        </button>
      </div>

      {/* Summary Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div className="bg-slate-900/60 border border-slate-800 rounded-3xl p-5 shadow-lg">
          <span className="text-xs font-bold uppercase tracking-wider text-slate-400">Total Assets</span>
          <h3 className="text-2xl font-black text-white mt-3">{formatCurrency(accounting.summary.assets)}</h3>
        </div>
        <div className="bg-slate-900/60 border border-slate-800 rounded-3xl p-5 shadow-lg">
          <span className="text-xs font-bold uppercase tracking-wider text-slate-400">Total Liabilities</span>
          <h3 className="text-2xl font-black text-rose-400 mt-3">{formatCurrency(accounting.summary.liabilities)}</h3>
        </div>
        <div className="bg-slate-900/60 border border-slate-800 rounded-3xl p-5 shadow-lg">
          <span className="text-xs font-bold uppercase tracking-wider text-slate-400">Total Equity</span>
          <h3 className="text-2xl font-black text-indigo-400 mt-3">{formatCurrency(accounting.summary.equity)}</h3>
        </div>
        <div className="bg-slate-900/60 border border-slate-800 rounded-3xl p-5 shadow-lg">
          <span className="text-xs font-bold uppercase tracking-wider text-slate-400">Net Income YTD</span>
          <h3 className="text-2xl font-black text-emerald-400 mt-3">{formatCurrency(accounting.summary.netIncome)}</h3>
        </div>
      </div>

      {/* Chart of Accounts */}
      <div className="bg-slate-900/60 border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
        <div className="p-5 border-b border-slate-800 flex items-center justify-between">
          <h3 className="text-base font-extrabold text-white flex items-center gap-2">
            <FileSpreadsheet className="h-5 w-5 text-emerald-400" /> {t("navigation.menu.accounting")}
          </h3>
        </div>
        <table className="w-full text-left text-xs">
          <thead className="bg-slate-950/80 text-slate-400 uppercase tracking-wider font-bold border-b border-slate-800">
            <tr>
              <th className="p-4">{t("accounting.accountCode")}</th>
              <th className="p-4">{t("accounting.accountName")}</th>
              <th className="p-4">{t("accounting.type")}</th>
              <th className="p-4 text-right">{t("accounting.balance")}</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-800/60 text-slate-200">
            {accounting.accounts.map((acc: any) => (
              <tr key={acc.code} className="hover:bg-slate-800/40 transition-colors">
                <td className="p-4 font-mono font-bold text-emerald-400">{acc.code}</td>
                <td className="p-4 font-bold text-white">{acc.name}</td>
                <td className="p-4">
                  <span className="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-800 text-slate-300">
                    {acc.type}
                  </span>
                </td>
                <td className="p-4 text-right font-bold text-white">{formatCurrency(acc.balance)}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
