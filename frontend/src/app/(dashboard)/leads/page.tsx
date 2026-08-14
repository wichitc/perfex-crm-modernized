"use client";

import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { MOCK_LEADS } from "@/lib/mock-data";
import { GitMerge, Plus, Kanban, Table as TableIcon, DollarSign, UserCheck, ArrowRight } from "lucide-react";

export default function LeadsPage() {
  const [viewMode, setViewMode] = useState<"kanban" | "table">("kanban");

  const { data: leads } = useQuery({
    queryKey: ["leads"],
    queryFn: async () => {
      try {
        const response = await apiClient.get("/leads");
        return response.data;
      } catch {
        return MOCK_LEADS;
      }
    },
    initialData: MOCK_LEADS,
  });

  const stages = ["New", "Contacted", "Qualified", "Proposal Sent", "Won"];

  return (
    <div className="space-y-8 animate-fadeIn">
      {/* Top Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-black tracking-tight text-white flex items-center gap-2">
            <GitMerge className="h-6 w-6 text-purple-400" />
            Leads Pipeline
          </h2>
          <p className="text-xs text-slate-400 mt-1">
            Track lead status stages, potential deal values, and sales conversion progress.
          </p>
        </div>
        <div className="flex items-center gap-3">
          {/* View Switcher */}
          <div className="flex items-center bg-slate-900 border border-slate-800 rounded-xl p-1">
            <button
              onClick={() => setViewMode("kanban")}
              className={`flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold cursor-pointer ${
                viewMode === "kanban" ? "bg-slate-800 text-white shadow-sm" : "text-slate-400"
              }`}
            >
              <Kanban className="h-3.5 w-3.5" /> Kanban
            </button>
            <button
              onClick={() => setViewMode("table")}
              className={`flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold cursor-pointer ${
                viewMode === "table" ? "bg-slate-800 text-white shadow-sm" : "text-slate-400"
              }`}
            >
              <TableIcon className="h-3.5 w-3.5" /> Table
            </button>
          </div>

          <button className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow-lg shadow-purple-500/25 transition-all cursor-pointer">
            <Plus className="h-4 w-4" /> Add Lead
          </button>
        </div>
      </div>

      {/* Kanban View */}
      {viewMode === "kanban" ? (
        <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 overflow-x-auto pb-4">
          {stages.map((stage) => {
            const stageLeads = leads.filter((l: any) => l.status === stage);
            return (
              <div key={stage} className="bg-slate-900/50 border border-slate-800/80 rounded-3xl p-4 flex flex-col min-w-[220px]">
                <div className="flex items-center justify-between border-b border-slate-800 pb-3 mb-3">
                  <span className="text-xs font-extrabold text-white">{stage}</span>
                  <span className="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-800 text-slate-400">
                    {stageLeads.length}
                  </span>
                </div>

                <div className="space-y-3 flex-1">
                  {stageLeads.map((lead: any) => (
                    <div
                      key={lead.id}
                      className="bg-slate-950/80 border border-slate-800/80 rounded-2xl p-4 shadow-md hover:border-purple-500/40 transition-all space-y-3 cursor-grab"
                    >
                      <div>
                        <h4 className="text-xs font-bold text-white">{lead.name}</h4>
                        <p className="text-[10px] text-slate-400 mt-0.5">{lead.email}</p>
                      </div>
                      <div className="flex items-center justify-between text-xs pt-2 border-t border-slate-900">
                        <span className="text-purple-400 font-extrabold flex items-center">
                          ฿{lead.value.toLocaleString()}
                        </span>
                        <span className="text-[10px] text-slate-500">{lead.source}</span>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            );
          })}
        </div>
      ) : (
        /* Table View */
        <div className="bg-slate-900/60 border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
          <table className="w-full text-left text-xs">
            <thead className="bg-slate-950/80 text-slate-400 uppercase tracking-wider font-bold border-b border-slate-800">
              <tr>
                <th className="p-4">Lead Name</th>
                <th className="p-4">Email</th>
                <th className="p-4">Est. Deal Value</th>
                <th className="p-4">Source</th>
                <th className="p-4">Status</th>
                <th className="p-4 text-right">Action</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-800/60 text-slate-200">
              {leads.map((lead: any) => (
                <tr key={lead.id} className="hover:bg-slate-800/40 transition-colors">
                  <td className="p-4 font-bold text-white">{lead.name}</td>
                  <td className="p-4 text-slate-400">{lead.email}</td>
                  <td className="p-4 font-bold text-purple-400">฿{lead.value.toLocaleString()}</td>
                  <td className="p-4 text-slate-400">{lead.source}</td>
                  <td className="p-4">
                    <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-500/10 text-purple-400 border border-purple-500/20">
                      {lead.status}
                    </span>
                  </td>
                  <td className="p-4 text-right">
                    <button className="text-cyan-400 font-semibold hover:underline flex items-center justify-end gap-1 ml-auto">
                      Convert <ArrowRight className="h-3 w-3" />
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
}
