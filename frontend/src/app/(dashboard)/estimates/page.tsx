"use client";

import { useState } from "react";
import { Layers, Plus, FileText, CheckCircle2 } from "lucide-react";

export default function EstimatesPage() {
  const estimates = [
    { id: "EST-2026-001", client: "Acme Technology Solutions", date: "2026-07-28", amount: 68000, status: "Sent" },
    { id: "EST-2026-002", client: "Siam Digital Innovations", date: "2026-07-26", amount: 145000, status: "Accepted" },
    { id: "EST-2026-003", client: "Eastern Manufacturing", date: "2026-07-20", amount: 210000, status: "Draft" },
  ];

  return (
    <div className="space-y-8 animate-fadeIn">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-black tracking-tight text-white flex items-center gap-2">
            <Layers className="h-6 w-6 text-teal-400" />
            Estimates & Proposals
          </h2>
          <p className="text-xs text-slate-400 mt-1">
            Generate pricing quotations, proposals, and client acceptance workflows.
          </p>
        </div>
        <button className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold text-xs shadow-lg shadow-teal-500/25 transition-all cursor-pointer">
          <Plus className="h-4 w-4" /> Create Estimate
        </button>
      </div>

      <div className="bg-slate-900/60 border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
        <table className="w-full text-left text-xs">
          <thead className="bg-slate-950/80 text-slate-400 uppercase tracking-wider font-bold border-b border-slate-800">
            <tr>
              <th className="p-4">Estimate #</th>
              <th className="p-4">Client Name</th>
              <th className="p-4">Issue Date</th>
              <th className="p-4">Quoted Amount</th>
              <th className="p-4 text-right">Status</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-800/60 text-slate-200">
            {estimates.map((est) => (
              <tr key={est.id} className="hover:bg-slate-800/40 transition-colors">
                <td className="p-4 font-mono font-bold text-teal-400">{est.id}</td>
                <td className="p-4 font-bold text-white">{est.client}</td>
                <td className="p-4 text-slate-400">{est.date}</td>
                <td className="p-4 font-bold text-teal-400">฿{est.amount.toLocaleString()}</td>
                <td className="p-4 text-right">
                  <span className={`px-2.5 py-0.5 rounded-full text-[10px] font-bold ${
                    est.status === "Accepted" ? "bg-emerald-500/10 text-emerald-400 border border-emerald-500/20" : "bg-teal-500/10 text-teal-400 border border-teal-500/20"
                  }`}>
                    {est.status}
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
