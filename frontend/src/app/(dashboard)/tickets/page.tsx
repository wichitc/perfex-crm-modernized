"use client";

import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { MOCK_TICKETS } from "@/lib/mock-data";
import { LifeBuoy, Plus, MessageSquare } from "lucide-react";

export default function TicketsPage() {
  const { data: tickets } = useQuery({
    queryKey: ["tickets"],
    queryFn: async () => {
      try {
        const response = await apiClient.get("/tickets");
        return response.data;
      } catch {
        return MOCK_TICKETS;
      }
    },
    initialData: MOCK_TICKETS,
  });

  return (
    <div className="space-y-8 animate-fadeIn">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-black tracking-tight text-white flex items-center gap-2">
            <LifeBuoy className="h-6 w-6 text-sky-400" />
            Support Desk & Tickets
          </h2>
          <p className="text-xs text-slate-400 mt-1">
            Resolve customer inquiries, support tickets, and maintain SLA resolution.
          </p>
        </div>
        <button className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs shadow-lg shadow-sky-500/25 transition-all cursor-pointer">
          <Plus className="h-4 w-4" /> Open New Ticket
        </button>
      </div>

      <div className="bg-slate-900/60 border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
        <table className="w-full text-left text-xs">
          <thead className="bg-slate-950/80 text-slate-400 uppercase tracking-wider font-bold border-b border-slate-800">
            <tr>
              <th className="p-4">Ticket ID</th>
              <th className="p-4">Subject</th>
              <th className="p-4">Client</th>
              <th className="p-4">Priority</th>
              <th className="p-4">Date</th>
              <th className="p-4 text-right">Status</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-800/60 text-slate-200">
            {tickets.map((t: any) => (
              <tr key={t.id} className="hover:bg-slate-800/40 transition-colors cursor-pointer">
                <td className="p-4 font-mono font-bold text-sky-400">{t.id}</td>
                <td className="p-4 font-bold text-white flex items-center gap-2">
                  <MessageSquare className="h-3.5 w-3.5 text-slate-500" /> {t.subject}
                </td>
                <td className="p-4 text-slate-300">{t.client}</td>
                <td className="p-4">
                  <span className={`px-2 py-0.5 rounded-md text-[10px] font-bold ${
                    t.priority === "High" ? "bg-rose-500/10 text-rose-400 border border-rose-500/20" : "bg-slate-800 text-slate-300"
                  }`}>
                    {t.priority}
                  </span>
                </td>
                <td className="p-4 text-slate-400">{t.date}</td>
                <td className="p-4 text-right">
                  <span className="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-500/10 text-sky-400 border border-sky-500/20">
                    {t.status}
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
