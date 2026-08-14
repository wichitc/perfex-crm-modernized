"use client";

import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { MOCK_LEADS } from "@/lib/mock-data";
import { useTranslation } from "@/providers/language-provider";
import { GitMerge, Plus, Kanban, Table as TableIcon, ArrowRight } from "lucide-react";

export default function LeadsPage() {
  const { t, formatCurrency } = useTranslation();
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

  const stages = [
    { id: "New", label: t("lead.stage.new") },
    { id: "Contacted", label: t("lead.stage.contacted") },
    { id: "Qualified", label: t("lead.stage.qualified") },
    { id: "Proposal Sent", label: t("lead.stage.proposal") },
    { id: "Won", label: t("lead.stage.won") },
  ];

  return (
    <div className="space-y-8 animate-fadeIn">
      {/* Top Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-black tracking-tight text-white flex items-center gap-2">
            <GitMerge className="h-6 w-6 text-purple-400" />
            {t("lead.title")}
          </h2>
          <p className="text-xs text-slate-400 mt-1">
            {t("lead.subtitle")}
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
              <Kanban className="h-3.5 w-3.5" /> {t("lead.kanbanView")}
            </button>
            <button
              onClick={() => setViewMode("table")}
              className={`flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold cursor-pointer ${
                viewMode === "table" ? "bg-slate-800 text-white shadow-sm" : "text-slate-400"
              }`}
            >
              <TableIcon className="h-3.5 w-3.5" /> {t("lead.tableView")}
            </button>
          </div>

          <button className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow-lg shadow-purple-500/25 transition-all cursor-pointer">
            <Plus className="h-4 w-4" /> {t("lead.addNew")}
          </button>
        </div>
      </div>

      {/* Kanban View */}
      {viewMode === "kanban" ? (
        <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 overflow-x-auto pb-4">
          {stages.map((stg) => {
            const stageLeads = leads.filter((l: any) => l.status === stg.id);
            return (
              <div key={stg.id} className="bg-slate-900/50 border border-slate-800/80 rounded-3xl p-4 flex flex-col min-w-[220px]">
                <div className="flex items-center justify-between border-b border-slate-800 pb-3 mb-3">
                  <span className="text-xs font-extrabold text-white">{stg.label}</span>
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
                          {formatCurrency(lead.value)}
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
                <th className="p-4">{t("common.name")}</th>
                <th className="p-4">{t("common.email")}</th>
                <th className="p-4">{t("lead.value")}</th>
                <th className="p-4">Source</th>
                <th className="p-4">{t("common.status")}</th>
                <th className="p-4 text-right">{t("common.actions")}</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-800/60 text-slate-200">
              {leads.map((lead: any) => (
                <tr key={lead.id} className="hover:bg-slate-800/40 transition-colors">
                  <td className="p-4 font-bold text-white">{lead.name}</td>
                  <td className="p-4 text-slate-400">{lead.email}</td>
                  <td className="p-4 font-bold text-purple-400">{formatCurrency(lead.value)}</td>
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
