"use client";

import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { MOCK_ACCOUNT_PLANNING } from "@/lib/mock-data";
import { Target, Plus, ShieldCheck, Zap, AlertTriangle, Crosshair } from "lucide-react";

export default function AccountPlanningPage() {
  const { data: plans } = useQuery({
    queryKey: ["account-planning"],
    queryFn: async () => {
      try {
        const response = await apiClient.get("/account-planning");
        return response.data;
      } catch {
        return MOCK_ACCOUNT_PLANNING;
      }
    },
    initialData: MOCK_ACCOUNT_PLANNING,
  });

  return (
    <div className="space-y-8 animate-fadeIn">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-black tracking-tight text-white flex items-center gap-2">
            <Target className="h-6 w-6 text-indigo-400" />
            Strategic Account Planning & SWOT
          </h2>
          <p className="text-xs text-slate-400 mt-1">
            Build strategic key account growth blueprints and SWOT matrices for key enterprise clients.
          </p>
        </div>
        <button className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-lg shadow-indigo-500/25 transition-all cursor-pointer">
          <Plus className="h-4 w-4" /> Create Account Plan
        </button>
      </div>

      {plans.map((p: any, idx: number) => (
        <div key={idx} className="bg-slate-900/60 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-6">
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-800 pb-4">
            <div>
              <span className="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                {p.tier}
              </span>
              <h3 className="text-xl font-black text-white mt-1">{p.client}</h3>
            </div>
            <span className="text-xs text-slate-400">Account Lead: <strong className="text-slate-200">{p.accountManager}</strong></span>
          </div>

          {/* SWOT Grid */}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div className="p-4 rounded-2xl bg-emerald-500/5 border border-emerald-500/20 space-y-2">
              <h4 className="text-xs font-bold text-emerald-400 uppercase tracking-wider flex items-center gap-1.5">
                <ShieldCheck className="h-4 w-4" /> Strengths
              </h4>
              <ul className="list-disc list-inside text-xs text-slate-300 space-y-1">
                {p.swot.strengths.map((s: string, i: number) => (
                  <li key={i}>{s}</li>
                ))}
              </ul>
            </div>

            <div className="p-4 rounded-2xl bg-amber-500/5 border border-amber-500/20 space-y-2">
              <h4 className="text-xs font-bold text-amber-400 uppercase tracking-wider flex items-center gap-1.5">
                <AlertTriangle className="h-4 w-4" /> Weaknesses
              </h4>
              <ul className="list-disc list-inside text-xs text-slate-300 space-y-1">
                {p.swot.weaknesses.map((w: string, i: number) => (
                  <li key={i}>{w}</li>
                ))}
              </ul>
            </div>

            <div className="p-4 rounded-2xl bg-cyan-500/5 border border-cyan-500/20 space-y-2">
              <h4 className="text-xs font-bold text-cyan-400 uppercase tracking-wider flex items-center gap-1.5">
                <Zap className="h-4 w-4" /> Opportunities
              </h4>
              <ul className="list-disc list-inside text-xs text-slate-300 space-y-1">
                {p.swot.opportunities.map((o: string, i: number) => (
                  <li key={i}>{o}</li>
                ))}
              </ul>
            </div>

            <div className="p-4 rounded-2xl bg-rose-500/5 border border-rose-500/20 space-y-2">
              <h4 className="text-xs font-bold text-rose-400 uppercase tracking-wider flex items-center gap-1.5">
                <Crosshair className="h-4 w-4" /> Threats
              </h4>
              <ul className="list-disc list-inside text-xs text-slate-300 space-y-1">
                {p.swot.threats.map((t: string, i: number) => (
                  <li key={i}>{t}</li>
                ))}
              </ul>
            </div>
          </div>
        </div>
      ))}
    </div>
  );
}
