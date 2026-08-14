"use client";

import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { useTranslation } from "@/providers/language-provider";
import { LifeBuoy, Plus, MessageSquare } from "lucide-react";

export default function TicketsPage() {
  const { t, formatDate } = useTranslation();

  const { data: ticketsData = [] } = useQuery({
    queryKey: ["tickets"],
    queryFn: async () => {
      const response = await apiClient.get("/tickets");
      return response.data;
    },
  });

  const defaultTickets = [
    { id: "T-8091", subject: "Request for API token generation help", client: "Acme Technology Solutions", priority: "Medium", status: "Open", date: "2026-07-29 16:20" },
    { id: "T-8092", subject: "Invoice PDF download formatting inquiry", client: "Siam Digital Innovations", priority: "Low", status: "Answered", date: "2026-07-29 14:10" },
  ];

  const tickets = ticketsData.length > 0 ? ticketsData : defaultTickets;

  return (
    <div className="space-y-8 animate-fadeIn">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-black tracking-tight text-white flex items-center gap-2">
            <LifeBuoy className="h-6 w-6 text-sky-400" />
            {t("ticket.title")}
          </h2>
          <p className="text-xs text-slate-400 mt-1">
            {t("ticket.subtitle")}
          </p>
        </div>
        <button className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs shadow-lg shadow-sky-500/25 transition-all cursor-pointer">
          <Plus className="h-4 w-4" /> {t("ticket.openTicket")}
        </button>
      </div>

      <div className="bg-slate-900/60 border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
        <table className="w-full text-left text-xs">
          <thead className="bg-slate-950/80 text-slate-400 uppercase tracking-wider font-bold border-b border-slate-800">
            <tr>
              <th className="p-4">Ticket ID</th>
              <th className="p-4">{t("ticket.subject")}</th>
              <th className="p-4">{t("ticket.client")}</th>
              <th className="p-4">Priority</th>
              <th className="p-4">{t("common.date")}</th>
              <th className="p-4 text-right">{t("common.status")}</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-800/60 text-slate-200">
            {tickets.map((tItem: any) => (
              <tr key={tItem.id} className="hover:bg-slate-800/40 transition-colors cursor-pointer">
                <td className="p-4 font-mono font-bold text-sky-400">{tItem.ticketid ? `T-${tItem.ticketid}` : tItem.id}</td>
                <td className="p-4 font-bold text-white flex items-center gap-2">
                  <MessageSquare className="h-3.5 w-3.5 text-slate-500" /> {tItem.subject}
                </td>
                <td className="p-4 text-slate-300">{tItem.client || tItem.client_name || "Enterprise Client"}</td>
                <td className="p-4">
                  <span className={`px-2 py-0.5 rounded-md text-[10px] font-bold ${
                    tItem.priority === "High" || tItem.priority === 3 ? "bg-rose-500/10 text-rose-400 border border-rose-500/20" : "bg-slate-800 text-slate-300"
                  }`}>
                    {tItem.priority || "Medium"}
                  </span>
                </td>
                <td className="p-4 text-slate-400">{formatDate(tItem.date || tItem.created_at || "2026-07-29")}</td>
                <td className="p-4 text-right">
                  <span className="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-500/10 text-sky-400 border border-sky-500/20">
                    {tItem.status || "Open"}
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
