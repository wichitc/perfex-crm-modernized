"use client";

import { useState } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { useTranslation } from "@/providers/language-provider";
import { GitMerge, Plus, Kanban, Table as TableIcon, ArrowRight, X, Loader2 } from "lucide-react";

export default function LeadsPage() {
  const { t, formatCurrency } = useTranslation();
  const queryClient = useQueryClient();
  const [viewMode, setViewMode] = useState<"kanban" | "table">("kanban");
  const [isModalOpen, setIsModalOpen] = useState(false);

  // Form state
  const [leadName, setLeadName] = useState("");
  const [leadEmail, setLeadEmail] = useState("");
  const [leadValue, setLeadValue] = useState("50000");

  const { data: leadsData = [] } = useQuery({
    queryKey: ["leads"],
    queryFn: async () => {
      const response = await apiClient.get("/leads");
      return response.data;
    },
  });

  const createLeadMutation = useMutation({
    mutationFn: async (newLead: { name: string; email: string; lead_value: number }) => {
      const res = await apiClient.post("/leads", newLead);
      return res.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["leads"] });
      setIsModalOpen(false);
      setLeadName("");
      setLeadEmail("");
      setLeadValue("50000");
    },
  });

  const convertLeadMutation = useMutation({
    mutationFn: async (leadId: number) => {
      const res = await apiClient.post(`/leads/${leadId}/convert`);
      return res.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["leads"] });
      queryClient.invalidateQueries({ queryKey: ["clients"] });
    },
  });

  const handleSaveLead = (e: React.FormEvent) => {
    e.preventDefault();
    if (!leadName.trim()) return;
    createLeadMutation.mutate({
      name: leadName,
      email: leadEmail,
      lead_value: parseFloat(leadValue) || 50000,
    });
  };

  const defaultLeads = [
    { id: 1, name: "Supatra Enterprise", email: "contact@supatra.com", status: "New", value: 45000, source: "Web Form", assignedTo: "Somchai" },
    { id: 2, name: "Nexus Cloud Systems", email: "info@nexuscloud.io", status: "Contacted", value: 120000, source: "LinkedIn", assignedTo: "Ananya" },
  ];

  const leads = leadsData.length > 0 ? leadsData : defaultLeads;

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

          <button
            onClick={() => setIsModalOpen(true)}
            className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow-lg shadow-purple-500/25 transition-all cursor-pointer"
          >
            <Plus className="h-4 w-4" /> {t("lead.addNew")}
          </button>
        </div>
      </div>

      {/* Kanban View */}
      {viewMode === "kanban" ? (
        <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 overflow-x-auto pb-4">
          {stages.map((stg) => {
            const stageLeads = leads.filter((l: any) => l.status === stg.id || (l.status === 1 && stg.id === "New"));
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
                      className="bg-slate-950/80 border border-slate-800/80 rounded-2xl p-4 shadow-md hover:border-purple-500/40 transition-all space-y-3"
                    >
                      <div>
                        <h4 className="text-xs font-bold text-white">{lead.name}</h4>
                        <p className="text-[10px] text-slate-400 mt-0.5">{lead.email}</p>
                      </div>
                      <div className="flex items-center justify-between text-xs pt-2 border-t border-slate-900">
                        <span className="text-purple-400 font-extrabold flex items-center">
                          {formatCurrency(lead.value || lead.lead_value || 50000)}
                        </span>
                        <button
                          onClick={() => convertLeadMutation.mutate(lead.id)}
                          disabled={convertLeadMutation.isPending}
                          className="text-[10px] text-cyan-400 hover:underline flex items-center gap-1 cursor-pointer"
                        >
                          Convert <ArrowRight className="h-3 w-3" />
                        </button>
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
                  <td className="p-4 font-bold text-purple-400">{formatCurrency(lead.value || lead.lead_value || 50000)}</td>
                  <td className="p-4 text-slate-400">{lead.source || "Web"}</td>
                  <td className="p-4">
                    <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-500/10 text-purple-400 border border-purple-500/20">
                      {lead.status}
                    </span>
                  </td>
                  <td className="p-4 text-right">
                    <button
                      onClick={() => convertLeadMutation.mutate(lead.id)}
                      disabled={convertLeadMutation.isPending}
                      className="text-cyan-400 font-semibold hover:underline flex items-center justify-end gap-1 ml-auto cursor-pointer"
                    >
                      Convert <ArrowRight className="h-3 w-3" />
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {/* Modal Add Lead */}
      {isModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 animate-fadeIn">
          <form onSubmit={handleSaveLead} className="bg-slate-900 border border-slate-800 rounded-3xl p-6 w-full max-w-lg shadow-2xl space-y-5">
            <div className="flex items-center justify-between border-b border-slate-800 pb-3">
              <h3 className="text-base font-extrabold text-white">{t("lead.addNew")}</h3>
              <button type="button" onClick={() => setIsModalOpen(false)} className="text-slate-400 hover:text-white">
                <X className="h-5 w-5" />
              </button>
            </div>
            <div className="space-y-4 text-xs">
              <div>
                <label className="text-slate-300 font-semibold block mb-1">{t("common.name")}</label>
                <input
                  type="text"
                  required
                  value={leadName}
                  onChange={(e) => setLeadName(e.target.value)}
                  placeholder="e.g. Bangkok Retail Enterprise"
                  className="w-full bg-slate-950 border border-slate-800 rounded-xl py-2 px-3 text-white focus:outline-none focus:border-purple-500"
                />
              </div>
              <div>
                <label className="text-slate-300 font-semibold block mb-1">{t("common.email")}</label>
                <input
                  type="email"
                  value={leadEmail}
                  onChange={(e) => setLeadEmail(e.target.value)}
                  placeholder="contact@lead.com"
                  className="w-full bg-slate-950 border border-slate-800 rounded-xl py-2 px-3 text-white focus:outline-none focus:border-purple-500"
                />
              </div>
              <div>
                <label className="text-slate-300 font-semibold block mb-1">{t("lead.value")}</label>
                <input
                  type="number"
                  value={leadValue}
                  onChange={(e) => setLeadValue(e.target.value)}
                  className="w-full bg-slate-950 border border-slate-800 rounded-xl py-2 px-3 text-white focus:outline-none focus:border-purple-500"
                />
              </div>
            </div>
            <div className="flex justify-end gap-3 pt-3">
              <button type="button" onClick={() => setIsModalOpen(false)} className="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-semibold">
                {t("common.cancel")}
              </button>
              <button
                type="submit"
                disabled={createLeadMutation.isPending}
                className="flex items-center gap-2 px-4 py-2 rounded-xl bg-purple-600 text-white text-xs font-bold hover:bg-purple-500 disabled:opacity-50 cursor-pointer"
              >
                {createLeadMutation.isPending && <Loader2 className="h-4 w-4 animate-spin" />}
                {t("common.save")}
              </button>
            </div>
          </form>
        </div>
      )}
    </div>
  );
}
