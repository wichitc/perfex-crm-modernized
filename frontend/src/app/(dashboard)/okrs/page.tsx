"use client";

import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { MOCK_OKRS } from "@/lib/mock-data";
import { Target, Plus, CheckCircle2 } from "lucide-react";

export default function OKRsPage() {
  const { data: okrs } = useQuery({
    queryKey: ["okrs"],
    queryFn: async () => {
      try {
        const response = await apiClient.get("/okrs");
        return response.data;
      } catch {
        return MOCK_OKRS;
      }
    },
    initialData: MOCK_OKRS,
  });

  return (
    <div className="space-y-8 animate-fadeIn">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-black tracking-tight text-white flex items-center gap-2">
            <Target className="h-6 w-6 text-rose-400" />
            Objectives & Key Results (OKRs)
          </h2>
          <p className="text-xs text-slate-400 mt-1">
            Align quarterly strategic goals and track real-time Key Result progress.
          </p>
        </div>
        <button className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-lg shadow-rose-500/25 transition-all cursor-pointer">
          <Plus className="h-4 w-4" /> Add Objective
        </button>
      </div>

      <div className="space-y-6">
        {okrs.map((obj: any) => (
          <div key={obj.id} className="bg-slate-900/60 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-800 pb-4">
              <div>
                <div className="flex items-center gap-2">
                  <span className="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                    {obj.period}
                  </span>
                  <span className="text-xs text-slate-400 font-semibold">{obj.owner}</span>
                </div>
                <h3 className="text-lg font-black text-white mt-1">{obj.title}</h3>
              </div>
              <div className="flex items-center gap-3">
                <div className="w-36 bg-slate-950 rounded-full h-3 border border-slate-800 overflow-hidden">
                  <div className="bg-gradient-to-r from-rose-500 to-pink-500 h-full rounded-full" style={{ width: `${obj.progress}%` }}></div>
                </div>
                <span className="text-sm font-extrabold text-rose-400">{obj.progress}%</span>
              </div>
            </div>

            {/* Key Results */}
            <div className="space-y-3">
              <h4 className="text-xs font-bold text-slate-400 uppercase tracking-wider">Key Results</h4>
              <div className="space-y-2">
                {obj.keyResults.map((kr: any) => (
                  <div key={kr.id} className="p-3.5 rounded-2xl bg-slate-950/60 border border-slate-800 flex items-center justify-between text-xs">
                    <span className="font-bold text-slate-200">{kr.title}</span>
                    <span className="font-extrabold text-cyan-400">
                      {kr.current.toLocaleString()} / {kr.target.toLocaleString()} {kr.unit}
                    </span>
                  </div>
                ))}
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
